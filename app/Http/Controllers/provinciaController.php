<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use App\Models\Punto_Emision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class provinciaController extends Controller
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
            $provincias = Provincia::provincias()->get();   
            $paises = Pais::paises()->get();
            return view('admin.configuracion.provincia.index',['paises'=>$paises, 'PE'=>Punto_Emision::puntos()->get(),'provincias'=>$provincias, 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
            $provincia = new Provincia();
            $provincia->provincia_nombre = $request->get('provincia_nombre');
            $provincia->provincia_codigo = $request->get('provincia_codigo');
            $provincia->pais_id = $request->get('pais_id');
            $provincia->provincia_estado = 1;
            $provincia->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de provincia -> '.$request->get('provincia_nombre'),'0','Asignada al pais con id -> '.$request->get('pais_id'));
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('provincia')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('provincia')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $provincia=Provincia::provincia($id)->first();
            if($provincia){
                return view('admin.configuracion.provincia.ver',['provincia'=>$provincia, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
            $paises = Pais::paises()->get();
            $provincia=Provincia::provincia($id)->first();
            if($provincia){
                return view('admin.configuracion.provincia.editar', ['paises'=>$paises, 'PE'=>Punto_Emision::puntos()->get(),'provincia'=>$provincia, 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
            $provincia = Provincia::findOrFail($id);
            $provincia->provincia_nombre = $request->get('provincia_nombre');
            $provincia->provincia_codigo = $request->get('provincia_codigo');
            $provincia->pais_id = $request->get('pais_id');
            if ($request->get('provincia_estado') == "on"){
                $provincia->provincia_estado = 1;
            }else{
                $provincia->provincia_estado = 0;
            }            
            $provincia->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de provincia -> '.$request->get('provincia_nombre'),'0','Asignada al pais con id -> '.$request->get('pais_id'));
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('provincia')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('provincia')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $provincia = Provincia::findOrFail($id);
            $provincia->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de provincia -> '.$provincia->provincia_nombre,'0','Asignada al pais con id -> '.$provincia->pais_id);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('provincia')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('provincia')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }
    }

    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $provincia=Provincia::provincia($id)->first();
            if($provincia){
                return view('admin.configuracion.provincia.eliminar',['provincia'=>$provincia, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
