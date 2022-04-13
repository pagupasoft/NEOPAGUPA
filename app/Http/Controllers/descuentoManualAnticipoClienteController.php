<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anticipo_Cliente;
use App\Models\Arqueo_Caja;
use App\Models\Banco;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class descuentoManualAnticipoClienteController extends Controller
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
        $clientes = Cliente::clientes()->get();
        $sucursales = sucursal::Sucursales()->get();
        $cajasxusuario=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first();                 
        $cajas = Caja::cajas()->get();
        return view('admin.cuentasCobrar.descuentoManual.index',
        ['clientes'=>$clientes,
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
           
            $anticiposClientesMatriz = [];                
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $anticipoClientes=Anticipo_Cliente::AnticipoClienteByFechaSucursal($request->get('clienteID'),$request->get('sucursalID'),$request->get('idDesde'),$request->get('idHasta'))->get();   
            $count = 1;            
            foreach($anticipoClientes as $anticipoCliente){  
                $anticiposClientesMatriz[$count]['ID'] = $anticipoCliente->anticipo_id;
                $anticiposClientesMatriz[$count]['Fecha'] = $anticipoCliente->anticipo_fecha;
                $anticiposClientesMatriz[$count]['Valor'] = $anticipoCliente->anticipo_valor;
                $anticiposClientesMatriz[$count]['Saldo'] = $anticipoCliente->anticipo_saldo;
                $anticiposClientesMatriz[$count]['Tipo'] = $anticipoCliente->anticipo_motivo;
                $anticiposClientesMatriz[$count]['Motivo'] = $anticipoCliente->anticipo_motivo;
                $anticiposClientesMatriz[$count]['Cliente'] = $anticipoCliente->cliente->cliente_nombre;
                if(isset($anticipoCliente->diario->diario_codigo)){
                    $anticiposClientesMatriz[$count]['Diario'] = $anticipoCliente->diario->diario_codigo;
                }else{
                    $anticiposClientesMatriz[$count]['Diario'] = '';
                }
                
                $count = $count + 1;
            }
            $fechaselect =  $request->get('idHasta');
            $fechaselect2 =  $request->get('idDesde');
            $clienteS =  $request->get('clienteID'); 
            $sucursalS =  $request->get('sucursalID');
            $clientes = Cliente::clientes()->get();
            $sucursales = sucursal::Sucursales()->get();
            $cajasxusuario=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first();                 
            $cajas = Caja::cajas()->get();
            return view('admin.cuentasCobrar.descuentoManual.index',
            ['anticiposClientesMatriz'=>$anticiposClientesMatriz,            
            'fechaselect'=>$fechaselect, 
            'fechaselect2'=>$fechaselect2, 
            'cajasxusuario'=>$cajasxusuario,
            'cajas'=>$cajas,
            'bancos'=>Banco::bancos()->get(),         
            'sucursalS'=>$sucursalS,
            'clienteS'=>$clienteS,           
            'sucursales'=>$sucursales,
            'clientes'=>$clientes,
            'gruposPermiso'=>$gruposPermiso,         
            'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('descuentoManualClientes')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
