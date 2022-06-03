<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Detalle_Diario;
use App\Models\Detalle_Prestamo;
use App\Models\Diario;
use App\Models\Prestamo_Banco;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class detallePrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }
    public function agregar($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $prestamos=Prestamo_Banco::findOrFail($id); 
            return view('admin.bancos.detallePrestamos.nuevo',['prestamos'=>$prestamos,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
           
            
            $prestamo=Prestamo_Banco::findOrFail($request->get('idprestamo'));
           
            $datetime1 = strtotime($prestamo->prestamo_inicio);
            $datetime2 = strtotime($request->get('idFecha'));
            $diff =  abs(($datetime1 - $datetime2) / 86400);
        
            $detalle = new Detalle_Prestamo();
            $detalle->detalle_fecha = $request->get('idFecha');
            $detalle->detalle_interes = $prestamo->prestamo_interes;
            $detalle->detalle_valor_interes = $request->get('idValor');
            $detalle->detalle_total = $request->get('idValor');
            
            $detalle->detalle_dias = $diff+1;
            $detalle->detalle_estado = '1';
            $detalle->prestamo()->associate($prestamo);
            $detalle->save();
            /*
            $general = new generalController();
            $diario = new Diario();
            $diario->diario_codigo = $general->generarCodigoDiario($request->get('idFecha'),'CIPB');
            $diario->diario_fecha = $request->get('idFecha');
            $diario->diario_referencia = 'COMPROBANTE DE INTERES DE PRESTAMO BANCARIO';
            $diario->diario_tipo_documento = 'INTERES DE PRESTAMO BANCARIO';
            $diario->diario_numero_documento = 0;
            $diario->diario_beneficiario = strtoupper($prestamo->banco->bancoLista->banco_lista_nombre);
            $diario->diario_tipo = 'CEBA';
            $diario->diario_secuencial = substr($diario->diario_codigo, 8);
            $diario->diario_mes = DateTime::createFromFormat('Y-m-d', $request->get('idFecha'))->format('m');
            $diario->diario_ano = DateTime::createFromFormat('Y-m-d', $request->get('idFecha'))->format('Y');
            $diario->diario_comentario = 'COMPROBANTE DE INTERES DE PRESTAMO BANCARIO: '.strtoupper($prestamo->banco->bancoLista->banco_lista_nombre) ;
            $diario->diario_cierre = '0';
            $diario->diario_estado = '1';
            $diario->empresa_id = Auth::user()->empresa_id;
            $diario->sucursal_id = $prestamo->sucursal->sucursal_id;
            $diario->save();
            $detalle->diario()->associate($diario); 

            $general->registrarAuditoria('Registro de Diario de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo,'Tipo de Diario -> '.$diario->diario_referencia.'');
          
            $detalleDiario = new Detalle_Diario();
            $detalleDiario->detalle_debe = $request->get('idValor');
            $detalleDiario->detalle_haber = 0.00 ;
            $detalleDiario->detalle_comentario = 'P/R DEL PAGO DEL INTERES DEL '.strtoupper($prestamo->banco->bancoLista->banco_lista_nombre). ' CON MONTO DE $'.$prestamo->prestamo_monto;
            $detalleDiario->detalle_tipo_documento = 'INTERES DE PRESTAMO BANCARIO';
            $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
            $detalleDiario->detalle_conciliacion = '0';
            $detalleDiario->detalle_estado = '1';     
            $detalleDiario->cuenta_id = $prestamo->cuentadebe->cuenta_id;
            $diario->detalles()->save($detalleDiario);
            $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo,'En la cuenta del debe -> '.$prestamo->cuentadebe->cuenta_numero.' con el valor de: -> '.$request->get('idValor'));
            
            $detalleDiario = new Detalle_Diario();
            $detalleDiario->detalle_debe = 0.00;
            $detalleDiario->detalle_haber = $request->get('idValor') ;
            $detalleDiario->detalle_comentario = 'P/R EL RECONOCIMIENTO DE GASTOS DEL PRESTAMO DEL'.strtoupper($prestamo->banco->bancoLista->banco_lista_nombre). ' CON MONTO DE $'.$prestamo->prestamo_monto;
            $detalleDiario->detalle_tipo_documento = 'INTERES DE PRESTAMO BANCARIO';
            $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
            $detalleDiario->detalle_conciliacion = '0';
            $detalleDiario->detalle_estado = '1';     
            $detalleDiario->cuenta_id = $prestamo->cuentahaber->cuenta_id;
            $diario->detalles()->save($detalleDiario);
            $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo,'En la cuenta del haber -> '.$prestamo->cuentahaber->cuenta_numero.' con el valor de: -> '.$request->get('idValor'));
            $detalle->save();
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de detalle de Interes -> '.$request->get('idValor').' con Prestamos Banco '.$prestamo->banco->bancoLista->banco_lista_nombre.' Con Interes '.$prestamo->prestamo_interes,$request->get('idprestamo'),'con Id '.$request->get('idprestamo'));
            
            $prestamo->prestamo_total_interes=(Detalle_Prestamo::Intereses($prestamo->prestamo_id)->sum('detalle_valor_interes'));
            $prestamo->prestamo_pago_total=$prestamo->prestamo_total_interes+$prestamo->prestamo_monto;
        
            $url = $general->pdfDiario($diario);
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualziacion  del Prestamo Interes Totales-> '.$prestamo->prestamo_total_interes.' con Prestamos Banco '.$prestamo->banco->bancoLista->banco_lista_nombre.' Con Interes '.$prestamo->prestamo_interes,$request->get('idprestamo'),'con Id '.$request->get('idprestamo'));
            */
           
           
            return redirect('/detalleprestamos/'.$request->get('idprestamo').'/agregar')->with('success','Datos guardados exitosamente');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $detalles=Detalle_Prestamo::Intereses($id)->get();
            return view('admin.bancos.detallePrestamos.index',['detalles'=>$detalles,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function ver($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();  
            $detalle=Detalle_Prestamo::findOrFail($id);
            return view('admin.bancos.detallePrestamos.ver',['detalle'=>$detalle, 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();  
            $detalle=Detalle_Prestamo::findOrFail($id);
            return view('admin.bancos.detallePrestamos.eliminar',['detalle'=>$detalle, 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            DB::beginTransaction();
            $auditoria = new generalController();
            $detalle = Detalle_Prestamo::findOrFail($id);
            
            $detalle->delete();  
            if(isset($detalle->diario_id)){
                $diario=Diario::findOrFail($detalle->diario_id);
                foreach($diario->detalles as $detalles){
                    $detalles->delete();
                    $auditoria->registrarAuditoria('Eliminacion del detalle diario  N°'.$diario->diario_codigo .'relacionado al interes del prestamo-> '.$detalle->detalle_total.' con el  monto del prestamo de '.$detalle->prestamo->prestamo_monto.' con Banco '.$detalle->prestamo->banco->bancoLista->banco_lista_nombre , 0, '');
                }
                $diario->delete();
                $auditoria->registrarAuditoria('Eliminacion del  diario  N°'.$diario->diario_codigo .'relacionado al prestamo de -> '.$detalle->prestamo->prestamo_monto.' con Banco '.$detalle->prestamo->banco->bancoLista->banco_lista_nombre , 0, '');
                /*Inicio de registro de auditoria */
            }
            $auditoria->registrarAuditoria('Eliminacion de detalle del prestamo -> '.$detalle->detalle_total.' con el  monto del prestamo de '.$detalle->prestamo->prestamo_monto.' con Banco '.$detalle->prestamo->banco->bancoLista->banco_lista_nombre ,'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('prestamos')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('prestamos')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }
    }
}
