<?php

use App\Http\Controllers\activoFijoController;
use App\Http\Controllers\actualizarCostosController;
use App\Http\Controllers\ajusteInventarioController;
use App\Http\Controllers\alimentacionController;
use App\Http\Controllers\analisis_LaboratorioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\empresaController;
use App\Http\Controllers\grupoPerController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\sucursalController;
use App\Http\Controllers\permisoController;
use App\Http\Controllers\paisController;
use App\Http\Controllers\rolController;
use App\Http\Controllers\usuarioController;
use App\Http\Controllers\provinciaController;
use App\Http\Controllers\ciudadController;
use App\Http\Controllers\puntoEmisionController;
use App\Http\Controllers\tipoComprobanteController;
use App\Http\Controllers\generalController;
use App\Http\Controllers\sustentoTributarioController;
use App\Http\Controllers\emailEmpresaController;
use App\Http\Controllers\bodegaController;
use App\Http\Controllers\bodegueroController;
use App\Http\Controllers\firmaElectronicaController;
use App\Http\Controllers\auditoriaController;
use App\Http\Controllers\transportistaController;
use App\Http\Controllers\rangoDocumentoController;
use App\Http\Controllers\cuentaController;
use App\Http\Controllers\categoriaProductoController;
use App\Http\Controllers\marcaProductoController;
use App\Http\Controllers\tipoSujetoController;
use App\Http\Controllers\tipoIdentificacionController;
use App\Http\Controllers\unidadMedidaController;
use App\Http\Controllers\empleadoCargoController;
use App\Http\Controllers\categoriaClienteController;
use App\Http\Controllers\categoriaProveedorController;
use App\Http\Controllers\tipoClienteController;
use App\Http\Controllers\creditoController;
use App\Http\Controllers\formaPagoController;
use App\Http\Controllers\rubroController;
use App\Http\Controllers\bancoListaController;
use App\Http\Controllers\parametrizacionContableController;
use App\Http\Controllers\centroConsumoController;
use App\Http\Controllers\tarifaIvaController;
use App\Http\Controllers\empresaDepartamentoController;
use App\Http\Controllers\tamanoProductoController;
use App\Http\Controllers\grupoProductoController;
use App\Http\Controllers\productoController;
use App\Http\Controllers\bancoController;
use App\Http\Controllers\facturaVentaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\cuentaBancariaController;
use App\Http\Controllers\proveedorController;
use App\Http\Controllers\rangoChequeController;
use App\Http\Controllers\zonaController;
use App\Http\Controllers\cuentaCobrarController;
use App\Http\Controllers\vendedorController;
use App\Http\Controllers\conceptoRetencionController;
use App\Http\Controllers\proformaController;
use App\Http\Controllers\tipoTransaccionController;
use App\Http\Controllers\tipoEmpleadoController;
use App\Http\Controllers\movimientoCuentaController;
use App\Http\Controllers\transaccionIdentificacionController;
use App\Http\Controllers\transaccionCompraController;
use App\Http\Controllers\empleadoController;
use App\Http\Controllers\especialidadController;
use App\Http\Controllers\medicoController;
use App\Http\Controllers\medicoAseguradoraController;
use App\Http\Controllers\anticipoClienteController;
use App\Http\Controllers\anticipoProveedorController;
use App\Http\Controllers\anticipoEmpleadoController;
use App\Http\Controllers\anularRetencionesController;
use App\Http\Controllers\arqueoCajaController;
use App\Http\Controllers\egresoCajaController;
use App\Http\Controllers\ingresoCajaController;
use App\Http\Controllers\procedimientoEspecialidadController;
use App\Http\Controllers\entidadController;
use App\Http\Controllers\aseguradoraProcedimientoController;
use App\Http\Controllers\asientoDiarioController;
use App\Http\Controllers\asignacionRolController;
use App\Http\Controllers\AtsController;
use App\Http\Controllers\balanceComprobacionController;
use App\Http\Controllers\cajaController;
use App\Http\Controllers\cierreCajaController;
use App\Http\Controllers\cobrosClientesController;
use App\Http\Controllers\conciliacionBancariaController;
use App\Http\Controllers\controlDiasController;
use App\Http\Controllers\descontarAnticipoClienteController;
use App\Http\Controllers\descontarAnticipoEmpleadoController;
use App\Http\Controllers\descontarAnticipoProveedorController;
use App\Http\Controllers\liquidacionCompraController;
use App\Http\Controllers\listaCentroConsumoController;
use App\Http\Controllers\listaChequeController;
use App\Http\Controllers\listaRetencionEmitidaController;
use App\Http\Controllers\pacienteController;
use App\Http\Controllers\notaCreditoController;
use App\Http\Controllers\notaDebitoController;
use App\Http\Controllers\entidadProcedimientoController;
use App\Http\Controllers\retencionVentaController;
use App\Http\Controllers\ordenAtencionController;
use App\Http\Controllers\documentoAnuladoController;
use App\Http\Controllers\documentosElectronicosController;
use App\Http\Controllers\estadoFinancieroController;
use App\Http\Controllers\estadoResultadosController;
use App\Http\Controllers\listaComprasController;
use App\Http\Controllers\listaProformaController;
use App\Http\Controllers\facturaproformaController;
use App\Http\Controllers\guiaremisionController;
use App\Http\Controllers\listaAnticipoClienteController;
use App\Http\Controllers\listaAnticipoEmpleadoController;
use App\Http\Controllers\listaAnticipoProveedorController;
use App\Http\Controllers\listaRetencionRecibidaController;
use App\Http\Controllers\listaVentasController;
use App\Http\Controllers\mayorAuxiliarController;
use App\Http\Controllers\ordenDespachoController;
use App\Http\Controllers\mayorClientesController;
use App\Http\Controllers\mayorProveedoresController;
use App\Http\Controllers\reporteComprasController;
use App\Http\Controllers\reporteDocsAnuladosController;
use App\Http\Controllers\reporteOrdenesDespachoController;
use App\Http\Controllers\reporteVentasController;
use App\Http\Controllers\egresoBodegaController;
use App\Http\Controllers\faltanteCajaController;
use App\Http\Controllers\impuestoRentaRolController;
use App\Http\Controllers\ingresoBodegaController;
use App\Http\Controllers\kardexController;
use App\Http\Controllers\kardexCostoController;
use App\Http\Controllers\listaCarteraController;
use App\Http\Controllers\listaDeudasController;
use App\Http\Controllers\listaEgresoCajaController;
use App\Http\Controllers\listaGuiasRemisionOrdenesController;
use App\Http\Controllers\listaIngresoCajaController;
use App\Http\Controllers\listaquincenaConsolidadaController;
use App\Http\Controllers\listarolConsolidadaController;
use App\Http\Controllers\listarquincenaController;
use App\Http\Controllers\pagosProveedoresController;
use App\Http\Controllers\parametrizarRolController;
use App\Http\Controllers\quincenaConsolidadaController;
use App\Http\Controllers\quincenaController;
use App\Http\Controllers\rolConsolidadoController;
use App\Http\Controllers\tipoMedicamentoController;
use App\Http\Controllers\tipoExamenController;
use App\Http\Controllers\tipoImagenController;
use App\Http\Controllers\medicamentoController;
use App\Http\Controllers\enfermedadController;
use App\Http\Controllers\examenController;
use App\Http\Controllers\imagenController;
use App\Http\Controllers\listaFaltanteCajaController;
use App\Http\Controllers\listaSobranteCajaController;
use App\Http\Controllers\notaEntregaController;
use App\Http\Controllers\rolIndividualController;
use App\Http\Controllers\rolOperativoController;
use App\Http\Controllers\sobranteCajaController;
use App\Http\Controllers\tipoMovimientoegresoController;
use App\Http\Controllers\vacacionController;
use App\Http\Controllers\signosVitalesController;
use App\Http\Controllers\atencionCitasController;
use App\Http\Controllers\atencionRecetasController;
use App\Http\Controllers\cargarBalancesController;
use App\Http\Controllers\cargarXMLController;
use App\Http\Controllers\categoriaCostoController;
use App\Http\Controllers\categoriaRolController;
use App\Http\Controllers\cierreMesController;
use App\Http\Controllers\contabilizacionMensualController;
use App\Http\Controllers\cuadreCajaAbiertaController;
use App\Http\Controllers\cuentaPagarController;
use App\Http\Controllers\decimoCuartoController;
use App\Http\Controllers\decimoTerceroController;
use App\Http\Controllers\depositoCajaController;
use App\Http\Controllers\depreciacionMensualController;
use App\Http\Controllers\detalleLaboratorioController;
use App\Http\Controllers\diasPlazoController;
use App\Http\Controllers\egresoBancoController;
use App\Http\Controllers\elimiacionComprobantesCompraController;
use App\Http\Controllers\facturacionElectronicaController;
use App\Http\Controllers\grupoActivoController;
use App\Http\Controllers\ingresoBancoController;
use App\Http\Controllers\listaCierreCajaController;
use App\Http\Controllers\listaEgresoBancoController;
use App\Http\Controllers\listaFacturaController;
use App\Http\Controllers\listaIngresoBancoController;
use App\Http\Controllers\listaliquidacionCompraController;
use App\Http\Controllers\listaNotaCreditoBancoController;
use App\Http\Controllers\listanotaCreditoController;
use App\Http\Controllers\listaNotaDebitoBancoController;
use App\Http\Controllers\listanotaDebitoController;
use App\Http\Controllers\listatransaccionCompraController;
use App\Http\Controllers\notaCreditoBancoController;
use App\Http\Controllers\notaDebitoBancoController;
use App\Http\Controllers\perfilController;
use App\Http\Controllers\tipoMovimientoBancoController;
use App\Http\Controllers\tipoMovimientoCajaController;
use App\Http\Controllers\ventaActivoController;
use App\Http\Controllers\historialClinicoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ordenExamenController;
use App\Http\Controllers\tipoMuestraController;
use App\Http\Controllers\tipoRecipienteController;
use App\Http\Controllers\tipoSeguroController;
use App\Http\Controllers\tuvoSanguineoController;
use App\Http\Controllers\valorLaboratorioController;
use App\Http\Controllers\valorReferencialController;
use App\Http\Controllers\tipoDependenciaController;
use App\Http\Controllers\documentoOrdenAtencionController;
use App\Http\Controllers\facturasinOrdenController;
use App\Http\Controllers\formulariosController;
use App\Http\Controllers\inicializarCuentasCobrarController;
use App\Http\Controllers\inicializarCuentasPagarController;
use App\Http\Controllers\listaAsientosDiariosController;
use App\Http\Controllers\tarjetaCreditoController;
use App\Http\Controllers\listaPrecioController;
use App\Http\Controllers\listaRolCMController;
use App\Http\Controllers\listaRolReporteController;
use App\Http\Controllers\modificarRolController;
use App\Http\Controllers\ordenAtencionIessController;
use App\Http\Controllers\ordenRecepcionController;
use App\Http\Controllers\reporteUtilidadController;
use App\Http\Controllers\repoteBancarioController;
use App\Http\Controllers\rolConsolidadoCostaMarketController;
use App\Http\Controllers\rolIndividualCostaMarketController;
use App\Http\Controllers\rolOperactivoCostaMarketController;
use App\Http\Controllers\RolReporteDetalladoController;
use App\Models\Cabecera_Rol_CM;
use App\Models\Movimiento_Producto;
use App\Models\Punto_Emision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('login');
});
Route::get('/principal', function () {
    $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
    return view('principal',['PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
})->middleware('auth');

Route::get('/inicio', function () {
    $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
    return view('inicio',['PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
})->middleware('auth');

/*RUTAS DE LOGIN*/
Route::get('/login', [loginController::class,'index'])->name('login');
Route::get('/logout', [loginController::class,'logout'])->name('logout');
Route::post('/sesion', [loginController::class,'authenticate']);


Route::get('/buscarimpuestorenta/searchN/', [impuestoRentaRolController::class, 'buscarBy'])->middleware('auth');

Route::get('/levantar', function () {
    return view('empresa');
});
/*RUTAS DE MANTENIMIENTOS*/
Route::resource('empresa', empresaController::class);
Route::resource('grupo', grupoPerController::class)->middleware('auth')->middleware('acceso');
Route::resource('sucursal', sucursalController::class)->middleware('auth')->middleware('acceso');
Route::resource('permiso', permisoController::class)->middleware('auth')->middleware('acceso');
Route::resource('pais', paisController::class)->middleware('auth')->middleware('acceso');
Route::resource('tipoMovimiento',tipoMovimientoegresoController::class)->middleware('auth')->middleware('acceso');
Route::resource('rol', rolController::class)->middleware('auth')->middleware('acceso');
Route::resource('usuario', usuarioController::class)->middleware('auth')->middleware('acceso');
Route::resource('provincia', provinciaController::class)->middleware('auth');
Route::resource('ciudad', ciudadController::class)->middleware('auth');
Route::resource('puntoEmision', puntoEmisionController::class)->middleware('auth');
Route::resource('tipoComprobante', tipoComprobanteController::class)->middleware('auth');
Route::resource('sustentoTributario', sustentoTributarioController::class)->middleware('auth');
Route::resource('emailEmpresa', emailEmpresaController::class)->middleware('auth');
Route::resource('bodega', bodegaController::class)->middleware('auth');
Route::resource('bodeguero', bodegueroController::class)->middleware('auth');
Route::resource('firmaElectronica', firmaElectronicaController::class)->middleware('auth');
Route::resource('auditoria', auditoriaController::class)->middleware('auth');
Route::resource('transportista', transportistaController::class)->middleware('auth');
Route::resource('rangoDocumento', rangoDocumentoController::class)->middleware('auth');
Route::resource('cuenta', cuentaController::class)->middleware('auth');
Route::resource('categoriaProducto', categoriaProductoController::class)->middleware('auth');
Route::resource('marcaProducto', marcaProductoController::class)->middleware('auth');
Route::resource('tipoSujeto', tipoSujetoController::class)->middleware('auth');
Route::resource('tipoIdentificacion', tipoIdentificacionController::class)->middleware('auth');
Route::resource('unidadMedida', unidadMedidaController::class)->middleware('auth');
Route::resource('empleadoCargo', empleadoCargoController::class)->middleware('auth');
Route::resource('categoriaCliente', categoriaClienteController::class)->middleware('auth');
Route::resource('categoriaProveedor', categoriaProveedorController::class)->middleware('auth');
Route::resource('tipoCliente', tipoClienteController::class)->middleware('auth');
Route::resource('credito', creditoController::class)->middleware('auth');
Route::resource('formaPago', formaPagoController::class)->middleware('auth');
Route::resource('rubro', rubroController::class)->middleware('auth');
Route::resource('bancoLista', bancoListaController::class)->middleware('auth');
Route::resource('parametrizacionContable', parametrizacionContableController::class)->middleware('auth');
Route::resource('centroConsumo', centroConsumoController::class)->middleware('auth');
Route::resource('tarifaIva', tarifaIvaController::class)->middleware('auth');
Route::resource('departamento', empresaDepartamentoController::class)->middleware('auth');
Route::resource('tamanoProducto', tamanoProductoController::class)->middleware('auth');
Route::resource('grupoProducto', grupoProductoController::class)->middleware('auth');
Route::resource('producto', productoController::class)->middleware('auth');
Route::resource('banco', bancoController::class)->middleware('auth');
Route::resource('factura', facturaVentaController::class)->middleware('auth');
Route::resource('cliente', clienteController::class)->middleware('auth');
Route::resource('proveedor', proveedorController::class)->middleware('auth');
Route::resource('cuentaBancaria', cuentaBancariaController::class)->middleware('auth');
Route::resource('rangoCheque', rangoChequeController::class)->middleware('auth');
Route::resource('zona', zonaController::class)->middleware('auth');
Route::resource('vendedor', vendedorController::class)->middleware('auth');
Route::resource('conceptoRetencion', conceptoRetencionController::class)->middleware('auth');
Route::resource('proforma', proformaController::class)->middleware('auth');
Route::resource('parametrizacionRol', parametrizarRolController::class)->middleware('auth');
Route::resource('impuestoRentaRol', impuestoRentaRolController::class)->middleware('auth');
Route::resource('pquincena', quincenaController::class)->middleware('auth');
Route::resource('lquincena', listarquincenaController::class)->middleware('auth');
Route::resource('listaroles', listarolConsolidadaController::class)->middleware('auth');
Route::resource('rolindividual', rolIndividualController::class)->middleware('auth');
Route::resource('decimoT', decimoTerceroController::class)->middleware('auth');
Route::resource('decimoC', decimoCuartoController::class)->middleware('auth');
Route::resource('tipoSeguro', tipoSeguroController::class)->middleware('auth');
Route::resource('tipoMuestra', tipoMuestraController::class)->middleware('auth');
Route::resource('tipoRecipiente', tipoRecipienteController::class)->middleware('auth');
Route::resource('analisisLaboratorio', analisis_LaboratorioController::class)->middleware('auth');
Route::resource('categoriaCosto', categoriaCostoController::class)->middleware('auth');
Route::resource('asignacionRol', asignacionRolController::class)->middleware('auth');
Route::resource('listaRolCM', listaRolCMController::class)->middleware('auth');
Route::resource('categoriaRol', categoriaRolController::class)->middleware('auth');
Route::resource('cargaBalances', cargarBalancesController::class)->middleware('auth');

Route::resource('tipoTransaccion', tipoTransaccionController::class)->middleware('auth');
Route::resource('tipoEmpleado', tipoEmpleadoController::class)->middleware('auth');
Route::resource('movimientoCuenta', movimientoCuentaController::class)->middleware('auth');
Route::resource('transaccionIdentificacion', transaccionIdentificacionController::class)->middleware('auth');
Route::resource('transaccionCompra', transaccionCompraController::class)->middleware('auth');
Route::resource('empleado', empleadoController::class)->middleware('auth');
Route::resource('especialidad', especialidadController::class)->middleware('auth');
Route::resource('medico', medicoController::class)->middleware('auth');
Route::resource('medicoAseguradora', medicoAseguradoraController::class)->middleware('auth');
Route::resource('anticipoCliente', anticipoClienteController::class)->middleware('auth');
Route::resource('anticipoProveedor', anticipoProveedorController::class)->middleware('auth');
Route::resource('anticipoEmpleado', anticipoEmpleadoController::class)->middleware('auth');
Route::resource('egresoCaja', egresoCajaController::class)->middleware('auth');
Route::resource('egresoBanco', egresoBancoController::class)->middleware('auth');
Route::resource('ingresoBanco', ingresoBancoController::class)->middleware('auth');
Route::resource('ingresoCaja', ingresoCajaController::class)->middleware('auth');
Route::resource('procedimientoEspecialidad', procedimientoEspecialidadController::class)->middleware('auth');
Route::resource('entidad', entidadController::class)->middleware('auth');
Route::resource('aseguradoraProcedimiento', aseguradoraProcedimientoController::class)->middleware('auth');
Route::resource('paciente', pacienteController::class)->middleware('auth');
Route::resource('notaCredito', notaCreditoController::class)->middleware('auth');
Route::resource('notaDebito', notaDebitoController::class)->middleware('auth');
Route::resource('liquidacionCompra', liquidacionCompraController::class)->middleware('auth');
Route::resource('entidadProcedimiento', entidadProcedimientoController::class)->middleware('auth');
Route::resource('retencionVenta', retencionVentaController::class)->middleware('auth');
Route::resource('ordenAtencion', ordenAtencionController::class)->middleware('auth');
Route::resource('listaCompras', listaComprasController::class)->middleware('auth');
Route::resource('listaEgresoCaja', listaEgresoCajaController::class)->middleware('auth');
Route::resource('listaEgresoBanco', listaEgresoBancoController::class)->middleware('auth');
Route::resource('listaIngresoCaja', listaIngresoCajaController::class)->middleware('auth');
Route::resource('caja', cajaController::class)->middleware('auth');
Route::resource('arqueoCaja', arqueoCajaController::class)->middleware('auth');
Route::resource('faltanteCaja', faltanteCajaController::class)->middleware('auth');
Route::resource('sobranteCaja', sobranteCajaController::class)->middleware('auth');
Route::resource('cierreCaja', cierreCajaController::class)->middleware('auth');
Route::resource('depositoCaja', depositoCajaController::class)->middleware('auth');
Route::resource('listaCierreCaja', listaCierreCajaController::class)->middleware('auth');
Route::resource('cuadreCajaAbierta', cuadreCajaAbiertaController::class)->middleware('auth');
Route::resource('grupoActivo', grupoActivoController::class)->middleware('auth');
Route::resource('perfil', perfilController::class)->middleware('auth');
Route::resource('activoFijo', activoFijoController::class)->middleware('auth');
Route::resource('ventaActivo', ventaActivoController::class)->middleware('auth');
Route::resource('tipoMovimientoCaja', tipoMovimientoCajaController::class)->middleware('auth');
Route::resource('tipoMovimientoBanco', tipoMovimientoBancoController::class)->middleware('auth');
Route::resource('depreciacionMensual', depreciacionMensualController::class)->middleware('auth');
Route::resource('notaCreditoBanco', notaCreditoBancoController::class)->middleware('auth');
Route::resource('notaDebitoBanco', notaDebitoBancoController::class)->middleware('auth');
Route::resource('detallelaboratorio', detalleLaboratorioController::class)->middleware('auth');
Route::resource('ordenRecepecion', ordenRecepcionController::class)->middleware('auth');
Route::resource('anularRetencion', anularRetencionesController::class)->middleware('auth');
Route::resource('rolindividualCM', rolIndividualCostaMarketController::class)->middleware('auth');
Route::resource('rolConsolidadoCM', rolConsolidadoCostaMarketController::class)->middleware('auth');
Route::resource('roloperativoCM', rolOperactivoCostaMarketController::class)->middleware('auth');
Route::resource('reporteRol', listaRolReporteController::class)->middleware('auth');
Route::resource('rolreporteDetallado', RolReporteDetalladoController::class)->middleware('auth');
Route::resource('modificacionRoles', modificarRolController::class)->middleware('auth');

Route::resource('tipoMedicamento', tipoMedicamentoController::class)->middleware('auth');
Route::resource('tipoExamen', tipoExamenController::class)->middleware('auth');
Route::resource('tipoImagen', tipoImagenController::class)->middleware('auth');
Route::resource('medicamento', medicamentoController::class)->middleware('auth');
Route::resource('enfermedad', enfermedadController::class)->middleware('auth');
Route::resource('examen', examenController::class)->middleware('auth');
Route::resource('imagen', imagenController::class)->middleware('auth');
Route::resource('signosVitales', signosVitalesController::class)->middleware('auth');
Route::resource('atencionCitas', atencionCitasController::class)->middleware('auth');
Route::resource('historialClinico', historialClinicoController::class)->middleware('auth');
Route::resource('valorLaboratorio', valorLaboratorioController::class)->middleware('auth');
Route::resource('valorReferencial', valorReferencialController::class)->middleware('auth');
Route::resource('ordenesExamen', ordenExamenController::class)->middleware('auth');
Route::resource('tipoDependencia', tipoDependenciaController::class)->middleware('auth');
Route::resource('documentoOrdenAtencion', documentoOrdenAtencionController::class)->middleware('auth');
Route::resource('tarjetaCredito', tarjetaCreditoController::class)->middleware('auth');
Route::resource('listaPrecio', listaPrecioController::class)->middleware('auth');
Route::resource('ajusteInventario', ajusteInventarioController::class)->middleware('auth');

//GuiaRemision
Route::resource('listaGuiasOrdenes', listaGuiasRemisionOrdenesController::class)->middleware('auth');
Route::resource('guiaremision', guiaremisionController::class)->middleware('auth');
Route::resource('listaGuias', guiaremisionController::class)->middleware('auth');
//OrdenesDespacho
Route::resource('listaOrdenes', ordenDespachoController::class)->middleware('auth');
Route::resource('egresoBodega', egresoBodegaController::class)->middleware('auth');
Route::resource('ingresoBodega', ingresoBodegaController::class)->middleware('auth');
Route::resource('ordenesdespacho', ordenDespachoController::class)->middleware('auth');
Route::resource('rolConsolidado', rolConsolidadoController::class)->middleware('auth');
Route::resource('quincenaConsolidada', quincenaConsolidadaController::class)->middleware('auth');
Route::resource('listaquincenaConsolidada', listaquincenaConsolidadaController::class)->middleware('auth');
Route::resource('controldiario', controlDiasController::class)->middleware('auth');
Route::resource('vacacion', vacacionController::class)->middleware('auth');
Route::resource('notaentrega', notaEntregaController::class)->middleware('auth');
Route::resource('listaFaltanteCaja', listaFaltanteCajaController::class)->middleware('auth');
Route::resource('listaSobranteCaja', listaSobranteCajaController::class)->middleware('auth');
Route::resource('roloperativo', rolOperativoController::class)->middleware('auth');
Route::resource('alimentacion', alimentacionController::class)->middleware('auth');
Route::resource('listanotaCredito', listanotaCreditoController::class)->middleware('auth');
Route::resource('listanotaDebito', listanotaDebitoController::class)->middleware('auth');
Route::resource('listaFactura', listaFacturaController::class)->middleware('auth');
Route::resource('listaliquidacionCompra', listaliquidacionCompraController::class)->middleware('auth');
Route::resource('listatransaccionCompra', listatransaccionCompraController::class)->middleware('auth');
Route::resource('eliminacionComprantes', elimiacionComprobantesCompraController::class)->middleware('auth');
Route::resource('listaIngresoBanco', listaIngresoBancoController::class)->middleware('auth');
Route::resource('listaIngresoBanco', listaIngresoBancoController::class)->middleware('auth');
Route::resource('reporteBancario', repoteBancarioController::class)->middleware('auth');
Route::resource('ordenAtencionIess', ordenAtencionIessController::class)->middleware('auth');

Route::resource('listanotaCreditoBancario', listaNotaCreditoBancoController::class)->middleware('auth');
Route::resource('listanotaDebitoBancario', listaNotaDebitoBancoController::class)->middleware('auth');
Route::resource('cambioPlazo', diasPlazoController::class)->middleware('auth');
Route::resource('listaAsientoDiario', listaAsientosDiariosController::class)->middleware('auth');

Route::resource('individualrol', cabeceraRolAdministrativoController::class)->middleware('auth');
Route::resource('operativorol', cabeceraRolController::class)->middleware('auth');

/*RUTAS PARA VER DATOS ANTES DE ELIMINAR REGISTROS */

Route::get('/datosEmpresa', [empresaController::class, 'indexDatosEmpresa'])->middleware('auth');
Route::get('/empresa/{id}/eliminar', [empresaController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/grupo/{id}/eliminar', [grupoPerController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/sucursal/{id}/eliminar', [sucursalController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/permiso/{id}/eliminar', [permisoController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/pais/{id}/eliminar', [paisController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/rol/{id}/eliminar', [rolController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/usuario/{id}/eliminar', [usuarioController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/provincia/{id}/eliminar', [provinciaController::class, 'delete'])->middleware('auth');
Route::get('/ciudad/{id}/eliminar', [ciudadController::class, 'delete'])->middleware('auth');
Route::get('/puntoEmision/{id}/eliminar', [puntoEmisionController::class, 'delete'])->middleware('auth');
Route::get('/tipoComprobante/{id}/eliminar', [tipoComprobanteController::class, 'delete'])->middleware('auth');
Route::get('/sustentoTributario/{id}/eliminar', [sustentoTributarioController::class, 'delete'])->middleware('auth');
Route::get('/emailEmpresa/{id}/eliminar', [emailEmpresaController::class, 'delete'])->middleware('auth');
Route::get('/bodega/{id}/eliminar', [bodegaController::class, 'delete'])->middleware('auth');
Route::get('/bodeguero/{id}/eliminar', [bodegueroController::class, 'delete'])->middleware('auth');
Route::get('/firmaElectronica/{id}/eliminar', [firmaElectronicaController::class, 'delete'])->middleware('auth');
Route::get('/transportista/{id}/eliminar', [transportistaController::class, 'delete'])->middleware('auth');
Route::get('/rangoDocumento/{id}/eliminar', [rangoDocumentoController::class, 'delete'])->middleware('auth');
Route::get('/cuenta/{id}/eliminar', [cuentaController::class, 'delete'])->middleware('auth');
Route::get('/categoriaProducto/{id}/eliminar', [categoriaProductoController::class, 'delete'])->middleware('auth');
Route::get('/categoriaRol/{id}/eliminar', [categoriaRolController::class, 'delete'])->middleware('auth');
Route::get('/marcaProducto/{id}/eliminar', [marcaProductoController::class, 'delete'])->middleware('auth');
Route::get('/tipoSujeto/{id}/eliminar', [tipoSujetoController::class, 'delete'])->middleware('auth');
Route::get('/tipoIdentificacion/{id}/eliminar', [tipoIdentificacionController::class, 'delete'])->middleware('auth');
Route::get('/unidadMedida/{id}/eliminar', [unidadMedidaController::class, 'delete'])->middleware('auth');
Route::get('/empleadoCargo/{id}/eliminar', [empleadoCargoController::class, 'delete'])->middleware('auth');
Route::get('/categoriaCliente/{id}/eliminar', [categoriaClienteController::class, 'delete'])->middleware('auth');
Route::get('/categoriaProveedor/{id}/eliminar', [categoriaProveedorController::class, 'delete'])->middleware('auth');
Route::get('/tipoCliente/{id}/eliminar', [tipoClienteController::class, 'delete'])->middleware('auth');
Route::get('/credito/{id}/eliminar', [creditoController::class, 'delete'])->middleware('auth');
Route::get('/formaPago/{id}/eliminar', [formaPagoController::class, 'delete'])->middleware('auth');
Route::get('/rubro/{id}/eliminar', [rubroController::class, 'delete'])->middleware('auth');
Route::get('/bancoLista/{id}/eliminar', [bancoListaController::class, 'delete'])->middleware('auth');
Route::get('/parametrizacionContable/{id}/eliminar', [parametrizacionContableController::class, 'delete'])->middleware('auth');
Route::get('/centroConsumo/{id}/eliminar', [centroConsumoController::class, 'delete'])->middleware('auth');
Route::get('/tarifaIva/{id}/eliminar', [tarifaIvaController::class, 'delete'])->middleware('auth');
Route::get('/departamento/{id}/eliminar', [empresaDepartamentoController::class, 'delete'])->middleware('auth');
Route::get('/tamanoProducto/{id}/eliminar', [tamanoProductoController::class, 'delete'])->middleware('auth');
Route::get('/grupoProducto/{id}/eliminar', [grupoProductoController::class, 'delete'])->middleware('auth');
Route::get('/producto/{id}/eliminar', [productoController::class, 'delete'])->middleware('auth');
Route::get('/banco/{id}/eliminar', [bancoController::class, 'delete'])->middleware('auth');
Route::get('/cliente/{id}/eliminar', [clienteController::class, 'delete'])->middleware('auth');
Route::get('/proveedor/{id}/eliminar', [proveedorController::class, 'delete'])->middleware('auth');
Route::get('/cuentaBancaria/{id}/eliminar', [cuentaBancariaController::class, 'delete'])->middleware('auth');
Route::get('/rangoCheque/{id}/eliminar', [rangoChequeController::class, 'delete'])->middleware('auth');
Route::get('/zona/{id}/eliminar', [zonaController::class, 'delete'])->middleware('auth');
Route::get('/vendedor/{id}/eliminar', [vendedorController::class, 'delete'])->middleware('auth');
Route::get('/conceptoRetencion/{id}/eliminar', [conceptoRetencionController::class, 'delete'])->middleware('auth');
Route::get('/tipoEmpleado/{id}/eliminar', [tipoEmpleadoController::class, 'delete'])->middleware('auth');
Route::get('/tipoTransaccion/{id}/eliminar', [tipoTransaccionController::class, 'delete'])->middleware('auth');
Route::get('/transaccionIdentificacion/{id}/eliminar', [transaccionIdentificacionController::class, 'delete'])->middleware('auth');
Route::get('/empleado/{id}/eliminar', [empleadoController::class, 'delete'])->middleware('auth');
Route::get('/especialidad/{id}/eliminar', [especialidadController::class, 'delete'])->middleware('auth');
Route::get('/tipoSeguro/{id}/eliminar', [tipoSeguroController::class, 'delete'])->middleware('auth');
Route::get('/medico/{id}/eliminar', [medicoController::class, 'delete'])->middleware('auth');
Route::get('/tipoMuestra/{id}/eliminar', [tipoMuestraController::class, 'delete'])->middleware('auth');
Route::get('/tipoRecipiente/{id}/eliminar', [tipoRecipienteController::class, 'delete'])->middleware('auth');

Route::get('/tipoMovimiento/{id}/eliminar', [tipoMovimientoegresoController::class, 'delete'])->middleware('auth');
Route::get('/parametrizacionRol/{id}/eliminar', [parametrizarRolController::class, 'delete'])->middleware('auth');
Route::get('/impuestoRentaRol/{id}/eliminar', [impuestoRentaRolController::class, 'delete'])->middleware('auth');
Route::get('/lquincena/{id}/eliminar', [listarquincenaController::class, 'delete'])->middleware('auth');
Route::post('/lquincena/anular', [listarquincenaController::class, 'anulacion'])->middleware('auth');
Route::get('/lquincena/{id}/anular', [listarquincenaController::class, 'anular'])->middleware('auth');
Route::get('/listaEgresoCaja/{id}/eliminar', [listaEgresoCajaController::class, 'delete'])->middleware('auth');
Route::get('/listaEgresoBanco/{id}/eliminar', [listaEgresoBancoController::class, 'delete'])->middleware('auth');
Route::get('/listaIngresoBanco/{id}/eliminar', [listaIngresoBancoController::class, 'delete'])->middleware('auth');
Route::get('/listaIngresoCaja/{id}/eliminar', [listaIngresoCajaController::class, 'delete'])->middleware('auth');
Route::get('/caja/{id}/eliminar', [cajaController::class, 'delete'])->middleware('auth');
Route::get('/tipoImagen/{id}/eliminar', [tipoImagenController::class, 'delete'])->middleware('auth');
Route::get('/tipoExamen/{id}/eliminar', [tipoExamenController::class, 'delete'])->middleware('auth');
Route::get('/tipoMedicamento/{id}/eliminar', [tipoMedicamentoController::class, 'delete'])->middleware('auth');
Route::get('/imagen/{id}/eliminar', [imagenController::class, 'delete'])->middleware('auth');
Route::get('/examen/{id}/eliminar', [examenController::class, 'delete'])->middleware('auth');
Route::get('/enfermedad/{id}/eliminar', [enfermedadController::class, 'delete'])->middleware('auth');
Route::get('/medicamento/{id}/eliminar', [medicamentoController::class, 'delete'])->middleware('auth');
Route::get('/vacacion/{id}/eliminar', [vacacionController::class, 'delete'])->middleware('auth');
Route::get('/notaentrega/{id}/eliminar', [notaEntregaController::class, 'delete'])->middleware('auth');
Route::get('/listaFaltanteCaja/{id}/eliminar', [listaFaltanteCajaController::class, 'delete'])->middleware('auth');
Route::get('/listaSobranteCaja/{id}/eliminar', [listaSobranteCajaController::class, 'delete'])->middleware('auth');
Route::get('/grupoActivo/{id}/eliminar', [grupoActivoController::class, 'delete'])->middleware('auth');
Route::get('/activoFijo/{id}/eliminar', [activoFijoController::class, 'delete'])->middleware('auth');
Route::get('/tipoMovimientoCaja/{id}/eliminar', [tipoMovimientoCajaController::class, 'delete'])->middleware('auth');
Route::get('/tipoMovimientoBanco/{id}/eliminar', [tipoMovimientoBancoController::class, 'delete'])->middleware('auth');
Route::get('/ventaActivo/{id}/eliminar', [ventaActivoController::class, 'delete'])->middleware('auth');
Route::get('/tipoDependencia/{id}/eliminar', [tipoDependenciaController::class, 'delete'])->middleware('auth');
Route::get('/documentoOrdenAtencion/{id}/eliminar', [documentoOrdenAtencionController::class, 'delete'])->middleware('auth');
Route::get('/tarjetaCredito/{id}/eliminar', [tarjetaCreditoController::class, 'delete'])->middleware('auth');
Route::get('/listaPrecio/{id}/eliminar', [listaPrecioController::class, 'delete'])->middleware('auth');
Route::get('/anularRetencion/{id}/eliminar', [anularRetencionesController::class, 'delete'])->middleware('auth');
Route::get('/compra/claveAcceso/{id}', [transaccionCompraController::class, 'compraByClaveAcceso'])->middleware('auth');


Route::get('/listaEgresoBanco/{id}/anular', [listaEgresoBancoController::class, 'anular'])->middleware('auth');
Route::post('listaEgresoBancoanular', [listaEgresoBancoController::class, 'anulacion'])->middleware('auth');

Route::get('/vacacion/{id}/anular', [vacacionController::class, 'anular'])->middleware('auth');
Route::post('vacacionanular', [vacacionController::class, 'anulacion'])->middleware('auth');
Route::post('eliminarquincena', [quincenaConsolidadaController::class, 'eliminar'])->middleware('auth');

Route::post('cargarIngreso', [rubroController::class, 'cargaringreso'])->middleware('auth');
Route::post('cargarEgreso', [rubroController::class, 'cargaregreso'])->middleware('auth');


/*RUTAS ADICIONALES*/
Route::get('/controldiario/new/{id}', [controlDiasController::class, 'nuevo'])->middleware('auth');
Route::get('/vacacion/new/{id}', [vacacionController::class, 'nuevo'])->middleware('auth');
Route::get('/quincenaConsolidada/new/{id}', [quincenaConsolidadaController::class, 'nuevo'])->middleware('auth');
Route::get('/pquincena/new/{id}', [quincenaController::class, 'nuevo'])->middleware('auth');
Route::get('/ordenRecepcion/new/{id}', [ordenRecepcionController::class, 'nuevo'])->middleware('auth');

Route::post('/datosEmpresa/{id}', [empresaController::class, 'updateDatosEpresa'])->name('datosEmpresa')->middleware('auth');
Route::get('/rol/{id}/permisos', [rolController::class, 'permisos'])->middleware('auth')->middleware('acceso');
Route::post('/rol/guardarPermisos/{id}', [rolController::class, 'guardarPermisos'])->name('rol.guardarPermisos')->middleware('auth')->middleware('acceso');
Route::get('/usuario/{id}/roles', [usuarioController::class, 'roles'])->middleware('auth')->middleware('acceso');
Route::post('/usuario/guardarRoles/{id}', [usuarioController::class, 'guardarRoles'])->name('usuario.guardarRoles')->middleware('auth')->middleware('acceso');
/*asigar usuarios a las cajas*/
Route::get('/caja/{id}/cajausers', [cajaController::class, 'verusers'])->middleware('auth')->middleware('acceso');
Route::post('/caja/guardarUsuario/{id}', [cajaController::class, 'guardarUsuario'])->name('caja.guardarUsuario')->middleware('auth');
/***************/
Route::get('/excelProducto', [productoController::class, 'excelProducto'])->middleware('auth');
Route::post('/excelProducto', [productoController::class, 'CargarExcelProducto'])->middleware('auth');
Route::get('/excelEnfermedad', [enfermedadController::class, 'excelEnfermedad'])->middleware('auth');
Route::post('/excelEnfermedad', [enfermedadController::class, 'CargarExcelEnfermedad'])->middleware('auth');
Route::post('/excelInventario', [ajusteInventarioController::class, 'CargarExcelInventario'])->middleware('auth');
Route::get('/excelTransportista', [transportistaController::class, 'excelTransportista'])->middleware('auth');
Route::post('/excelTransportista', [transportistaController::class, 'CargarExcel'])->middleware('auth');
Route::get('/excelAnticipoEmpleado', [anticipoEmpleadoController::class, 'excelAnticipoEmpleado'])->middleware('auth');
Route::post('/excelAnticipoEmpleado', [anticipoEmpleadoController::class, 'CargarExcel'])->middleware('auth');
Route::get('/excelActivoFijo', [activoFijoController::class, 'excelActivoFijo'])->middleware('auth');
Route::post('/excelActivoFijo', [activoFijoController::class, 'CargarExcel'])->middleware('auth');

Route::get('/excelBalances', [balanceComprobacionController::class, 'excelBalances'])->middleware('auth');
Route::post('/excelBalances', [balanceComprobacionController::class, 'CargarExcel'])->middleware('auth');

Route::get('/excelAnticipoProveedor', [anticipoProveedorController::class, 'excelAnticipo'])->middleware('auth');
Route::post('/excelAnticipoProveedor', [anticipoProveedorController::class, 'CargarExcel'])->middleware('auth');
Route::get('/excelAnticipoCliente', [anticipoClienteController::class, 'excelAnticipo'])->middleware('auth');
Route::post('/excelAnticipoCliente', [anticipoClienteController::class, 'CargarExcel'])->middleware('auth');

Route::get('/excelRubro', [RubroController::class, 'excelRubro'])->middleware('auth');
Route::post('/excelRubro', [RubroController::class, 'CargarExcelRubro'])->middleware('auth');
Route::get('/excelCheque', [listaChequeController::class, 'excelCheque'])->middleware('auth');
Route::post('/excelCheque', [listaChequeController::class, 'CargarExcelCheque'])->middleware('auth');

Route::get('/excelProveedor', [proveedorController::class, 'excelProveedor'])->middleware('auth');
Route::post('/excelProveedor', [proveedorController::class, 'CargarExcelProveedor'])->middleware('auth');

Route::get('/excelEmpleado', [empleadoController::class, 'excelEmpleado'])->middleware('auth');
Route::post('/excelEmpleado', [empleadoController::class, 'CargarExcelEmpleado'])->middleware('auth');
Route::get('/updateEmpleado', [empleadoController::class, 'excelEmpleadoUpdate'])->middleware('auth');
Route::post('/updateEmpleado', [empleadoController::class, 'UpdateExcelEmpleado'])->middleware('auth');

Route::get('/excelCuenta', [cuentaController::class, 'subir'])->middleware('auth');
Route::post('/excelCuenta', [cuentaController::class, 'cargarguardar'])->middleware('auth');

Route::get('/excelCliente', [clienteController::class, 'excelCliente'])->middleware('auth');
Route::post('/excelCliente', [clienteController::class, 'CargarExcelCliente'])->middleware('auth');

Route::post('/facturacionsinOrden', [facturasinOrdenController::class, 'guardarfactura'])->middleware('auth');

Route::post('/cuenta/enviar', [cuentaController::class, 'enviar'])->middleware('auth');
Route::get('/PlanCuentaUp', [cuentaController::class, 'subir'])->middleware('auth');
Route::get('/denegado', [generalController::class, 'denegado'])->middleware('auth');
Route::get('/usuario/{id}/restablecer', [usuarioController::class, 'restablecePass'])->middleware('auth');
Route::get('/cuenta/{id}/subcuenta', [cuentaController::class, 'agregarCuenta'])->middleware('auth');
Route::post('/cuenta/guardarCuentas/{id}', [cuentaController::class, 'guardarCuenta'])->name('cuenta.guardarCuentas')->middleware('auth');
Route::get('/factura/new/{id}', [facturaVentaController::class, 'nuevo'])->middleware('auth');
Route::get('/facturacionsinOrden/new/{id}', [facturasinOrdenController::class, 'nuevo'])->middleware('auth');
Route::get('/transaccionCompra/new/{id}', [transaccionCompraController::class, 'nuevo'])->middleware('auth');
Route::get('/medicoAseguradora/{id}/aseguradoras', [medicoAseguradoraController::class, 'aseguradoras'])->middleware('auth')->middleware('acceso');
Route::post('/medicoAseguradora/guardarAseguradoras/{id}', [medicoAseguradoraController::class, 'guardarAseguradoras'])->name('medicoAseguradora.guardarAseguradoras')->middleware('auth');
Route::get('/usuario/{id}/puntos', [usuarioController::class, 'puntosEmisionPermiso'])->middleware('auth');
Route::post('/usuario/guardarPuntos/{id}', [usuarioController::class, 'guardarPuntos'])->name('usuario.guardarPuntosE')->middleware('auth');
Route::get('/procedimientoEspecialidad/{id}/especialidad', [procedimientoEspecialidadController::class, 'especialidad'])->middleware('auth')->middleware('acceso');
Route::post('/procedimientoEspecialidad/guardarEspecialidades/{id}', [procedimientoEspecialidadController::class, 'guardarEspecialidades'])->name('procedimientoEspecialidad.guardarEspecialidades')->middleware('auth');
Route::get('/aseguradoraProcedimiento/{id}/procedimiento', [aseguradoraProcedimientoController::class, 'procedimiento'])->middleware('auth')->middleware('acceso');
Route::post('/aseguradoraProcedimiento/guardarProcedimiento/{id}', [aseguradoraProcedimientoController::class, 'guardarProcedimiento'])->name('aseguradoraProcedimiento.guardarProcedimiento')->middleware('auth');
Route::get('/listaCheque', [listaChequeController::class, 'vista'])->middleware('auth');
Route::post('/listaCheque', [listaChequeController::class, 'listarCheques'])->middleware('auth');
Route::get('/listaRetencion', [listaRetencionEmitidaController::class, 'vista'])->middleware('auth');
Route::post('/listaRetencion', [listaRetencionEmitidaController::class, 'listarRetencionesEmitidas'])->middleware('auth');
Route::get('/notaCredito/new/{id}', [notaCreditoController::class, 'nuevo'])->middleware('auth');
Route::get('/reporteCompras', [reporteComprasController::class, 'nuevo'])->middleware('auth');
Route::post('/reporteCompras', [reporteComprasController::class, 'consultar'])->middleware('auth');
Route::get('/listaCc', [listaCentroConsumoController::class, 'vista'])->middleware('auth');
Route::post('/listaCc', [listaCentroConsumoController::class, 'listarCentrosconsumo'])->middleware('auth');
Route::get('/notaDebito/new/{id}', [notaDebitoController::class, 'nuevo'])->middleware('auth');
Route::get('/liquidacionCompra/new/{id}', [liquidacionCompraController::class, 'nuevo'])->middleware('auth');
Route::get('/entidadProcedimiento/{id}/procedimientos', [entidadProcedimientoController::class, 'procedimientos'])->middleware('auth')->middleware('acceso');
Route::post('/entidadProcedimiento/guardarProcedimientos/{id}', [entidadProcedimientoController::class, 'guardarProcedimientos'])->name('entidadProcedimiento.guardarProcedimientos')->middleware('auth');
Route::get('/anularDocumento', [documentoAnuladoController::class, 'nuevo'])->middleware('auth');
Route::post('/anularDocumento', [documentoAnuladoController::class, 'anular'])->middleware('auth');
Route::get('/docsElectronicos', [documentosElectronicosController::class, 'nuevo'])->middleware('auth');
Route::post('/docsElectronicos', [documentosElectronicosController::class, 'buscar'])->middleware('auth');
Route::get('/autorizarFactura/{id}', [documentosElectronicosController::class, 'reenviarFactura'])->middleware('auth');
Route::get('/PdfFactura/{id}', [documentosElectronicosController::class, 'facturaPDF'])->middleware('auth');
Route::get('/emailFactura/{id}', [documentosElectronicosController::class, 'emailFactura'])->middleware('auth');
Route::get('/autorizarNC/{id}', [documentosElectronicosController::class, 'reenviarNc'])->middleware('auth');
Route::get('/emailNC/{id}', [documentosElectronicosController::class, 'emailNC'])->middleware('auth');
Route::get('/autorizarND/{id}', [documentosElectronicosController::class, 'reenviarND'])->middleware('auth');
Route::get('/emailND/{id}', [documentosElectronicosController::class, 'emailND'])->middleware('auth');
Route::get('/autorizarRet/{id}', [documentosElectronicosController::class, 'reenviarRet'])->middleware('auth');
Route::get('/emailRet/{id}', [documentosElectronicosController::class, 'emailRet'])->middleware('auth');
Route::get('/autorizarLC/{id}', [documentosElectronicosController::class, 'reenviarLC'])->middleware('auth');
Route::get('/emailLC/{id}', [documentosElectronicosController::class, 'emailLC'])->middleware('auth');
Route::get('/autorizarGR/{id}', [documentosElectronicosController::class, 'reenviarGR'])->middleware('auth');
Route::get('/emailGR/{id}', [documentosElectronicosController::class, 'emailGR'])->middleware('auth');
Route::get('/consultarDocElec/{id}', [documentosElectronicosController::class, 'consultarDoc'])->middleware('auth');
Route::get('/nuevaOrden', [ordenAtencionController::class, 'nuevaOrden'])->middleware('auth');
Route::get('/ordenAtencionIess', [ordenAtencionIessController::class, 'nuevaOrdenIess'])->middleware('auth');
Route::post('/listaEmpleadoSucursal', [empleadoController::class, 'consultar'])->middleware('auth');


Route::get('/facturarOrden/{id}', [ordenAtencionController::class, 'facturarOrden'])->middleware('auth');
Route::post('/facturarOrden', [ordenAtencionController::class, 'facturarOrdenGuardar'])->middleware('auth');
Route::post('/facturarOrdenexamen', [examenController::class, 'facturarOrdenGuardar'])->middleware('auth');
Route::get('/conciliacionBancaria', [conciliacionBancariaController::class, 'nuevo'])->middleware('auth');
Route::post('/conciliacionBancaria', [conciliacionBancariaController::class, 'consultar'])->middleware('auth');
Route::get('/listaVentas', [listaVentasController::class, 'nuevo'])->middleware('auth');
Route::post('/listaVentas', [listaVentasController::class, 'consultar'])->middleware('auth');
Route::get('/reporteVentas', [reporteVentasController::class, 'nuevo'])->middleware('auth');
Route::post('/reporteVentas', [reporteVentasController::class, 'consultar'])->middleware('auth');
Route::get('/reporteDocsAnulados', [reporteDocsAnuladosController::class, 'nuevo'])->middleware('auth');
Route::post('/reporteDocsAnulados', [reporteDocsAnuladosController::class, 'consultar'])->middleware('auth');
Route::get('/listaRetencionRecibida', [listaRetencionRecibidaController::class, 'nuevo'])->middleware('auth');
Route::post('/listaRetencionRecibida', [listaRetencionRecibidaController::class, 'consultar'])->middleware('auth');
Route::get('/nuevoSignosV/{id}', [signosVitalesController::class, 'nuevoSigno'])->middleware('auth');
Route::get('/atencionCitas/{id}/atender', [atencionCitasController::class, 'atender'])->middleware('auth')->middleware('acceso');

Route::get('/receta', [atencionRecetasController::class, 'index'])->middleware('auth')->middleware('acceso');
Route::post('/receta', [atencionRecetasController::class, 'buscarPrescripcion'])->middleware('auth')->middleware('acceso');
Route::get('/receta/{id}', [atencionRecetasController::class, 'showPrescripcion'])->middleware('auth')->middleware('acceso');
Route::get('/receta/entregar/{id}', [atencionRecetasController::class, 'entregarPrescripcion'])->middleware('auth')->middleware('acceso');
Route::get('/receta/imprimir/{id}', [atencionRecetasController::class, 'imprimirPrescripcion'])->middleware('auth')->middleware('acceso');

Route::get('/ordenExamen/{id}/atender', [ordenExamenController::class, 'atender'])->middleware('auth')->middleware('acceso');
Route::get('/ordenExamen/{id}/facturarOrden', [ordenExamenController::class, 'facturarOrden'])->middleware('auth')->middleware('acceso');
Route::get('/historialClinico/{id}/historial', [historialClinicoController::class, 'historial'])->middleware('auth')->middleware('acceso');
Route::get('/historialClinico/{id}/ver', [historialClinicoController::class, 'ver'])->middleware('auth')->middleware('acceso');
Route::get('/reporteDepreciacion', [depreciacionMensualController::class, 'reporteDepreciacionActivo'])->middleware('auth');
Route::get('/reporteDepreciacion', [depreciacionMensualController::class, 'nuevo'])->middleware('auth');
Route::get('/examen/{id}/agregarValores', [examenController::class, 'agregarValores'])->middleware('auth')->middleware('acceso');
Route::get('/valorLaboratorio/{id}/agregarValorLaboratorio', [examenController::class, 'agregarValorLaboratorio'])->middleware('auth')->middleware('acceso');
Route::get('/valorLaboratorio/{id}/agregarValorReferencial', [examenController::class, 'agregarValorReferencial'])->middleware('auth')->middleware('acceso');
Route::get('/medico/{id}/especialidades', [medicoController::class, 'medicoEspecialidad'])->middleware('auth');
Route::post('/medico/especialidades', [medicoController::class, 'medicoEspecialidadGuardar'])->middleware('auth');
Route::get('/medico/{id}/horario', [medicoController::class, 'horario'])->middleware('auth');
Route::post('/medico/horario', [medicoController::class, 'horarioGuardar'])->middleware('auth');
Route::get('/medico/horario', [medicoController::class, 'horarioGuardar'])->middleware('auth');
Route::get('/cheque/imprimir/{id}', [listaChequeController::class, 'imprimirCheque'])->middleware('auth');

Route::post('/listarRetencionesAnuladas', [anularRetencionesController::class, 'consultar'])->middleware('auth');


//CHEQUE IMPRESION
Route::get('/cuentaBancaria/new/{id}', [cuentaBancariaController::class, 'configurarCheque'])->middleware('auth');
Route::post('/cuentaBancaria/guardarConfigCheque/{id}', [cuentaBancariaController::class, 'guardarConfCheque'])->name('cuentaBancaria.guardarConfCheque')->middleware('auth');


/*---------------------*/
/*PRECIOS PRODUCTO*/
Route::get('/producto/precio/{id}', [productoController::class, 'nuevoPrecio'])->middleware('auth');
Route::post('/producto/precio', [productoController::class, 'guardarPrecio'])->middleware('auth');
/*ANEXO TRANSACCIONAL SIMPLIFICADO*/
Route::get('/atsSRI', [AtsController::class, 'nuevo'])->middleware('auth');
Route::post('/atsSRI', [AtsController::class, 'consultar'])->middleware('auth');
/*BALANCE DE COMPROBACION*/
Route::get('/balanceComprobacion', [balanceComprobacionController::class, 'nuevo'])->middleware('auth');
Route::post('/balanceComprobacion', [balanceComprobacionController::class, 'consultar'])->middleware('auth');
/*BALANCE DE RESULTADOS*/
Route::get('/estadoResultados', [estadoResultadosController::class, 'nuevo'])->middleware('auth');
Route::post('/estadoResultados', [estadoResultadosController::class, 'consultar'])->middleware('auth');
/*ESTADO DE SITUACION FINANCIERO*/
Route::get('/estadoFinanciero', [estadoFinancieroController::class, 'nuevo'])->middleware('auth');
Route::post('/estadoFinanciero', [estadoFinancieroController::class, 'consultar'])->middleware('auth');
/*MAYOR AUXILIAR*/
Route::get('/mayorAuxiliar', [mayorAuxiliarController::class, 'nuevo'])->middleware('auth');
Route::post('/mayorAuxiliar', [mayorAuxiliarController::class, 'consultar'])->middleware('auth');
/*MAYOR DE CLIENTES*/
Route::get('/mayorClientes', [mayorClientesController::class, 'nuevo'])->middleware('auth');
Route::post('/mayorClientes', [mayorClientesController::class, 'consultar'])->middleware('auth');
/*MAYOR DE PROVEEDORES*/
Route::get('/mayorProveedores', [mayorProveedoresController::class, 'nuevo'])->middleware('auth');
Route::post('/mayorProveedores', [mayorProveedoresController::class, 'consultar'])->middleware('auth');
/*LISTA DE ANTICIPO A CLIENTES*/
Route::get('/listaAnticipoCliente', [listaAnticipoClienteController::class, 'nuevo'])->middleware('auth');
Route::post('/listaAnticipoCliente', [listaAnticipoClienteController::class, 'consultar'])->middleware('auth');
/*LISTA DE ANTICIPO A PROVEEDORES*/
Route::get('/listaAnticipoProveedor', [listaAnticipoProveedorController::class, 'nuevo'])->middleware('auth');
Route::post('/listaAnticipoProveedor', [listaAnticipoProveedorController::class, 'consultar'])->middleware('auth');
/*LISTA DE ANTICIPO A EMPLEADOS*/
Route::get('/listaAnticipoEmpleado', [listaAnticipoEmpleadoController::class, 'nuevo'])->middleware('auth');
Route::post('/listaAnticipoEmpleado', [listaAnticipoEmpleadoController::class, 'consultar'])->middleware('auth');
/*INICIALIZAR CUENTAS POR COBRAR*/
Route::get('/inicializarCXC', [inicializarCuentasCobrarController::class, 'nuevo'])->middleware('auth');
Route::post('/inicializarCXC', [inicializarCuentasCobrarController::class, 'consultar'])->middleware('auth');
/*INICIALIZAR CUENTAS POR PAGAR*/
Route::get('/inicializarCXP', [inicializarCuentasPagarController::class, 'nuevo'])->middleware('auth');
Route::post('/inicializarCXP', [inicializarCuentasPagarController::class, 'consultar'])->middleware('auth');
/*ACTUALIZAR COSTOS*/
Route::get('/actualizarCostos', [actualizarCostosController::class, 'nuevo'])->middleware('auth');
Route::post('/actualizarCostos', [actualizarCostosController::class, 'actualizar'])->middleware('auth');


/*PARAMETRIZACION CONTABLE BUSCAR POR SUCURSAL*/
Route::post('/parametrizacionContableBuscar', [parametrizacionContableController::class, 'buscarSucursal'])->middleware('auth');
Route::post('/grupoActivoBuscar', [grupoActivoController::class, 'buscarGrupo'])->middleware('auth');
Route::post('/ActivoBuscar', [activoFijoController::class, 'buscarActivo'])->middleware('auth');
Route::post('/ActivoBuscarDepreciacion', [depreciacionMensualController::class, 'buscarActivoDepreciacion'])->middleware('auth');

Route::post('/ActivoVentaBuscar', [ventaActivoController::class, 'buscarVentaActivo'])->middleware('auth');

/*DESCONTAR ANTICIPO CLIENTE*/
Route::get('/descontarAntCli', [descontarAnticipoClienteController::class, 'nuevo'])->middleware('auth');
Route::post('/descontarAntCli', [descontarAnticipoClienteController::class, 'descontar'])->middleware('auth');
/*DESCONTAR ANTICIPO PROVEEDOR*/
Route::get('/descontarAntPro', [descontarAnticipoProveedorController::class, 'nuevo'])->middleware('auth');
Route::post('/descontarAntPro', [descontarAnticipoProveedorController::class, 'descontar'])->middleware('auth');
/*DESCONTAR ANTICIPO EMPLEADO*/
Route::get('/descontarAntEmp', [descontarAnticipoEmpleadoController::class, 'nuevo'])->middleware('auth');
Route::post('/descontarAntEmp', [descontarAnticipoEmpleadoController::class, 'descontar'])->middleware('auth');
Route::post('/descontarAntEmpDep', [descontarAnticipoEmpleadoController::class, 'empleadosDepartamentoAnticipo'])->middleware('auth');
/*ELIMINAR ANTICIPO CLIENTE*/
Route::get('/eliminatAntCli', [anticipoClienteController::class, 'nuevoE'])->middleware('auth');
Route::post('/eliminatAntCli', [anticipoClienteController::class, 'buscarEliminar'])->middleware('auth');
/*ELIMINAR ANTICIPO PROVEEDOR*/
Route::get('/eliminatAntPro', [anticipoProveedorController::class, 'nuevoE'])->middleware('auth');
Route::post('/eliminatAntPro', [anticipoProveedorController::class, 'buscarEliminar'])->middleware('auth');
/*ELIMINAR ANTICIPO EMPLEADO*/
Route::get('/eliminatAntEmp', [anticipoEmpleadoController::class, 'nuevoE'])->middleware('auth');
Route::post('/eliminatAntEmp', [anticipoEmpleadoController::class, 'buscarEliminar'])->middleware('auth');
/*ASIENTO DIARIO*/
Route::get('/asientoDiario', [asientoDiarioController::class, 'nuevo'])->middleware('auth');
Route::post('/asientoDiario', [asientoDiarioController::class, 'consultar'])->middleware('auth');
Route::get('/asientoDiario/ver/{id}', [asientoDiarioController::class, 'ver'])->middleware('auth');
Route::get('/asientoDiarioEgreso/ver/{id}', [asientoDiarioController::class, 'verComprabanteegreso'])->middleware('auth');
Route::get('/asientoDiario/imprimir/{id}', [asientoDiarioController::class, 'imprimir'])->middleware('auth');
Route::get('/asientoDiario/imprimirEgreso/{id}', [asientoDiarioController::class, 'imprimirEgreso'])->middleware('auth');
Route::get('/asientoDiario/listar', [asientoDiarioController::class, 'listar'])->middleware('auth');
Route::post('/asientoDiario/listar', [asientoDiarioController::class, 'buscarLista'])->middleware('auth');
Route::get('/asientoDiario/editar/{id}', [asientoDiarioController::class, 'editar'])->middleware('auth');
Route::post('/asientoDiario/editar/guardar', [asientoDiarioController::class, 'guardarAsientoEditado'])->middleware('auth');
Route::get('/asientoDiario/eiminar/{id}', [asientoDiarioController::class, 'verEliminar'])->middleware('auth');
Route::post('/asientoDiario/eliminar', [asientoDiarioController::class, 'eliminar'])->middleware('auth');
Route::get('/asientoDiario/editarD/{id}', [asientoDiarioController::class, 'editarD'])->middleware('auth');
Route::post('/asientoDiario/editarD', [asientoDiarioController::class, 'guardarDescuadrado'])->middleware('auth');
Route::get('/asientoDiario/descuadrados', [asientoDiarioController::class, 'descuadradosIndex'])->middleware('auth');
Route::post('/asientoDiario/descuadrados', [asientoDiarioController::class, 'descuadrados'])->middleware('auth');
Route::get('/asientoDiario/asientoAjuste', [asientoDiarioController::class, 'asientoAjusteIndex'])->middleware('auth');
Route::post('/asientoDiario/asientoAjuste', [asientoDiarioController::class, 'asientoAjusteGuardar'])->middleware('auth');
Route::get('/fichaEmpeladoPdf/imprimir/{id}', [empleadoController::class, 'fichaEmpleadoImprime'])->middleware('auth');
Route::get('/cierreCajaPdf/imprimir/{id}', [cierreCajaController::class, 'cierreCajaImprime'])->middleware('auth');
Route::get('/chequeImpresionPdf/imprimir/{id}', [cuentaBancariaController::class, 'chequeImprima'])->middleware('auth');

/*CONFIGURACION QUINCENA PUNTOS DE EMISION*/

Route::get('/quincenapuntoemision', [quincenaController::class, 'mision'])->middleware('auth');
Route::post('/lquincena/puntomision', [quincenaController::class, 'asignarmision'])->middleware('auth');
//////////////////


/*CONFIGURACION ESPECIALIDAD*/
Route::get('/especialidad/configuracionEspecialidad/{id}', [especialidadController::class, 'configuracionEsecialidad'])->middleware('auth');
Route::post('/especialidad/configuracionEspecialidad/guardar', [especialidadController::class, 'configuracionEsecialidadGuardar'])->middleware('auth');
/*SIGNOS VITALES ESPECIALIDAD*/
Route::get('/especialidad/signose/{id}', [especialidadController::class, 'signose'])->middleware('auth');
Route::post('/especialidad/signose/guardar', [especialidadController::class, 'signoseGuardar'])->middleware('auth');

/*LISTA DE CARTERA*/
Route::get('/listaCartera', [listaCarteraController::class, 'nuevo'])->middleware('auth');
Route::post('/listaCartera', [listaCarteraController::class, 'consultar'])->middleware('auth');
Route::post('/depreciacionConsultar', [depreciacionMensualController::class, 'consultar'])->middleware('auth');

/*LISTA DE DEUDAS*/
Route::get('/listaDeudas', [listaDeudasController::class, 'nuevo'])->middleware('auth');
Route::post('/listaDeudas', [listaDeudasController::class, 'consultar'])->middleware('auth');
/*COBROS DE CLIENTES*/
Route::get('/pagosCXC', [cobrosClientesController::class, 'nuevo'])->middleware('auth');
Route::post('/pagosCXC', [cobrosClientesController::class, 'guardar'])->middleware('auth');
Route::post('/pagosCliCXC', [cobrosClientesController::class, 'clientesSucursalCXC'])->middleware('auth');
/*Eliminar cobros de clientes*/
Route::get('/eliminarPagoCXC', [cobrosClientesController::class, 'nuevoEliminarPago'])->middleware('auth');
Route::post('/eliminarPagoCXC', [cobrosClientesController::class, 'buscarEliminar'])->middleware('auth');
/*PAGOS A PROVEEDORES*/
Route::get('/pagosCXP', [pagosProveedoresController::class, 'nuevo'])->middleware('auth');
Route::post('/pagosCXP', [pagosProveedoresController::class, 'guardar'])->middleware('auth');
Route::post('/pagosProCXP', [pagosProveedoresController::class, 'proveedoresSucursalCXP'])->middleware('auth');
/*Eliminar pagos de proveedores*/
Route::get('/eliminarPagoCXP', [pagosProveedoresController::class, 'nuevoEliminarPago'])->middleware('auth');
Route::post('/eliminarPagoCXP', [pagosProveedoresController::class, 'buscarEliminar'])->middleware('auth');
/*KARDEX*/
Route::get('/kardex', [kardexController::class, 'nuevo'])->middleware('auth');
Route::post('/kardex', [kardexController::class, 'consultar'])->middleware('auth');
/*KARDEX COSTO*/
Route::get('/kardexCosto', [kardexCostoController::class, 'nuevo'])->middleware('auth');
Route::post('/kardexCosto', [kardexCostoController::class, 'consultar'])->middleware('auth');
/*CUENTA POR COBRAR*/
Route::get('/cxc', [cuentaCobrarController::class, 'index'])->middleware('auth');
Route::post('/cxc/buscar', [cuentaCobrarController::class, 'consultar'])->middleware('auth');
Route::post('/cxc/buscarSaldo', [cuentaCobrarController::class, 'consultarSaldo'])->middleware('auth');
/*CUENTA POR PAGAR*/
Route::get('/cxp', [cuentaPagarController::class, 'index'])->middleware('auth');
Route::post('/cxp/buscar', [cuentaPagarController::class, 'consultar'])->middleware('auth');
Route::post('/cxp/buscarSaldo', [cuentaPagarController::class, 'consultarSaldo'])->middleware('auth');
/*CIERRE DE MES CONTABLE*/
Route::get('/cierreMes', [cierreMesController::class, 'index'])->middleware('auth');
Route::post('/cierreMes', [cierreMesController::class, 'consultar'])->middleware('auth');
Route::post('/cierreMes/guadar', [cierreMesController::class, 'guardar'])->middleware('auth');
Route::get('/cierreMes/editar/{id}', [cierreMesController::class, 'editar'])->middleware('auth');
Route::post('/cierreMes/edit/{id}', [cierreMesController::class, 'edit'])->name('cierre.edit')->middleware('auth');
Route::get('/cierreMes/eliminar/{id}', [cierreMesController::class, 'eliminar'])->middleware('auth');
Route::post('/cierreMes/elim/{id}', [cierreMesController::class, 'elim'])->name('cierre.elim')->middleware('auth');
/*NOTA DE CREDITO BANCARIA*/
Route::get('/notaCreditoBanco/new/{id}', [notaCreditoBancoController::class, 'nuevo'])->middleware('auth');
/*NOTA DE DEBITO BANCARIA*/
Route::get('/notaDebitoBanco/new/{id}', [notaDebitoBancoController::class, 'nuevo'])->middleware('auth');
/*CONSULTAR DOCUMENTOS ELECTRONICOS*/
Route::get('/sriDocElec', [facturacionElectronicaController::class, 'nuevaConsultaSri'])->middleware('auth');
Route::post('/sriDocElec', [facturacionElectronicaController::class, 'consultarDocSri'])->middleware('auth');


//AJAX
Route::get('/puntomision/searchN/{ide}', [puntoEmisionController::class, 'buscarByIdSucursal'])->middleware('auth');
Route::post('/procedimiento/searchN', [procedimientoEspecialidadController::class, 'buscarBy'])->middleware('auth');
Route::post('/analisis/searchN', [examenController::class, 'buscarByExamen'])->middleware('auth');
Route::get('/medicinas/searchN/{buscar}', [medicamentoController::class, 'buscarBy'])->middleware('auth');

Route::get('/nuevartencion/searchN/{ide}', [transaccionCompraController::class, 'buscarBy'])->middleware('auth');
Route::get('/proveedores/searchN/{buscar}', [proveedorController::class, 'buscarByProveedor'])->middleware('auth');
Route::get('/categoria/searchN/{ide}', [categoriaProductoController::class, 'buscarBy'])->middleware('auth');
Route::get('/laboratorio/searchN/{ide}', [examenController::class, 'buscarByanalisis'])->middleware('auth');
Route::get('/laboratoriovalores/searchN/{ide}', [valorLaboratorioController::class, 'buscarBy'])->middleware('auth');
Route::get('/buscarAlimentacion/search/{ide}', [alimentacionController::class, 'buscarByAlimentacion'])->middleware('auth');
Route::get('/buscarAlimentacion/searchN/{ide}', [alimentacionController::class, 'buscarByEmpleado'])->middleware('auth');
Route::get('/dias/searchN/{ide}', [controlDiasController::class, 'buscarByEmpleado'])->middleware('auth');
Route::get('/dias/search/{ide}', [controlDiasController::class, 'PresentarEmpleado'])->middleware('auth');
Route::get('/buscarquincena/searchN/{ide}', [quincenaController::class, 'buscarByEmpleado'])->middleware('auth');
Route::get('/buscarvacaciones/searchN/{ide}', [vacacionController::class, 'buscarByEmpleado'])->middleware('auth');
Route::get('/empleadosalimentos/searchN/{ide}', [empleadoController::class, 'presentarEmpleados'])->middleware('auth');
Route::get('/empleados/searchN/{ide}', [empleadoController::class, 'buscarByEmpleado'])->middleware('auth');
Route::get('/empleados/banco/{ide}', [empleadoController::class, 'buscarByEmpleadoBanco'])->middleware('auth');
Route::get('/buscarAnticipos/searchN/{ide}', [anticipoEmpleadoController::class, 'buscarByEmpleado'])->middleware('auth');
Route::post('/servicios/searchN', [productoController::class, 'servicios'])->middleware('auth');
Route::get('/especilidadesPaciente/searchN/{id}', [pacienteController::class, 'buscarByidPaciente'])->middleware('auth');
Route::get('/ordenes/searchN/{id}', [ordenAtencionController::class, 'buscarByFecha'])->middleware('auth');
Route::get('/horas/searchN/{id}', [ordenAtencionController::class, 'buscarByDia'])->middleware('auth');
Route::get('/sucursales/searchN/{id}', [sucursalController::class, 'buscarByIdSucursal'])->middleware('auth');
Route::get('/paciente/searchN/{buscar}', [pacienteController::class, 'buscarByNombrePaciente'])->middleware('auth');
Route::post('/documentoAnulado/searchN', [documentoAnuladoController::class, 'buscarDocumento'])->middleware('auth');
Route::post('/documentoDetalle/searchN', [documentoAnuladoController::class, 'detalleDocumento'])->middleware('auth');
Route::get('/entidadProcedimiento/searchN/{buscar}', [entidadProcedimientoController::class, 'buscarByEspecialidadId'])->middleware('auth');
Route::post('/valorAsignado/searchN', [entidadProcedimientoController::class, 'buscarByEntidadId'])->middleware('auth');
Route::post('/procedimientosAsignados/searchN', [aseguradoraProcedimientoController::class, 'buscarByClienteId'])->middleware('auth');
Route::post('/facturaVenta/searchN', [facturaVentaController::class, 'buscarByNumeroFactura'])->middleware('auth');
Route::post('/facturaVentaRetencionRecibida/searchN', [facturaVentaController::class, 'buscarByNumeroFacturaRetRecibida'])->middleware('auth');
Route::post('/transaccioncompra/searchN', [transaccionCompraController::class, 'buscarByAliemtacion'])->middleware('auth');
Route::post('/facturaVentaDetalle/searchN', [facturaVentaController::class, 'buscarByDetalleFactura'])->middleware('auth');
Route::post('/facturaVentaDetalleRet/searchN', [facturaVentaController::class, 'buscarByDetalleFacturaRet'])->middleware('auth');
Route::get('/entidad/searchN/{buscar}', [pacienteController::class, 'buscarByEntidad'])->middleware('auth');
Route::get('/facturasCompra/searchN/{buscar}', [transaccionCompraController::class, 'buscarByProveedor'])->middleware('auth');
Route::get('/datosFactCompra/searchN/{buscar}', [transaccionCompraController::class, 'buscarByTransaccion'])->middleware('auth');
Route::get('/provincia/searchN/{buscar}', [pacienteController::class, 'buscarByPais'])->middleware('auth');
Route::get('/ciudad/searchN/{buscar}', [pacienteController::class, 'buscarByProvincia'])->middleware('auth');
Route::get('/aseguradoraProcedimiento/searchN/{buscar}', [aseguradoraProcedimientoController::class, 'buscarByNombre'])->middleware('auth');
Route::get('/cuentaBancaria/searchN/{buscar}', [cuentaBancariaController::class, 'buscarByBanco'])->middleware('auth');
Route::get('/cuentaBanco/searchN/{buscar}', [cuentaBancariaController::class, 'buscarByBancoCuenta'])->middleware('auth');
Route::get('/cuentaContable/searchN/{buscar}', [cuentaBancariaController::class, 'buscarByCuentaBanco'])->middleware('auth');
Route::get('/cuentaBancariaId/searchN/{buscar}', [cuentaBancariaController::class, 'buscarByCuentaBancaria'])->middleware('auth');
Route::get('/cuentasCaja/searchN', [anticipoClienteController::class, 'buscarByCuentas'])->middleware('auth');
Route::get('/cuentaParametrizadaCaja/searchN/{buscar}', [parametrizacionContableController::class, 'buscarByNomCuenta'])->middleware('auth');
Route::get('/cliente/searchN/{buscar}', [clienteController::class, 'buscarByNombre'])->middleware('auth');
Route::get('/cliente/searchNCedula/{buscar}', [clienteController::class, 'buscarByNombreCedula'])->middleware('auth');
Route::get('/producto/searchN/{buscar}', [productoController::class, 'buscarByNombre'])->middleware('auth');
Route::get('/productoVenta/searchN/{buscar}', [productoController::class, 'buscarByNombreVenta'])->middleware('auth');
Route::post('/productoVenta/precio/searchN', [productoController::class, 'buscarPrecio'])->middleware('auth');
Route::get('/proveedor/searchN/{buscar}', [proveedorController::class, 'buscarByNombre'])->middleware('auth');
Route::post('/anticiposCliente/searchN', [anticipoClienteController::class, 'buscarByCliente'])->middleware('auth');
Route::post('/facturaVentaAnt/searchN', [facturaVentaController::class, 'buscarByNumeroFacturaAnt'])->middleware('auth');
Route::post('/factura/searchN', [facturaVentaController::class, 'buscarByFactura'])->middleware('auth');
Route::post('/facturaCompraAnt/searchN', [transaccionCompraController::class, 'buscarByNumeroFacturaAnt'])->middleware('auth');
Route::post('/facturaCompraDetalle/searchN', [transaccionCompraController::class, 'buscarByDetalleFactura'])->middleware('auth');
Route::post('/anticiposProveedor/searchN', [anticipoProveedorController::class, 'buscarByProveedor'])->middleware('auth');
Route::post('/facturasCXC/searchN', [cuentaCobrarController::class, 'buscarByCliente'])->middleware('auth');
Route::post('/facturasCXP/searchN', [cuentaPagarController::class, 'buscarByProveedor'])->middleware('auth');
Route::get('/quincena/searchN', [quincenaController::class, 'buscarBy'])->middleware('auth');
Route::get('/cajaCuentaContable/searchN/{buscar}', [cajaController::class, 'cuentaCaja'])->middleware('auth');
Route::get('/grupoSucursal/searchN/{buscar}', [activoFijoController::class, 'buscarBySucursal'])->middleware('auth');
Route::get('/activoSucursal/searchN/{buscar}', [ventaActivoController::class, 'buscarBySucursal'])->middleware('auth');
Route::get('/cuentaDepreciacion/searchN/{buscar}', [activoFijoController::class, 'buscarCuenta'])->middleware('auth');
Route::get('/cuentaGasto/searchN/{buscar}', [activoFijoController::class, 'buscarGasto'])->middleware('auth');
Route::get('/porcentajeDepreciacion/searchN/{buscar}', [activoFijoController::class, 'buscarPorcentaje'])->middleware('auth');
Route::get('/facturaCompra/searchN/{buscar}', [activoFijoController::class, 'buscarFactura'])->middleware('auth');
Route::get('/fechaDocumento/searchN/{buscar}', [activoFijoController::class, 'buscarFecha'])->middleware('auth');
Route::get('/fechaDiario/searchN/{buscar}', [activoFijoController::class, 'buscarFechaDiario'])->middleware('auth');
Route::get('/sumaDiario/searchN/{buscar}', [activoFijoController::class, 'sumatoriaDiario'])->middleware('auth');
Route::get('/sumaVentasActivo/searchN/{buscar}', [ventaActivoController::class, 'sumatoriaVentas'])->middleware('auth');
Route::get('/diarioCodigo/searchN/{buscar}', [asientoDiarioController::class, 'diarioCodigo'])->middleware('auth');
Route::get('/cuentaContablePadre/searchN/{buscar}', [cuentaController::class, 'cuentaPadre'])->middleware('auth');
Route::get('/activoVentaActivo/searchN/{buscar}', [ventaActivoController::class, 'buscarByActivo'])->middleware('auth');
Route::get('/especialidadMedicoHorario/searchN/{buscar}', [medicoController::class, 'buscarHorarioByEspecialidad'])->middleware('auth');
Route::get('/medicoEspecialidad/searchN/{buscar}', [medicoController::class, 'buscarByEspecialidad'])->middleware('auth');
Route::post('/asegProsedimiento/searchN', [ordenAtencionController::class, 'buscarAseguradoraProcedimiento'])->middleware('auth');
Route::get('/ordenAtencionReclamo/searchN/{buscar}', [ordenAtencionController::class, 'secuencialReclamo'])->middleware('auth');
Route::get('/enfermedad/searchN', [enfermedadController::class, 'buscarBy'])->middleware('auth');
Route::post('/listaPrecio/searchN', [listaPrecioController::class, 'buscarByProductoId'])->middleware('auth');
Route::get('/cajaSucursal/searchN/{buscar}', [cuadreCajaAbiertaController::class, 'buscarCajaBySucursal'])->middleware('auth');
Route::get('/bancos/searchN', [bancoListaController::class, 'buscarBy'])->middleware('auth');
Route::get('/diario/searchN/{buscar}', [asientoDiarioController::class, 'diarioById'])->middleware('auth');
Route::get('/rubro/searchN/{buscar}', [rubroController::class, 'rubroById'])->middleware('auth');
//Crear excedentesd de caja
Route::get('/faltanteCaja/new/{id}', [faltanteCajaController::class, 'nuevo'])->middleware('auth');
Route::get('/sobranteCaja/new/{id}', [sobranteCajaController::class, 'nuevo'])->middleware('auth');
Route::get('/egresoCaja/new/{id}', [egresoCajaController::class, 'nuevo'])->middleware('auth');
Route::get('/ingresoCaja/new/{id}', [ingresoCajaController::class, 'nuevo'])->middleware('auth');
Route::get('/egresoBanco/new/{id}', [egresoBancoController::class, 'nuevo'])->middleware('auth');
Route::get('/ingresoBanco/new/{id}', [ingresoBancoController::class, 'nuevo'])->middleware('auth');
Route::get('/anticipoCliente/new/{id}', [anticipoClienteController::class, 'nuevo'])->middleware('auth');
Route::get('/anticipoProveedor/new/{id}', [anticipoProveedorController::class, 'nuevo'])->middleware('auth');
Route::get('/anticipoEmpleado/new/{id}', [anticipoEmpleadoController::class, 'nuevo'])->middleware('auth');
Route::post('/empleadosrubro/searchN', [asignacionRolController::class, 'presentarEmpleadosRubro'])->middleware('auth');


//BuscarHorarios
Route::get('/horarios/getCitaDisponible', [ordenAtencionIessController::class, 'getCitaMedicaDisponible'])->middleware('auth');
Route::get('/horarios/getOrdenesMedico', [ordenAtencionController::class, 'getOrdenesMedico'])->middleware('auth');
Route::get('/horarios/getOrdenesIessMedico', [ordenAtencionIessController::class, 'getOrdenesMedico'])->middleware('auth');

//ordenes de examen
Route::get('/examenes/testOrden', [atencionCitasController::class, 'pruebaOrden'])->middleware('auth');
Route::get('/examenes/testGetOrdenes', [atencionCitasController::class, 'pruebaGetOrdenes'])->middleware('auth');
Route::get('/examenes/testGetOrden', [atencionCitasController::class, 'pruebaGetOrden'])->middleware('auth');
Route::get('/examenes/testGetOrdenPdf', [atencionCitasController::class, 'pruebaGetOrdenPdf'])->middleware('auth');

//Crear ROLES
Route::get('/rolConsolidado/new/{id}', [rolConsolidadoController::class, 'nuevo'])->middleware('auth');
Route::get('/rolindividual/new/{id}', [rolIndividualController::class, 'nuevo'])->middleware('auth');
Route::get('/roloperativo/new/{id}', [rolOperativoController::class, 'nuevo'])->middleware('auth');


//Crear ROLES COSTAMARKET
Route::get('/rolConsolidadoCM/new/{id}', [rolConsolidadoCostaMarketController::class, 'nuevo'])->middleware('auth');
Route::get('/rolindividualCM/new/{id}', [rolIndividualCostaMarketController::class, 'nuevo'])->middleware('auth');
Route::get('/roloperativoCM/new/{id}', [rolOperactivoCostaMarketController::class, 'nuevo'])->middleware('auth');

Route::get('/roloperativoCM/{id}/eliminar', [rolOperactivoCostaMarketController::class, 'eliminar'])->middleware('auth');
Route::get('/rolindividualCM/{id}/eliminar', [rolIndividualCostaMarketController::class, 'eliminar'])->middleware('auth');

Route::get('/roloperativoCM/{id}/cambiocheque', [rolOperactivoCostaMarketController::class, 'cambiocheque'])->middleware('auth');
Route::get('/rolindividualCM/{id}/cambiocheque', [rolIndividualCostaMarketController::class, 'cambiocheque'])->middleware('auth');
//Crear proforma
Route::get('/proforma/new/{id}', [proformaController::class, 'nuevo'])->middleware('auth');

//Presentar proforma
Route::get('/listaProforma', [listaProformaController::class, 'nuevo'])->middleware('auth');
//Facturar Proforma
Route::post('/listaProforma', [listaProformaController::class, 'consultar'])->middleware('auth');
Route::post('/proforma/factura', [facturaproformaController::class, 'extraer'])->middleware('auth');
Route::post('/factura/proforma', [facturaproformaController::class, 'guardarfactura'])->middleware('auth');

//Editar Proforma
Route::get('/proforma/edit/{id}', [proformaController::class, 'editar'])->middleware('auth');

Route::get('/operativorol/new/{id}', [cabeceraRolController::class, 'nuevo'])->middleware('auth');
Route::get('/individualrol/new/{id}', [cabeceraRolAdministrativoController::class, 'nuevo'])->middleware('auth');

//Guias de remision
Route::get('/guiaRemision/new/{id}', [guiaremisionController::class, 'nuevo'])->middleware('auth');
Route::get('/guiaRemision/searchN/{id}', [guiaremisionController::class, 'buscarByIdTransportista'])->middleware('auth');
Route::post('/guia/consultar', [guiaremisionController::class, 'consultar'])->middleware('auth');
Route::get('/guia/{id}/eliminar', [guiaremisionController::class, 'delete'])->middleware('auth')->middleware('acceso');
Route::get('/guia/{id}/visualizar', [guiaremisionController::class, 'edit'])->middleware('auth')->middleware('acceso');
Route::get('/guia/{id}/imprimir', [guiaremisionController::class, 'imprimir'])->middleware('auth');
Route::get('/guia/{id}/veranular', [guiaremisionController::class, 'veranular'])->middleware('auth');
Route::post('/guia/anular', [guiaremisionController::class, 'anular'])->middleware('auth');

//Guias de remision con ordenes de despacho
Route::get('/guiaordenes/{id}/visualizar', [guiaremisionController::class, 'edit'])->middleware('auth')->middleware('acceso');
Route::post('/guiaordenes/consultar', [listaGuiasRemisionOrdenesController::class, 'verificar'])->middleware('auth');
Route::get('/guiaordenes/{id}/eliminar', [listaGuiasRemisionOrdenesController::class, 'delete'])->middleware('auth')->middleware('acceso');

Route::get('/ordenDespacho/new/{id}', [ordenDespachoController::class, 'nuevo'])->middleware('auth');
Route::post('/ordenDespacho/consultar', [ordenDespachoController::class, 'consultar'])->middleware('auth');
Route::get('/ordenDespacho/{id}/visualizar', [ordenDespachoController::class,'visualizar'])->middleware('auth')->middleware('acceso');
Route::get('/ordenDespacho/{id}/eliminar', [ordenDespachoController::class, 'Presentardelete'])->middleware('auth')->middleware('acceso');
Route::get('/ordenDespacho/{id}/anular', [ordenDespachoController::class, 'Presentaranular'])->middleware('auth');
Route::post('/ordenDespacho/anular', [ordenDespachoController::class, 'anular'])->middleware('auth');
Route::get('/ordenDespacho/{id}/editar', [ordenDespachoController::class, 'Presentareditar'])->middleware('auth');
Route::get('/ordenDespacho/{id}/imprimir', [ordenDespachoController::class,'imprimir'])->middleware('auth')->middleware('acceso');

Route::get('/ordenDespacho/reporte', [reporteOrdenesDespachoController::class, 'nuevo'])->middleware('auth');
Route::post('/ordenDespacho/buscar', [reporteOrdenesDespachoController::class, 'consultar'])->middleware('auth');

Route::post('/ordenDespacho/guia', [ordenDespachoController::class, 'verificar'])->middleware('auth');
Route::post('/guia/ordenDespacho', [guiaremisionController::class, 'enviar'])->middleware('auth');
Route::post('/factura/guia', [facturaVentaController::class, 'guardarfactura'])->middleware('auth');


//Route::post('/ordenDespacho/guia', [ordenDespachoController::class, 'extraer'])->middleware('auth');

//crear egreso de bodega
Route::get('/egresoBodega/new/{id}', [egresoBodegaController::class, 'nuevo'])->middleware('auth');
Route::get('/egresoBodega/eliminar/{id}', [egresoBodegaController::class, 'Presentardelete'])->middleware('auth');
Route::get('/egresoBodega/visualizar/{id}', [egresoBodegaController::class, 'Presentarvisualizar'])->middleware('auth');
Route::post('/egresoBodega/buscar', [egresoBodegaController::class, 'consultar'])->middleware('auth');

Route::get('/ingresoBodega/new/{id}', [ingresoBodegaController::class, 'nuevo'])->middleware('auth');
Route::get('/ingresoBodega/eliminar/{id}', [ingresoBodegaController::class, 'Presentardelete'])->middleware('auth');
Route::get('/ingresoBodega/visualizar/{id}', [ingresoBodegaController::class, 'Presentarvisualizar'])->middleware('auth');
Route::post('/ingresoBodega/buscar', [ingresoBodegaController::class, 'consultar'])->middleware('auth');

// quincena
Route::post('/quincenaConsolidada/generar', [quincenaConsolidadaController::class, 'generar'])->middleware('auth');
Route::get('/lquincena/{id}/imprimir', [listaquincenaConsolidadaController::class, 'imprimir'])->middleware('auth');
Route::get('/lquincena/{id}/imprimirempleado', [quincenaController::class, 'imprimir'])->middleware('auth');
//rol consolidado
Route::get('/rolconsolidado/{id}/imprimir', [rolConsolidadoController::class, 'imprimirrol'])->middleware('auth');
Route::get('/rolindividual/{id}/imprimir', [rolConsolidadoController::class, 'imprimirrol'])->middleware('auth');
Route::get('/rolindividual/{id}/imprimirdiario', [rolConsolidadoController::class, 'imprimirdiario'])->middleware('auth');
Route::get('/rolindividual/{id}/imprimirdiariocontabilizado', [rolConsolidadoController::class, 'imprimirdiariocontabilizado'])->middleware('auth');

Route::get('/rolCM/{id}/imprimir', [rolOperactivoCostaMarketController::class, 'imprimirrol'])->middleware('auth');
Route::get('/rolCM/{id}/imprimirdiario', [rolOperactivoCostaMarketController::class, 'imprimirdiario'])->middleware('auth');
Route::get('/rolCM/{id}/imprimirdiariocontabilizado', [rolOperactivoCostaMarketController::class, 'imprimirdiariocontabilizado'])->middleware('auth');
Route::get('/rolConsolidadoimpresion/{fecha}', [rolConsolidadoController::class, 'ver'])->middleware('auth');
//rol individual

Route::get('/lvacaciones', [vacacionController::class, 'imprimir'])->middleware('auth');
Route::post('/lvacaciones', [vacacionController::class, 'buscar'])->middleware('auth');
Route::get('/vacacion/{id}/ver', [vacacionController::class, 'ver'])->middleware('auth');
Route::get('/vacacion/{id}/imprimir', [vacacionController::class, 'imprimirdiario'])->middleware('auth');

Route::get('/notaentrega/new/{id}', [notaEntregaController::class, 'nuevo'])->middleware('auth');
Route::get('/notaentrega/{id}/ver', [notaEntregaController::class, 'visualizar'])->middleware('auth');
Route::get('/notaentrega/{id}/imprimir', [notaEntregaController::class, 'imprimir'])->middleware('auth');
Route::get('/notaentrega/{id}/imprimirRecibo', [notaEntregaController::class, 'imprimirRecibo'])->middleware('auth');
Route::post('/notaentrega/buscar', [notaEntregaController::class, 'busqueda'])->middleware('auth');



Route::get('/decimoTercero/{fecha}', [decimoTerceroController::class, 'ver'])->middleware('auth');
Route::get('/decimoCuarto/{fecha}', [decimoCuartoController::class, 'ver'])->middleware('auth');


Route::get('/decimoTercero/{id}/imprimir', [decimoTerceroController::class, 'imprimir'])->middleware('auth');
Route::get('/decimoCuarto/{id}/imprimir', [decimoCuartoController::class, 'imprimir'])->middleware('auth');
Route::get('/diarioTercero/{id}/imprimir', [decimoTerceroController::class, 'imprimirdiario'])->middleware('auth');
Route::get('/diarioCuarto/{id}/imprimir', [decimoCuartoController::class, 'imprimirdiario'])->middleware('auth');


Route::get('/Roloperativo/{id}/cambiocheque', [rolConsolidadoController::class, 'verChequeoperativo'])->middleware('auth');
Route::get('/Rol/{id}/cambiocheque', [rolConsolidadoController::class, 'verChequeindividual'])->middleware('auth');
Route::get('/Roloperativo/{id}/ver', [rolConsolidadoController::class, 'veroperativo'])->middleware('auth');
Route::get('/Rol/{id}/ver', [rolConsolidadoController::class, 'verindividual'])->middleware('auth');
Route::get('/Roloperativo/{id}/eliminar', [rolConsolidadoController::class, 'eliminarChequeoperativo'])->middleware('auth');
Route::get('/Roles/{id}/eliminar', [rolConsolidadoController::class, 'eliminarChequeindividual'])->middleware('auth');
Route::post('/roloperativoCM/cheque', [rolOperactivoCostaMarketController::class, 'actualizarcheque'])->middleware('auth');

Route::get('/Rol/{id}/cambiocheque', [rolConsolidadoController::class, 'verChequeindividual'])->middleware('auth');


Route::post('/contabilizado/extraer', [contabilizacionMensualController::class, 'extraer'])->middleware('auth');

Route::get('/notaDebito/{id}/ver',  [listanotaDebitoController::class, 'ver'])->middleware('auth');
Route::get('/notaCredito/{id}/ver',  [listanotaCreditoController::class, 'ver'])->middleware('auth');

Route::get('/notaDebito/{id}/eliminar',  [listanotaDebitoController::class, 'eliminar'])->middleware('auth');
Route::get('/notaCredito/{id}/eliminar',  [listanotaCreditoController::class, 'eliminar'])->middleware('auth');
Route::get('/liquidacioncompra/{id}/eliminar',  [liquidacionCompraController::class, 'eliminar'])->middleware('auth');
Route::get('/transaccioncompra/{id}/edit',  [transaccionCompraController::class, 'editar'])->middleware('auth');
Route::get('/transaccioncompra/{id}/eliminar',  [transaccionCompraController::class, 'eliminar'])->middleware('auth');

Route::get('/factura/{id}/ver',  [listaFacturaController::class, 'ver'])->middleware('auth');
Route::get('/factura/{id}/ordenDespacho',  [listaFacturaController::class, 'ordenDespacho'])->middleware('auth');
Route::get('/factura/{id}/eliminar',  [listaFacturaController::class, 'eliminar'])->middleware('auth');
Route::get('/factura/{id}/imprimir',  [listaFacturaController::class, 'imprimir'])->middleware('auth');
Route::get('/factura/{id}/imprimirRecibo    ',  [listaFacturaController::class, 'imprimirRecibo'])->middleware('auth');

Route::get('/transaccioncompra/{id}/ver',  [listatransaccionCompraController::class, 'ver'])->middleware('auth');
Route::get('/liquidacioncompras/{id}/ver',  [listaliquidacionCompraController::class, 'ver'])->middleware('auth');

Route::get('/transaccioncompra/{id}/ver',  [listatransaccionCompraController::class, 'ver'])->middleware('auth');
Route::get('/liquidacioncompras/{id}/ver',  [listaliquidacionCompraController::class, 'ver'])->middleware('auth');

Route::get('/listanotaCreditoBancario/{id}/eliminar',  [listaNotaCreditoBancoController::class, 'eliminar'])->middleware('auth');
Route::get('/listanotaDebitoBancario/{id}/eliminar',  [listaNotaDebitoBancoController::class, 'eliminar'])->middleware('auth');

//laboratorio
Route::get('/valorLaboratorio/{id}/editar',  [detalleLaboratorioController::class, 'editar'])->middleware('auth');
Route::get('/valorLaboratorio/{id}/eliminar',  [detalleLaboratorioController::class, 'eliminar'])->middleware('auth');

//analisis
Route::get('/analisisLaboratorio/{id}/imprimirorden', [analisis_LaboratorioController::class, 'imprimiranalisis'])->middleware('auth');
Route::get('/analisisLaboratorio/{id}/resultados', [analisis_LaboratorioController::class, 'resultados'])->middleware('auth');
Route::get('/analisisLaboratorio/{id}/enviar', [analisis_LaboratorioController::class, 'enviar'])->middleware('auth');

//orden de recepcion
Route::get('/ordenRecepecion/{id}/eliminar',  [ordenRecepcionController::class, 'eliminar'])->middleware('auth');
Route::post('/ordenRecepecion/buscar',  [ordenRecepcionController::class, 'buscar'])->middleware('auth');
Route::post('/transaccionCompra/ordenrecepcion', [ordenRecepcionController::class, 'guardarorden'])->middleware('auth');
Route::get('/ordenRecepecion/{id}/imprimir',  [ordenRecepcionController::class, 'imprimir'])->middleware('auth');

//cargar Compras XML
Route::get('/compras/xml/{punto}',  [cargarXMLController::class, 'nuevo'])->middleware('auth');
Route::post('/compras/xml',  [cargarXMLController::class, 'cargar'])->middleware('auth');
Route::get('/compras/xmlProcesar/{clave}/{punto}',  [cargarXMLController::class, 'procesar'])->middleware('auth');

//reporte tributario
Route::get('/reporteTributario',  [formulariosController::class, 'nuevo'])->middleware('auth');
Route::post('/reporteTributario',  [formulariosController::class, 'consultar'])->middleware('auth');

//reporte utilidad por producto
Route::get('/utilidadProducto',  [reporteUtilidadController::class, 'nuevo'])->middleware('auth');
Route::post('/utilidadProducto',  [reporteUtilidadController::class, 'consultar'])->middleware('auth');