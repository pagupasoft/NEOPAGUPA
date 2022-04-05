<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anticipo_Proveedor;
use App\Models\Arqueo_Caja;
use App\Models\Banco;
use App\Models\Caja;
use App\Models\Proveedor;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class descuentoManualAnticipoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
        $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
        $proveedores = Proveedor::Proveedores()->get();
        $sucursales = sucursal::Sucursales()->get();
        $cajasxusuario=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first();                 
        $cajas = Caja::cajas()->get();
        return view('admin.cuentasPagar.descuentoManual.index',
        ['proveedores'=>$proveedores,
        'cajasxusuario'=>$cajasxusuario,
        'cajas'=>$cajas,
        'bancos'=>Banco::bancos()->get(),
        'sucursales'=>$sucursales,   
        'gruposPermiso'=>$gruposPermiso,             
        'permisosAdmin'=>$permisosAdmin]);
    
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
           
            $anticiposProveedoresMatriz = [];                
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $anticiposProveedores=Anticipo_Proveedor::AnticiposByProveedorFechaSucursal($request->get('proveedorID'),$request->get('sucursalID'),$request->get('idDesde'),$request->get('idHasta'))->get();   
            $count = 1;            
            foreach($anticiposProveedores as $anticipoProveedor){  
                $anticiposProveedoresMatriz[$count]['ID'] = $anticipoProveedor->anticipo_id;
                $anticiposProveedoresMatriz[$count]['Fecha'] = $anticipoProveedor->anticipo_fecha;
                $anticiposProveedoresMatriz[$count]['Valor'] = $anticipoProveedor->anticipo_valor;
                $anticiposProveedoresMatriz[$count]['Saldo'] = $anticipoProveedor->anticipo_saldo;
                $anticiposProveedoresMatriz[$count]['Tipo'] = $anticipoProveedor->anticipo_motivo;
                $anticiposProveedoresMatriz[$count]['Motivo'] = $anticipoProveedor->anticipo_motivo;
                $anticiposProveedoresMatriz[$count]['Proveedor'] = $anticipoProveedor->proveedor->proveedor_nombre;
                if(isset($anticipoProveedor->diario->diario_codigo)){
                    $anticiposProveedoresMatriz[$count]['Diario'] = $anticipoProveedor->diario->diario_codigo;
                }else{
                    $anticiposProveedoresMatriz[$count]['Diario'] = '';
                }
                
                $count = $count + 1;
            }
            $fechaselect =  $request->get('idHasta');
            $fechaselect2 =  $request->get('idDesde');
            $proveedorS =  $request->get('proveedorID'); 
            $sucursalS =  $request->get('sucursalID');
            $proveedores = Proveedor::Proveedores()->get();
            $sucursales = sucursal::Sucursales()->get();
            $cajasxusuario=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first();                 
            $cajas = Caja::cajas()->get();
            return view('admin.cuentasPagar.descuentoManual.index',
            ['anticiposProveedoresMatriz'=>$anticiposProveedoresMatriz,            
            'fechaselect'=>$fechaselect, 
            'fechaselect2'=>$fechaselect2, 
            'cajasxusuario'=>$cajasxusuario,
            'cajas'=>$cajas,
            'bancos'=>Banco::bancos()->get(),         
            'sucursalS'=>$sucursalS,
            'proveedorS'=>$proveedorS,           
            'sucursales'=>$sucursales,
            'proveedores'=>$proveedores,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('descuentoManualProveedores')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
