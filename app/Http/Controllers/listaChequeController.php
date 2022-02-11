<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Cheque;
use App\Models\Cuenta_Bancaria;
use App\Models\Punto_Emision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class listaChequeController extends Controller
{
    public function vista()
    {
        try{ 
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.bancos.listaCheque.index',['bancos'=>Banco::bancos()->get(),'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function listarCheques(Request $request)
    {   
        try{
            if ($request->get('idTodos') == '0'){
                //todos
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
                $listadoCheques=Cheque::listadoCheques()           
                ->where('banco_lista.empresa_id','=',Auth::user()->empresa_id)
                ->where('cheque_fecha_emision','>=',$request->get('idDesde'))
                ->where('cheque_fecha_emision','<=',$request->get('idHasta'))
                ->where('cuenta_bancaria.cuenta_bancaria_id','=',$request->get('cuenta_id'))->orderBy('cheque_numero','asc')->get();   
                return view('admin.bancos.listaCheque.index',['estadC'=>$request->get('idTodos'),'fechaI'=>$request->get('idDesde'),'fechaF'=>$request->get('idHasta'),'bancoC'=>$request->get('banco_id'),'cuentaBancaria'=>Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first(),'bancos'=>Banco::bancos()->get(),'listadoCheques'=>$listadoCheques, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }
            if ($request->get('idTodos') == '1'){
                //activos
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
                $listadoCheques=Cheque::listadoCheques()
                ->where('banco_lista.empresa_id','=',Auth::user()->empresa_id)
                ->where('cheque_fecha_emision','>=',$request->get('idDesde'))
                ->where('cheque_fecha_emision','<=',$request->get('idHasta'))
                ->where('cuenta_bancaria.cuenta_bancaria_id','=',$request->get('cuenta_id'))
                ->where('cheque_estado','=','1')->orderBy('cheque_numero','asc')->get();     
                return view('admin.bancos.listaCheque.index',['estadC'=>$request->get('idTodos'),'fechaI'=>$request->get('idDesde'),'fechaF'=>$request->get('idHasta'),'bancoC'=>$request->get('banco_id'),'cuentaBancaria'=>Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first(),'bancos'=>Banco::bancos()->get(),'listadoCheques'=>$listadoCheques, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }
            if ($request->get('idTodos') == '2'){
                //anulados
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
                $listadoCheques=Cheque::listadoCheques()
                ->where('banco_lista.empresa_id','=',Auth::user()->empresa_id)
                ->where('cheque_fecha_emision','>=',$request->get('idDesde'))
                ->where('cheque_fecha_emision','<=',$request->get('idHasta'))
                ->where('cuenta_bancaria.cuenta_bancaria_id','=',$request->get('cuenta_id'))
                ->where('cheque_estado','=','2')->orderBy('cheque_numero','asc')->get();            
                return view('admin.bancos.listaCheque.index',['estadC'=>$request->get('idTodos'),'fechaI'=>$request->get('idDesde'),'fechaF'=>$request->get('idHasta'),'bancoC'=>$request->get('banco_id'),'cuentaBancaria'=>Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first(),'bancos'=>Banco::bancos()->get(),'listadoCheques'=>$listadoCheques, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }      
    }

    public function imprimirCheque($id){
        $general = new generalController();
        return $general->pdfImprimeCheque2(Cheque::findOrFail($id)->cuenta_bancaria_id ,Cheque::findOrFail($id));
    }

}
