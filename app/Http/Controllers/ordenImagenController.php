<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Analisis_Laboratorio;
use App\Models\Arqueo_Caja;
use App\Models\Aseguradora_Procedimiento;
use App\Models\Bodega;
use App\Models\Cliente;
use App\Models\Entidad_Procedimiento;
use App\Models\Especialidad;
use App\Models\Factura_Venta;
use App\Models\Forma_Pago;
use App\Models\Orden_Examen;
use App\Models\Paciente;
use App\Models\Procedimiento_Especialidad;
use App\Models\Punto_Emision;
use App\Models\Rango_Documento;
use App\Models\Sucursal;
use App\Models\Tarifa_Iva;
use App\Models\Tipo_Seguro;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ordenExamenController extends Controller
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
            $ordenesAtencion=Orden_Examen::OrdenExamenesHOY()->select('orden_examen.orden_id as orden_examen_id', 'orden_atencion.orden_id', 'orden_fecha','orden_codigo','orden_numero', 'paciente_apellidos','paciente_nombres','orden_otros','orden_examen.orden_estado')->get();
           
            return view('admin.laboratorio.ordenesExamen.index',['sucursales'=>Sucursal::Sucursales()->get(),'ordenesAtencion'=>$ordenesAtencion,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function atender($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $orden = Orden_Examen::findOrFail($id);
            return view('admin.laboratorio.ordenesExamen.atender',['orden'=>$orden,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function facturarOrden($id)
    {
        //try{
            $count=1;
            $orden = Orden_Examen::findOrFail($id);
            if($orden){
                $puntoEmision = Punto_Emision::PuntoSucursalUser($orden->expediente->ordenatencion->sucursal_id,Auth::user()->user_id)->first();
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
                $sucursales=Sucursal::Sucursales()->get();
                $cajaAbierta=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first();
                $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'Analisis de Laboratorio')->first();
                $Paciente=Paciente::Paciente($orden->expediente->ordenatencion->paciente->paciente_id)->first();
                $especialidad=Especialidad::EspecialidadBuscar('Laboratorio')->first();
                $total=0;

                //echo json_encode($Paciente).'<br><br>paciente '.$orden->expediente->ordenatencion->paciente->paciente_id.'<br><br>';

                foreach($orden->detalle as $ordenes){

                    //echo json_encode($ordenes).'<br><br><br>';

                    $tcopago=0;
                    $procedimiento=Procedimiento_Especialidad::ProcedimientoProductoEspecialidad($ordenes->examen->producto->producto_id,$especialidad->especialidad_id)->first();
                    $producto=Aseguradora_Procedimiento::ProcedimientosAsignados($procedimiento->procedimiento_id,$Paciente->cliente_id)->first();
                    
                    //echo 'buscando<br><br>proc '.$procedimiento->procedimiento_id.'     cliente '.$Paciente->cliente_id.'<br>';
                    //echo '<br><br>';
                    //return 'producto id '.$ordenes->examen->producto->producto_id.',       procedimiento_id '.$procedimiento->procedimiento_id.',     cliente id: '.$Paciente->cliente_id;

                    if(!$producto){
                        //echo 'dfsdf';
                        //return redirect('inicio')->with('error','El procedimiento no pudo ser encontrado, consulte a su Administrador del Servicio');
                    }

                    //echo 'producto <br>'.json_encode($producto).'<br><br>';

                    $datos[$count]['idproducto']=$ordenes->examen->producto->producto_id;
                    $datos[$count]['codigo']=$producto->procedimientoA_codigo;
                    $datos[$count]['cantidad']=1;
                    $datos[$count]['nombre']=$ordenes->examen->producto->producto_nombre;
                    $datos[$count]['valor']=$producto->procedimientoA_valor;
                    $datos[$count]['%Cobertura']='0 %';
                    $datos[$count]['Cobertura']=$producto->procedimientoA_valor;;
                    $datos[$count]['Copago']=$producto->procedimientoA_valor;
                    $tcopago=$producto->procedimientoA_valor;
                    
                    $copago=Entidad_Procedimiento::ValorAsignado($procedimiento->procedimiento_id, $Paciente->entidad_id)->get();
                    
                    foreach($copago as $copagos){
                        if($copagos->procedimiento->producto_id==$ordenes->examen->producto->producto_id){
                            
                            $datos[$count]['%Cobertura']=$copagos->ep_valor.' %';
                            $datos[$count]['Cobertura']=(($producto->procedimientoA_valor*$copagos->ep_valor)/100);

                            $datos[$count]['Copago']=$producto->procedimientoA_valor+round(($producto->procedimientoA_valor*$copagos->ep_valor/100), 2);
                            $tcopago=($producto->procedimientoA_valor)+round(($producto->procedimientoA_valor*$copagos->ep_valor/100), 2);
                        }
                    }
                    $count++;
                    $total=$total+$tcopago;
                }

                //return  'fin<br>';

                
                $secuencial=1;
                if($rangoDocumento){
                    $secuencial=$rangoDocumento->rango_inicio;
                    $secuencialAux=Analisis_Laboratorio::secuencial($rangoDocumento->rango_id)->max('analisis_secuencial');
                    if($secuencialAux){$secuencial=$secuencialAux+1;}
                    
                    $data=[
                        'especialidad'=>$especialidad,
                        'total'=>$total,
                        'datos'=>$datos,
                        'paciente'=>$Paciente,
                        'seguros'=>Tipo_Seguro::tipos()->get(),
                        'cajaAbierta'=>$cajaAbierta,
                        'sucursales'=>$sucursales,
                        'ordenAtencion'=>$orden,
                        //'clienteO'=>Cliente::Cliente($orden->expediente->ordenatencion->factura->cliente_id)->first(),
                        'clienteO'=>Cliente::Cliente($orden->expediente->ordenatencion->cliente_id)->first(),
                        'vendedores'=>Vendedor::Vendedores()->get(),
                        'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(),
                        'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9), 
                        'bodegas'=>Bodega::bodegasSucursal($puntoEmision->punto_id)->get(),
                        'formasPago'=>Forma_Pago::formaPagos()->get(),
                        'rangoDocumento'=>$rangoDocumento,'PE'=>Punto_Emision::puntos()->get(),
                        'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin
                    ];

                    return view('admin.laboratorio.ordenesExamen.facturar', $data);
                }else{
                    return redirect('inicio')->with('error','No tiene configurado, un punto de emisión o un rango de documentos para emitir facturas de venta, configueros y vuelva a intentar');
                }
            }else{
                return redirect('/denegado');
            }
        //}
        //catch(\Exception $ex){      
        //    return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        //}
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
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $ordenesAtencion = Orden_Examen::OrdenesByFechaSuc($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('sucursal_id'))->select('orden_examen.orden_id as orden_examen_id', 'orden_atencion.orden_id', 'orden_fecha','orden_codigo','orden_numero', 'paciente_apellidos','paciente_nombres','orden_otros','orden_examen.orden_estado')->orderBy('orden_numero','asc')->get();
            return view('admin.laboratorio.ordenesExamen.index',['fecI'=>$request->get('fecha_desde'),'fecF'=>$request->get('fecha_hasta'),'sucurslaC'=>$request->get('sucursal_id'),'sucursales'=>Sucursal::Sucursales()->get(),'ordenesAtencion'=>$ordenesAtencion,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
        try{
            $orden = Orden_Examen::OrdenExamen($id)->first();
            $orden->orden_estado='2';
            $orden->save();
            $analisis=new Analisis_Laboratorio();
            $puntoEmision = Punto_Emision::PuntoSucursalUser($orden->sucursal_id,Auth::user()->user_id)->first();
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'laboratorio')->first();
            $secuencial=1;
            if($rangoDocumento){
                $secuencial=$rangoDocumento->rango_inicio;
                $secuencialAux=Analisis_Laboratorio::secuencial($rangoDocumento->rango_id)->max('analisis_secuencial');
                if($secuencialAux){$secuencial=$secuencialAux+1;}
                return view('admin.laboratorio.ordenesExamen.facturarOrden',['ordenAtencion'=>$orden,'clienteO'=>Cliente::ClientesByCedula($orden->paciente->paciente_cedula)->first(),'vendedores'=>Vendedor::Vendedores()->get(),'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(),'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9), 'bodegas'=>Bodega::bodegasSucursal($puntoEmision->punto_id)->get(),'formasPago'=>Forma_Pago::formaPagos()->get(), 'rangoDocumento'=>$rangoDocumento,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('inicio')->with('error','No tiene configurado, un punto de emisión o un rango de documentos para emitir facturas de venta, configueros y vuelva a intentar');
            }
            $general = new generalController();
            $url = $general->LaboratorioAnalisis($orden);
            return redirect('ordenesExamen')->with('success','Analisis Preatendido exitosamente')->with('diario',$url);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
