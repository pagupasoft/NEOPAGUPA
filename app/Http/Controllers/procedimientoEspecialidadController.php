<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Especialidad;
use App\Models\Procedimiento_Especialidad;
use App\Models\Punto_Emision;
use App\Http\Controllers\Controller;
use App\Models\Aseguradora_Procedimiento;
use App\Models\Entidad_Procedimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class procedimientoEspecialidadController extends Controller
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
            $procedimientoEspecialidades=Procedimiento_Especialidad::procedimientoEspecialidades()->get();
            $productos=Producto::productosG()->get();
            $especialidades=Especialidad::especialidades()->get();
            return view('admin.agendamientoCitas.procedimientoEspecialidad.index',['procedimientoEspecialidades'=>$procedimientoEspecialidades, 'productos'=>$productos, 'especialidades'=>$especialidades,'PE'=>Punto_Emision::puntos()->get(), 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function especialidad($id)
    {
        try{
            $gruposPermiso = DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono', 'grupo_orden')->join('rol_permiso', 'usuario_rol.rol_id', '=', 'rol_permiso.rol_id')->join('permiso', 'permiso.permiso_id', '=', 'rol_permiso.permiso_id')->join('grupo_permiso', 'grupo_permiso.grupo_id', '=', 'permiso.grupo_id')->where('permiso_estado', '=', '1')->where('usuario_rol.user_id', '=', Auth::user()->user_id)->orderBy('grupo_orden', 'asc')->distinct()->get();
            $permisosAdmin = DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso', 'usuario_rol.rol_id', '=', 'rol_permiso.rol_id')->join('permiso', 'permiso.permiso_id', '=', 'rol_permiso.permiso_id')->where('permiso_estado', '=', '1')->where('usuario_rol.user_id', '=', Auth::user()->user_id)->orderBy('permiso_orden', 'asc')->get();
            $especialidades=Especialidad::especialidades()->get();
            $producto=Producto::producto($id)->first();  
            $aseguradoras = Aseguradora_Procedimiento::AseguradoraProcedimientos()->get();
            $procedimientoEspecialidad=Procedimiento_Especialidad::procedimientoEspecialidades()->get();
            if($producto) {
                return view('admin.agendamientoCitas.procedimientoEspecialidad.especialidad', ['aseguradoras' => $aseguradoras, 'producto' => $producto, 'procedimientoEspecialidad' => $procedimientoEspecialidad, 'especialidades' => $especialidades, 'PE' => Punto_Emision::puntos()->get(), 'gruposPermiso' => $gruposPermiso, 'permisosAdmin' => $permisosAdmin]);
            } else {
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function guardarEspecialidades(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $EspeAsignadas = '';      
            $especialidades = Especialidad::especialidades()->get();
            $procedimientos = Procedimiento_Especialidad::procedimientoProducto($id)->get();
            $aseguradoras = Aseguradora_Procedimiento::AseguradoraProcedimientos()->get();

            foreach ($procedimientos as $procedimientoE) {
                $existe = false;
                foreach ($aseguradoras as $aseguradora) {
                    if($aseguradora->procedimiento_id == $procedimientoE->procedimiento_id){
                        $existe = true;
                    }
                }
                if(!$existe) {
                    $procedimiento = Procedimiento_Especialidad::where('procedimiento_id', '=',  $procedimientoE->procedimiento_id)->delete();
                }
            }

            foreach ($especialidades as $especialidad) {
                if ($request->get($especialidad->especialidad_id) == "on") {
                    $procedimiento = new Procedimiento_Especialidad;
                    $procedimiento->procedimiento_estado = 1;
                    $procedimiento->especialidad_id = $especialidad->especialidad_id;
                    $procedimiento->producto_id = $id;
                    $procedimiento->save();
                    $EspeAsignadas = $EspeAsignadas . ' - ' . $especialidad->especialidad_nombre;
                }
            }
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de productos con id -> ' . $id, '0', 'Las especialidades asignadas  fueron -> ' . $EspeAsignadas);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('procedimientoEspecialidad')->with('success', 'Datos guardados exitosamente');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect('procedimientoEspecialidad')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
        return redirect('/denegado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/denegado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect('/denegado');
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
        return redirect('/denegado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('/denegado');
    }
    public function buscarBy(Request $request){
        $datos=null;
        $proce=Procedimiento_Especialidad::ProcedimientoProductoEspecialidad($request->get('buscar'),$request->get('especialidad'))->first();
        $ase=Aseguradora_Procedimiento::ProcedimientosAsignados($proce->procedimiento_id,$request->get('Aseguradora'))->first();
        $entid=Entidad_Procedimiento::ValorAsignadoproducto($proce->procedimiento_id,$request->get('entidad'),$request->get('buscar'))->first();
        $datos[0]=$ase;
        $datos[1]=$entid;
        return $datos;
    }
}
