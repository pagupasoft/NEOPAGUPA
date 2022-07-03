<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Bodega;
use App\Models\Proforma;
use App\Models\Punto_Emision;
use App\Models\Tarifa_Iva;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class listaProformaController extends Controller
{
    
    public function nuevo()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $clientes = Proforma::ClientesDistinsc()->select('cliente.cliente_id','cliente.cliente_nombre')->distinct()->get();
            $puntoEmisiones = Punto_Emision::puntos()->get();
            $proforma=Proforma::Proformas()->get();
            $sucursal = Proforma::SucursalDistinsc()->select('sucursal_nombre')->distinct()->get();
            return view('admin.ventas.proforma.view',['sucursal'=>$sucursal,'reporteproforma'=>$proforma,'clientes'=>$clientes, 'puntoEmisiones'=>$puntoEmisiones,'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function consultar(Request $request)
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $valor_cliente=$request->get('nombre_cliente');
            $clientes = Proforma::ClientesDistinsc()->select('cliente.cliente_id','cliente.cliente_nombre')->distinct()->get();
            $sucursal = Proforma::SucursalDistinsc()->select('sucursal_nombre')->distinct()->get();
            $puntoEmisiones = Punto_Emision::puntos()->get();
            
           
            if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') == "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                                ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)->get();
            }
            if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') != "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                        ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)                    
                        ->where('proforma.proforma_fecha', '>=', $request->get('fecha_desde'))
                        ->where('proforma.proforma_fecha', '<=', $request->get('fecha_hasta'))
                        ->where('sucursal_nombre', '<=', $request->get('sucursal'))
                        ->where('cliente_nombre', '=', $valor_cliente)->get();
                        
            }             
            if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') == "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)
                            ->where('proforma.proforma_fecha', '>=', $request->get('fecha_desde'))
                            ->where('proforma.proforma_fecha', '<=', $request->get('fecha_hasta'))
                            ->get();
            }
            if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') == "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)                
                            ->where('cliente_nombre', '=', $valor_cliente)->get();
            }   
            if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') != "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)                
                            ->where('sucursal_nombre', '<=', $request->get('sucursal'))->get();
            }  
            
            if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') == "--TODOS--" && $request->get('sucursal') != "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)  
                            ->where('proforma.proforma_fecha', '>=', $request->get('fecha_desde'))
                            ->where('proforma.proforma_fecha', '<=', $request->get('fecha_hasta'))              
                            ->where('sucursal_nombre', '<=', $request->get('sucursal'))->get();
            }  
            if ($request->get('fecha_todo') != "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') == "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)   
                            ->where('proforma.proforma_fecha', '>=', $request->get('fecha_desde'))
                            ->where('proforma.proforma_fecha', '<=', $request->get('fecha_hasta'))             
                            ->where('cliente_nombre', '=', $valor_cliente)->get();
            } 
            if ($request->get('fecha_todo') == "on" && $request->get('nombre_cliente') != "--TODOS--" && $request->get('sucursal') != "--TODOS--") {
                $reporteproforma=DB::table('proforma')->join('cliente', 'cliente.cliente_id', '=', 'proforma.cliente_id')->join('bodega', 'bodega.bodega_id', '=', 'proforma.bodega_id')->join('sucursal', 'sucursal.sucursal_id', '=', 'bodega.sucursal_id')->join('rango_documento', 'rango_documento.rango_id', '=', 'proforma.rango_id')->join('punto_emision', 'punto_emision.punto_id', '=', 'rango_documento.punto_id')
                            ->where('sucursal.empresa_id', '=', Auth::user()->empresa_id)                
                            ->where('sucursal_nombre', '<=', $request->get('sucursal'))->where('cliente_nombre', '=', $valor_cliente)->get();
            }   
            return view('admin.ventas.proforma.view', ['sucursal'=>$sucursal,'idsucursal'=>$request->get('sucursal'),'fecha_desde'=>$request->get('fecha_desde'),'fecha_hasta'=>$request->get('fecha_hasta'),'fecha_todo'=>$request->get('fecha_todo'),'nombre_cliente'=>$valor_cliente,'reporteproforma'=>$reporteproforma, 'puntoEmisiones'=>$puntoEmisiones, 'clientes'=>$clientes,  'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        
        }catch(\Exception $ex){
            return view('admin.ventas.proforma.view',['reporteproforma'=>$reporteproforma, 'puntoEmisiones'=>$puntoEmisiones, 'clientes'=>$clientes,  'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin])->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function editar($id)
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $proforma=Proforma::Proforma($id)->first();
            $clientes = Cliente::clientes()->get();   
            if($proforma){
                return view('admin.ventas.proforma.editar',['proforma'=>$proforma,'clientes'=>$clientes, 'bodegas'=>Bodega::bodegasSucursal($proforma->proforma_serie)->get(),'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(), 'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('listaProforma')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    
    
}
