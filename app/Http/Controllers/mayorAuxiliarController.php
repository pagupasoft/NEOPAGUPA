<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Detalle_Diario;
use App\Models\Empresa;
use App\Models\Punto_Emision;
use App\Models\Sucursal;
use DateTime;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class mayorAuxiliarController extends Controller
{
    public function nuevo()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.contabilidad.mayorAuxiliar.index',['sucursales'=>Sucursal::sucursales()->get(),'cuentas'=>Cuenta::Cuentas()->orderBy('cuenta_numero','asc')->get(),'cuentaFinal'=>Cuenta::CuentasDesc()->first()->cuenta_id,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function consultar(Request $request)
    {   
        if (isset($_POST['buscar'])){
            return $this->buscar($request);
        }
        if (isset($_POST['pdf'])){
            return $this->pdf($request);
        }
    }
    public function buscar(Request $request){
        try{   
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $datos = null;
            $count = 1;
            $debe = 0;
            $haber = 0;
            foreach(Cuenta::CuentasRango($request->get('cuenta_inicio'),$request->get('cuenta_fin'))->get() as $cuenta){
                $datos[$count]['cod'] = $cuenta->cuenta_numero;
                $datos[$count]['nom'] = $cuenta->cuenta_nombre;
                $datos[$count]['fec'] = '';
                $datos[$count]['doc'] = '';
                $datos[$count]['num'] = '';
                $datos[$count]['deb'] = '';
                $datos[$count]['hab'] = '';
                $datos[$count]['act'] = Detalle_Diario::SaldoAnteriorCuentaSucursal($cuenta->cuenta_id,$request->get('fecha_desde'),$request->get('sucursal_id'))->select(DB::raw('SUM(detalle_debe)-SUM(detalle_haber) as saldo'))->first()->saldo;
                if($datos[$count]['act'] == ''){
                    $datos[$count]['act'] = 0;
                }
                $datos[$count]['ben'] = '';
                $datos[$count]['dia'] = '';
                $datos[$count]['com'] = '';
                $datos[$count]['suc'] = '';
                $datos[$count]['tot'] = '1';
                $count ++;
                $debe = 0;
                $haber = 0;
                foreach(Detalle_Diario::MovimientosCuenta($cuenta->cuenta_id,$request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('sucursal_id'))->get() as $detalle){
                    $datos[$count]['cod'] = $cuenta->cuenta_numero;
                    $datos[$count]['nom'] = $cuenta->cuenta_nombre;
                    $datos[$count]['fec'] = $detalle->diario->diario_fecha;
                    $datos[$count]['doc'] = $detalle->detalle_tipo_documento;
                    $datos[$count]['num'] = $detalle->detalle_numero_documento;;
                    $datos[$count]['ant'] = '';
                    $datos[$count]['deb'] = $detalle->detalle_debe;
                    $datos[$count]['hab'] = $detalle->detalle_haber;
                    $datos[$count]['act'] = doubleval($datos[$count-1]['act']) + doubleval($datos[$count]['deb']) - doubleval($datos[$count]['hab']);
                    $datos[$count]['ben'] = $detalle->diario_beneficiario;
                    $datos[$count]['dia'] = $detalle->diario->diario_codigo;
                    $datos[$count]['com'] = 'Decripcion: '.$detalle->detalle_comentario.' '.'Comentario: '.$detalle->diario->diario_comentario;                   
                    $datos[$count]['suc'] = $detalle->diario->sucursal->sucursal_nombre;
                    $datos[$count]['tot'] = '0';
                    $debe = $debe + doubleval($datos[$count]['deb']);
                    $haber = $haber + doubleval($datos[$count]['hab']);
                    $count ++;
                }
                if($debe > 0 or $haber > 0){
                    $datos[$count]['cod'] = '';
                    $datos[$count]['nom'] = '';
                    $datos[$count]['fec'] = '';
                    $datos[$count]['doc'] = '';
                    $datos[$count]['num'] = '';
                    $datos[$count]['ant'] = '';
                    $datos[$count]['deb'] = $debe;
                    $datos[$count]['hab'] = $haber;
                    $datos[$count]['act'] = '';
                    $datos[$count]['ben'] = '';
                    $datos[$count]['dia'] = '';
                    $datos[$count]['com'] = '';
                    $datos[$count]['suc'] = '';
                    $datos[$count]['tot'] = '2';
                    $count ++;
                }
                if( $datos[$count-1]['tot'] == '1' ){
                    array_pop($datos);
                    $count = $count - 1;
                }
            }
            return view('admin.contabilidad.mayorAuxiliar.index',['ini'=>$request->get('cuenta_inicio'),'fin'=>$request->get('cuenta_fin'),'sucursalC'=>$request->get('sucursal_id'),'sucursales'=>Sucursal::sucursales()->get(),'fDesde'=>$request->get('fecha_desde'),'fHasta'=>$request->get('fecha_hasta'),'datos'=>$datos,'cuentas'=>Cuenta::Cuentas()->orderBy('cuenta_numero','asc')->get(),'cuentaFinal'=>Cuenta::CuentasDesc()->first()->cuenta_id,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);      
        }catch(\Exception $ex){
            return redirect('mayorAuxiliar')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function pdf(Request $request){
        try{            
            $datos = null;
            $count = 1;
            $cod = $request->get('idCod');
            $nom = $request->get('idNom');
            $fec = $request->get('idFec');
            $doc = $request->get('idDoc');
            $num = $request->get('idNum');
            $deb = $request->get('idDeb');
            $hab = $request->get('idHab');
            $act = $request->get('idAct');
            $ben = $request->get('idBen');
            $dia = $request->get('idDia');
            $com = $request->get('idCom');
            $suc = $request->get('idSuc');
            $tot = $request->get('idTot');
            if($cod){
                for ($i = 0; $i < count($cod); ++$i){
                    $datos[$count]['cod'] = $cod[$i];
                    $datos[$count]['nom'] = $nom[$i];
                    $datos[$count]['fec'] = $fec[$i];
                    $datos[$count]['doc'] = $doc[$i];
                    $datos[$count]['num'] = $num[$i];
                    $datos[$count]['deb'] = $deb[$i];
                    $datos[$count]['hab'] = $hab[$i];
                    $datos[$count]['act'] = $act[$i];
                    $datos[$count]['ben'] = $ben[$i];
                    $datos[$count]['dia'] = $dia[$i];
                    $datos[$count]['com'] = $com[$i];
                    $datos[$count]['suc'] = $suc[$i];
                    $datos[$count]['tot'] = $tot[$i];
                    $count ++;
                }
            }
            $empresa =  Empresa::empresa()->first();
            $ruta = public_path().'/PDF/'.$empresa->empresa_ruc;
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $view =  \View::make('admin.formatosPDF.mayorAuxiliar', ['datos'=>$datos,'desde'=>DateTime::createFromFormat('Y-m-d', $request->get('fecha_desde'))->format('d/m/Y'),'hasta'=>DateTime::createFromFormat('Y-m-d', $request->get('fecha_hasta'))->format('d/m/Y'),'empresa'=>$empresa]);
            $nombreArchivo = 'MAYOR DE CUENTAS '.DateTime::createFromFormat('Y-m-d', $request->get('fecha_desde'))->format('d-m-Y').' AL '.DateTime::createFromFormat('Y-m-d', $request->get('fecha_hasta'))->format('d-m-Y');
            return PDF::loadHTML($view)->setPaper('a4', 'landscape')->save('PDF/'.$empresa->empresa_ruc.'/'.$nombreArchivo.'.pdf')->download($nombreArchivo.'.pdf');
            //return PDF::loadHTML($view)->save('PDF/'.$empresa->empresa_ruc.'/'.$nombreArchivo.'.pdf')->stream($nombreArchivo.'.pdf');
        }catch(\Exception $ex){
            return redirect('mayorAuxiliar')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
