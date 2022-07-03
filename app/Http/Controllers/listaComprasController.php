<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Punto_Emision;
use App\Models\Transaccion_Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class listaComprasController extends Controller
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
            $proveedores=Transaccion_Compra::ProveedorDistinsc()->select('proveedor.proveedor_id','proveedor.proveedor_nombre')->distinct()->get();
            $sucursales=Transaccion_Compra::SucursalDistinsc()->select('sucursal_nombre')->distinct()->get();
            return view('admin.compras.listaCompras.index',['proveedores'=>$proveedores,'sucursales'=>$sucursales,'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
        try{ 
           $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $proveedores=Transaccion_Compra::ProveedorDistinsc()->select('proveedor.proveedor_id','proveedor.proveedor_nombre')->distinct()->get();
            $sucursales=Transaccion_Compra::SucursalDistinsc()->select('sucursal_nombre')->distinct()->get();
            $transaccionCompras=null;

            if ($request->get('fecha_todo') != "on" && $request->get('idProveedor') != "--TODOS--" && $request->get('sucursal') != "--TODOS--"){                 
                $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_fecha','>=',$request->get('idDesde'))
                ->where('transaccion_fecha','<=',$request->get('idHasta'))
                ->where('proveedor_id','=',$request->get('idProveedor'))
                ->where('sucursal_nombre','=',$request->get('sucursal'))
                ->where('transaccion_estado','=','1')->distinct()->get();
            } 
            if ($request->get('fecha_todo') == "on" && $request->get('idProveedor') == "--TODOS--" && $request->get('sucursal') == "--TODOS--"){  
                $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
            }
            if ($request->get('fecha_todo') != "on" && $request->get('idProveedor') == "--TODOS--" && $request->get('sucursal') == "--TODOS--"){  
                 $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('transaccion_fecha','>=',$request->get('idDesde'))
                ->where('transaccion_fecha','<=',$request->get('idHasta'))
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
            }
            if ($request->get('fecha_todo') == "on" && $request->get('idProveedor') != "--TODOS--" && $request->get('sucursal') == "--TODOS--"){  
                $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('proveedor_id','=',$request->get('idProveedor'))
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
            }
            if ($request->get('fecha_todo') == "on" && $request->get('idProveedor') == "--TODOS--" && $request->get('sucursal') != "--TODOS--"){  
                $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('sucursal_nombre','=',$request->get('sucursal'))
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
            }
            if ($request->get('fecha_todo') == "on" && $request->get('idProveedor') != "--TODOS--" && $request->get('sucursal') != "--TODOS--"){  
                 $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('proveedor_id','=',$request->get('idProveedor'))
                ->where('sucursal_nombre','=',$request->get('sucursal'))
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
            }
            if ($request->get('fecha_todo') != "on" && $request->get('idProveedor') == "--TODOS--" && $request->get('sucursal') != "--TODOS--"){  
                $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('transaccion_fecha','>=',$request->get('idDesde'))
                ->where('transaccion_fecha','<=',$request->get('idHasta'))
                ->where('sucursal_nombre','=',$request->get('sucursal'))
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
            }
            if ($request->get('fecha_todo') != "on" && $request->get('idProveedor') != "--TODOS--" && $request->get('sucursal') == "--TODOS--"){  
                $transaccionCompras=Transaccion_Compra::reporteTransacciones()
                ->where('transaccion_fecha','>=',$request->get('idDesde'))
                ->where('transaccion_fecha','<=',$request->get('idHasta'))
                ->where('proveedor_id','=',$request->get('idProveedor'))
                ->where('tipo_comprobante.empresa_id','=',Auth::user()->empresa_id)
                ->where('transaccion_estado','=','1')->distinct()->orderBy('transaccion_fecha','asc')->get();
              
            }
            return view('admin.compras.listaCompras.index',['sucursales'=>$sucursales,'idsucursal'=>$request->get('sucursal'),'fecha_todo'=>$request->get('fecha_todo'),'fecI'=>$request->get('idDesde'),'fecF'=>$request->get('idHasta'),'transaccionCompras'=>$transaccionCompras,'proveedores'=>$proveedores,'nombre_cliente'=>$request->get('idProveedor'),'PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]); 
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
}
