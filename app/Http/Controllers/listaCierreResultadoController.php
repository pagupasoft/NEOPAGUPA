<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Diario;
use App\Models\Punto_Emision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class listaCierreResultadoController extends Controller
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
            $sucursal=Diario::DiarioSucursalCierre()->select('sucursal.sucursal_id', 'sucursal_nombre')->distinct()->get();
            return view('admin.contabilidad.listaCierre.indexResultado',['sucursales'=>$sucursal,'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $sucursal=Diario::DiarioSucursalCierre()->select('sucursal.sucursal_id', 'sucursal_nombre')->distinct()->get();
            $diarios=Diario::DiariosCierres($request->get('sucursal'),$request->get('idDesde'),$request->get('idHasta'))->get();
            return view('admin.contabilidad.listaCierre.indexResultado',['diarios'=>$diarios,'idsucursal'=>$request->get('sucursal'),'fecI'=>$request->get('idDesde'),'fecF'=>$request->get('idHasta'),'sucursales'=>$sucursal,'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function verEliminar($id){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.contabilidad.listaCierre.eliminar',['diarioS'=>Diario::findOrFail($id),'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('listaCierreResultado')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function eliminar(Request $request){
        try{
            DB::beginTransaction();
            $general = new generalController();
            $diario =  Diario::findOrFail($request->get('IdDiario'));
            $cierre = $general->cierre($diario->diario_fecha);
            if($cierre){
                return redirect('listaCierreResultado')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
            }
            foreach($diario->detalles as $detalle){
                $detalle->delete();
                $general->registrarAuditoria('Eliminacion de detalle deDiario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo,'');
            }
            $diario->delete();
            $general->registrarAuditoria('Eliminacion de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo,'');
            DB::commit();
            return redirect('listaCierreResultado')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('listaCierreResultado')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
