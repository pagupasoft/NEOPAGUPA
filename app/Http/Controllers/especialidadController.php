<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Punto_Emision;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Configuracion_Especialidad;
use App\Models\Signos_Vitales_Especialidad;
use Illuminate\Http\Request;

class especialidadController extends Controller
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
            $especialidades = Especialidad::especialidades()->get();   
            return view('admin.agendamientoCitas.especialidad.index',['especialidades'=>$especialidades,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
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
        return redirect('/denegado');
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
            $especialidad = new Especialidad();
            $especialidad->especialidad_codigo = $request->get('idCodigo');
            $especialidad->especialidad_nombre = $request->get('especialidad_nombre');
            $especialidad->especialidad_tipo = $request->get('especialidad_tipo');
            $especialidad->especialidad_duracion = $request->get('especialidad_duracion');
            if ($request->get('especialidad_flexible') == "on"){
                $especialidad->especialidad_flexible ="1";
            }else{
                $especialidad->especialidad_flexible ="0";
            }
            $especialidad->especialidad_estado = 1;
            $especialidad->empresa_id = Auth::user()->empresa_id;
            $especialidad->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de especialidad -> '.$request->get('especialidad_nombre'),'0','de tipo -> '.$request->get('especialidad_tipo'));
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('especialidad')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('especialidad')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $especialidad = Especialidad::especialidad($id)->first();
            if($especialidad){
                return view('admin.agendamientoCitas.especialidad.ver',['especialidad'=>$especialidad, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }catch(\Exception $ex){
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
            $especialidad = Especialidad::especialidad($id)->first();
            if($especialidad){
                return view('admin.agendamientoCitas.especialidad.editar', ['especialidad'=>$especialidad, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }catch(\Exception $ex){
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
            $especialidad = Especialidad::findOrFail($id);
            $especialidad->especialidad_codigo = $request->get('idCodigo');
            $especialidad->especialidad_nombre = $request->get('especialidad_nombre');
            $especialidad->especialidad_tipo = $request->get('especialidad_tipo'); 
            $especialidad->especialidad_duracion = $request->get('especialidad_duracion');
            if ($request->get('especialidad_flexible') == "on"){
                $especialidad->especialidad_flexible ="1";
            }else{
                $especialidad->especialidad_flexible ="0";
            }                  
            if ($request->get('especialidad_estado') == "on"){
                $especialidad->especialidad_estado = 1;
            }else{
                $especialidad->especialidad_estado = 0;
            }
            $especialidad->save();       
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de especialidad -> '.$request->get('especialidad_nombre'),'0','de tipo -> '.$request->get('especialidad_tipo'));
            /*Fin de registro de auditoria */   
            DB::commit();
            return redirect('especialidad')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('especialidad')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $especialidad = Especialidad::findOrFail($id);
            $especialidad->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de producto -> '.$especialidad->especialidad_nombre.' con codigo de -> '.$especialidad->especialidad_tipo,'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('especialidad')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('especialidad')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }
    }

    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $especialidad = Especialidad::especialidad($id)->first();
            if($especialidad){
                return view('admin.agendamientoCitas.especialidad.eliminar',['especialidad'=>$especialidad, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        } 
    }
    public function configuracionEsecialidad($id){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.agendamientoCitas.especialidad.configuracionEspecialidad',['especialidad'=>Especialidad::findOrFail($id),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function configuracionEsecialidadGuardar(Request $request){
        try{            
            DB::beginTransaction();
            $campos = $request->get('Dcampo');
            $tipo = $request->get('Dtipo');
            $url = $request->get('DcampoUrl');
            $medida = $request->get('Dunidad');
            $countURL = 1;
            $countMEDIDA = 1;
            $configracion=Configuracion_Especialidad::where('especialidad_id','=',$request->get('especialidad_id'))->delete();
            for ($i = 3; $i < count($campos); ++$i){
                $configracion = new Configuracion_Especialidad();
                $configracion->configuracion_nombre = $campos[$i];
                $configracion->configuracion_tipo = $tipo[$i];
                $configracion->configuracion_medida = '';
                $configracion->configuracion_url = '';
                $configracion->configuracion_multiple = '';
                if($tipo[$i]=='2'){
                    $configracion->configuracion_medida = $medida[$countMEDIDA];
                    $countMEDIDA ++;
                }
                if($tipo[$i]=='3'){
                    $configracion->configuracion_medida = $url[$countURL];
                    $countURL ++;
                }
                $configracion->configuracion_estado = '1';
                $configracion->especialidad_id = $request->get('especialidad_id');
                $configracion->save();
            }
            DB::commit();
            return redirect('especialidad')->with('success','Datos registrados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('especialidad')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function signose($id){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.agendamientoCitas.especialidad.signose',['especialidad'=>Especialidad::findOrFail($id),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('especialidad')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function signoseGuardar(Request $request){
        try{            
            DB::beginTransaction();
            $campos = $request->get('Dcampo');
            $tipo = $request->get('Dtipo');
            $medida = $request->get('Dunidad');
            $countMEDIDA = 1;
            $signo=Signos_Vitales_Especialidad::where('especialidad_id','=',$request->get('especialidad_id'))->delete();
            for ($i = 2; $i < count($campos); ++$i){
                $signo = new Signos_Vitales_Especialidad();
                $signo->signose_nombre = $campos[$i];
                $signo->signose_tipo = $tipo[$i];
                $signo->signose_medida = '';
                if($tipo[$i]=='2'){
                    $signo->signose_medida = $medida[$countMEDIDA];
                    $countMEDIDA ++;
                }
                $signo->signose_estado = '1';
                $signo->especialidad_id = $request->get('especialidad_id');
                $signo->save();
            }
            DB::commit();
            return redirect('especialidad')->with('success','Datos registrados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('especialidad')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
