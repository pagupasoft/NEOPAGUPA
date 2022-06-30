<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Http\Controllers\Controller;
use App\Models\GrupoPer;
use App\Models\Punto_Emision;
use App\Models\Tipo_Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class permisoController extends Controller
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
            $permisos=Permiso::permisos()->get();
            $gruposPers=GrupoPer::grupos()->get();
            return view('admin.seguridad.permiso.index',['gruposPers'=>$gruposPers,'permisos'=>$permisos, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
            $activador=false;
            $grupo=GrupoPer::findOrFail($request->get('idGrupo'));
            foreach($grupo->detalles as $detalle){
                if($detalle->tipo_nombre==$request->get('idTipo')){
                    $activador=true;
                    $tipo=Tipo_Grupo::findOrFail($detalle->tipo_id);
                }

            }
            if($activador==false){
                $tipo = new Tipo_Grupo();
                $tipo->tipo_nombre = $request->get('idTipo');
                $tipo->tipo_icono = 'fas fa-circle';
                if($request->get('idTipo')=='MANTENIMIENTOS'){
                    $tipo->tipo_orden = '1';
                }
                if($request->get('idTipo')=='TRANSACCIONES'){
                    $tipo->tipo_orden = '2';
                }
                if($request->get('idTipo')=='REPORTES Y CONSULTAS'){
                    $tipo->tipo_orden = '3';
                }
                $tipo->tipo_estado = 1;
                $tipo->grupo_id  = $request->get('idGrupo');
                $tipo->save();
                $auditoria = new generalController();
                $auditoria->registrarAuditoria('Registro de tipo de Grupo -> '.$request->get('idTipo'),'0','');
            }
            $permiso = new Permiso();
            $permiso->permiso_nombre = $request->get('idNombre');
            $permiso->permiso_ruta = $request->get('idRuta');
            $permiso->permiso_tipo = $request->get('idTipo');
            $permiso->permiso_icono = $request->get('idIcono');
            $permiso->permiso_orden = $request->get('idOrden');
            $permiso->permiso_estado = 1;
            $permiso->empresa_id = Auth::user()->empresa_id;
            $permiso->grupo_id  = $request->get('idGrupo');
            $permiso->tipo_id  = $tipo->tipo_id;
            $permiso->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de permiso -> '.$request->get('idNombre'),'0','Ruta del permiso -> '.$request->get('idRuta').' agregado al grupo cuyo id -> '.$request->get('idGrupo'));
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('permiso')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('permiso')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $permiso=Permiso::permiso($id)->first();
            if($permiso){
                return view('admin.seguridad.permiso.ver',['permiso'=>$permiso, 'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
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
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $gruposPers=GrupoPer::grupos()->get();
            $permiso=Permiso::permiso($id)->first();
            if($permiso){
                return view('admin.seguridad.permiso.editar',['gruposPers'=>$gruposPers,'permiso'=>$permiso, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
        try{
            DB::beginTransaction();
            $activador=false;
            $grupo=GrupoPer::findOrFail($request->get('idGrupo'));
            foreach($grupo->detalles as $detalle){
                if($detalle->tipo_nombre==$request->get('idTipo')){
                    $activador=true;
                    $tipo=Tipo_Grupo::findOrFail($detalle->tipo_id);
                }

            }
            if($activador==false){
                $tipo = new Tipo_Grupo();
                $tipo->tipo_nombre = $request->get('idTipo');
                $tipo->tipo_icono = 'fas fa-circle';
                if($request->get('idTipo')=='MANTENIMIENTOS'){
                    $tipo->tipo_orden = '1';
                }
                if($request->get('idTipo')=='TRANSACCIONES'){
                    $tipo->tipo_orden = '2';
                }
                if($request->get('idTipo')=='REPORTES Y CONSULTAS'){
                    $tipo->tipo_orden = '3';
                }
                $tipo->tipo_estado = 1;
                $tipo->grupo_id  = $request->get('idGrupo');
                $tipo->save();
                $auditoria = new generalController();
                $auditoria->registrarAuditoria('Registro de tipo de Grupo -> '.$request->get('idTipo'),'0','');
            }

            $permiso = Permiso::findOrFail($id);
            $permiso->permiso_nombre = $request->get('idNombre');
            $permiso->permiso_ruta = $request->get('idRuta');
            $permiso->permiso_tipo = $request->get('idTipo');
            $permiso->permiso_icono = $request->get('idIcono');
            $permiso->permiso_orden = $request->get('idOrden');       
            if ($request->get('idEstado') == "on"){
                $permiso->permiso_estado = 1; 
            }else{
                $permiso->permiso_estado = 0;
            }
            $permiso->grupo_id  = $request->get('idGrupo');
            $permiso->tipo_id  = $tipo->tipo_id;
            $permiso->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de permiso -> '.$request->get('idNombre'),'0','Permiso con id -> '.$id.' ruta del permiso -> '.$request->get('idRuta').' agregado al grupo cuyo id -> '.$request->get('idGrupo'));
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('permiso')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('permiso')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
            $permiso = Permiso::findOrFail($id);
            $permiso->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de permiso -> '.$permiso->permido_nombre,'0','Permiso con id -> '.$id);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('permiso')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('permiso')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');

        }
    }
    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $permiso=Permiso::permiso($id)->first();
            if($permiso){
                return view('admin.seguridad.permiso.eliminar',['permiso'=>$permiso, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
