<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Cuenta;
use App\Models\Prestamo_Banco;
use App\Models\Punto_Emision;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class prestamoBancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $bancos=Banco::Bancos()->get();
            $cuentas=Cuenta::Cuentas()->get();
            $prestamos=Prestamo_Banco::Prestamos()->get();
            $sucursales=sucursal::Sucursales()->get();
            return view('admin.bancos.prestamos.index',['sucursales'=>$sucursales,'prestamos'=>$prestamos,'cuentas'=>$cuentas,'bancos'=>$bancos,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
        try{
            DB::beginTransaction();
            $prestamo = new Prestamo_Banco();
            $prestamo->prestamo_inicio = $request->get('idFechaini');
            $prestamo->prestamo_fin = $request->get('idFechafin');
            $prestamo->prestamo_monto = $request->get('idMonto');
            $prestamo->prestamo_interes = $request->get('idInteres');
            $prestamo->prestamo_plazo = $request->get('idPlazo');
            $prestamo->prestamo_total_interes = 0;
            $prestamo->prestamo_pago_total = 0;
            $prestamo->cuenta_debe = $request->get('idDebe');
            $prestamo->cuenta_haber = $request->get('idHaber');
            $prestamo->banco_id = $request->get('idBanco');
            $prestamo->sucursal_id = $request->get('sucursal_id');
            $prestamo->prestamo_estado = 1;
            $prestamo->empresa_id = Auth::user()->empresa_id;
            $prestamo->save();
            $Banco=Banco::findOrFail($request->get('idBanco'));
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de prestamo -> Con Monto '.$request->get('idMonto').' Con Banco '.$Banco->bancoLista->banco_lista_nombre,'0','Con Interes -> '.$request->get('idInteres'));
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('prestamos')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('prestamos')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
            $prestamos=Prestamo_Banco::findOrFail($id);
            return view('admin.bancos.prestamos.ver',['prestamos'=>$prestamos, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            
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
            $prestamos=Prestamo_Banco::findOrFail($id);
            return view('admin.bancos.prestamos.eliminar',['prestamos'=>$prestamos, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            
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
            $prestamo = Prestamo_Banco::findOrFail($id);
            $prestamo->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de prestamo -> '.$prestamo->prestamo_monto.' con Banco '.$prestamo->banco->bancoLista->banco_lista_nombre ,'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('prestamos')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('prestamos')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }
    }
}
