<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Bodega;
use App\Models\Punto_Emision;
use App\Models\Factura_Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Sucursal;

class listaVentasController extends Controller
{
    public function nuevo()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $puntoEmisiones = Punto_Emision::puntos()->get();
            $clientes = Factura_Venta::ClienteDistinsc()->select('cliente.cliente_id','cliente.cliente_nombre')->distinct()->get();
            $bodegas = Factura_Venta::BodegaDistinsc()->select('bodega_nombre')->distinct()->get();        
            $surcursal = Factura_Venta::SurcusalDistinsc()->select('sucursal_nombre')->distinct()->get();
            $reporteVentas=null;
            $total=0;
            return view('admin.ventas.listaVentas.index',['total'=>$total,'surcursal'=>$surcursal,'reporteVentas'=>$reporteVentas,'clientes'=>$clientes, 'bodegas'=>$bodegas,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
    public function consultar(Request $request)
    {
        try{
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono', 'grupo_orden')->join('rol_permiso', 'usuario_rol.rol_id', '=', 'rol_permiso.rol_id')->join('permiso', 'permiso.permiso_id', '=', 'rol_permiso.permiso_id')->join('grupo_permiso', 'grupo_permiso.grupo_id', '=', 'permiso.grupo_id')->where('permiso_estado', '=', '1')->where('usuario_rol.user_id', '=', Auth::user()->user_id)->orderBy('grupo_orden', 'asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso', 'usuario_rol.rol_id', '=', 'rol_permiso.rol_id')->join('permiso', 'permiso.permiso_id', '=', 'rol_permiso.permiso_id')->where('permiso_estado', '=', '1')->where('usuario_rol.user_id', '=', Auth::user()->user_id)->orderBy('permiso_orden', 'asc')->get();
                $valor_cliente=$request->get('nombre_cliente');
                $valor_bodega=$request->get('nombre_bodega');
                $valor_emision=$request->get('nombre_emision');
                $clientes = Factura_Venta::ClienteDistinsc()->select('cliente.cliente_id','cliente.cliente_nombre')->distinct()->get();
                $bodegas = Factura_Venta::BodegaDistinsc()->select('bodega_nombre')->distinct()->get();        
                $surcursal = Factura_Venta::SurcusalDistinsc()->select('sucursal_nombre')->distinct()->get();
                $puntoEmisiones = Punto_Emision::puntos()->get();
                $suma=null;
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--" && $request->get('sucursal') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')
                    ->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')
                    ->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')
                    ->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')
                    ->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')
                    ->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')
                    ->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')
                    ->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')
                    ->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')
                    ->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')
                    ->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')
                    ->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')
                    ->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_estado','<>','2')
                                ->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')->get();

                }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--" && $request->get('sucursal') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                            ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                            ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                            ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                            ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                            ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                            ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                            ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                            ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                            ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                            ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                            ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                            ->where('factura_venta.factura_estado','<>','2')
                            ->groupBy('grupo_nombre')->get();
                }
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->where('factura_venta.factura_estado','<>','2')
                                ->groupBy('grupo_nombre')->get();
                }
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                                $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->where('factura_venta.factura_estado','<>','2')
                                ->groupBy('grupo_nombre')->get();
                }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('factura_venta.factura_estado','<>','2')->get();

                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    
                }
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
                
                }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
              
                }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                    ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
              
                            }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
               
                            }
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
               
                            }
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
               
                            }

                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
                
                            }
                
                if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                               ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
                
                            }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')->where('factura_venta.factura_estado','<>','2')
                                ->get();
               
                            }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') != "--TODOS--" && $request->get('nombre_bodega') == "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('sucursal.sucursal_nombre', '=', $request->get('sucursal'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
                
                            }
                if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') == "--TODOS--" && $request->get('nombre_bodega') != "--TODOS--") {
                    $reporteVentas=DB::table('factura_venta')->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))
                                ->where('factura_venta.factura_estado','<>','2')->get();
                    $reportedetalle=DB::table('factura_venta')->join('detalle_fv', 'detalle_fv.factura_id', '=', 'factura_venta.factura_id')
                    ->join('producto', 'producto.producto_id', '=', 'detalle_fv.producto_id')
                    ->join('grupo_producto', 'grupo_producto.grupo_id', '=', 'producto.grupo_id')
                    ->join('cliente', 'cliente.cliente_id', '=', 'factura_venta.cliente_id')
                    ->join('bodega', 'bodega.bodega_id', '=', 'factura_venta.bodega_id')
                    ->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')
                    ->join('rango_documento', 'rango_documento.rango_id', '=', 'factura_venta.rango_id')
                    ->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                                ->where('factura_venta.factura_fecha', '>=', $request->get('fecha_desde'))
                                ->where('factura_venta.factura_fecha', '<=', $request->get('fecha_hasta'))
                                ->where('cliente.cliente_nombre', '=', $request->get('nombre_cliente'))
                                ->where('bodega.bodega_nombre', '=', $request->get('nombre_bodega'))->select('grupo_nombre',DB::raw("SUM(detalle_total) as total"))
                                ->groupBy('grupo_nombre')
                                ->where('factura_venta.factura_estado','<>','2')->get();
                
                            }
                $count=1;
    
                $validar=false;
                $datos=null;
                $total=0;
             
                foreach($reportedetalle as $report){        
                            $datos[$count]["Numero"]=$count;
                            $datos[$count]["Grupo"]=$report->grupo_nombre;
                            $datos[$count]["valor"]=$report->total;
                            $count++;
                            $total=$total+$report->total;     
                        
                        

                }
                return view('admin.ventas.listaVentas.index', ['total'=>$total,'idsucursal'=>$request->get('sucursal'),'surcursal'=>$surcursal,'datos'=>$datos,'fecha_desde'=>$request->get('fecha_desde'),'fecha_hasta'=>$request->get('fecha_hasta'),'valor_bodega'=>$valor_bodega,'nombre_emision'=>$request->get('nombre_emision'),'nombre_cliente'=>$request->get('nombre_cliente'),'fecha_todo'=>$request->get('fecha_todo'),'reporteVentas'=>$reporteVentas, 'puntoEmisiones'=>$puntoEmisiones, 'clientes'=>$clientes, 'bodegas'=>$bodegas, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }  
     
    }
}
