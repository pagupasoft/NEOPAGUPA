<?php

namespace App\Http\Controllers;

use App\Models\Detalle_RC;
use App\Models\Detalle_RV;
use App\Models\Empresa;
use App\Models\Factura_Venta;
use App\Models\Nota_Credito;
use App\Models\Reporte_Tributario;
use App\Models\Transaccion_Compra;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class formulariosController extends Controller
{
    public function guadarReporteTributario()
    {        
    }
    public function nuevo()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.sri.formularios.reporteTributario',['gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function consultar(Request $request)
    {
        try{
            $datos = null;
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $datos =  $this->datos($request);
            $valor1 = 0;
            $valor2 = 0;
            $valor3 = 0;
            $valor4 = 0;
            $valor5 = 0;
            $valor6 = 0;
            if($request->get('valor1')){$valor1 = str_replace(',','',$request->get('valor1'));}
            if($request->get('valor2')){$valor2 = str_replace(',','',$request->get('valor2'));}
            if($request->get('valor3')){$valor3 = str_replace(',','',$request->get('valor3'));}
            if($request->get('valor4')){$valor4 = str_replace(',','',$request->get('valor4'));}
            if($request->get('valor5')){$valor5 = str_replace(',','',$request->get('valor5'));}
            if($request->get('valor6')){$valor6 = str_replace(',','',$request->get('valor6'));}
            if (isset($_POST['consultar'])){
                return view('admin.sri.formularios.reporteTributario',['fecI'=>$request->get('fecha_desde'),'fecF'=>$request->get('fecha_hasta'),'datos'=>$datos,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }
            if (isset($_POST['pdf'])){
                $empresa =  Empresa::empresa()->first();
                $ruta = public_path().'/PDF/'.$empresa->empresa_ruc;
                if (!is_dir($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $view =  \View::make('admin.formatosPDF.reporteTributario', ['valor1'=>$valor1,'valor2'=>$valor2,'valor3'=>$valor3,'valor4'=>$valor4,'valor5'=>$valor5,'valor6'=>$valor6,'datos'=>$datos,'desde'=>$request->get('fecha_desde'),'hasta'=>$request->get('fecha_hasta'),'empresa'=>$empresa]);
                $nombreArchivo = 'REPORTE TRIBUTARIO DEL '.DateTime::createFromFormat('Y-m-d', $request->get('fecha_desde'))->format('d-m-Y').' AL '.DateTime::createFromFormat('Y-m-d', $request->get('fecha_hasta'))->format('d-m-Y');
                return PDF::loadHTML($view)->setPaper('a4', 'landscape')->save('PDF/'.$empresa->empresa_ruc.'/'.$nombreArchivo.'.pdf')->download($nombreArchivo.'.pdf');
            } 
            if (isset($_POST['guardar'])){                
                
            }   
        }catch(\Exception $ex){
            return redirect('reporteTributario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function datos(Request $request){
        try{
            $resultado = null;
            $datos = [];
            $count = 1;
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;

            $totalT1 = 0;
            $totalT2 = 0;
            $totalT3 = 0;
            $totalT4 = 0;

            $totalConCredito = 0;
            $liquidoMes = 0;
            /*VENTAS 12%*/
            $datos = [];
            $count = 1;
            $datos[1]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa diferente de cero';
            $datos[1]['porcentaje'] = '12'; 
            $datos[1]['casillero'] = '401'; 
            $datos[1]['compraBruta'] = 0; 
            $datos[1]['nc'] = 0; 
            $datos[1]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']);  
            $datos[1]['iva'] = 0; 

            $datos[2]['sustento'] = 'Ventas de activos fijos gravadas tarifa diferente de cero';
            $datos[2]['porcentaje'] = '12'; 
            $datos[2]['casillero'] = '402'; 
            $datos[2]['compraBruta'] = 0; 
            $datos[2]['nc'] = 0;
            $datos[2]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']);  
            $datos[2]['iva'] = 0; 
            foreach(Factura_Venta::FacturasbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                ->where('factura_tarifa12','>','0')->get() as $venta){
                foreach($venta->detalles as $detalle){
                    if($detalle->detalle_iva > 0){
                        $compra = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                        ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
                        ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')->select('sustento_tributario.sustento_venta12')
                        ->distinct('sustento_tributario.sustento_venta12')->where('detalle_tc.producto_id','=',$detalle->producto_id)->first();
                        if(isset($compra->sustento_venta12)){
                            if($compra->sustento_venta12 == '401' or $detalle->producto->producto_compra_venta == '2'){
                                $datos[1]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa diferente de cero';
                                $datos[1]['porcentaje'] = $venta->factura_porcentaje_iva; 
                                $datos[1]['casillero'] = '401'; 
                                $datos[1]['compraBruta'] = floatval($datos[1]['compraBruta']) + $detalle->detalle_total; 
                            }
                            if($compra->sustento_venta12 == '402'){
                                $datos[2]['sustento'] = 'Ventas de activos fijos gravadas tarifa diferente de cero';
                                $datos[2]['porcentaje'] = $venta->factura_porcentaje_iva; 
                                $datos[2]['casillero'] = '402'; 
                                $datos[2]['compraBruta'] = floatval($datos[2]['compraBruta']) + $detalle->detalle_total; 
                            }
                        }else{
                            $datos[1]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa diferente de cero';
                            $datos[1]['porcentaje'] = $venta->factura_porcentaje_iva; 
                            $datos[1]['casillero'] = '401'; 
                            $datos[1]['compraBruta'] = floatval($datos[1]['compraBruta']) + $detalle->detalle_total; 
                        }
                    }
                }
            }
            foreach(Nota_Credito::NCbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta')) 
                ->where('nc_tarifa12','>','0')->get()as $nc){
                foreach($nc->detalles as $detallenc){
                    if($detallenc->detalle_iva > 0){
                        $compra = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                        ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
                        ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')->select('sustento_tributario.sustento_venta12')
                        ->distinct('sustento_tributario.sustento_venta12')->where('detalle_tc.producto_id','=',$detallenc->producto_id)->first();
                        if(isset($compra->sustento_venta12)){
                            if($compra->sustento_venta12 == '401' or $detalle->producto->producto_compra_venta == '2'){
                                $datos[1]['nc'] = floatval($datos[1]['nc']) + $detallenc->detalle_total; 
                            }
                            if($compra->sustento_venta12 == '402'){
                                $datos[2]['nc'] = floatval($datos[2]['nc']) + $detallenc->detalle_total; 
                            }
                        }else{
                            $datos[1]['nc'] = floatval($datos[1]['nc']) + $detallenc->detalle_total; 
                        }
                    }
                }
            }   
            $datos[1]['compraNeta'] = floatval($datos[1]['compraBruta']) - floatval($datos[1]['nc']);  
            $datos[1]['iva'] = floatval($datos[1]['compraNeta']) * (floatval($datos[1]['porcentaje']) / 100); 
            $datos[1]['porcentaje'] = $datos[1]['porcentaje'].'%';
            $liquidoMes = $liquidoMes + floatval($datos[1]['iva']);

            $datos[2]['compraNeta'] = floatval($datos[2]['compraBruta']) - floatval($datos[2]['nc']);  
            $datos[2]['iva'] = floatval($datos[2]['compraNeta']) * (floatval($datos[2]['porcentaje']) / 100); 
            $datos[2]['porcentaje'] = $datos[2]['porcentaje'].'%';
            $liquidoMes= $liquidoMes + floatval($datos[2]['iva']);

            $total1 = $total1 + $datos[1]['compraBruta'] + $datos[2]['compraBruta'];
            $total2 = $total2 + $datos[1]['nc'] + $datos[2]['nc'];
            $total3 = $total3 + $datos[1]['compraNeta'] + $datos[2]['compraNeta'];
            $total4 = $total4 + $datos[1]['iva'] + $datos[2]['iva'];
            $resultado[0]= $datos;
            /*************/
            /*VENTAS 0%*/
            $datos = [];
            $count = 1;
            $datos[1]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa 0% que no dan derecho a crédito tributario';
            $datos[1]['porcentaje'] = '0'; 
            $datos[1]['casillero'] = '403'; 
            $datos[1]['compraBruta'] = 0; 
            $datos[1]['nc'] = 0; 
            $datos[1]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']);  
            $datos[1]['iva'] = 0; 

            $datos[2]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa 0% que dan derecho a crédito tributario';
            $datos[2]['porcentaje'] = '0'; 
            $datos[2]['casillero'] = '405'; 
            $datos[2]['compraBruta'] = 0; 
            $datos[2]['nc'] = 0;
            $datos[2]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']);  
            $datos[2]['iva'] = 0; 

            $datos[3]['sustento'] = 'Ventas de activos fijos gravadas tarifa 0% que dan derecho a crédito tributario';
            $datos[3]['porcentaje'] = '0'; 
            $datos[3]['casillero'] = '406'; 
            $datos[3]['compraBruta'] = 0; 
            $datos[3]['nc'] = 0;
            $datos[3]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']);  
            $datos[3]['iva'] = 0; 

            foreach(Factura_Venta::FacturasbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                ->where('factura_tarifa0','>','0')->get() as $venta){
                foreach($venta->detalles as $detalle){
                    if($detalle->detalle_iva == 0){           
                        $compra = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                        ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
                        ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')->select('sustento_tributario.sustento_venta0')
                        ->distinct('sustento_tributario.sustento_venta0')->where('detalle_tc.producto_id','=',$detalle->producto_id)->first();
                        if(isset($compra->sustento_venta0)){
                            if($compra->sustento_venta0 == '403'){
                                $datos[1]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa 0% que no dan derecho a crédito tributario';
                                $datos[1]['porcentaje'] = 0; 
                                $datos[1]['casillero'] = '403'; 
                                $datos[1]['compraBruta'] = floatval($datos[1]['compraBruta']) + $detalle->detalle_total; 
                            }
                            elseif($compra->sustento_venta0 == '405' or $detalle->producto->producto_compra_venta == '2'){
                                $datos[2]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa 0% que dan derecho a crédito tributario';
                                $datos[2]['porcentaje'] = 0; 
                                $datos[2]['casillero'] = '405'; 
                                $datos[2]['compraBruta'] = floatval($datos[2]['compraBruta']) + $detalle->detalle_total; 
                            }
                            elseif($compra->sustento_venta0 == '406'){
                                $datos[3]['sustento'] = 'Ventas de activos fijos gravadas tarifa 0% que dan derecho a crédito tributario';
                                $datos[3]['porcentaje'] = 0; 
                                $datos[3]['casillero'] = '406'; 
                                $datos[3]['compraBruta'] = floatval($datos[3]['compraBruta']) + $detalle->detalle_total;  
                            }
                        }else{
                            if($venta->cliente->tipoCliente->tipo_cliente_nombre == 'CLIENTE LOCAL'){
                                $datos[1]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa 0% que no dan derecho a crédito tributario';
                                $datos[1]['porcentaje'] = 0; 
                                $datos[1]['casillero'] = '403'; 
                                $datos[1]['compraBruta'] = floatval($datos[1]['compraBruta']) + $detalle->detalle_total; 
                            }else{
                                $datos[2]['sustento'] = 'Ventas locales (excluye activos fijos) gravadas tarifa 0% que dan derecho a crédito tributario';
                                $datos[2]['porcentaje'] = 0; 
                                $datos[2]['casillero'] = '405'; 
                                $datos[2]['compraBruta'] = floatval($datos[2]['compraBruta']) + $detalle->detalle_total; 
                            }
                            
                        }
                    }
                }
            }
            foreach(Nota_Credito::NCbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta')) 
                ->where('nc_tarifa0','>','0')->get()as $nc){
                foreach($nc->detalles as $detallenc){
                    if($detallenc->detalle_iva == 0){
                        $compra = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                        ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
                        ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')->select('sustento_tributario.sustento_venta0')
                        ->distinct('sustento_tributario.sustento_venta0')->where('detalle_tc.producto_id','=',$detallenc->producto_id)->first();
                        if(isset($compra->sustento_venta0)){
                            if($compra->sustento_venta0 == '403'){
                                $datos[1]['nc'] = floatval($datos[1]['nc']) + $detallenc->detalle_total; 
                            }
                            if($compra->sustento_venta0 == '405' or $detallenc->producto->producto_compra_venta == '2'){
                                $datos[2]['nc'] = floatval($datos[2]['nc']) + $detallenc->detalle_total; 
                            }
                            if($compra->sustento_venta0 == '406'){
                                $datos[3]['nc'] = floatval($datos[3]['nc']) + $detallenc->detalle_total; 
                            }
                        }else{
                            if($nc->cliente->tipoCliente->tipo_cliente_nombre == 'CLIENTE LOCAL'){
                                $datos[1]['nc'] = floatval($datos[1]['nc']) + $detallenc->detalle_total; 
                            }else{
                                $datos[2]['nc'] = floatval($datos[2]['nc']) + $detallenc->detalle_total; 
                            }
                        }
                    }
                }
            }   
            $datos[1]['compraNeta'] = floatval($datos[1]['compraBruta']) - floatval($datos[1]['nc']);  
            $datos[1]['iva'] = floatval($datos[1]['compraNeta']) * (floatval($datos[1]['porcentaje']) / 100); 
            $datos[1]['porcentaje'] = $datos[1]['porcentaje'].'%';

            $datos[2]['compraNeta'] = floatval($datos[2]['compraBruta']) - floatval($datos[2]['nc']);  
            $datos[2]['iva'] = floatval($datos[2]['compraNeta']) * (floatval($datos[2]['porcentaje']) / 100); 
            $datos[2]['porcentaje'] = $datos[2]['porcentaje'].'%';

            $datos[3]['compraNeta'] = floatval($datos[3]['compraBruta']) - floatval($datos[3]['nc']);  
            $datos[3]['iva'] = floatval($datos[3]['compraNeta']) * (floatval($datos[3]['porcentaje']) / 100); 
            $datos[3]['porcentaje'] = $datos[3]['porcentaje'].'%';

            $total1 = $total1 + $datos[1]['compraBruta'] + $datos[2]['compraBruta'] + $datos[3]['compraBruta'];
            $total2 = $total2 + $datos[1]['nc'] + $datos[2]['nc'] + $datos[3]['nc'];
            $total3 = $total3 + $datos[1]['compraNeta'] + $datos[2]['compraNeta'] + $datos[3]['compraNeta'];
            $total4 = $total4 + $datos[1]['iva'] + $datos[2]['iva'] + $datos[3]['iva'];
            $resultado[1]= $datos;
            /*************/
            /*TOTAL VENTAS 12% Y 0%*/
            $datos = [];
            $count = 1;
            $datos[$count]['compraBruta'] = $total1; 
            $datos[$count]['nc'] = $total2; 
            $datos[$count]['compraNeta'] = $total3; 
            $datos[$count]['iva'] = $total4; 
            $totalT1 = $totalT1 + $total1;
            $totalT2 = $totalT2 + $total2;
            $totalT3 = $totalT3 + $total3;
            $totalT4 = $totalT4 + $total4;
            $resultado[2]= $datos;
            /*************/
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*VENTAS REEMBOLSO 0%*/
            $datos = [];
            $count = 1;
            $datos[$count]['sustento'] = 'Ingresos por reembolso como intermediario 0%'; 
            $datos[$count]['porcentaje'] = '0%'; 
            $datos[$count]['casillero'] = ''; 
            $datos[$count]['compraBruta'] = Factura_Venta::FacturasbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_fv','detalle_fv.factura_id','=','factura_venta.factura_id')
            ->join('producto','producto.producto_id','=','detalle_fv.producto_id')
            ->where('factura_venta.factura_tarifa0','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 0%')
            ->sum('factura_venta.factura_tarifa0'); 
            $datos[$count]['nc'] = Nota_Credito::NCbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_nc','detalle_nc.nc_id','=','nota_credito.nc_id')
            ->join('producto','producto.producto_id','=','detalle_nc.producto_id')
            ->where('nota_credito.nc_tarifa0','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 0%')->sum('nota_credito.nc_tarifa0'); 
            $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
            $datos[$count]['iva'] = 0; 
            $total1 = $total1 + $datos[$count]['compraBruta'];
            $total2 = $total2 + $datos[$count]['nc'];
            $total3 = $total3 + $datos[$count]['compraNeta'];
            $total4 = $total4 + $datos[$count]['iva'];
            $count ++;
            /*************/
            /*VENTAS REEMBOLSO 12%*/
            $datos[$count]['sustento'] = 'Ingresos por reembolso como intermediario 12%'; 
            $datos[$count]['porcentaje'] = '12%'; 
            $datos[$count]['casillero'] = ''; 
            $datos[$count]['compraBruta'] = Factura_Venta::FacturasbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_fv','detalle_fv.factura_id','=','factura_venta.factura_id')
            ->join('producto','producto.producto_id','=','detalle_fv.producto_id')
            ->where('factura_venta.factura_tarifa12','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 12%')
            ->sum('factura_venta.factura_tarifa12');  
            $datos[$count]['nc'] = Nota_Credito::NCbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_nc','detalle_nc.nc_id','=','nota_credito.nc_id')
            ->join('producto','producto.producto_id','=','detalle_nc.producto_id')
            ->where('nota_credito.nc_tarifa12','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 12%')->sum('nota_credito.nc_tarifa12'); 
            $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
            $datos[$count]['iva'] = Factura_Venta::FacturasbyFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_fv','detalle_fv.factura_id','=','factura_venta.factura_id')
            ->join('producto','producto.producto_id','=','detalle_fv.producto_id')
            ->where('factura_venta.factura_tarifa12','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 12%')
            ->sum('factura_venta.factura_iva'); 
            $total1 = $total1 + $datos[$count]['compraBruta'];
            $total2 = $total2 + $datos[$count]['nc'];
            $total3 = $total3 + $datos[$count]['compraNeta'];
            $total4 = $total4 + $datos[$count]['iva'];
            $count ++;
            $resultado[3]= $datos;
            /*************/
            /*TOTAL VENTAS REEMBOLSO 12% Y 0%*/
            $datos = [];
            $count = 1;
            $datos[$count]['compraBruta'] = $total1; 
            $datos[$count]['nc'] = $total2; 
            $datos[$count]['compraNeta'] = $total3; 
            $datos[$count]['iva'] = $total4; 
            $resultado[4]= $datos;
            /*************/
            $totalT1 = $totalT1 + $total1;
            $totalT2 = $totalT2 + $total2;
            $totalT3 = $totalT3 + $total3;
            $totalT4 = $totalT4 + $total4;
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*TOTAL VENTAS*/
            $datos = [];
            $count = 1;
            $datos[$count]['compraBruta'] = $totalT1; 
            $datos[$count]['nc'] = $totalT2; 
            $datos[$count]['compraNeta'] = $totalT3; 
            $datos[$count]['iva'] = $totalT4; 
            $resultado[20]= $datos;
            /*************/
            /*IMPUESTO A LIQUIDAR*/
            $datos = [];
            $count = 1;
            $datos[$count]['iva'] = $liquidoMes; 
            $resultado[21]= $datos;
            /*************/
            $totalT1 = 0;
            $totalT2 = 0;
            $totalT3 = 0;
            $totalT4 = 0;
            /*COMPRAS 12%*/
            $datos = [];
            $count = 1;
            foreach(Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
            ->select('sustento_tributario.sustento_compra12','sustento_tributario.sustento_credito','transaccion_porcentaje_iva',DB::raw('SUM(transaccion_compra.transaccion_tarifa12) as tarifa12')
            ,DB::raw('SUM(transaccion_compra.transaccion_iva) as iva'))->where('tipo_comprobante.tipo_comprobante_codigo','<>','04')
            ->where('transaccion_compra.transaccion_tarifa12','>',0)->groupBy('sustento_tributario.sustento_compra12')
            ->groupBy('sustento_tributario.sustento_credito')->groupBy('transaccion_porcentaje_iva')
            ->orderBy('sustento_tributario.sustento_compra12')->get() as $compra){
                $datos[$count]['sustento'] = ''; 
                if($compra->sustento_compra12 == '500'){
                    $datos[$count]['sustento'] ='Adquisiciones y pagos (excluye activos fijos) gravados tarifa diferente de cero (con derecho a crédito tributario)'; 
                }
                if($compra->sustento_compra12 == '501'){
                    $datos[$count]['sustento'] ='Adquisiciones locales de activos fijos gravados tarifa diferente de cero (con derecho a crédito tributario)'; 
                }
                if($compra->sustento_compra12 == '502'){
                    $datos[$count]['sustento'] ='Otras adquisiciones y pagos gravados tarifa diferente de cero (sin derecho a crédito tributario)'; 
                }
                $datos[$count]['porcentaje'] = $compra->transaccion_porcentaje_iva.'%'; 
                $datos[$count]['casillero'] = $compra->sustento_compra12; 
                $datos[$count]['compraBruta'] = $compra->tarifa12; 
                $datos[$count]['nc'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
                ->where('transaccion_porcentaje_iva','=',$compra->transaccion_porcentaje_iva)->where('tipo_comprobante.tipo_comprobante_codigo','=','04')
                ->where('sustento_tributario.sustento_compra12','=',$compra->sustento_compra12)->sum('transaccion_compra.transaccion_tarifa12'); 
                $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
                $datos[$count]['iva'] = floatval($datos[$count]['compraNeta']) * (floatval($compra->transaccion_porcentaje_iva) / 100); 
                if($compra->sustento_credito == '1'){
                    $totalConCredito = $totalConCredito + floatval($datos[$count]['iva']);
                }
                $total1 = $total1 + $datos[$count]['compraBruta'];
                $total2 = $total2 + $datos[$count]['nc'];
                $total3 = $total3 + $datos[$count]['compraNeta'];
                $total4 = $total4 + $datos[$count]['iva'];
                $count ++;
            }
            $resultado[5]= $datos;
            /*************/
            /*COMPRAS 0%*/
            $datos = [];
            $count = 1;
            foreach(Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
            ->select('sustento_tributario.sustento_compra0','transaccion_porcentaje_iva',DB::raw('SUM(transaccion_compra.transaccion_tarifa0) as tarifa0')
            ,DB::raw('SUM(transaccion_compra.transaccion_iva) as iva'))->where('tipo_comprobante.tipo_comprobante_codigo','<>','04')->where('tipo_comprobante.tipo_comprobante_codigo','<>','02')
            ->where('transaccion_compra.transaccion_tarifa0','>',0)->groupBy('sustento_tributario.sustento_compra0')->groupBy('transaccion_porcentaje_iva')
            ->orderBy('sustento_tributario.sustento_compra0')->get() as $compra){
                if($compra->sustento_compra0 != '535'){
                    $datos[$count]['sustento'] = ''; 
                    if($compra->sustento_compra0 == '506'){
                        $datos[$count]['sustento'] ='Importaciones de bienes (incluye activos fijos) gravados tarifa 0%'; 
                    }
                    if($compra->sustento_compra0 == '507'){
                        $datos[$count]['sustento'] ='Adquisiciones y pagos (incluye activos fijos) gravados tarifa 0%'; 
                    }
                    $datos[$count]['porcentaje'] = '0%'; 
                    $datos[$count]['casillero'] = $compra->sustento_compra0; 
                    $datos[$count]['compraBruta'] = $compra->tarifa0; 
                    $datos[$count]['nc'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
                    ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
                    ->where('transaccion_porcentaje_iva','=',$compra->transaccion_porcentaje_iva)->where('tipo_comprobante.tipo_comprobante_codigo','=','04')
                    ->where('sustento_tributario.sustento_compra0','=',$compra->sustento_compra0)->sum('transaccion_compra.transaccion_tarifa0'); 
                    $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
                    $datos[$count]['iva'] =0; 
                    $total1 = $total1 + $datos[$count]['compraBruta'];
                    $total2 = $total2 + $datos[$count]['nc'];
                    $total3 = $total3 + $datos[$count]['compraNeta'];
                    $total4 = $total4 + $datos[$count]['iva'];
                    $count ++;
                }
            }
            foreach(Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('sustento_tributario','sustento_tributario.sustento_id','=','transaccion_compra.sustento_id')
            ->select('sustento_tributario.sustento_compra0','transaccion_porcentaje_iva',DB::raw('SUM(transaccion_compra.transaccion_tarifa0) as tarifa0')
            ,DB::raw('SUM(transaccion_compra.transaccion_iva) as iva'))->where('tipo_comprobante.tipo_comprobante_codigo','<>','04')->where('tipo_comprobante.tipo_comprobante_codigo','=','02')
            ->where('transaccion_compra.transaccion_tarifa0','>',0)->groupBy('sustento_tributario.sustento_compra0')->groupBy('transaccion_porcentaje_iva')
            ->orderBy('sustento_tributario.sustento_compra0')->get() as $compra){
                if($compra->sustento_compra0 != '535'){
                    $datos[$count]['sustento'] ='Adquisiciones realizadas a contribuyentes RISE (RIMPE POPULARES)'; 
                    $datos[$count]['porcentaje'] = '0%'; 
                    $datos[$count]['casillero'] = '508'; 
                    $datos[$count]['compraBruta'] = $compra->tarifa0; 
                    $datos[$count]['nc'] = 0; 
                    $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
                    $datos[$count]['iva'] =0; 
                    $total1 = $total1 + $datos[$count]['compraBruta'];
                    $total2 = $total2 + $datos[$count]['nc'];
                    $total3 = $total3 + $datos[$count]['compraNeta'];
                    $total4 = $total4 + $datos[$count]['iva'];
                    $count ++;
                }
            }
            $resultado[6]= $datos;
            /*************/
            /*TOTAL COMPRAS 12% Y 0%*/
            $datos = [];
            $count = 1;
            $datos[$count]['compraBruta'] = $total1; 
            $datos[$count]['nc'] = $total2; 
            $datos[$count]['compraNeta'] = $total3; 
            $datos[$count]['iva'] = $total4; 
            $totalT1 = $totalT1 + $total1;
            $totalT2 = $totalT2 + $total2;
            $totalT3 = $totalT3 + $total3;
            $totalT4 = $totalT4 + $total4;
            $resultado[7]= $datos;
            /*************/
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*COMPRAS REEMBOLSO 0%*/
            $datos = [];
            $count = 1;
            $datos[$count]['sustento'] = 'Pagos Netos por reembolso como intermediario 0%'; 
            $datos[$count]['porcentaje'] = '0%'; 
            $datos[$count]['casillero'] = ''; 
            $datos[$count]['compraBruta'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')
            ->join('producto','producto.producto_id','=','detalle_tc.producto_id')->where('tipo_comprobante.tipo_comprobante_codigo','<>','04')
            ->where('transaccion_compra.transaccion_tarifa0','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 0%')
            ->sum('transaccion_compra.transaccion_tarifa0'); 
            $datos[$count]['nc'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')
            ->join('producto','producto.producto_id','=','detalle_tc.producto_id')
            ->where('transaccion_compra.transaccion_tarifa0','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 0%')
            ->where('tipo_comprobante.tipo_comprobante_codigo','=','04')->sum('transaccion_compra.transaccion_tarifa0'); 
            $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
            $datos[$count]['iva'] = 0; 
            $total1 = $total1 + $datos[$count]['compraBruta'];
            $total2 = $total2 + $datos[$count]['nc'];
            $total3 = $total3 + $datos[$count]['compraNeta'];
            $total4 = $total4 + $datos[$count]['iva'];
            $count ++;
            /*************/
            /*COMPRAS REEMBOLSO 12%*/
            $datos[$count]['sustento'] = 'Pagos Netos por reembolso como intermediario 12%'; 
            $datos[$count]['porcentaje'] = '12%'; 
            $datos[$count]['casillero'] = ''; 
            $datos[$count]['compraBruta'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')
            ->join('producto','producto.producto_id','=','detalle_tc.producto_id')->where('tipo_comprobante.tipo_comprobante_codigo','<>','04')
            ->where('transaccion_compra.transaccion_tarifa12','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 12%')
            ->sum('transaccion_compra.transaccion_tarifa12'); 
            $datos[$count]['nc'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')
            ->join('producto','producto.producto_id','=','detalle_tc.producto_id')
            ->where('transaccion_compra.transaccion_tarifa12','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 12%')
            ->where('tipo_comprobante.tipo_comprobante_codigo','=','04')->sum('transaccion_compra.transaccion_tarifa12'); 
            $datos[$count]['compraNeta'] = floatval($datos[$count]['compraBruta']) - floatval($datos[$count]['nc']); 
            $datos[$count]['iva'] = Transaccion_Compra::TransaccionByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->join('detalle_tc','detalle_tc.transaccion_id','=','transaccion_compra.transaccion_id')
            ->join('producto','producto.producto_id','=','detalle_tc.producto_id')
            ->where('transaccion_compra.transaccion_tarifa12','>',0)->where('producto_nombre','like','%REEMBOLSO DE GASTO 12%')
            ->sum('transaccion_compra.transaccion_iva'); ; 
            $total1 = $total1 + $datos[$count]['compraBruta'];
            $total2 = $total2 + $datos[$count]['nc'];
            $total3 = $total3 + $datos[$count]['compraNeta'];
            $total4 = $total4 + $datos[$count]['iva'];
            $count ++;
            $resultado[8]= $datos;
            /*************/
            /*TOTAL COMPRAS REEMBOLSO 12% Y 0%*/
            $datos = [];
            $count = 1;
            $datos[$count]['compraBruta'] = $total1; 
            $datos[$count]['nc'] = $total2; 
            $datos[$count]['compraNeta'] = $total3; 
            $datos[$count]['iva'] = $total4; 
            $resultado[9]= $datos;
            /*************/
            $totalT1 = $totalT1 + $total1;
            $totalT2 = $totalT2 + $total2;
            $totalT3 = $totalT3 + $total3;
            $totalT4 = $totalT4 + $total4;
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*TOTAL COMPRAS*/
            $datos = [];
            $count = 1;
            $datos[$count]['compraBruta'] = $totalT1; 
            $datos[$count]['nc'] = $totalT2; 
            $datos[$count]['compraNeta'] = $totalT3; 
            $datos[$count]['iva'] = $totalT4; 
            $resultado[10]= $datos;
            /*************/
            /*CREDITO TRIBUTARIO*/
            $datos = [];
            $count = 1;
            $datos[$count]['iva'] = $totalConCredito;
            $resultado[11]= $datos;
            /*************/
            /*RETENCIONES DE IVA EMITIDAS*/
            $datos = [];
            $count = 1;
            foreach(Detalle_RC::DetalleByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->select('concepto_codigo','concepto_nombre','concepto_porcentaje',DB::raw('SUM(detalle_rc.detalle_base) as base'),DB::raw('SUM(detalle_rc.detalle_valor) as valor'),
            DB::raw('COUNT(detalle_rc.detalle_id) as cantidad'))->groupBy('concepto_codigo','concepto_nombre','concepto_porcentaje')->where('detalle_tipo','=','IVA')
            ->where('retencion_compra.retencion_estado','=','1')->orderBy('concepto_porcentaje')->get() as $retenciones){
                $datos[$count]['nombre'] = $retenciones->concepto_nombre; 
                $datos[$count]['cantidad'] = $retenciones->cantidad; 
                $datos[$count]['codigo'] = $retenciones->concepto_codigo; 
                $datos[$count]['base'] = $retenciones->base; 
                $datos[$count]['valor'] = $retenciones->valor; 

                $total1 = $total1 + $datos[$count]['base'];
                $total2 = $total2 + $datos[$count]['valor'];
                $count ++;
            }
            $resultado[12]= $datos;
            /*************/
            /*TOTAL RETENCIONES DE IVA EMITIDAS*/
            $datos = [];
            $count = 1;
            $datos[$count]['base'] = $total1;
            $datos[$count]['valor'] = $total2;
            $resultado[13]= $datos;
            /*************/
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*RETENCIONES DE FUENTE EMITIDAS*/
            $datos = [];
            $count = 1;
            foreach(Detalle_RC::DetalleByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->select('concepto_codigo','concepto_nombre',DB::raw('SUM(detalle_rc.detalle_base) as base'),DB::raw('SUM(detalle_rc.detalle_valor) as valor'),
            DB::raw('COUNT(detalle_rc.detalle_id) as cantidad'))->groupBy('concepto_codigo','concepto_nombre')->where('detalle_tipo','=','FUENTE')
            ->where('retencion_compra.retencion_estado','=','1')->orderBy('concepto_codigo')->get() as $retenciones){
                $datos[$count]['nombre'] = $retenciones->concepto_nombre; 
                $datos[$count]['cantidad'] = $retenciones->cantidad; 
                $datos[$count]['codigo'] = $retenciones->concepto_codigo; 
                $datos[$count]['base'] = $retenciones->base; 
                $datos[$count]['valor'] = $retenciones->valor; 

                $total1 = $total1 + $datos[$count]['base'];
                $total2 = $total2 + $datos[$count]['valor'];
                $count ++;
            }
            $resultado[14]= $datos;
            /*************/
            /*TOTAL RETENCIONES DE FUENTE EMITIDAS*/
            $datos = [];
            $count = 1;
            $datos[$count]['base'] = $total1;
            $datos[$count]['valor'] = $total2;
            $resultado[15]= $datos;
            /*************/
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*RETENCIONES DE IVA RECIBIDAS*/
            $datos = [];
            $count = 1;
            foreach(Detalle_RV::DetalleByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->select('concepto_codigo','concepto_nombre','concepto_porcentaje',DB::raw('SUM(detalle_rv.detalle_base) as base'),
            DB::raw('SUM(detalle_rv.detalle_valor) as valor'),DB::raw('COUNT(detalle_rv.detalle_id) as cantidad'))
            ->where('detalle_tipo','=','IVA')->where('retencion_venta.retencion_estado','=','1')
            ->groupBy('concepto_codigo','concepto_nombre','concepto_porcentaje')->orderBy('concepto_porcentaje')->get() as $retenciones){
                $datos[$count]['nombre'] = $retenciones->concepto_nombre; 
                $datos[$count]['cantidad'] = $retenciones->cantidad; 
                $datos[$count]['codigo'] = $retenciones->concepto_codigo; 
                $datos[$count]['base'] = $retenciones->base; 
                $datos[$count]['valor'] = $retenciones->valor; 

                $total1 = $total1 + $datos[$count]['base'];
                $total2 = $total2 + $datos[$count]['valor'];
                $count ++;
            }
            $resultado[16]= $datos;
            /*************/
            /*TOTAL RETENCIONES DE IVA RECIBIDAS*/
            $datos = [];
            $count = 1;
            $datos[$count]['base'] = $total1;
            $datos[$count]['valor'] = $total2;
            $resultado[17]= $datos;
            /*************/
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            /*RETENCIONES DE FUENTE RECIBIDAS*/
            $datos = [];
            $count = 1;
            foreach(Detalle_RV::DetalleByFecha($request->get('fecha_desde'),$request->get('fecha_hasta'))
            ->select('concepto_codigo','concepto_nombre',DB::raw('SUM(detalle_rv.detalle_base) as base'),
            DB::raw('SUM(detalle_rv.detalle_valor) as valor'),DB::raw('COUNT(detalle_rv.detalle_id) as cantidad'))
            ->where('detalle_tipo','=','FUENTE')->where('retencion_venta.retencion_estado','=','1')
            ->groupBy('concepto_codigo','concepto_nombre')->orderBy('concepto_codigo')->get() as $retenciones){
                $datos[$count]['nombre'] = $retenciones->concepto_nombre; 
                $datos[$count]['cantidad'] = $retenciones->cantidad; 
                $datos[$count]['codigo'] = $retenciones->concepto_codigo; 
                $datos[$count]['base'] = $retenciones->base; 
                $datos[$count]['valor'] = $retenciones->valor; 

                $total1 = $total1 + $datos[$count]['base'];
                $total2 = $total2 + $datos[$count]['valor'];
                $count ++;
            }
            $resultado[18]= $datos;
            /*************/
            /*TOTAL RETENCIONES DE FUENTE RECIBIDAS*/
            $datos = [];
            $count = 1;
            $datos[$count]['base'] = $total1;
            $datos[$count]['valor'] = $total2;
            $resultado[19]= $datos;
            /*************/
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            if((floatval($resultado[21][1]['iva']) - floatval($resultado[11][1]['iva']) < 0)){
                $resultado[22]=0;
            }else{
                $resultado[22]=floatval($resultado[21][1]['iva']) - floatval($resultado[11][1]['iva']);
            }
            if((floatval($resultado[21][1]['iva']) - floatval($resultado[11][1]['iva']) > 0)){
                $resultado[23]=0;
            }else{
                $resultado[23]=abs(floatval($resultado[21][1]['iva']) - floatval($resultado[11][1]['iva']));
            }

            return $resultado;
        }catch(\Exception $ex){
            return redirect('reporteTributario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
