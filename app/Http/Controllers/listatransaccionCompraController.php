<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaccion_Compra;
use App\Models\Punto_Emision;
use App\Models\Sustento_Tributario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class listatransaccionCompraController extends Controller
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
            $compas=null; 
            $sucursal= Transaccion_Compra::SucursalDistinsc()->select('sucursal_nombre')->distinct()->get();  
            return view('admin.compras.listatransaccionCompra.index',['sucursales'=>$sucursal,'compas'=>$compas, 'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
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
    public function ver($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $compras=Transaccion_Compra::TransaccionID($id)->get()->first();      
            return view('admin.compras.listatransaccionCompra.view',['sustentos'=>Sustento_Tributario::Sustentos()->get(),'compras'=>$compras, 'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function store(Request $request)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $sucursal= Transaccion_Compra::SucursalDistinsc()->select('sucursal_nombre')->distinct()->get();  
            if ($request->get('fecha_todo') != "on"  && $request->get('sucursal') != "--TODOS--") {
                $compras=Transaccion_Compra::TransaccionFiltrar($request->get('fecha_desde'), $request->get('fecha_hasta'), $request->get('descripcion'), $request->get('sucursal'))->distinct()->get();
            }
            if ($request->get('fecha_todo') == "on"  && $request->get('sucursal') == "--TODOS--") {
                $compras=Transaccion_Compra::TransaccionOnFiltrar( $request->get('descripcion'))->distinct()->get();
            }
            if ($request->get('fecha_todo') != "on"  && $request->get('sucursal') == "--TODOS--") {
                $compras=Transaccion_Compra::TransaccionFechaFiltrar($request->get('fecha_desde'), $request->get('fecha_hasta'), $request->get('descripcion'))->distinct()->get();
            }
            if ($request->get('fecha_todo') == "on"  && $request->get('sucursal') != "--TODOS--") {
                $compras=Transaccion_Compra::TransaccionSucursalFiltrar($request->get('descripcion'), $request->get('sucursal'))->distinct()->get();
            }
            //$compras=Transaccion_Compra::TransaccionFiltrar($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('descripcion'))->select('tipo_comprobante_codigo','transaccion_fecha','transaccion_numero','tipo_comprobante_nombre','proveedor_nombre','transaccion_subtotal','transaccion_tarifa0','transaccion_tarifa12','transaccion_descuento','transaccion_porcentaje_iva','transaccion_total')->distinct()->get();    
 

            return view('admin.compras.listatransaccionCompra.index',['sucursales'=>$sucursal,'idsucursal'=>$request->get('sucursal'),'fecha_todo'=>$request->get('fecha_todo'),'fecha_desde'=>$request->get('fecha_desde'),'fecha_hasta'=>$request->get('fecha_hasta'),'descripcion'=>$request->get('descripcion'),'compras'=>$compras, 'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
