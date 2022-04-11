<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Banco;
use App\Models\Cabecera_Rol_CM;
use App\Models\Cheque;
use App\Models\Cheque_Impresion;
use App\Models\Cierre_Mes_Contable;
use App\Models\Cuenta_Bancaria;
use App\Models\Decimo_Cuarto;
use App\Models\Decimo_Tercero;
use App\Models\Diario;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\Factura_Venta;
use App\Models\Movimiento_Producto;
use App\Models\Nota_Entrega;
use App\Models\Orden_Despacho;
use App\Models\Orden_Examen;
use App\Models\Pais;
use App\Models\Provincia;
use App\Models\Punto_Emision;
use App\Models\Rol_Consolidado;
use App\Models\Rubro;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use PDF;
use PhpParser\Node\Stmt\Return_;

class generalController extends Controller
{
    public function denegado()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('errors.denegado',['PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function cierre($fecha){
        try{
            $fechaEntera = strtotime($fecha);
            $anio = date("Y", $fechaEntera);    
            $mes=date("m", $fechaEntera);    
            $cierre=Cierre_Mes_Contable::Cierre($anio)->get()->first();
            $cierre3='cierre_'.$mes;
            if ($cierre) {
                if ($cierre->$cierre3 =='1') {
                    return true;
                } else {
                    return false;
                }
            }
            else{
                return false;
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function registrarAuditoria($descripcion, $numeroDocumento, $adicional){
        try{
            DB::beginTransaction();
            if(Auth::user()->user_username != 'SuperAdministrador'){
                $auditoria = new Auditoria();
                $auditoria->auditoria_fecha=date("Y")."-".date("m")."-".date("d");
                $auditoria->auditoria_hora=date("H:i:s");
                $auditoria->auditoria_maquina=gethostname();
                $auditoria->auditoria_adicional=$adicional;
                $auditoria->auditoria_descripcion=$descripcion;
                $auditoria->auditoria_numero_documento=$numeroDocumento;
                $auditoria->auditoria_estado='1';
                $auditoria->user_id=Auth::user()->user_id;
                $auditoria->save();
            }
            DB::commit();
        }
        catch(\Exception $ex){    
            DB::rollBack();  
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function generarCodigoDiario($fecha, $tipo){
        $mes  = DateTime::createFromFormat('Y-m-d', $fecha)->format('m');
        $ano  = DateTime::createFromFormat('Y-m-d', $fecha)->format('y');
        $secuencialDiario = Diario::DiarioSecuencial($tipo,$mes,DateTime::createFromFormat('Y-m-d', $fecha)->format('Y'))->max('diario_secuencial');
        $sec = 1;
        if($secuencialDiario){
            $sec = $secuencialDiario +1;
        }
        $codigoDiario = $tipo.$mes.$ano.substr(str_repeat(0, 7).$sec, - 7);
        return $codigoDiario;
    }
    public function pdfDiario(Diario $diario){
        $empresa = Empresa::empresa()->first();
        $diario=Diario::findOrFail($diario->diario_id);
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $diario->diario_codigo. ".pdf";
        $view =  \View::make('admin.formatosPDF.diario', ['empresa'=> $empresa,'diario'=> $diario]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
        return 'DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y').'/'.$nombreArchivo;
    }
    public function pdfVariosDiario($diarios, $fecha){
        $empresa = Empresa::empresa()->first();
        $nombre = '';
        for ($i = 0; $i < count($diarios); ++$i){
            $diario=Diario::findOrFail($diarios[$i]->diario_id);
            $nombre = $nombre.' - '.$diario->diario_codigo;
        }
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $nombre. ".pdf";
        $view =  \View::make('admin.formatosPDF.variosDiarios', ['empresa'=> $empresa,'diarios'=> $diarios]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
        return 'DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $fecha)->format('d-m-Y').'/'.$nombreArchivo;
    }
    public function pdfDiarioEgreso(Diario $diario){
        $empresa = Empresa::empresa()->first();
        $diario=Diario::findOrFail($diario->diario_id);
        $empleado=null;
        foreach($diario->detalles as $detalle){
            if(isset($detalle->empleado_id)){
                $empleado=Empleado::findOrFail($detalle->empleado_id);
            } 
        }
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $diario->diario_codigo. ".pdf";
        $view =  \View::make('admin.formatosPDF.diarioEgreso', ['empresa'=> $empresa,'diario'=> $diario,'empleado'=> $empleado]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
        return 'DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y').'/'.$nombreArchivo;
    }
    
    public function pdfDiarioPago(Diario $diario){
        $empresa = Empresa::empresa()->first();
        $diario=Diario::findOrFail($diario->diario_id);
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $diario->diario_codigo. ".pdf";
        $view =  \View::make('admin.formatosPDF.diarioPago', ['empresa'=> $empresa,'diario'=> $diario]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
        return 'DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y').'/'.$nombreArchivo;
    }
    public function pdfImprimeCheque($idCuenta, Cheque $cheque){
        $formatter = new NumeroALetras();
        $newValorLetras = $formatter->toInvoice($cheque->cheque_valor, 2, 'Dolares');
        $cheque->cheque_valor_letras = $newValorLetras;
        $cheque->update();
        $empresa = Empresa::empresa()->first();
        $cuentaBancaria = Cuenta_Bancaria::cuentaBancaria($idCuenta)->first();
        $chequeImpresion = Cheque_Impresion::chequeImpresion($idCuenta)->first();        
        $ruta = public_path().'/chequesImpresosPDF/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $cheque->cheque_fecha_emision)->format('d-m-Y');
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $cuentaBancaria->cuenta_bancaria_numero.'_'.$cheque->cheque_numero. ".pdf";
        $view =  \View::make('admin.formatosPDF.chequeImpresionPdf', ['cheque'=>$cheque,'chequeImpresion'=>$chequeImpresion,'empresa'=>$empresa]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
        return 'chequesImpresosPDF/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $cheque->cheque_fecha_emision)->format('d-m-Y').'/'.$nombreArchivo;
  
    }
    public function pdfImprimeCheque2($idCuenta, Cheque $cheque){
        $formatter = new NumeroALetras();
        $newValorLetras = $formatter->toInvoice($cheque->cheque_valor, 2, 'Dolares');
        $cheque->cheque_valor_letras = $newValorLetras;
        $cheque->update();
        $empresa = Empresa::empresa()->first();
        $cuentaBancaria = Cuenta_Bancaria::cuentaBancaria($idCuenta)->first();
        $chequeImpresion = Cheque_Impresion::chequeImpresion($idCuenta)->first();        
        $ruta = public_path().'/chequesImpresosPDF/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $cheque->cheque_fecha_emision)->format('d-m-Y');
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $cuentaBancaria->cuenta_bancaria_numero.'_'.$cheque->cheque_numero. ".pdf";
        $view =  \View::make('admin.formatosPDF.chequeImpresionPdf', ['cheque'=>$cheque,'chequeImpresion'=>$chequeImpresion,'empresa'=>$empresa]);
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream($nombreArchivo);
    }
    public function NotaEntrega(Nota_Entrega $nota,$url){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/notasEntrega/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $nota->nt_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = 'NT-'.$nota->nt_numero. ".pdf";
        $view =  \View::make('admin.formatosPDF.notaEntrega', ['nt'=>$nota,'empresa'=>$empresa]);
        if($url == 0){
            return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('notaEntrega.pdf');
        }else{
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
            return 'notasEntrega/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $nota->nt_fecha)->format('d-m-Y').'/'.$nombreArchivo;
        }
    }
   
    public function NotaEntregaRecibo(Nota_Entrega $nota,$url){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/notasEntrega/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $nota->nt_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = 'NT-'.$nota->nt_numero. ".pdf";
        $view =  \View::make('admin.formatosPDF.reciboNotaEntrega', ['nt'=>$nota,'empresa'=>$empresa]);
        if($url == 0){
            return PDF::loadHTML($view)->setPaper(array(0,0,249.45,300.33 + (count($nota->detalle) * 15)), 'portrait')->save($ruta.'/'.$nombreArchivo)->stream('recibo.pdf');
        }else{
            PDF::loadHTML($view)->setPaper(array(0,0,249.45,300.33 + (count($nota->detalle) * 15)), 'portrait')->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
            return 'notasEntrega/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $nota->nt_fecha)->format('d-m-Y').'/'.$nombreArchivo;
        }
    }
    public function FacturaRecibo(Factura_Venta $factura,$url){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/documentos/reciboFactura/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $factura->factura_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = 'FAC-'.$factura->factura_numero. ".pdf";
        $view =  \View::make('admin.formatosPDF.reciboFactura', ['factura'=>$factura,'empresa'=>$empresa]);
        if($url == 0){
            return PDF::loadHTML($view)->setPaper(array(0,0,249.45,450.33 + (count($factura->detalles) * 15)), 'portrait')->save($ruta.'/'.$nombreArchivo)->stream('recibo.pdf');
        }else{
            PDF::loadHTML($view)->setPaper(array(0,0,249.45,450.33 + (count($factura->detalles) * 15)), 'portrait')->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
            return 'documentos/reciboFactura/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $factura->factura_fecha)->format('d-m-Y').'/'.$nombreArchivo;
        }
    }
    public function pdfDiariourl(Diario $diario){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $diario->diario_codigo. ".pdf";
        $view =  \View::make('admin.formatosPDF.diario', ['empresa'=> $empresa,'diario'=> $diario]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('diario.pdf');
    }
    public function pdfDiariourl2(Diario $diario){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $diario->diario_codigo. ".pdf";
        $view =  \View::make('admin.formatosPDF.diariodecimo', ['empresa'=> $empresa,'diario'=> $diario]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('diario.pdf');
    }
    public function pdfDiarioEgresourl(Diario $diario){
        $empresa = Empresa::empresa()->first();
        $diario=Diario::findOrFail($diario->diario_id);
        $empleado=null;
        foreach($diario->detalles as $detalle){
            if(isset($detalle->empleado_id)){
                $empleado=Empleado::findOrFail($detalle->empleado_id);
            } 
        }
        $ruta = public_path().'/DIARIOS/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $diario->diario_fecha)->format('d-m-Y');
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $diario->diario_codigo. ".pdf";
        $view =  \View::make('admin.formatosPDF.diarioEgreso', ['empresa'=> $empresa,'diario'=> $diario,'empleado'=> $empleado]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('diario.pdf');
    }
    public function pdfRolDetalle(Rol_Consolidado $rol){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha));
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $rol->empleado->empleado_nombre.'-'.date("m-Y", strtotime($rol->cabecera_rol_fecha)). ".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($rol->cabecera_rol_fecha));
        $view =  \View::make('admin.formatosPDF.rolindividual', ['empresa'=> $empresa,'rol'=> $rol,'mes'=> $mes]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return 'roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha)).'/'.$nombreArchivo;

    }
    public function pdfRolCm(Cabecera_Rol_CM $rol){
        $cuenta=null;
        $diario=Diario::findOrFail($rol->diario_pago_id);
        foreach($diario->detalles as $detalle){
            if(isset($detalle->cheque)){
                $cuenta=Cuenta_Bancaria::findOrFail($detalle->cheque->cuenta_bancaria_id);
            }
            if(isset($detalle->transferencia)){
                $cuenta=Cuenta_Bancaria::findOrFail($detalle->transferencia->cuenta_bancaria_id);
            }
        }
        $empresa = Empresa::empresa()->first();
        $rubro=Rubro::RubrosRH()->get();
        $ingresos=Rubro::Rubrotipoorder('2')->get();
        $egresos=Rubro::Rubrotipoorder('1')->get();
        $ruta = public_path().'/roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha));
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $rol->empleado->empleado_nombre.'-'.date("m-Y", strtotime($rol->cabecera_rol_fecha)). ".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($rol->cabecera_rol_fecha));
        $view =  \View::make('admin.formatosPDF.rolesCM.rolOperativo', ['empresa'=> $empresa,'rol'=> $rol,'mes'=> $mes,'bancaria'=> $cuenta,'egresos'=>$egresos,'ingresos'=>$ingresos,'rubros'=>$rubro]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo);
        return 'roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha)).'/'.$nombreArchivo;

    }
    public function imprimirRolCm(Cabecera_Rol_CM $rol){
        $cuenta=null;
        $diario=Diario::findOrFail($rol->diario_pago_id);
        foreach($diario->detalles as $detalle){
            if(isset($detalle->cheque)){
                $cuenta=Cuenta_Bancaria::findOrFail($detalle->cheque->cuenta_bancaria_id);
            }
            if(isset($detalle->transferencia)){
                $cuenta=Cuenta_Bancaria::findOrFail($detalle->transferencia->cuenta_bancaria_id);
            }
        }
        $empresa = Empresa::empresa()->first();
        $rubro=Rubro::RubrosRH()->get();
        $ingresos=Rubro::Rubrotipoorder('2')->get();
        $egresos=Rubro::Rubrotipoorder('1')->get();
        $ruta = public_path().'/roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha));
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $rol->empleado->empleado_nombre.'-'.date("m-Y", strtotime($rol->cabecera_rol_fecha)). ".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($rol->cabecera_rol_fecha));
        $view =  \View::make('admin.formatosPDF.rolesCM.rolOperativo', ['empresa'=> $empresa,'rol'=> $rol,'mes'=> $mes,'bancaria'=> $cuenta,'egresos'=>$egresos,'ingresos'=>$ingresos,'rubros'=>$rubro]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('rol.pdf');

    }
    public function pdfRolOperativo(Rol_Consolidado $rol,Cuenta_Bancaria $cuenta){
        $empresa = Empresa::empresa()->first();

        $ruta = public_path().'/roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha));
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $rol->empleado->empleado_nombre.'-'.date("m-Y", strtotime($rol->cabecera_rol_fecha)). ".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($rol->cabecera_rol_fecha));
        $view =  \View::make('admin.formatosPDF.roles.rolOperativo', ['empresa'=> $empresa,'rol'=> $rol,'bancaria'=> $cuenta,'mes'=> $mes]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return 'roles/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha)).'/'.$nombreArchivo;

    }
    public function pdfRol(Rol_Consolidado $rol){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/decimoCuarto/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($rol->cabecera_rol_fecha));
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $rol->empleado->empleado_nombre.'-'.date("m-Y", strtotime($rol->cabecera_rol_fecha)). ".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($rol->cabecera_rol_fecha));
        
        $view =  \View::make('admin.formatosPDF.rolindividual', ['empresa'=> $empresa,'rol'=> $rol,'mes'=> $mes]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('rol.pdf');
    }

    public function pdfTercero(Decimo_Tercero $tercero){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/decimoTercero/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($tercero->decimo_fecha));
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $tercero->empleado->empleado_nombre.'-'.date("m-Y",strtotime($tercero->decimo_fecha)).".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($tercero->decimo_fecha));
        $fecha = strftime("%d de %B de %Y", strtotime($tercero->decimo_fecha));
        $view =  \View::make('admin.formatosPDF.tercero', ['empresa'=> $empresa,'tercero'=> $tercero,'fecha'=> $fecha,'mes'=> $mes]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('tercero.pdf');
    }
    public function LaboratorioAnalisis(Orden_Examen $orden){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/ordenesExamenes/Analisis/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $orden->expediente->ordenatencion->orden_fecha)->format('d-m-Y');
        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = 'OD-'.$orden->expediente->ordenatencion->orden_numero;
        $view =  \View::make('admin.formatosPDF.ordendeexamen', ['orden'=>$orden,'empresa'=>$empresa]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf')->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf')->stream('analisis.pdf');
}
    public function ordendespacho(Orden_Despacho $orden){
            $empresa = Empresa::empresa()->first();
            $ruta = public_path().'/ordenDespacho/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $orden->orden_fecha)->format('d-m-Y');
            echo "$ruta";
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $nombreArchivo = 'OD-'.$orden->orden_numero;
            $view =  \View::make('admin.formatosPDF.ordenDespacho', ['orden'=>$orden,'empresa'=>$empresa]);
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
            return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('ordenDespacho.pdf');
    }
    
    public function pdfCuarto(Decimo_Cuarto $Cuarto){
        $empresa = Empresa::empresa()->first();
        $ruta = public_path().'/decimoCuarto/'.$empresa->empresa_ruc.'/'.date("m-Y", strtotime($Cuarto->decimo_fecha));

        echo "$ruta";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $nombreArchivo = $Cuarto->empleado->empleado_nombre.'-'.date("m-Y", strtotime($Cuarto->decimo_fecha)). ".pdf";
        setlocale(LC_ALL, 'spanish');
        $mes=strftime('%B',strtotime($Cuarto->decimo_fecha_emision));
        $fecha = strftime("%d de %B de %Y", strtotime($Cuarto->decimo_fecha));
        $view =  \View::make('admin.formatosPDF.cuarto', ['empresa'=> $empresa,'cuarto'=> $Cuarto,'fecha'=> $fecha,'mes'=> $mes]);
        PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->download($nombreArchivo.'.pdf');
        return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo)->stream('cuarto.pdf');
    }

    public function preciocosto($fechaInicio,$fechaFin,$producto_id){
        if($fechaInicio == ''){
            $fechaInicio = date("Y-m-d",strtotime($fechaFin."- 60 days"));
        }
        $movimientoAnterior = Movimiento_Producto::MovProductoByFechaCorte($producto_id,date("Y-m-d",strtotime($fechaInicio."- 1 days")))->orderBy('movimiento_fecha','desc')->orderBy('movimiento_id','desc')->first();
        $precioCosto=0;
        foreach(Movimiento_Producto::MovProductoByFecha($producto_id,$fechaInicio,$fechaFin)->get() as $movimiento){
            if($movimiento->movimiento_motivo == 'COMPRA'){
                if($movimientoAnterior){
                    if($movimiento->movimiento_stock_actual != 0){
                        $precioCosto = abs((($movimientoAnterior->movimiento_cantidad*$movimientoAnterior->movimiento_costo_promedio) + $movimiento->movimiento_total)/$movimiento->movimiento_stock_actual);
                    }
                }
            }
            $movimientoAnterior = $movimiento;
        }
        return $precioCosto;
    }
}
