<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Models\Movimiento_Producto;
use App\Models\Parametrizacion_Contable;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class actualizarCostosController extends Controller
{
    public function nuevo()
    {
        try{  
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();        
            return view('admin.contabilidad.actualizarCostos.index',['sucursales'=>Sucursal::sucursales()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }
    }
    public function actualizar(Request $request){
        try{
            $this->actualizarPrecioCosto($request->get('fecha_desde'), $request->get('fecha_hasta'));
            return redirect('actualizarCostos')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            return redirect('actualizarCostos')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function actualizarPrecioCosto($fechaDesde, $fechaHasta){
        try{           
            DB::beginTransaction();
            $productos = Producto::Productos()->where('producto_compra_venta','=','3')->get();
            foreach($productos as $producto){
                $general = new generalController();
                $cierre = $general->cierre($fechaDesde);          
                if($cierre){
                    return redirect('actualizarCostos')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
                }
                $cierre = $general->cierre($fechaHasta);          
                if($cierre){
                    return redirect('actualizarCostos')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
                }
                $datos = null;
                $count = 0;
                /***********************SALDO ANTERIOR***********************/
                $datos[$count]['fec'] = '';
                $datos[$count]['can1'] = 0;
                $datos[$count]['pre1'] = 0;
                $datos[$count]['tot1'] = 0;
                $datos[$count]['can2'] = 0;
                $datos[$count]['pre2'] = 0;
                $datos[$count]['tot2'] = 0;
                $datos[$count]['can3'] = Movimiento_Producto::MovProductoByFechaCorte($producto->producto_id,date("Y-m-d",strtotime($fechaDesde."- 1 days")) )->where('movimiento_tipo','=','ENTRADA')->sum('movimiento_cantidad')-Movimiento_Producto::MovProductoByFechaCorte($producto->producto_id,date("Y-m-d",strtotime($fechaDesde."- 1 days")) )->where('movimiento_tipo','=','SALIDA')->sum('movimiento_cantidad');
                $costoPromedio = Movimiento_Producto::MovProductoByFechaCorte($producto->producto_id,date("Y-m-d",strtotime($fechaDesde."- 1 days")))->orderBy('movimiento_fecha','desc')->orderBy('movimiento_id','desc')->first();
                $datos[$count]['pre3'] = 0;
                if($costoPromedio){
                    $datos[$count]['pre3'] = $costoPromedio->movimiento_costo_promedio;
                }
                $datos[$count]['tot3'] = floatval($datos[$count]['can3'])*floatval($datos[$count]['pre3']);
                $datos[$count]['cos'] = '';
                $count ++;
                /***********************************************************/
                foreach(Movimiento_Producto::MovProductoByFecha($producto->producto_id,$fechaDesde,$fechaHasta)->orderBy('movimiento_fecha','asc')->orderBy('movimiento_id','asc')->get() as $movimiento){
                    $bandera2 = false;
                    if($movimiento->movimiento_motivo != "ANULACION"){
                        if($movimiento->movimiento_tipo == "SALIDA" and $movimiento->movimiento_motivo == "VENTA" and $movimiento->movimiento_documento == "FACTURA DE VENTA"){
                            if(isset($movimiento->detalle_fv->facturaVenta->diario->diario_id)){
                                $bandera2 = true;
                            }
                        }else{
                            $bandera2 = true;
                        }
                    }
                    $datos[$count]['fec'] = $movimiento->movimiento_fecha;
                    $datos[$count]['can1'] = 0;
                    $datos[$count]['pre1'] = 0;
                    $datos[$count]['tot1'] = 0;
                    $datos[$count]['can2'] = 0;
                    $datos[$count]['pre2'] = 0;
                    $datos[$count]['tot2'] = 0;
                    if($movimiento->movimiento_tipo == "ENTRADA"){
                        $datos[$count]['can1'] = $movimiento->movimiento_cantidad;
                        $datos[$count]['pre1'] = $movimiento->movimiento_precio;
                        $datos[$count]['tot1'] = $movimiento->movimiento_total;
                    }
                    if($movimiento->movimiento_tipo == "SALIDA"){
                        $datos[$count]['can2'] = $movimiento->movimiento_cantidad;
                    }
                    if($count-1 > 0){
                        $datos[$count]['can3'] = floatval($datos[$count-1]['can3'])+floatval($datos[$count]['can1'])-floatval($datos[$count]['can2']);
                    }else{
                        $datos[$count]['can3'] = floatval($datos[$count]['can1'])-floatval($datos[$count]['can2']);
                    }
                    if($movimiento->movimiento_motivo == "COMPRA" or $movimiento->movimiento_motivo == 'AJUSTE DE INVENTARIO'){
                        if(floatval($datos[$count]['can3']) <> 0){
                            if($count-1 > 0){
                                $datos[$count]['pre3'] = (floatval($datos[$count-1]['tot3'])+floatval($datos[$count]['tot1']))/floatval($datos[$count]['can3']);
                            }else{
                                $datos[$count]['pre3'] = floatval($datos[$count]['tot1'])/floatval($datos[$count]['can3']);
                            }
                            
                        }else{
                            $datos[$count]['pre3'] = 0;
                        }
                    }else{
                        if($count-1 > 0){
                            $datos[$count]['pre3'] = $datos[$count-1]['pre3'];
                        }else{
                            $datos[$count]['pre3'] = 0;
                        }
                    }                    
                    $datos[$count]['tot3'] = floatval($datos[$count]['can3'])*round(floatval($datos[$count]['pre3']),4);
                    if($movimiento->movimiento_tipo == "SALIDA"){
                        $datos[$count]['pre2'] = $datos[$count]['pre3'];
                        $datos[$count]['tot2'] = floatval($datos[$count]['can2'])*floatval($datos[$count]['pre2']);
                    }
                    $datos[$count]['dia'] = '';
                    $datos[$count]['cos'] = '';
                    if($movimiento->detalle_fv){
                        if($movimiento->detalle_fv->facturaVenta->diarioCosto){
                            $datos[$count]['cos'] = $movimiento->detalle_fv->facturaVenta->diarioCosto->diario_codigo;
                        }
                    }
                    if($movimiento->detalle_nc){
                        if($movimiento->detalle_nc->notaCredito->diarioCosto){
                            $datos[$count]['cos'] = $movimiento->detalle_nc->notaCredito->diarioCosto->diario_codigo;
                        }
                    }
                    if($movimiento->detalle_od){
                        if(isset($movimiento->detalle_od->ordenDespacho->Factura->diarioCosto)){
                            $datos[$count]['cos'] = $movimiento->detalle_od->ordenDespacho->Factura->diarioCosto->diario_codigo;
                        }
                    }
                    $datos[$count]['pre3'] = round($datos[$count]['pre3'],4);
                    $movimiento->movimiento_stock_actual = $datos[$count]['can3'];
                    $movimiento->movimiento_costo_promedio = $datos[$count]['pre3'];
                    $movimiento->update();
                    if($datos[$count]['cos'] <> ''){
                        $diario = Diario::DiarioCodigo($datos[$count]['cos'])->first();
                        foreach($movimiento->detallesDiario as $detalle){
                            if($diario->diario_id == $detalle->diario->diario_id){
                                if(isset($movimiento->detalle_fv->detalle_id)){
                                    if($detalle->cuenta_id == $movimiento->detalle_fv->producto->producto_cuenta_inventario){
                                        $detalle->detalle_haber = $datos[$count]['tot2'];
                                    }
                                    $parametrizacionContable  = Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id, 'COSTOS DE MERCADERIA')->first();
                                    if($detalle->cuenta_id == $parametrizacionContable->cuenta_id){
                                        $detalle->detalle_debe = $datos[$count]['tot2'];
                                    }
                                }
                                if(isset($movimiento->detalle_nc->detalle_id)){
                                    if($detalle->cuenta_id == $movimiento->detalle_nc->producto->producto_cuenta_inventario){
                                        $detalle->detalle_debe = $datos[$count]['tot2'];
                                    }
                                    $parametrizacionContable  = Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id, 'COSTOS DE MERCADERIA')->first();
                                    if($detalle->cuenta_id == $parametrizacionContable->cuenta_id){
                                        $detalle->detalle_haber = $datos[$count]['tot2'];
                                    }
                                }
                                if(isset($movimiento->detalle_od->detalle_id)){
                                    if($detalle->cuenta_id == $movimiento->detalle_od->producto->producto_cuenta_inventario){
                                        $detalle->detalle_haber = $datos[$count]['tot2'];
                                    }
                                    $parametrizacionContable  = Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id, 'COSTOS DE MERCADERIA')->first();
                                    if($detalle->cuenta_id == $parametrizacionContable->cuenta_id){
                                        $detalle->detalle_debe = $datos[$count]['tot2'];
                                    }
                                }
                                $detalle->update();
                            }
                        }
                    }
                    if($bandera2){
                        $count ++;
                    }
                }
            }
            DB::commit();
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('actualizarCostos')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
