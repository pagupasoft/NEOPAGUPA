<?php

namespace App\Http\Controllers;

use App\Models\Procedimiento_Especialidad;
use App\Models\Tipo_Cliente;
use App\Models\Cliente;
use App\Models\Especialidad;
use App\Models\Aseguradora_Procedimiento;
use App\Models\Punto_Emision;
use App\Http\Controllers\Controller;
use App\Models\Orden_Atencion;
use Dompdf\FrameDecorator\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

class aseguradoraProcedimientoController extends Controller
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
            $aseguradoraProcedimientos=Aseguradora_Procedimiento::aseguradoraProcedimientos()->get();
            $clienteAseguradoras = Cliente::ClienteAseguradora()->get();
            $procedimientoEspecialidades=Procedimiento_Especialidad::procedimientoEspecialidades()->get();
            $especialidades=Especialidad::especialidades()->get();
            return view('admin.agendamientoCitas.aseguradoraProcedimiento.index',['especialidades'=>$especialidades, 'procedimientoEspecialidad'=>$procedimientoEspecialidades, 'clienteAseguradoras'=>$clienteAseguradoras, 'aseguradoraProcedimientos'=>$aseguradoraProcedimientos,'PE'=>Punto_Emision::puntos()->get(), 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
         
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }
    }

    public function procedimiento($id)
    {
        try{ 
        $gruposPermiso = DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono', 'grupo_orden')->join('rol_permiso', 'usuario_rol.rol_id', '=', 'rol_permiso.rol_id')->join('permiso', 'permiso.permiso_id', '=', 'rol_permiso.permiso_id')->join('grupo_permiso', 'grupo_permiso.grupo_id', '=', 'permiso.grupo_id')->where('permiso_estado', '=', '1')->where('usuario_rol.user_id', '=', Auth::user()->user_id)->orderBy('grupo_orden', 'asc')->distinct()->get();
        $permisosAdmin = DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso', 'usuario_rol.rol_id', '=', 'rol_permiso.rol_id')->join('permiso', 'permiso.permiso_id', '=', 'rol_permiso.permiso_id')->where('permiso_estado', '=', '1')->where('usuario_rol.user_id', '=', Auth::user()->user_id)->orderBy('permiso_orden', 'asc')->get();
        $cliente=Cliente::cliente($id)->first();
        $especialidades=Especialidad::especialidades()->get();
        if ($cliente) {
            return view('admin.agendamientoCitas.aseguradoraProcedimiento.procedimiento',['especialidades'=>$especialidades, 'cliente'=>$cliente,'PE'=>Punto_Emision::puntos()->get(), 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        } else {
            return redirect('/denegado');
        }  
        }catch(\Exception $ex){
           
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }      
    }

    public function guardarProcedimiento(Request $request, $id){
            $procedimiento = $request->get('Pprocedimiento');
            $Pcosto = $request->get('Pcosto');
            $PcodigoT = $request->get('PcodigoT');
            $check = $request->get('Pcheckbox');
            $idasegura = $request->get('ide');
            $auditoria = new generalController();
          
            for ($i = 1; $i < count($idasegura); ++$i) {
                $aseguradoras = Procedimiento_Especialidad::ProcedimientoProductoEspecialidad($idasegura[$i],$request->get('especialidad_id'))->first();
                $procedimiento=Aseguradora_Procedimiento::ProcedimientosAsignados($aseguradoras->procedimiento_id, $request->get('cliente_id'))->get();
                foreach ($procedimiento as $procedimientos) {
                    $procedimientos->delete();
                    $auditoria->registrarAuditoria('Eliminacion de procedimientos con id -> ' . $procedimientos->procedimientoA_codigo, $aseguradoras->procedimiento_id, '');
                }  
            }

            if($check){
                for ($i = 0; $i < count($check); ++$i) {
                    $aseguradoras = Procedimiento_Especialidad::ProcedimientoProductoEspecialidad($idasegura[$check[$i]],$request->get('especialidad_id'))->first();
                    $aseguradora_procedimiento = new Aseguradora_Procedimiento();
                    $aseguradora_procedimiento->procedimientoA_valor = $Pcosto[$check[$i]];
                    $aseguradora_procedimiento->procedimientoA_codigo = $PcodigoT[$check[$i]];
                    $aseguradora_procedimiento->procedimientoA_estado = 1;
                    $aseguradora_procedimiento->procedimiento_id = $aseguradoras->procedimiento_id;
                    $aseguradora_procedimiento->cliente_id =  $request->get('cliente_id');
                    $aseguradora_procedimiento->save();
                    $auditoria->registrarAuditoria('Registro de procedimientos con id -> ' .$aseguradoras->procedimiento_id.' Con cliente id'.$request->get('cliente_id'), '0', 'Los procedimientos con Codigo-> ' . $PcodigoT[$check[$i]].' Con costo -> ' . $Pcosto[$check[$i]]);           
                }
            }
            return redirect('aseguradoraProcedimiento')->with('success', 'Datos guardados exitosamente');
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

    public function buscarByNombre($buscar) {       
        return Procedimiento_Especialidad::procedimientoEspecialidadE($buscar)->get();        
    }

    public function buscarByClienteId(Request $request){
        return Aseguradora_Procedimiento::ProcedimientosAsignados($request->get('procedimiento'),$request->get('aseguradora'))->get();
    }
}
