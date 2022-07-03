<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Punto_Emision;
use App\Models\Medicamento;
use App\Models\Producto;
use App\Models\Tipo_Medicamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class medicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();        
            $medicamentos=Medicamento::medicamentos()->get();  
            $tipoMedicamentos=Tipo_Medicamento::tipoMedicamentos()->get();
            $productos=Producto::ProductosMedicamentos()->get();
            return view('admin.citasMedicas.medicamento.index',['productos'=>$productos,'tipoMedicamentos'=>$tipoMedicamentos,'medicamentos'=>$medicamentos,'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
            $medicamento = new Medicamento();
         
            $medicamento->medicamento_composicion = $request->get('idComposicion');    
            $medicamento->medicamento_indicacion = $request->get('idIndicacion');  
            $medicamento->medicamento_contraindicacion = $request->get('idContraindicacion');         
            $medicamento->medicamento_estado  = 1;
            $medicamento->tipo_id = $request->get('idTipo');
            $medicamento->producto_id = $request->get('idproducto');
            $medicamento->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de medicamento -> con producto id'.$request->get('idproducto'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('medicamento')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('medicamento')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
        $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
        $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
        $medicamento=Medicamento::medicamento($id)->first();
        $tipoMedicamentos=Tipo_Medicamento::tipoMedicamentos()->get();

        if($medicamento){
            return view('admin.citasMedicas.medicamento.ver',['tipoMedicamentos'=>$tipoMedicamentos,'medicamento'=>$medicamento, 'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }else{
            return redirect('/denegado');
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
        $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
        $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
        $medicamento=Medicamento::medicamento($id)->first();
        $tipoMedicamentos=Tipo_Medicamento::tipoMedicamentos()->get();
        $productos=Producto::ProductosMedicamentos()->get();
        if($medicamento){
            return view('admin.citasMedicas.medicamento.editar',['productos'=>$productos,'tipoMedicamentos'=>$tipoMedicamentos,'medicamento'=>$medicamento, 'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }else{
            return redirect('/denegado');
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
            $medicamento = Medicamento::findOrFail($id);
            $medicamento->medicamento_composicion = $request->get('idComposicion');    
            $medicamento->medicamento_indicacion = $request->get('idIndicacion');  
            $medicamento->medicamento_contraindicacion = $request->get('idContraindicacion');        
            if ($request->get('idEstado') == "on"){
                $medicamento->medicamento_estado = 1;
            }else{
                $medicamento->medicamento_estado = 0;
            }
            $medicamento->tipo_id = $request->get('idTipo');
            $medicamento->producto_id = $request->get('idproducto');
            $medicamento->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de medicamento -> con produco Id '.$request->get('idproducto'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('medicamento')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('medicamento')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $medicamento = Medicamento::findOrFail($id);
            $medicamento->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de Medicamento -> '.$medicamento->producto_id,'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('medicamento')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('medicamento')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }        
    }

    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicamento=Medicamento::medicamento($id)->first();
            $tipoMedicamentos=Tipo_Medicamento::tipoMedicamentos()->get();
            if($medicamento){
                return view('admin.citasMedicas.medicamento.eliminar',['tipoMedicamentos'=>$tipoMedicamentos,'medicamento'=>$medicamento, 'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function buscarBy($buscar){
        return Producto::BuscarProductosmedicinas($buscar)->get();
    }

    public function buscarId($buscar){
        return Producto::findOrFail($buscar);
    }
}
