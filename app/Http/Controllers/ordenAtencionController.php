<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Empleado;
use App\Models\Medico_Especialidad;
use App\Models\Paciente;
use App\Models\Sucursal;
use App\Models\Punto_Emision;
use App\Http\Controllers\Controller;
use App\Models\Arqueo_Caja;
use App\Models\Aseguradora_Procedimiento;
use App\Models\Bodega;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Cuenta_Cobrar;
use App\Models\Detalle_Diario;
use App\Models\Detalle_FV;
use App\Models\Detalle_Pago_CXC;
use App\Models\Diario;
use App\Models\Documento_Orden_Atencion;
use App\Models\Empresa;
use App\Models\Entidad_Procedimiento;
use App\Models\Especialidad;
use App\Models\Factura_Venta;
use App\Models\Forma_Pago;
use App\Models\HorarioFijo;
use App\Models\Movimiento_Producto;
use App\Models\Orden_Atencion;
use App\Models\Pago_CXC;
use App\Models\Parametrizacion_Contable;
use App\Models\Producto;
use App\Models\Rango_Documento;
use App\Models\Tarifa_Iva;
use App\Models\Tipo_Dependencia;
use App\Models\Tipo_Seguro;
use App\Models\Vendedor;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

use function PHPSTORM_META\type;

class ordenAtencionController extends Controller
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
            $empleados = Empleado::Empleados()->get();
            $proveedores = Proveedor::Proveedores()->get();        
            $pacientes = Paciente::Pacientes()->get(); 
            return view('admin.agendamientoCitas.ordenAtencion.index',['sucursales'=>Sucursal::Sucursales()->get(),'empleados'=>$empleados,'proveedores'=>$proveedores,'pacientes'=>$pacientes,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        } 
    }

    public function ordenAtencionBuscar(Request $request){
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $ordenesAtencion = Orden_Atencion::OrdenesByFechaSuc($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('sucursal_id'))->get();
            $empleados = Empleado::Empleados()->get();
            $proveedores = Proveedor::Proveedores()->get();        
            $pacientes = Paciente::Pacientes()->get(); 
            return view('admin.agendamientoCitas.ordenAtencion.index',['fecI'=>$request->get('fecha_desde'),'fecF'=>$request->get('fecha_hasta'),'sucurslaC'=>$request->get('sucursal_id'),'sucursales'=>Sucursal::Sucursales()->get(),'ordenesAtencion'=>$ordenesAtencion,'empleados'=>$empleados,'proveedores'=>$proveedores,'pacientes'=>$pacientes,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        } 
    }

    public function nuevaOrden()
    {
        try{ 
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $sucursales=Sucursal::Sucursales()->get();
            $cajaAbierta=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first(); 
            $pacientes = Paciente::Pacientes()->get();    
            $especialidades = Especialidad::Especialidades()->get();
            $ordenesAtencion = Orden_Atencion::Ordenes()->get();
            $secuencial=1;
            $secuencialAux = Orden_Atencion::Ordenes()->max('orden_secuencial');
            if($secuencialAux){
                $secuencial=$secuencialAux+1;
            }
            return view('admin.agendamientoCitas.ordenAtencion.nuevaOrden',['cajaAbierta'=>$cajaAbierta,'bodegas'=>Bodega::Bodegas()->get(),'formasPago'=>Forma_Pago::formaPagos()->get(),'seguros'=>Tipo_Seguro::tipos()->get(),'documentos'=>Documento_Orden_Atencion::DocumentosOrdenesAtencion()->get(),'tiposDependencias'=>Tipo_Dependencia::TiposDependencias()->get(),'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9),'sucursales'=>$sucursales,'pacientes'=>$pacientes,'especialidades'=>$especialidades,'ordenesAtencion'=>$ordenesAtencion,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
        try {
            DB::beginTransaction();
           
         
         
            $empresa = Empresa::empresa()->first();
        
            /***************SABER SI SE GENERAR UN ASIENTO DE COSTO****************/

            $dateNew = $request->get('idFechaFac'); 

           
            $banderaP = false;
        
            $producto = Producto::findOrFail($request->get('IdCodigo'));
            if ($producto->producto_tipo == '1') {
                $banderaP = true;
            }
            $general = new generalController();
            /**********************************************************************/
            /********************cabecera de factura de venta ********************/
            $docElectronico = new facturacionElectronicaController();
            $factura = new Factura_Venta();
            $puntoEmision = Punto_Emision::PuntoSucursalUser($request->get('idSucursal'), Auth::user()->user_id)->first();
            $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'Factura')->first();
            $secuencial=1;
            if ($rangoDocumento) {
                $secuencialAux=Factura_Venta::secuencial($rangoDocumento->rango_id)->max('factura_secuencial');
                if ($secuencialAux) {
                    $secuencial=$secuencialAux+1;
                }
            }
            $factura->factura_numero = $rangoDocumento->puntoEmision->sucursal->sucursal_codigo.$rangoDocumento->puntoEmision->punto_serie.substr(str_repeat(0, 9).$secuencial, - 9);
            $factura->factura_serie = $rangoDocumento->puntoEmision->sucursal->sucursal_codigo.$rangoDocumento->puntoEmision->punto_serie;
            $factura->factura_secuencial = $secuencial;
            $factura->rango_id = $rangoDocumento->rango_id;

            $factura->factura_fecha =$dateNew;
            $factura->factura_lugar = $request->get('factura_lugar');
            $factura->factura_tipo_pago = $request->get('factura_tipo_pago');
            $factura->factura_dias_plazo = 0;
            $factura->factura_fecha_pago = $dateNew;
            $factura->factura_subtotal = $request->get('IdCopago');
            $factura->factura_descuento = 0;
            $factura->factura_tarifa0 = 0;
            $factura->factura_tarifa12 = 0;
            $factura->factura_iva = 0;
            $factura->factura_total = $request->get('IdCopago');
            $factura->factura_comentario = 'ORDEN DE ATENCION N° '. $request->get('Codigo').'-'.$request->get('Secuencial');
            ;
            $factura->factura_porcentaje_iva = 12;
            $factura->factura_emision = $request->get('tipoDoc');
            $factura->factura_ambiente = 'PRODUCCIÓN';
            $factura->factura_autorizacion = $docElectronico->generarClaveAcceso($factura->factura_numero, $request->get('idFechaFac'), "01");
            $factura->factura_estado = '1';
            $factura->bodega_id = $request->get('bodega_id');
            $factura->cliente_id = $request->get('clienteID');
            $factura->forma_pago_id = $request->get('forma_pago_id');
            /********************cuenta por cobrar***************************/
            $cxc = new Cuenta_Cobrar();
            $cxc->cuenta_descripcion = 'VENTA CON FACTURA No. '.$factura->factura_numero;
            if ($request->get('factura_tipo_pago') == 'CREDITO' or $request->get('factura_tipo_pago') == 'CONTADO') {
                $cxc->cuenta_tipo =$request->get('factura_tipo_pago');
                $cxc->cuenta_saldo = $request->get('IdCopago');
                $cxc->cuenta_estado = '1';
            } else {
                $cxc->cuenta_tipo = $request->get('factura_tipo_pago');
                $cxc->cuenta_saldo = 0.00;
                $cxc->cuenta_estado = '2';
            }
            $cxc->cuenta_fecha = $dateNew;
            $cxc->cuenta_fecha_inicio = $dateNew;
            $cxc->cuenta_fecha_fin = $dateNew;
            $cxc->cuenta_monto = $request->get('IdCopago');
            $cxc->cuenta_valor_factura = $request->get('IdCopago');
            $cxc->cliente_id = $request->get('clienteID');
            $cxc->sucursal_id = Rango_Documento::rango($rangoDocumento->rango_id)->first()->puntoEmision->sucursal_id;
            $cxc->save();
            $general->registrarAuditoria('Registro de cuenta por cobrar de factura -> '.$factura->factura_numero, $factura->factura_numero, 'Registro de cuenta por cobrar de factura -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('IdCopago').' con clave de acceso -> '.$factura->factura_autorizacion);
            /****************************************************************/
            $factura->cuentaCobrar()->associate($cxc);
            /**********************asiento diario****************************/
            $diario = new Diario();
            $diario->diario_codigo = $general->generarCodigoDiario($dateNew, 'CFVE');
            $diario->diario_fecha = $dateNew;
            $diario->diario_referencia = 'COMPROBANTE DIARIO DE FACTURA DE VENTA';
            $diario->diario_tipo_documento = 'FACTURA';
            $diario->diario_numero_documento = $factura->factura_numero;
            $diario->diario_beneficiario = $request->get('buscarCliente');
            $diario->diario_tipo = 'CFVE';
            $diario->diario_secuencial = substr($diario->diario_codigo, 8);
            $diario->diario_mes = DateTime::createFromFormat('Y-m-d', $dateNew)->format('m');
            $diario->diario_ano = DateTime::createFromFormat('Y-m-d', $dateNew)->format('Y');
            $diario->diario_comentario = 'COMPROBANTE DIARIO DE FACTURA: '.$factura->factura_numero;
            $diario->diario_cierre = '0';
            $diario->diario_estado = '1';
            $diario->empresa_id = Auth::user()->empresa_id;
            $diario->sucursal_id = Rango_Documento::rango($rangoDocumento->rango_id)->first()->puntoEmision->sucursal_id;
            $diario->save();
            $general->registrarAuditoria('Registro de diario de venta de factura -> '.$factura->factura_numero, $factura->factura_numero, 'Registro de diario de venta de factura -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('IdCopago').' y con codigo de diario -> '.$diario->diario_codigo);
            /****************************************************************/
            if ($banderaP) {
                /**********************asiento diario de costo ****************************/
                $diarioC = new Diario();
                $diarioC->diario_codigo = $general->generarCodigoDiario($dateNew, 'CCVP');
                $diarioC->diario_fecha = $dateNew;
                $diarioC->diario_referencia = 'COMPROBANTE DE COSTO DE VENTA DE PRODUCTO';
                $diarioC->diario_tipo_documento = 'FACTURA';
                $diarioC->diario_numero_documento = $factura->factura_numero;
                $diarioC->diario_beneficiario = $request->get('buscarCliente');
                $diarioC->diario_tipo = 'CCVP';
                $diarioC->diario_secuencial = substr($diarioC->diario_codigo, 8);
                $diarioC->diario_mes = DateTime::createFromFormat('Y-m-d', $dateNew)->format('m');
                $diarioC->diario_ano = DateTime::createFromFormat('Y-m-d', $dateNew)->format('Y');
                $diarioC->diario_comentario = 'COMPROBANTE DE COSTO DE VENTA DE PRODUCTO CON FACTURA: '.$factura->factura_numero;
                $diarioC->diario_cierre = '0';
                $diarioC->diario_estado = '1';
                $diarioC->empresa_id = Auth::user()->empresa_id;
                $diarioC->sucursal_id = Rango_Documento::rango($rangoDocumento->rango_id)->first()->puntoEmision->sucursal_id;
                $diarioC->save();
                $general->registrarAuditoria('Registro de diario de costo de venta de factura -> '.$factura->factura_numero, $factura->factura_numero, 'Registro de diario de costo de venta de factura -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('IdCopago').' y con codigo de diario -> '.$diarioC->diario_codigo);
                /************************************************************************/
                $factura->diarioCosto()->associate($diarioC);
            }
            if ($cxc->cuenta_estado == '2') {
                /********************Pago por Venta en efectivo***************************/
                $pago = new Pago_CXC();
                $pago->pago_descripcion = 'PAGO EN EFECTIVO';
                $pago->pago_fecha = $cxc->cuenta_fecha;
                $pago->pago_tipo = 'PAGO EN EFECTIVO';
                $pago->pago_valor = $cxc->cuenta_monto;
                $pago->pago_estado = '1';
                $pago->diario()->associate($diario);
                $pago->save();

                $detallePago = new Detalle_Pago_CXC();
                $detallePago->detalle_pago_descripcion = 'PAGO EN EFECTIVO';
                $detallePago->detalle_pago_valor = $cxc->cuenta_monto;
                $detallePago->detalle_pago_cuota = 1;
                $detallePago->detalle_pago_estado = '1';
                $detallePago->cuenta_id = $cxc->cuenta_id;
                $detallePago->pagoCXC()->associate($pago);
                $detallePago->save();
                /****************************************************************/
            }
            /********************detalle de diario de venta********************/
            $detalleDiario = new Detalle_Diario();
            $detalleDiario->detalle_debe = $request->get('IdCopago');
            $detalleDiario->detalle_haber = 0.00 ;
            $detalleDiario->detalle_tipo_documento = 'FACTURA';
            $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
            $detalleDiario->detalle_conciliacion = '0';
            $detalleDiario->detalle_estado = '1';
            if ($request->get('factura_tipo_pago') == 'CONTADO') {
                $detalleDiario->cliente_id = $request->get('clienteID');
                $detalleDiario->detalle_comentario = 'P/R CUENTA POR COBRAR DE CLIENTE';
                $parametrizacionContable=Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id, 'CUENTA POR COBRAR')->first();
                if ($parametrizacionContable->parametrizacion_cuenta_general == '1') {
                    $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                } else {
                    $parametrizacionContable = Cliente::findOrFail($request->get('clienteID'));
                    $detalleDiario->cuenta_id = $parametrizacionContable->cliente_cuenta_cobrar;
                }
            } else {
                $Caja=Caja::findOrFail($request->get('caja_id'));
                $detalleDiario->cuenta_id = $Caja->cuenta_id;
            }
            
            $diario->detalles()->save($detalleDiario);
            $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo, $factura->factura_numero, 'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el debe por un valor de -> '.$request->get('IdCopago'));
           
            /*
            if ($request->get('idIva') > 0){
                $detalleDiario = new Detalle_Diario();
                $detalleDiario->detalle_debe = 0.00;
                $detalleDiario->detalle_haber = $request->get('idIva') ;
                $detalleDiario->detalle_comentario = 'P/R IVA COBRADO EN VENTA';
                $detalleDiario->detalle_tipo_documento = 'FACTURA';
                $detalleDiario->detalle_conciliacion = '0';
                $detalleDiario->detalle_estado = '1';
                $parametrizacionContable=Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id,'IVA VENTAS')->first();
                $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                $diario->detalles()->save($detalleDiario);
                $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$parametrizacionContable->cuenta->cuenta_numero.' en el haber por un valor de -> '.$request->get('idIva'));
            }
            */
            /****************************************************************/
            /****************************************************************/
            
            $factura->diario()->associate($diario);
            $factura->save();
            $general->registrarAuditoria('Registro de factura de venta numero -> '.$factura->factura_numero, $factura->factura_numero, 'Registro de factura de venta numero -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('IdCopago').' con clave de acceso -> '.$factura->factura_autorizacion.' y con codigo de diario -> '.$diario->diario_codigo);
        
       

            /*******************************************************************/
            /********************detalle de factura de venta********************/
        
            $detalleFV = new Detalle_FV();
            $detalleFV->detalle_cantidad = 1;
            $detalleFV->detalle_precio_unitario = $request->get('IdCopago');
            $detalleFV->detalle_descuento = 0;
            $detalleFV->detalle_iva = 0;
            $detalleFV->detalle_total = $request->get('IdCopago');
            $detalleFV->detalle_descripcion = $request->get('nombreP');
            $detalleFV->detalle_estado = '1';
            $detalleFV->producto_id = $request->get('IdCodigo');
            /******************registro de movimiento de producto******************/
            $movimientoProducto = new Movimiento_Producto();
            $movimientoProducto->movimiento_fecha=$dateNew;
            $movimientoProducto->movimiento_cantidad=1;
            $movimientoProducto->movimiento_precio=$request->get('IdCopago');
            $movimientoProducto->movimiento_iva=0;
            $movimientoProducto->movimiento_total=$request->get('IdCopago');
            $movimientoProducto->movimiento_stock_actual=0;
            $movimientoProducto->movimiento_costo_promedio=0;
            $movimientoProducto->movimiento_documento='FACTURA DE VENTA';
            $movimientoProducto->movimiento_motivo='VENTA';
            $movimientoProducto->movimiento_tipo='SALIDA';
            $movimientoProducto->movimiento_descripcion='FACTURA DE VENTA No. '.$factura->factura_numero;
            $movimientoProducto->movimiento_estado='1';
            $movimientoProducto->producto_id=$request->get('IdCodigo');
            $movimientoProducto->bodega_id=$factura->bodega_id;
            $movimientoProducto->empresa_id=Auth::user()->empresa_id;
            $movimientoProducto->save();
            $general->registrarAuditoria('Registro de movimiento de producto por factura de venta numero -> '.$factura->factura_numero, $factura->factura_numero, 'Registro de movimiento de producto por factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$request->get('nombreP').' con la cantidad de -> 1 con un stock actual de -> '.$movimientoProducto->movimiento_stock_actual);
            /*********************************************************************/
            $detalleFV->movimiento()->associate($movimientoProducto);
            $factura->detalles()->save($detalleFV);
            $general->registrarAuditoria('Registro de detalle de factura de venta numero -> '.$factura->factura_numero, $factura->factura_numero, 'Registro de detalle de factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$request->get('nombreP').' con la cantidad de -> 1 a un precio unitario de -> '.$request->get('IdCopago'));
            
            $producto = Producto::findOrFail($request->get('IdCodigo'));
            $detalleDiario = new Detalle_Diario();
            $detalleDiario->detalle_debe = 0.00;
            $detalleDiario->detalle_haber =$request->get('IdCopago');
            $detalleDiario->detalle_comentario = 'P/R VENTA DE PRODUCTO '.$producto->producto_codigo;
            $detalleDiario->detalle_tipo_documento = 'FACTURA';
            $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
            $detalleDiario->detalle_conciliacion = '0';
            $detalleDiario->detalle_estado = '1';
            $detalleDiario->movimientoProducto()->associate($movimientoProducto);
            $detalleDiario->cuenta_id = $producto->producto_cuenta_venta;
            $diario->detalles()->save($detalleDiario);
            $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo, $factura->factura_numero, 'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$producto->cuentaVenta->cuenta_numero.' en el haber por un valor de -> '.$request->get('IdCopago'));
            
            if ($banderaP) {
                if ($producto->producto_tipo == '1') {
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = $movimientoProducto->movimiento_costo_promedio;
                    $detalleDiario->detalle_comentario = 'P/R COSTO DE INVENTARIO POR VENTA DE PRODUCTO '.$producto->producto_codigo;
                    $detalleDiario->detalle_tipo_documento = 'FACTURA';
                    $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $detalleDiario->cuenta_id = $producto->producto_cuenta_inventario;
                    $detalleDiario->movimientoProducto()->associate($movimientoProducto);
                    $diarioC->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo, $factura->factura_numero, 'Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el haber por un valor de -> '.$detalleDiario->detalle_haber);
                    
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = $movimientoProducto->movimiento_costo_promedio;
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'P/R COSTO DE INVENTARIO POR VENTA DE PRODUCTO '.$producto->producto_codigo;
                    $detalleDiario->detalle_tipo_documento = 'FACTURA';
                    $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $detalleDiario->movimientoProducto()->associate($movimientoProducto);
                    $parametrizacionContable = Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id, 'COSTOS DE MERCADERIA')->first();
                    $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                    $diarioC->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo, $factura->factura_numero, 'Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el debe por un valor de -> '.$detalleDiario->detalle_debe);
                }
            }
        
            /*******************************************************************/
            if($factura->factura_emision == 'ELECTRONICA'){
                $facturaAux = $docElectronico->enviarDocumentoElectronico($docElectronico->xmlFactura($factura), 'FACTURA');
                $factura->factura_xml_estado = $facturaAux->factura_xml_estado;
                $factura->factura_xml_mensaje = $facturaAux->factura_xml_mensaje;
                $factura->factura_xml_respuestaSRI = $facturaAux->factura_xml_respuestaSRI;
                if ($facturaAux->factura_xml_estado == 'AUTORIZADO') {
                    $factura->factura_xml_nombre = $facturaAux->factura_xml_nombre;
                    $factura->factura_xml_fecha = $facturaAux->factura_xml_fecha;
                    $factura->factura_xml_hora = $facturaAux->factura_xml_hora;
                }
                $factura->update();
            }
        
       
            $ordenAtencion = new Orden_Atencion();
           
            
           
            
            $cierre = $general->cierre($dateNew);
            if ($cierre) {
                return redirect('ordenAtencion')->with('error2', 'No puede realizar la operacion por que pertenece a un mes bloqueado');
            }

            $ordenAtencion->orden_codigo = $request->get('Codigo');
            $ordenAtencion->orden_numero = $request->get('Codigo').'-'.$request->get('Secuencial');
            $ordenAtencion->orden_secuencial = $request->get('Secuencial');
            $ordenAtencion->orden_reclamo =$request->get('idReclamoNum');
            $ordenAtencion->orden_secuencial_reclamo =$request->get('idReclamoSec');
            $ordenAtencion->orden_fecha =$request->get('fechaCitaID');
            $ordenAtencion->orden_hora = $request->get('horaCitaID');
            $ordenAtencion->orden_observacion = $request->get('Observacion');

            $ordenAtencion->orden_iess = '0';
            $ordenAtencion->orden_frecuencia = $request->get('tipo_atencion');
            $ordenAtencion->orden_dependencia = $request->get('es_dependiente');
            $ordenAtencion->orden_cedula_afiliado = $request->get('idCedulaAsegurado');
            $ordenAtencion->orden_nombre_afiliado = $request->get('idNombreAsegurado');
            $ordenAtencion->orden_precio = $request->get('IdPrecio');
            $ordenAtencion->orden_cobertura_porcentaje = $request->get('IdCoberturaPorcen');
            $ordenAtencion->orden_cobertura = $request->get('IdCobertura');
            $ordenAtencion->orden_copago = $request->get('IdCopago');

            $ordenAtencion->factura_id = $factura->factura_id;
            $mespecialidad=Medico_Especialidad::findOrFail($request->get('idMespecialidad'));
            $ordenAtencion->medico_id = $mespecialidad->medico->medico_id;

            $ordenAtencion->tipod_id = $request->get('IdTipoDependencia');
            $ordenAtencion->entidad_id = $request->get('identidad');

            $ordenAtencion->orden_estado  = 2;
            $ordenAtencion->cliente_id = $request->get('clienteID');
            $ordenAtencion->sucursal_id = $request->get('idSucursal');
            $ordenAtencion->paciente_id = $request->get('idPaciente');
            $ordenAtencion->especialidad_id = $request->get('especialidad_id');
            $ordenAtencion->producto_id = $request->get('IdCodigo');
            $ordenAtencion->tipo_id = $request->get('idSeguro');
            $ordenAtencion->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de orden de atencion -> '.$request->get('Codigo').' del Paciente -> '.$request->get('idPaciente'), '0', '');
            /*Fin de registro de auditoria */

            $documento=Documento_Orden_Atencion::DocumentosOrdenesAtencion()->get();
            foreach ($documento as $documentos) {
                $file='file-es'.$documentos->documento_id;
                if (($request->get($file))) {
                    if ($request->file($file)) {
                        $ruta = public_path().'/DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$dateNew.'/'.$ordenAtencion->orden_numero.'/Documentos';
                        if (!is_dir($ruta)) {
                            mkdir($ruta, 0777, true);
                        }
                        if ($request->file($file)->isValid()) {
                            $name = $documentos->documento_nombre.'.'.$request->file($file)->getClientOriginalExtension();
                            $path = $request->file($file)->move($ruta, $name);
                            $documen_orden=new Documento_Orden_Atencion();
                            $documen_orden->doccita_nombre=$name;
                            $documen_orden->doccita_url=$ruta.'/'.$name;
                            $documen_orden->doccita_estado='1';
                            $documen_orden->orden_id=$ordenAtencion->orden_id;
                        }
                    }
                }
            }

            $empresa = Empresa::empresa()->first();
            $view =  \View::make('admin.formatosPDF.ordenesAtenciones.ordenAtencion', ['orden'=>$ordenAtencion,'empresa'=>$empresa]);
            $ruta = public_path().'/DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$dateNew.'/'.$ordenAtencion->orden_numero.'/Documentos';
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $nombreArchivo = 'Orden de atencion';
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf');
            $url = $general->pdfDiario($diario);
            DB::commit();
            if ($facturaAux->factura_xml_estado == 'AUTORIZADO') {
                return redirect('ordenAtencion')->with('success', 'Datos guardados exitosamente, Factura registrada y autorizada exitosamente')->with('diario',$url)->with('pdf', 'documentosElectronicos/'.Empresa::Empresa()->first()->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('d-m-Y').'/'.$factura->factura_xml_nombre.'.pdf')->with('pdf2', '/DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$newformat2.'/'.$ordenAtencion->orden_numero.'/Documentos/'.$nombreArchivo.'.pdf');
            } else {
                return redirect('ordenAtencion')->with('success', 'Datos guardados exitosamente')->with('diario',$url)->with('error2', 'ERROR --> '.$facturaAux->factura_xml_estado.' : '.$facturaAux->factura_xml_mensaje)->with('pdf2', 'DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$dateNew.'/'.$ordenAtencion->orden_numero.'/Documentos/'.$nombreArchivo.'.pdf');
            }
        }
        catch(\Exception $ex){    
            DB::rollBack();  
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        } 
        
    }
    public function imprimirorden($id)
    {
        try{     
            $orden=Orden_Atencion::Orden($id)->get()->first();
            $empresa = Empresa::empresa()->first();
            $ruta = public_path().'/'.$empresa->empresa_ruc.'/DocumentosOrdenAtencion/'.DateTime::createFromFormat('Y-m-d', $orden->orden_fecha)->format('d-m-Y').'/'.$orden->orden_numero.'/Documentos';
            echo "$ruta";
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $nombreArchivo = 'Orden de atencion';
            $view =  \View::make('admin.formatosPDF.ordenesAtenciones.ordenAtencion', ['orden'=>$orden,'empresa'=>$empresa]);
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf')->download($nombreArchivo.'.pdf');
            return PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf')->stream('ordenAtencion.pdf');
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
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $sucursales=Sucursal::Sucursales()->get();
            $especialidades = Especialidad::Especialidades()->get();
            $pacientes = Paciente::Pacientes()->get();  
            $empleados = Empleado::Empleados()->get();
            $proveedores = Proveedor::Proveedores()->get();
            $ordenAtencion=Orden_Atencion::Orden($id)->first();
            $secuencial = $ordenAtencion->orden_secuencial;
            if($ordenAtencion){
                return view('admin.agendamientoCitas.ordenAtencion.ver',['empleados'=>$empleados,'proveedores'=>$proveedores,'ordenAtencion'=>$ordenAtencion,'pacientes'=>$pacientes,'especialidades'=>$especialidades,'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9),'sucursales'=>$sucursales, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
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
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            
            $sucursales=Sucursal::Sucursales()->get();
            $pacientes = Paciente::Pacientes()->get();    
            $especialidades = Especialidad::Especialidades()->get();
            $ordenAtencion = Orden_Atencion::Orden($id)->first();
            $secuencial = $ordenAtencion->orden_secuencial;
            if($ordenAtencion){
                return view('admin.agendamientoCitas.ordenAtencion.editar',['secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9),'ordenAtencion'=>$ordenAtencion, 'sucursales'=>$sucursales,'pacientes'=>$pacientes,'especialidades'=>$especialidades,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
        try{
            DB::beginTransaction();
            $ordenAtencion = Orden_Atencion::findOrFail($id); 
            $general = new generalController();
            $cierre = $general->cierre($ordenAtencion->orden_fecha);          
            if($cierre){
                return redirect('ordenAtencion')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
            }  
            $ordenAtencion->orden_codigo = $request->get('Codigo');
            $ordenAtencion->orden_numero = $request->get('Codigo').'-'.$request->get('Secuencial');
            $ordenAtencion->orden_secuencial = $request->get('Secuencial');
            $ordenAtencion->orden_fecha = $request->get('Fecha');
            $ordenAtencion->orden_hora = $request->get('Hora');
            $ordenAtencion->orden_observacion = $request->get('Observacion');                      
            if ($request->get('idEstado') == "on"){
                $ordenAtencion->orden_estado ="1";
            }else{
                $ordenAtencion->orden_estado ="0";
            }
            $ordenAtencion->sucursal_id = $request->get('idSucursal');
            $ordenAtencion->paciente_id = $request->get('idPaciente');
            $ordenAtencion->mespecialidad_id = $request->get('idMespecialidad');           
            $ordenAtencion->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de orden de atencion -> '.$request->get('Codigo').' del Paciente -> '.$request->get('idPaciente'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('ordenAtencion')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('ordenAtencion')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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

    public function buscarByDia($mespecialidad_id){
        return HorarioFijo::HorarioDia($mespecialidad_id)->get();
    }

    public function buscarByFecha($buscar){
        return Orden_Atencion::OrdenFecha($buscar)->get();
    }
    public function facturarOrden($idOrden){
        try{
            $orden = Orden_Atencion::findOrFail($idOrden);
            if($orden){
                $puntoEmision = Punto_Emision::PuntoSucursalUser($orden->sucursal_id,Auth::user()->user_id)->first();
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
                $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'Factura')->first();
                $secuencial=1;
                if($rangoDocumento){
                    $secuencial=$rangoDocumento->rango_inicio;
                    $secuencialAux=Factura_Venta::secuencial($rangoDocumento->rango_id)->max('factura_secuencial');
                    if($secuencialAux){$secuencial=$secuencialAux+1;}
                    return view('admin.agendamientoCitas.ordenAtencion.facturarOrden',['ordenAtencion'=>$orden,'clienteO'=>Cliente::ClientesByCedula($orden->paciente->paciente_cedula)->first(),'vendedores'=>Vendedor::Vendedores()->get(),'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(),'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9), 'bodegas'=>Bodega::bodegasSucursal($puntoEmision->punto_id)->get(),'formasPago'=>Forma_Pago::formaPagos()->get(), 'rangoDocumento'=>$rangoDocumento,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
                }else{
                    return redirect('inicio')->with('error','No tiene configurado, un punto de emisión o un rango de documentos para emitir facturas de venta, configueros y vuelva a intentar');
                }
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }    
    public function facturarOrdenGuardar(Request $request){
        try{            
            DB::beginTransaction();
            $cantidad = $request->get('Dcantidad');
            $isProducto = $request->get('DprodcutoID');
            $nombre = $request->get('Dnombre');
            $iva = $request->get('DViva');
            $pu = $request->get('Dpu');
            $total = $request->get('Dtotal');
            $descuento = $request->get('Ddescuento');
            /***************SABER SI SE GENERAR UN ASIENTO DE COSTO****************/
            $banderaP = false;
            for ($i = 1; $i < count($cantidad); ++$i){
                $producto = Producto::findOrFail($isProducto[$i]);
                if($producto->producto_tipo == '1'){
                    $banderaP = true;
                }
            }
            $general = new generalController();
            $cierre = $general->cierre($request->get('factura_fecha'));          
            if($cierre){
                return redirect('ordenAtencion')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
            } 
            /**********************************************************************/
            /********************cabecera de factura de venta ********************/
            $general = new generalController();
            $docElectronico = new facturacionElectronicaController();
            $factura = new Factura_Venta();
            $factura->factura_numero = $request->get('factura_serie').substr(str_repeat(0, 9).$request->get('factura_numero'), - 9);
            $factura->factura_serie = $request->get('factura_serie');
            $factura->factura_secuencial = $request->get('factura_numero');
            $factura->factura_fecha = $request->get('factura_fecha');
            $factura->factura_lugar = $request->get('factura_lugar');
            $factura->factura_tipo_pago = $request->get('factura_tipo_pago');
            $factura->factura_dias_plazo = 0;
            $factura->factura_fecha_pago = $request->get('factura_fecha');
            $factura->factura_subtotal = $request->get('idSubtotal');
            $factura->factura_descuento = $request->get('idDescuento');
            $factura->factura_tarifa0 = $request->get('idTarifa0');
            $factura->factura_tarifa12 = $request->get('idTarifa12');
            $factura->factura_iva = $request->get('idIva');
            $factura->factura_total = $request->get('idTotal');
            if($request->get('factura_comentario')){
                $factura->factura_comentario = $request->get('factura_comentario');
            }else{
                $factura->factura_comentario = '';
            }
            $factura->factura_porcentaje_iva = $request->get('factura_porcentaje_iva');
            $factura->factura_emision = $request->get('tipoDoc');
            $factura->factura_ambiente = 'PRODUCCIÓN';
            $factura->factura_autorizacion = $docElectronico->generarClaveAcceso($factura->factura_numero,$request->get('factura_fecha'),"01");
            $factura->factura_estado = '1';
            $factura->bodega_id = $request->get('bodega_id');
            $factura->cliente_id = $request->get('clienteID');
            $factura->forma_pago_id = $request->get('forma_pago_id');
            $factura->rango_id = $request->get('rango_id');
            $factura->vendedor_id = $request->get('vendedor_id');
                /********************cuenta por cobrar***************************/
                $cxc = new Cuenta_Cobrar();
                $cxc->cuenta_descripcion = 'VENTA CON FACTURA No. '.$factura->factura_numero;
                if($request->get('factura_tipo_pago') == 'CREDITO' or $request->get('factura_tipo_pago') == 'CONTADO'){
                    $cxc->cuenta_tipo =$request->get('factura_tipo_pago');
                    $cxc->cuenta_saldo = $request->get('idTotal');
                    $cxc->cuenta_estado = '1';
                }else{
                    $cxc->cuenta_tipo = $request->get('factura_tipo_pago');
                    $cxc->cuenta_saldo = 0.00;
                    $cxc->cuenta_estado = '2';
                }
                $cxc->cuenta_fecha = $request->get('factura_fecha');
                $cxc->cuenta_fecha_inicio = $request->get('factura_fecha');
                $cxc->cuenta_fecha_fin = $request->get('factura_fecha');
                $cxc->cuenta_monto = $request->get('idTotal');
                $cxc->cuenta_valor_factura = $request->get('idTotal');
                $cxc->cliente_id = $request->get('clienteID');
                $cxc->sucursal_id = Rango_Documento::rango($request->get('rango_id'))->first()->puntoEmision->sucursal_id;
                $cxc->save();
                $general->registrarAuditoria('Registro de cuenta por cobrar de factura -> '.$factura->factura_numero,$factura->factura_numero,'Registro de cuenta por cobrar de factura -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('idTotal').' con clave de acceso -> '.$factura->factura_autorizacion);
                /****************************************************************/
            $factura->cuentaCobrar()->associate($cxc);
                /**********************asiento diario****************************/
                $diario = new Diario();
                $diario->diario_codigo = $general->generarCodigoDiario($request->get('factura_fecha'),'CFVE');
                $diario->diario_fecha = $request->get('factura_fecha');
                $diario->diario_referencia = 'COMPROBANTE DIARIO DE FACTURA DE VENTA';
                $diario->diario_tipo_documento = 'FACTURA';
                $diario->diario_numero_documento = $factura->factura_numero;
                $diario->diario_beneficiario = $request->get('buscarCliente');
                $diario->diario_tipo = 'CFVE';
                $diario->diario_secuencial = substr($diario->diario_codigo, 8);
                $diario->diario_mes = DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('m');
                $diario->diario_ano = DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('Y');
                $diario->diario_comentario = 'COMPROBANTE DIARIO DE FACTURA: '.$factura->factura_numero;
                $diario->diario_cierre = '0';
                $diario->diario_estado = '1';
                $diario->empresa_id = Auth::user()->empresa_id;
                $diario->sucursal_id = Rango_Documento::rango($request->get('rango_id'))->first()->puntoEmision->sucursal_id;
                $diario->save();
                $general->registrarAuditoria('Registro de diario de venta de factura -> '.$factura->factura_numero,$factura->factura_numero,'Registro de diario de venta de factura -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('idTotal').' y con codigo de diario -> '.$diario->diario_codigo);
                /****************************************************************/
                if($banderaP){
                    /**********************asiento diario de costo ****************************/
                    $diarioC = new Diario();
                    $diarioC->diario_codigo = $general->generarCodigoDiario($request->get('factura_fecha'),'CCVP');
                    $diarioC->diario_fecha = $request->get('factura_fecha');
                    $diarioC->diario_referencia = 'COMPROBANTE DE COSTO DE VENTA DE PRODUCTO';
                    $diarioC->diario_tipo_documento = 'FACTURA';
                    $diarioC->diario_numero_documento = $factura->factura_numero;
                    $diarioC->diario_beneficiario = $request->get('buscarCliente');
                    $diarioC->diario_tipo = 'CCVP';
                    $diarioC->diario_secuencial = substr($diarioC->diario_codigo, 8);
                    $diarioC->diario_mes = DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('m');
                    $diarioC->diario_ano = DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('Y');
                    $diarioC->diario_comentario = 'COMPROBANTE DE COSTO DE VENTA DE PRODUCTO CON FACTURA: '.$factura->factura_numero;
                    $diarioC->diario_cierre = '0';
                    $diarioC->diario_estado = '1';
                    $diarioC->empresa_id = Auth::user()->empresa_id;
                    $diarioC->sucursal_id = Rango_Documento::rango($request->get('rango_id'))->first()->puntoEmision->sucursal_id;
                    $diarioC->save();
                    $general->registrarAuditoria('Registro de diario de costo de venta de factura -> '.$factura->factura_numero,$factura->factura_numero,'Registro de diario de costo de venta de factura -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('idTotal').' y con codigo de diario -> '.$diarioC->diario_codigo);
                    /************************************************************************/
                    $factura->diarioCosto()->associate($diarioC);
                }
                if($cxc->cuenta_estado == '2'){
                    /********************Pago por Venta en efectivo***************************/
                    $pago = new Pago_CXC();
                    $pago->pago_descripcion = 'PAGO EN EFECTIVO';
                    $pago->pago_fecha = $cxc->cuenta_fecha;
                    $pago->pago_tipo = 'PAGO EN EFECTIVO';
                    $pago->pago_valor = $cxc->cuenta_monto;
                    $pago->pago_estado = '1';
                    $pago->diario()->associate($diario);
                    $pago->save();

                    $detallePago = new Detalle_Pago_CXC();
                    $detallePago->detalle_pago_descripcion = 'PAGO EN EFECTIVO';
                    $detallePago->detalle_pago_valor = $cxc->cuenta_monto; 
                    $detallePago->detalle_pago_cuota = 1;
                    $detallePago->detalle_pago_estado = '1'; 
                    $detallePago->cuenta_id = $cxc->cuenta_id; 
                    $detallePago->pagoCXC()->associate($pago);
                    $detallePago->save();
                    /****************************************************************/
                }
                /********************detalle de diario de venta********************/
                $detalleDiario = new Detalle_Diario();
                $detalleDiario->detalle_debe = $request->get('idTotal');
                $detalleDiario->detalle_haber = 0.00 ;
                $detalleDiario->detalle_tipo_documento = 'FACTURA';
                $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                $detalleDiario->detalle_conciliacion = '0';
                $detalleDiario->detalle_estado = '1';
                if($request->get('factura_tipo_pago') == 'CONTADO'){
                    $detalleDiario->detalle_comentario = 'P/R CUENTA POR COBRAR DE CLIENTE';
                    $parametrizacionContable=Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id,'CUENTA POR COBRAR')->first();
                    if($parametrizacionContable->parametrizacion_cuenta_general == '1'){
                        $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                    }else{
                        $parametrizacionContable = Cliente::findOrFail($request->get('clienteID'));
                        $detalleDiario->cuenta_id = $parametrizacionContable->cliente_cuenta_cobrar;
                    }
                }else{
                    $Caja=Caja::findOrFail($request->get('caja_id'));
                    $detalleDiario->cuenta_id = $Caja->cuenta_id;
                }              
                $diario->detalles()->save($detalleDiario);
                $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el debe por un valor de -> '.$request->get('idTotal'));
                if ($request->get('idIva') > 0){
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = $request->get('idIva') ;
                    $detalleDiario->detalle_comentario = 'P/R IVA COBRADO EN VENTA';
                    $detalleDiario->detalle_tipo_documento = 'FACTURA';
                    $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $parametrizacionContable=Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id,'IVA VENTAS')->first();
                    $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                    $diario->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el haber por un valor de -> '.$request->get('idIva'));
                }
                /****************************************************************/
                /****************************************************************/
             
            $factura->diario()->associate($diario);
            $factura->save();
            $general->registrarAuditoria('Registro de factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de factura de venta numero -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('idTotal').' con clave de acceso -> '.$factura->factura_autorizacion.' y con codigo de diario -> '.$diario->diario_codigo);
            /*******************************************************************/
            /********************detalle de factura de venta********************/
            for ($i = 1; $i < count($cantidad); ++$i){
                $detalleFV = new Detalle_FV();
                $detalleFV->detalle_cantidad = $cantidad[$i];
                $detalleFV->detalle_precio_unitario = floatval($pu[$i]);
                $detalleFV->detalle_descuento = $descuento[$i];
                $detalleFV->detalle_iva = $iva[$i];
                $detalleFV->detalle_total = $total[$i];
                $detalleFV->detalle_descripcion = $nombre[$i];
                $detalleFV->detalle_estado = '1';
                $detalleFV->producto_id = $isProducto[$i];
                    /******************registro de movimiento de producto******************/
                    $movimientoProducto = new Movimiento_Producto();
                    $movimientoProducto->movimiento_fecha=$request->get('factura_fecha');
                    $movimientoProducto->movimiento_cantidad=$cantidad[$i];
                    $movimientoProducto->movimiento_precio=$pu[$i];
                    $movimientoProducto->movimiento_iva=$iva[$i];
                    $movimientoProducto->movimiento_total=$total[$i];
                    $movimientoProducto->movimiento_stock_actual=0;
                    $movimientoProducto->movimiento_costo_promedio=0;
                    $movimientoProducto->movimiento_documento='FACTURA DE VENTA';
                    $movimientoProducto->movimiento_motivo='VENTA';
                    $movimientoProducto->movimiento_tipo='SALIDA';
                    $movimientoProducto->movimiento_descripcion='FACTURA DE VENTA No. '.$factura->factura_numero;
                    $movimientoProducto->movimiento_estado='1';
                    $movimientoProducto->producto_id=$isProducto[$i];
                    $movimientoProducto->bodega_id=$factura->bodega_id;
                    $movimientoProducto->empresa_id=Auth::user()->empresa_id;
                    $movimientoProducto->save();
                    $general->registrarAuditoria('Registro de movimiento de producto por factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de movimiento de producto por factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$nombre[$i].' con la cantidad de -> '.$cantidad[$i].' con un stock actual de -> '.$movimientoProducto->movimiento_stock_actual);
                    /*********************************************************************/
                $detalleFV->movimiento()->associate($movimientoProducto);
                $factura->detalles()->save($detalleFV);
                $general->registrarAuditoria('Registro de detalle de factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de detalle de factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$nombre[$i].' con la cantidad de -> '.$cantidad[$i].' a un precio unitario de -> '.$pu[$i]);
                
                $producto = Producto::findOrFail($isProducto[$i]);
                $detalleDiario = new Detalle_Diario();
                $detalleDiario->detalle_debe = 0.00;
                $detalleDiario->detalle_haber = $total[$i];
                $detalleDiario->detalle_comentario = 'P/R VENTA DE PRODUCTO '.$producto->producto_codigo;
                $detalleDiario->detalle_tipo_documento = 'FACTURA';
                $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                $detalleDiario->detalle_conciliacion = '0';
                $detalleDiario->detalle_estado = '1';
                $detalleDiario->movimientoProducto()->associate($movimientoProducto);
                $detalleDiario->cuenta_id = $producto->producto_cuenta_venta;
                $diario->detalles()->save($detalleDiario);
                $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$producto->cuentaVenta->cuenta_numero.' en el haber por un valor de -> '.$total[$i]);
                
                if($banderaP){
                    if($producto->producto_tipo == '1'){
                        $detalleDiario = new Detalle_Diario();
                        $detalleDiario->detalle_debe = 0.00;
                        $detalleDiario->detalle_haber = $movimientoProducto->movimiento_costo_promedio;
                        $detalleDiario->detalle_comentario = 'P/R COSTO DE INVENTARIO POR VENTA DE PRODUCTO '.$producto->producto_codigo;
                        $detalleDiario->detalle_tipo_documento = 'FACTURA';
                        $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                        $detalleDiario->detalle_conciliacion = '0';
                        $detalleDiario->detalle_estado = '1';
                        $detalleDiario->cuenta_id = $producto->producto_cuenta_inventario;
                        $detalleDiario->movimientoProducto()->associate($movimientoProducto);
                        $diarioC->detalles()->save($detalleDiario);
                        $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el haber por un valor de -> '.$detalleDiario->detalle_haber);
                        
                        $detalleDiario = new Detalle_Diario();
                        $detalleDiario->detalle_debe = $movimientoProducto->movimiento_costo_promedio;
                        $detalleDiario->detalle_haber = 0.00;
                        $detalleDiario->detalle_comentario = 'P/R COSTO DE INVENTARIO POR VENTA DE PRODUCTO '.$producto->producto_codigo;
                        $detalleDiario->detalle_tipo_documento = 'FACTURA';
                        $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                        $detalleDiario->detalle_conciliacion = '0';
                        $detalleDiario->detalle_estado = '1';
                        $detalleDiario->movimientoProducto()->associate($movimientoProducto);
                        $parametrizacionContable = Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id,'COSTOS DE MERCADERIA')->first();
                        $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                        $diarioC->detalles()->save($detalleDiario);
                        $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diarioC->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el debe por un valor de -> '.$detalleDiario->detalle_debe);
                    }
                }
            }
            /*******************************************************************/
            if($factura->factura_emision == 'ELECTRONICA'){
                $facturaAux = $docElectronico->enviarDocumentoElectronico($docElectronico->xmlFactura($factura),'FACTURA');
                $factura->factura_xml_estado = $facturaAux->factura_xml_estado;
                $factura->factura_xml_mensaje = $facturaAux->factura_xml_mensaje;
                $factura->factura_xml_respuestaSRI = $facturaAux->factura_xml_respuestaSRI;
                if($facturaAux->factura_xml_estado == 'AUTORIZADO'){
                    $factura->factura_xml_nombre = $facturaAux->factura_xml_nombre;
                    $factura->factura_xml_fecha = $facturaAux->factura_xml_fecha;
                    $factura->factura_xml_hora = $facturaAux->factura_xml_hora;
                }
                $factura->update();
            }
            $orden = Orden_Atencion::findOrFail($request->get('orden_id'));
            $orden->orden_estado = '2';
            $orden->update();
            $url = $general->pdfDiario($diario);
            DB::commit();
            if($facturaAux->factura_xml_estado == 'AUTORIZADO'){
                return redirect('ordenAtencion')->with('success','Factura registrada y autorizada exitosamente')->with('diario',$url)->with('pdf','documentosElectronicos/'.Empresa::Empresa()->first()->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('d-m-Y').'/'.$factura->factura_xml_nombre.'.pdf');
            }elseif($factura->factura_emision != 'ELECTRONICA'){
                return redirect('ordenAtencion')->with('success','Factura registrada exitosamente')->with('diario',$url);
            }else{
                return redirect('ordenAtencion')->with('success','Factura registrada exitosamente')->with('diario',$url)->with('error2','ERROR SRI--> '.$facturaAux->factura_xml_estado.' : '.$facturaAux->factura_xml_mensaje);
            }
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('ordenAtencion')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function buscarAseguradoraProcedimiento(Request $request){
        $datos = null;
        $aseguradoraProcedimiento = Aseguradora_Procedimiento::AseguradoraProcedimientoById($request->get('procedimientoA_id'))->first();
        $datos[0]=$aseguradoraProcedimiento;
        $datos[1]=Entidad_Procedimiento::ValorAsignado($aseguradoraProcedimiento->procedimiento_id,$request->get('entidad_id'))->first();

        return $datos;
    }
    public function secuencialReclamo($aseguradora){
        $final=null;
        $orden =Cliente::Cliente($aseguradora)->first(); 
        $datos = Orden_Atencion::where('cliente_id','=',$aseguradora)->max('orden_secuencial_reclamo')+1;  
        $anulada=substr(str_repeat(0, 9).$datos, - 9);
        $final[0]=$datos;
        $final[1] = $orden->cliente_abreviatura.'-'.$anulada;
     return $final;
    }
}
