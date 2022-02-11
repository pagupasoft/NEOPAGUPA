<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Analisis_Laboratorio;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Cuenta_Cobrar;
use App\Models\Detalle_Analisis;
use App\Models\Detalle_Diario;
use App\Models\Detalle_FV;
use App\Models\Detalle_Laboratorio;
use App\Models\Detalle_Pago_CXC;
use App\Models\Diario;
use App\Models\Empresa;
use App\Models\Examen;
use App\Models\Factura_Venta;
use App\Models\Movimiento_Producto;
use App\Models\Orden_Examen;
use App\Models\Pago_CXC;
use App\Models\Parametrizacion_Contable;
use App\Models\Producto;
use App\Models\Tipo_Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Punto_Emision;
use App\Models\Rango_Documento;
use App\Models\Valor_Laboratorio;
use App\Models\Valor_Referencial;
use DateTime;
use PDF;

class examenController extends Controller
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
            $examenes=Examen::Examenes()->get();        
            $tipoExamenes=Tipo_Examen::TipoExamenes()->get();
            $productos=Producto::Productoslaboratorio()->get();
            return view('admin.citasMedicas.examen.index',['examenes'=>$examenes,'producto'=>$productos,'tipoExamenes'=>$tipoExamenes,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
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
          
            /* ---------------------------------------------------- */ 
            DB::beginTransaction();   
            $examen = new Examen();     
            $examen->examen_estado = 1;  
            $examen->tipo_id = $request->get('tipo_examen');
            $examen->producto_id = $request->get('producto_id');      
            $examen->save();
           
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de examen con producto id -> '.$request->get('producto_id'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('examen')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('examen')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function agregarValores($id){
        try{  
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $examenes=Examen::Examenes()->get();     
            $detallesLaboratorio = Detalle_Laboratorio::DetalleLaboratorios()->get();   
            $examen=Examen::Examen($id)->first();
            
            return view('admin.citasMedicas.examen.detalleLaboratorio',['detallesLaboratorio'=>$detallesLaboratorio,'examen'=>$examen,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function agregarValorLaboratorio($id){
        try{  
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
           
            $detalle_id = Detalle_Laboratorio::DetalleLaboratorio($id)->first();
            $valoresLab = Valor_Laboratorio::ValorLaboratoriodetalle($id)->get();  
            return view('admin.citasMedicas.examen.nuevoValorL',['valoresLab'=>$valoresLab,'detalle_id'=>$detalle_id,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function agregarValorReferencial($id){
        try{  
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
           
            $detallesLaboratorio = Valor_Referencial::ValorReferencialdetalle($id)->get(); 
            $detalle_id = Detalle_Laboratorio::DetalleLaboratorio($id)->first();
            return view('admin.citasMedicas.examen.nuevoValorR',['detalle_id'=>$detalle_id,'detallesLaboratorio'=>$detallesLaboratorio,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
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
            $examen=Examen::Examen($id)->first();
            $tipoExamenes=Tipo_Examen::TipoExamenes()->get();
            if($examen){
                return view('admin.citasMedicas.examen.ver',['examen'=>$examen ,'tipoExamenes'=>$tipoExamenes ,'PE'=>Punto_Emision::puntos()->get(), 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
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
        try{  
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $examen=Examen::Examen($id)->first();
            $tipoExamenes=Tipo_Examen::TipoExamenes()->get();
            $productos=Producto::Productoslaboratorio()->get();
            if($examen){
                return view('admin.citasMedicas.examen.editar',['examen'=>$examen,'producto'=>$productos,'tipoExamenes'=>$tipoExamenes,'PE'=>Punto_Emision::puntos()->get(), 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }catch(\Exception $ex){
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
            $examen=Examen::Examen($id)->first();
            $examen->producto_id = $request->get('producto_id');
           
            $examen->tipo_id = $request->get('tipo_id');     
            $examen->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de examen con producto-> '.$request->get('producto_id'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('examen')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('examen')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
        
          
            $examen=Examen::findOrFail($id);
            $examen->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion del examen -> '.$examen->producto_id.' con id -> '.$examen->examen_id,'0','');
            /*Fin de registro de auditoria */
           
            return redirect('examen')->with('success','Datos eliminados exitosamente');
        
    }

    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $examen=Examen::Examen($id)->first();
            $tipoExamenes=Tipo_Examen::TipoExamenes()->get();
            $productos=Producto::Productoslaboratorio()->get();
            if($examen){
                return view('admin.citasMedicas.examen.eliminar',['examen'=>$examen,'producto'=>$productos,'tipoExamenes'=>$tipoExamenes,'PE'=>Punto_Emision::puntos()->get(), 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function buscarByExamen(Request $request){
        return Producto::BuscarProductoslaboratorio($request->get('buscar'))->get();
    }
    public function facturarOrdenGuardar(Request $request){
        try{
            DB::beginTransaction();
            $orden = Orden_Examen::findOrFail($request->get('orden_id'));
        
            $cantidad = $request->get('Dcantidad');
            $isProducto = $request->get('DprodcutoID');
            $nombre = $request->get('Dnombre');
            $pcodigo = $request->get('Dcodigo');
            $pu = $request->get('DCopago');
            $total = $request->get('DCopago');

                  
           
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
                return redirect('ordenesExamen')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
            } 
            $puntoEmision = Punto_Emision::PuntoSucursalUser($request->get('idSucursal'),Auth::user()->user_id)->first();
            $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'Factura')->first();
            $secuencial=1;
            if ($rangoDocumento) {
                $secuencialAux=Factura_Venta::secuencial($rangoDocumento->rango_id)->max('factura_secuencial');
                if ($secuencialAux) {
                    $secuencial=$secuencialAux+1;
                }
            }
            $general = new generalController();
            $docElectronico = new facturacionElectronicaController();
            $factura = new Factura_Venta();
            $factura->factura_numero = $rangoDocumento->puntoEmision->sucursal->sucursal_codigo.$rangoDocumento->puntoEmision->punto_serie.substr(str_repeat(0, 9).$secuencial, - 9);
            $factura->factura_serie = $rangoDocumento->puntoEmision->sucursal->sucursal_codigo.$rangoDocumento->puntoEmision->punto_serie;
            $factura->factura_secuencial = $secuencial;
            $factura->rango_id = $rangoDocumento->rango_id;

            $factura->factura_fecha = $request->get('factura_fecha');
            $factura->factura_lugar = $request->get('factura_lugar');
            $factura->factura_tipo_pago = $request->get('factura_tipo_pago');
            $factura->factura_dias_plazo = 0;
            $factura->factura_fecha_pago = $request->get('factura_fecha');
            $factura->factura_subtotal = $request->get('idTotal');
            $factura->factura_descuento = 0;
            $factura->factura_tarifa0 = 0;
            $factura->factura_tarifa12 = 0;
            $factura->factura_iva = 0;
            $factura->factura_total = $request->get('idTotal');
            if($request->get('factura_comentario')){
                $factura->factura_comentario = $request->get('factura_comentario');
            }else{
                $factura->factura_comentario = '';
            }
            $factura->factura_porcentaje_iva = 12;
            $factura->factura_emision = $request->get('tipoDoc');
            $factura->factura_ambiente = 'PRODUCCIÓN';
            $factura->factura_autorizacion = $docElectronico->generarClaveAcceso($factura->factura_numero,$request->get('factura_fecha'),"01");
            $factura->factura_estado = '1';
            $factura->bodega_id = $request->get('bodega_id');
            $factura->cliente_id = $request->get('clienteID');
            $factura->forma_pago_id = $request->get('forma_pago_id');
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
        
                $factura->cuentaCobrar()->associate($cxc);
        
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
        
                if($banderaP){
         
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
      
                    $factura->diarioCosto()->associate($diarioC);
                }
                if($cxc->cuenta_estado == '2'){
       
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
        
                }
        
                $detalleDiario = new Detalle_Diario();
                $detalleDiario->detalle_debe = $request->get('idTotal');
                $detalleDiario->detalle_haber = 0.00 ;
                $detalleDiario->detalle_tipo_documento = 'FACTURA';
                $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                $detalleDiario->detalle_conciliacion = '0';
                $detalleDiario->detalle_estado = '1';
                if($request->get('factura_tipo_pago') == 'CONTADO'){
                    $detalleDiario->cliente_id = $request->get('clienteID');
                    $detalleDiario->detalle_comentario = 'P/R CUENTA POR COBRAR DE CLIENTE';
                    $parametrizacionContable=Parametrizacion_Contable::ParametrizacionByNombre($diario->sucursal_id,'CUENTA POR COBRAR')->first();
                    if($parametrizacionContable->parametrizacion_cuenta_general == '1'){
                        $detalleDiario->cuenta_id = $parametrizacionContable->cuenta_id;
                    }else{
                        $parametrizacionContable = Cliente::findOrFail($request->get('clienteID'));
                        $detalleDiario->cuenta_id = $parametrizacionContable->cliente_cuenta_cobrar;
                    }
                }else{
                    $detalleDiario->detalle_comentario = 'P/R PAGO EN EFECTIVO';
                    $Caja=Caja::findOrFail($request->get('caja_id'));
                    $detalleDiario->cuenta_id = $Caja->cuenta_id;
                }
                $diario->detalles()->save($detalleDiario);
                $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$detalleDiario->cuenta->cuenta_numero.' en el debe por un valor de -> '.$request->get('idTotal'));
               
     
                
            $factura->diario()->associate($diario);
            $factura->save();
            $general->registrarAuditoria('Registro de factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de factura de venta numero -> '.$factura->factura_numero.' con cliente -> '.$request->get('buscarCliente').' con un total de -> '.$request->get('idTotal').' con clave de acceso -> '.$factura->factura_autorizacion.' y con codigo de diario -> '.$diario->diario_codigo);
       
            for ($i = 1; $i < count($cantidad); ++$i){
                $detalleFV = new Detalle_FV();
                $detalleFV->detalle_cantidad = $cantidad[$i];
                $detalleFV->detalle_precio_unitario = floatval($pu[$i]);
                $detalleFV->detalle_descuento = 0;
                $detalleFV->detalle_iva = 0;
                $detalleFV->detalle_total = floatval($total[$i]);
                $detalleFV->detalle_descripcion = $nombre[$i];
                $detalleFV->detalle_estado = '1';
                $detalleFV->producto_id = $isProducto[$i];
                $factura->detalles()->save($detalleFV);
                $general->registrarAuditoria('Registro de detalle de factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de detalle de factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$nombre[$i].' con la cantidad de -> '.$cantidad[$i].' a un precio unitario de -> '.$pu[$i]);
                
                $movimientoProducto = new Movimiento_Producto();
                $movimientoProducto->movimiento_fecha=$request->get('factura_fecha');
                $movimientoProducto->movimiento_cantidad=1;
                $movimientoProducto->movimiento_precio=floatval($total[$i]);
                $movimientoProducto->movimiento_iva=0;
                $movimientoProducto->movimiento_total=floatval($total[$i]);
                $movimientoProducto->movimiento_stock_actual=0;
                $movimientoProducto->movimiento_costo_promedio=0;
                $movimientoProducto->movimiento_documento='FACTURA DE VENTA';
                $movimientoProducto->movimiento_motivo='VENTA';
                $movimientoProducto->movimiento_tipo='SALIDA';
                $movimientoProducto->movimiento_descripcion='FACTURA DE VENTA No. '.$factura->factura_numero;
                $movimientoProducto->movimiento_estado='1';
                $movimientoProducto->producto_id= $isProducto[$i];
                $movimientoProducto->bodega_id=$factura->bodega_id;
                $movimientoProducto->empresa_id=Auth::user()->empresa_id;
                $movimientoProducto->save();
                $general->registrarAuditoria('Registro de movimiento de producto por factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de movimiento de producto por factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$nombre[$i].' con la cantidad de -> 1 con un stock actual de -> '.$movimientoProducto->movimiento_stock_actual);
                /*********************************************************************/
            $detalleFV->movimiento()->associate($movimientoProducto);
            $factura->detalles()->save($detalleFV);
            $general->registrarAuditoria('Registro de detalle de factura de venta numero -> '.$factura->factura_numero,$factura->factura_numero,'Registro de detalle de factura de venta numero -> '.$factura->factura_numero.' producto de nombre -> '.$nombre[$i].' con la cantidad de -> 1 a un precio unitario de -> '.floatval($total[$i]));
                
                $producto = Producto::findOrFail($isProducto[$i]);
                $detalleDiario = new Detalle_Diario();
                $detalleDiario->detalle_debe = 0.00;
                $detalleDiario->detalle_haber =floatval($total[$i]);
                $detalleDiario->detalle_comentario = 'P/R VENTA DE PRODUCTO '.$producto->producto_codigo;
                $detalleDiario->detalle_tipo_documento = 'FACTURA';
                $detalleDiario->detalle_numero_documento = $diario->diario_numero_documento;
                $detalleDiario->detalle_conciliacion = '0';
                $detalleDiario->detalle_estado = '1';
                $detalleDiario->movimientoProducto()->associate($movimientoProducto);
                $detalleDiario->cuenta_id = $producto->producto_cuenta_venta;
                $diario->detalles()->save($detalleDiario);
                $general->registrarAuditoria('Registro de detalle de diario con codigo -> '.$diario->diario_codigo,$factura->factura_numero,'Registro de detalle de diario con codigo -> '.$diario->diario_codigo.' con cuenta contable -> '.$producto->cuentaVenta->cuenta_numero.' en el haber por un valor de -> '.floatval($total[$i]));
                
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
           

        
            $puntoEmision = Punto_Emision::PuntoSucursalUser(Rango_Documento::rango($request->get('rango_id'))->first()->puntoEmision->sucursal_id,Auth::user()->user_id)->first();
            $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'Analisis de Laboratorio')->first();
            $secuencial=1;
            $analisis=new Analisis_Laboratorio();
            if($rangoDocumento){
                $secuencialAux=Analisis_Laboratorio::secuencial($rangoDocumento->rango_id)->max('analisis_secuencial');
                if($secuencialAux){$secuencial=$secuencialAux+1;  
                }
               
                $analisis->analisis_numero=$request->get('Codigo').'-'.substr(str_repeat(0, 9).$secuencial, - 9);
                $analisis->analisis_serie=$request->get('Codigo');
                $analisis->analisis_secuencial=$secuencial;
                $analisis->analisis_fecha=$request->get('factura_fecha');
                $analisis->analisis_otros=$request->get('otros');
                $analisis->analisis_observacion='';
                $analisis->analisis_estado='2';
                $analisis->sucursal_id=Rango_Documento::rango($request->get('rango_id'))->first()->puntoEmision->sucursal_id;
                $analisis->orden_id=$orden->orden_id;
                $analisis->factura_id=$factura->factura_id;
                $analisis->orden_particular_id=null;
                $analisis->save();
                for ($i = 1; $i < count($cantidad); ++$i) 
                {
                    
                    $detalleanalisis=new Detalle_Analisis();
                    $detalleanalisis->detalle_estado='1';
                    $detalleanalisis->producto_id=$isProducto[$i];
                    $analisis->detalles()->save($detalleanalisis);
                    $detalleanalisis->save();
                }

            }else{
                return redirect('inicio')->with('error','No tiene configurado, un punto de emisión o un rango de documentos para emitir facturas de venta, configueros y vuelva a intentar');
            }
            $orden = Orden_Examen::findOrFail($request->get('orden_id'));
            $ordenes = Orden_Examen::Ordenanalisis($request->get('orden_id'))->get();
           
            $orden->orden_estado = '3';
            $orden->update();
            $tipo= Orden_Examen::Ordenanalisis($request->get('orden_id'))->select('tipo_examen.tipo_id','tipo_examen.tipo_nombre')->distinct()->get();
            $etiquetas= Orden_Examen::Ordenetiquetas($request->get('orden_id'))->select('tipo_recipiente.tipo_recipiente_id','tipo_recipiente.tipo_nombre')->distinct()->get();

            $empresa =  Empresa::empresa()->first();
            $view =  \View::make('admin.formatosPDF.ordendeexamen', ['etiquetas'=>$etiquetas,'analisis'=>$analisis,'ordenes'=>$ordenes,'tipo'=>$tipo,'orden'=>$orden,'empresa'=>$empresa]);
            $ruta = public_path().'/ordenesExamenes/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d',$analisis->analisis_fecha)->format('d-m-Y');
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $nombreArchivo = $analisis->analisis_numero;
            PDF::loadHTML($view)->save($ruta.'/'.$nombreArchivo.'.pdf');

            //return redirect('ordenesExamen')->with('success','Analisis Preatendido exitosamente')->with('pdf','ordenesExamenes/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $orden->expediente->ordenatencion->orden_fecha)->format('d-m-Y').'/'.$nombreArchivo.'.pdf');
            DB::commit();
            if($facturaAux->factura_xml_estado == 'AUTORIZADO'){
                return redirect('ordenesExamen')->with('success','Factura y analisis registrada y autorizada exitosamente')->with('pdf','documentosElectronicos/'.Empresa::Empresa()->first()->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $request->get('factura_fecha'))->format('d-m-Y').'/'.$factura->factura_xml_nombre.'.pdf')->with('pdf2','ordenesExamenes/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d', $analisis->analisis_fecha)->format('d-m-Y').'/'.$nombreArchivo.'.pdf');
            }else{
                return redirect('ordenesExamen')->with('success','Factura y analisis registrada exitosamente')->with('error2','ERROR --> '.$facturaAux->factura_xml_estado.' : '.$facturaAux->factura_xml_mensaje)->with('pdf2','ordenesExamenes/'.$empresa->empresa_ruc.'/'.DateTime::createFromFormat('Y-m-d',$analisis->analisis_fecha)->format('d-m-Y').'/'.$nombreArchivo.'.pdf');
            }
            
        }catch(\Exception $ex){
            DB::rollBack();  
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
       

    
    }
    public function buscarByanalisis($id){
        return Examen::BuscarProductoslaboratorio($id)->get();
    }
}
