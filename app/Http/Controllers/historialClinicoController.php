<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Expediente;
use App\Models\Orden_Atencion;
use App\Models\Paciente;
use App\Models\Punto_Emision;
use App\Models\Tipo_Identificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class historialClinicoController extends Controller
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
            $pacientes=Paciente::Pacientes()->get();
            return view('admin.citasMedicas.historialClinico.index',['pacientes'=>$pacientes,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function historial($id)
    {
        try{
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();

                $paciente = Paciente::PacienteTipoIdentificacion($id)->first();

                if($paciente){
                    return view('admin.citasMedicas.historialClinico.historial',['paciente'=>$paciente,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
                }else{
                    return redirect('/denegado');
                }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function informacion($id){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();

            $orden=Orden_Atencion::findOrFail($id);
            $expediente=$orden->expediente;

            ///////////////diagnóstico///////////////////////////////
            $diagnostico=$expediente->diagnostico;
            
            if($diagnostico){
                $diagDetalle=$diagnostico->detallediagnostico;

                foreach($diagDetalle as $detalle){
                    $detalle->enfermedad;
                }
            }

            //////////////////prescripciones//////////////////////////
            $prescripcion=$expediente->prescripcion;

            if($prescripcion){
                $presDetalle=$prescripcion->presMedicamento;

                foreach($presDetalle as $detalle){
                    $detalle->medicamento->producto;
                }
            }


            //////////////////////examenes/////////////////////////////
            $examen=$expediente->ordenExamen;
            
            if($examen->analisis){
                $analisisDetalle=$examen->analisis->detalles;

                foreach($analisisDetalle as $detalle){
                    $detalle->detalles;
                }
            }
            

            //////////////////////imagenes/////////////////////////////
            $imagen=$expediente->ordenImagen;
            
            if($imagen){
                $imagDetalle=$imagen->detalleImagen;

                foreach($imagDetalle as $detalle){
                    $detalle->imagen;
                }
            }
            

            $data=[
                'diagnostico'=>$diagnostico,
                'examen'=>$examen,
                'prescripcion'=>$prescripcion,
                'imagen'=>$imagen
            ];


            return $data;
            
        
            //if($orden_atencion){
            //    return view('admin.citasMedicas.historialClinico.ver',['historial'=>$historial,'paciente'=>$paciente,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            //}else{
            //    return redirect('/denegado');
            //}
        }
        catch(\Exception $ex){      
            return response()->json(['result'=>'error', 'message'=>$ex->getMessage()], 500);
        }
    }

    public function ver($id)
    {
        try{
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();

                $paciente = Paciente::PacienteTipoIdentificacion($id)->first();
                $historial=Orden_Atencion::Historial($id)->get();
            
                if($paciente){
                    return view('admin.citasMedicas.historialClinico.ver',['historial'=>$historial,'paciente'=>$paciente,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
                }else{
                    return redirect('/denegado');
                }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
