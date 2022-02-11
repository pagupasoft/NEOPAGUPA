<?php

namespace App\Http\Controllers;

use App\Models\Punto_Emision;
use App\Models\Retencion_Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class listaRetencionRecibidaController extends Controller
{
    public function nuevo()
    {
        try{ 
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $sucursal=Retencion_Venta::Sucursales()->select('sucursal_nombre')->distinct()->get();
            return view('admin.sri.listaRetencionesRecibidas.index',['sucursal'=>$sucursal,'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function consultar(Request $request)
    {  
        try{        
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $sucursal=Retencion_Venta::Sucursales()->select('sucursal_nombre')->distinct()->get();
            if ($request->get('sucursal') == "--TODOS--") {
                $retencionVentas=Retencion_Venta::listadoRetencionesEmitidas($request->get('idDesde'), $request->get('idHasta'))->distinct()->get();
            }
            if ($request->get('sucursal') != "--TODOS--") {
                $retencionVentas=Retencion_Venta::listadoRetencionesEmitidasSucursal($request->get('idDesde'),$request->get('idHasta'),$request->get('sucursal'))->distinct()->get();
            }
            return view('admin.sri.listaRetencionesRecibidas.index',['fecha_desde'=>$request->get('idDesde'),'fecha_hasta'=>$request->get('idHasta'),'sucursal'=>$sucursal,'retencionVentas'=>$retencionVentas,'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
