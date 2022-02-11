<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Activo_Fijo;
use App\Models\Cuenta;
use App\Models\Diario;
use App\Models\Grupo_Activo;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Punto_Emision;
use App\Models\Sucursal;
use App\Models\Transaccion_Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class activoFijoController extends Controller
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
            $diarios=Diario::diarios()->get();
            $cuentas=Cuenta::CuentasMovimiento()->get();
            $productos=Producto::productos()->get();
            $proveedores=Proveedor::proveedores()->get();
            $sucursales=Sucursal::sucursales()->get();
            //$activoFijos=Activo_Fijo::ActivoFijos()->get();
            return view('admin.activosFijos.activoFijo.index',
            ['cuentas'=>$cuentas,        
            'sucursales'=>$sucursales,
            'productos'=>$productos,
            'diarios'=>$diarios,
            'proveedores'=>$proveedores,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
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
            $activoFijo = new Activo_Fijo();
            $activoFijo->activo_fecha_inicio = $request->get('idDesde');
            $activoFijo->activo_fecha_documento = $request->get('idFecha');
            $activoFijo->activo_descripcion = $request->get('idDescripcion');
            $activoFijo->activo_valor = $request->get('idValor');
            $activoFijo->activo_valor2 = $request->get('idValor');
            $activoFijo->activo_base_depreciar = $request->get('idBaseDepreciar');
            $activoFijo->activo_vida_util = $request->get('idVidaUtil');
            $activoFijo->activo_valor_util = $request->get('idValorUtil');
            $activoFijo->activo_depreciacion = $request->get('porcentaje_depreciacion');
            $activoFijo->activo_depreciacion_mensual = $request->get('idDepreciacionMensual');
            $activoFijo->activo_depreciacion_anual = $request->get('idDepreciacionAnual');
            $activoFijo->activo_depreciacion_acumulada = $request->get('idDepreciacionAcumulada');
            $activoFijo->activo_estado = 1;
            $activoFijo->grupo_id = $request->get('idGrupo');
            $activoFijo->diario_id = $request->get('idDiario');
            $activoFijo->producto_id = $request->get('idProducto');                
            $activoFijo->proveedor_id = $request->get('idProveedor');
            $activoFijo->transaccion_id = $request->get('idFactura');
            $activoFijo->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de Activo Fijo -> '.$request->get('idDescripcion'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('activoFijo')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('activoFijo')->with('error','Ocurrio un error vuelva a intentarlo');
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
            $activoFijo=Activo_Fijo::ActivoFijo($id)->first();
            $diarios=Diario::diarios()->get();
            $cuentas=Cuenta::CuentasMovimiento()->get();
            $productos=Producto::productos()->get();
            $proveedores=Proveedor::proveedores()->get();
            $sucursales=Sucursal::sucursales()->get();
            return view('admin.activosFijos.activoFijo.ver',
            ['cuentas'=>$cuentas,
            'activoFijo'=>$activoFijo,
            'sucursales'=>$sucursales,
            'productos'=>$productos,
            'diarios'=>$diarios,
            'proveedores'=>$proveedores,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('activoFijo')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $activoFijo=Activo_Fijo::ActivoFijo($id)->first();
            $gruposActivo=Grupo_Activo::GrupoxSucursal($activoFijo->grupoActivo->sucursal_id)->get();
            $diarios=Diario::diarios()->get();
            $cuentas=Cuenta::CuentasMovimiento()->get();
            $productos=Producto::productos()->get();
            $proveedores=Proveedor::proveedores()->get();
            $sucursales=Sucursal::sucursales()->get();
            return view('admin.activosFijos.activoFijo.editar',
            ['cuentas'=>$cuentas,
            'activoFijo'=>$activoFijo,
            'sucursales'=>$sucursales,
            'productos'=>$productos,
            'gruposActivo'=>$gruposActivo,
            'diarios'=>$diarios,    
            'proveedores'=>$proveedores,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('activoFijo')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
            $activoFijo=Activo_Fijo::findOrFail($id);
            $activoFijoAux=Activo_Fijo::findOrFail($id);               
            $activoFijo->activo_fecha_inicio = $request->get('idDesde');
            $activoFijo->activo_fecha_documento = $request->get('idFecha');
            $activoFijo->activo_descripcion = $request->get('idDescripcion');
            $activoFijo->activo_valor = str_replace(",","",$request->get('idValor'));
            $activoFijo->activo_valor2 = str_replace(",","",$request->get('idValor'));
            $activoFijo->activo_base_depreciar = str_replace(",","",$request->get('idBaseDepreciar'));
            $activoFijo->activo_vida_util = $request->get('idVidaUtil');
            $activoFijo->activo_valor_util = str_replace(",","",$request->get('idValorUtil'));
            $activoFijo->activo_depreciacion = $request->get('porcentaje_depreciacion');
            $activoFijo->activo_depreciacion_mensual = str_replace(",","",$request->get('idDepreciacionMensual'));
            $activoFijo->activo_depreciacion_anual = str_replace(",","",$request->get('idDepreciacionAnual'));
            $activoFijo->activo_depreciacion_acumulada = str_replace(",","",$request->get('idDepreciacionAcumulada'));            
            $activoFijo->grupo_id = $request->get('idGrupo');
            $activoFijo->diario_id = $request->get('idDiario');
            $activoFijo->producto_id = $request->get('idProducto');            
            if($request->get('rdDocumento') == 'FACTURA'){
                $activoFijo->proveedor_id = $request->get('idProveedor');
                $activoFijo->transaccion_id = $request->get('idFactura');
            }                           
            $activoFijo->save();       
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de Activo fijo -> '.$request->get('idNombre'),'0', 'Nombre anterior: '. $activoFijoAux->activo_descripcion);
            /*Fin de registro de auditoria */   
            DB::commit();
            return redirect('activoFijo')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('activoFijo')->with('error', 'Oucrrio un error en el procedimiento. Vuelva a intentar.');
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
            $activoFijo = Activo_Fijo::findOrFail($id);
            $activoFijoaux = Grupo_Activo::findOrFail($id);
            $activoFijo->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de Activo Acivo Fijo -> '.$activoFijoaux->activo_descripcion,'0','por el valor de'.$activoFijoaux->activo_valor);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('activoFijo')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('activoFijo')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }
    }
    public function delete($id)
    {
        try{            
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $activoFijo=Activo_Fijo::ActivoFijo($id)->first();
            $diarios=Diario::diarios()->get();
            $cuentas=Cuenta::CuentasMovimiento()->get();
            $productos=Producto::productos()->get();
            $proveedores=Proveedor::proveedores()->get();
            $sucursales=Sucursal::sucursales()->get();
            return view('admin.activosFijos.activoFijo.eliminar',
            ['cuentas'=>$cuentas,
            'activoFijo'=>$activoFijo,
            'sucursales'=>$sucursales,
            'productos'=>$productos,
            'diarios'=>$diarios,
            'proveedores'=>$proveedores,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('activoFijo')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function buscarActivo(Request $request){        
        try{            
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $activoFijos=Activo_Fijo::activoFijoxSucursal($request->get('idsucursal'))->get();
            $sucursalselect = $request->get('idsucursal');              
            $diarios=Diario::diarios()->get();
            $cuentas=Cuenta::CuentasMovimiento()->get();
            $productos=Producto::productos()->get();
            $proveedores=Proveedor::proveedores()->get();
            $sucursales=Sucursal::sucursales()->get();
            return view('admin.activosFijos.activoFijo.index',
            ['cuentas'=>$cuentas,
            'activoFijos'=>$activoFijos,
            'sucursales'=>$sucursales,
            'sucursalselect'=>$sucursalselect,
            'productos'=>$productos,
            'diarios'=>$diarios,
            'proveedores'=>$proveedores,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('activoFijo')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function buscarBySucursal($buscar){
        return Grupo_Activo::GrupoxSucursal($buscar)->get();
    }
    public function buscarCuenta($buscar){        
        $grupoActivo= Grupo_Activo::grupo($buscar)->first();
        return Grupo_Activo::GrupoxCuenta($grupoActivo->cuenta_depreciacion)->first();
    }
    public function buscarGasto($buscar){        
        $grupoActivo2= Grupo_Activo::grupo($buscar)->first();
        return Grupo_Activo::GrupoxCuentaGasto($grupoActivo2->cuenta_gasto)->first();
    }
    public function buscarPorcentaje($buscar){ 
        return Grupo_Activo::grupo($buscar)->first();
    }
    //buscarFactura
    public function buscarFactura($buscar){ 
        return Transaccion_Compra::Transacciones($buscar)->get();
    }
    //buscarFecha
    public function buscarFecha($buscar){
        return Transaccion_Compra::TransaccionID($buscar)->first();
    }
    //buscarFechaDiario
    public function buscarFechaDiario($buscar){
        return Diario::Diario($buscar)->first();
    }
    //sumatoriaDiario
    public function sumatoriaDiario($buscar){
        return Diario::Diariosuma($buscar)->first();
    }
}
