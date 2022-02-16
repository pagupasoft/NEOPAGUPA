<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Expediente;
use App\Models\Medico;
use App\Models\Medico_Especialidad;
use App\Models\Orden_Atencion;
use App\Models\Paciente;
use App\Models\Prescripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Punto_Emision;
use PDF;

class atencionRecetasController extends Controller
{
    public function index(){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicos = Medico::medicos()->get();
            $id = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $id = $medico->medico_id;
                }
            }
            
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();
            $prescripciones = Prescripcion::prescripcionesPaciente()->get();
            $pacientes = Paciente::pacientes()->get();

            //return $prescripciones;

            return view('admin.citasMedicas.farmacia.index',['medico'=>$medico, 'mespecialidadM'=>$mespecialidadM,'prescripciones'=>$prescripciones, 'pacientes'=>$pacientes, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function entregarPrescripcion($orden){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicos = Medico::medicos()->get();
            $id = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $id = $medico->medico_id;
                }
            }
            
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();


            DB::beginTransaction();
            $prescripcionD = Prescripcion::prescripcionDetalle($orden)->get();
            $ordenAtencion = Orden_Atencion::findOrfail($orden);
            $expediente = Expediente::findByOrden($ordenAtencion->orden_id)->first();

            //return $prescripcionD;

            $pr = Prescripcion::findByExpediente($expediente->expediente_id)->first();

            $prescripcion = Prescripcion::findOrFail($pr->prescripcion_id);
            $prescripcion->prescripcion_estado=2;
            $prescripcion->save();
            

            $empresa = Empresa::empresa()->first();
            $view =  \View::make('admin.formatosPDF.ordenesAtenciones.farmacia.receta', ['ordenAtencion'=>$ordenAtencion, 'prescripcionD'=>$prescripcionD, 'empresa'=>$empresa]);
            $ruta = public_path().'/DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$ordenAtencion->orden_fecha.'/'.$ordenAtencion->orden_numero.'/Documentos';
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $nombreArchivo = 'Prescripcion';
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf');
            DB::commit();
            return redirect('receta/'.$ordenAtencion->orden_id)->with('success', 'Datos Actualizados exitosamente')->with('pdf2', 'DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$ordenAtencion->orden_fecha.'/'.$ordenAtencion->orden_numero.'/Documentos/'.$nombreArchivo.'.pdf');    
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function imprimirPrescripcion($orden){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicos = Medico::medicos()->get();
            $id = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $id = $medico->medico_id;
                }
            }
            
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();
            $prescripcionD = Prescripcion::prescripcionDetalle($orden)->get();
            $ordenAtencion = Orden_Atencion::findOrfail($orden);
 
            

            $empresa = Empresa::empresa()->first();
            $view =  \View::make('admin.formatosPDF.ordenesAtenciones.farmacia.receta', ['ordenAtencion'=>$ordenAtencion, 'prescripcionD'=>$prescripcionD, 'empresa'=>$empresa]);
            $ruta = public_path().'/DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$ordenAtencion->orden_fecha.'/'.$ordenAtencion->orden_numero.'/Documentos';
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $nombreArchivo = 'Prescripcion';
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf');
            DB::commit();
            return redirect('DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$ordenAtencion->orden_fecha.'/'.$ordenAtencion->orden_numero.'/Documentos/'.$nombreArchivo.'.pdf');    
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function buscarPrescripcion(Request $request){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicos = Medico::medicos()->get();
            $id = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $id = $medico->medico_id;
                }
            }
            
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();

            $prescripciones = Prescripcion::prescripcionesBusqueda($request)->get();
            $pacientes = Paciente::pacientes()->get();

            return view('admin.citasMedicas.farmacia.index',['medico'=>$medico, 'mespecialidadM'=>$mespecialidadM,'prescripciones'=>$prescripciones, 'pacienteID'=>$request->pacienteID, 'fDesde' => $request->fecha_desde, 'fHasta' => $request->fecha_hasta, 'prescripcionE'=>$request->estado, 'pacientes'=>$pacientes, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function showPrescripcion($orden){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicos = Medico::medicos()->get();
            $id = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $id = $medico->medico_id;
                }
            }
            
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();
            $prescripcionDet = Prescripcion::prescripcionDetalle($orden)->get();
            $ordenAtencion = Orden_Atencion::findOrfail($orden);

            $expediente = Expediente::findByOrden($ordenAtencion->orden_id)->first();
            $prescripcionCab = Prescripcion::findByExpediente($expediente->expediente_id)->first();
            //return $ordenAtencion."  <br><br><br>".$prescripcionD;

            return view('admin.citasMedicas.farmacia.receta',['medico'=>$medico, 'mespecialidadM'=>$mespecialidadM, 'ordenAtencion'=>$ordenAtencion, 'prescripcionCab'=>$prescripcionCab, 'prescripcionDet'=>$prescripcionDet, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
