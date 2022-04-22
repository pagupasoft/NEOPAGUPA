<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Cheque;
use App\Models\Cuenta_Bancaria;
use App\Models\Deposito;
use App\Models\Detalle_Diario;
use App\Models\Egreso_Banco;
use App\Models\Empresa;
use App\Models\Ingreso_Banco;
use App\Models\Nota_Credito_banco;
use App\Models\Nota_Debito_banco;
use App\Models\Punto_Emision;
use App\Models\Transferencia;
use App\Models\User;
use App\NEOPAGUPA\ViewExcel;
use PDF;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use TypeError;

class conciliacionBancariaController extends Controller
{
    public function nuevo()
    {
        try{     
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.bancos.conciliacionBancaria.index',['bancos'=>Banco::Bancos()->get(),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
           
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }
    }
    public function consultar(Request $request)
    {
        if (isset($_POST['buscar']) or isset($_POST['excel']) or isset($_POST['pdf'])){
            return $this->procesar($request);
        }
        if (isset($_POST['guardar'])){
            return $this->guardar($request);
        }
    }
    private function procesar(Request $request){
        //try{ 
            $datos =  $this->consulta($request);
            if (isset($_POST['buscar'])){
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
                $cuentaBancaria = Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first();  
                return view('admin.bancos.conciliacionBancaria.index',
                ['bancoC'=>$cuentaBancaria->banco,
                'saldoAnteriorContable'=>$datos[0],
                'saldoContableActual'=>$datos[1],
                'saldoEstadoCuenta'=>$datos[2],
                'chequeGiradoNoCobrado'=>$datos[3],
                'depositosConciliados'=>$datos[4],
                'depositosNoConciliados'=>$datos[5],
                'depositosConciliadosOtros'=>$datos[6],
                'ndConciliado'=>$datos[7],
                'ndNoConciliado'=>$datos[8],
                'ncConciliado'=>$datos[9],
                'ncNoConciliado'=>$datos[10],
                'ncConciliadoOtros'=>$datos[11],
                'ndConciliadoOtros'=>$datos[12],
                'chequesConciliados'=>$datos[13],
                'chequesNoConciliados'=>$datos[14],
                'chequesConciliadosOtros'=>$datos[15],
                'transferenciasEgresosConciliadas'=>$datos[16],
                'transferenciasEgresosNoConciliadas'=>$datos[17],
                'transferenciasEgresosConciliadasOtros'=>$datos[18],
                'transferenciaIngresosConciliados'=>$datos[19],
                'transferenciaIngresosNoConciliados'=>$datos[20],
                'transferenciaIngresosConciliadosOtros' =>$datos[21],
                'cuentaBancaria'=>$cuentaBancaria,
                'conciliacionBancariaMatriz'=>$datos[22],
                'otrasconciliacionesBancariaMatriz'=>$datos[23],
                'fechaI'=>$request->get('idDesde'),
                'fechaF'=>$request->get('idHasta'),           
                'bancos'=>Banco::Bancos()->get(),
                'PE'=>Punto_Emision::puntos()->get(),
                'gruposPermiso'=>$gruposPermiso, 
                'permisosAdmin'=>$permisosAdmin]);  
            }
            if (isset($_POST['excel'])){
                $datos[24] = $request->get('idDesde');
                $datos[25] = $request->get('idHasta');
                return Excel::download(new ViewExcel('admin.formatosExcel.conciliacionBancaria',$datos), 'NEOPAGUPA  Sistema Contable.xls');
            }
            if (isset($_POST['pdf'])){
                $cuentaBancaria = Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first();
                $datos[24] = date("d/m/Y", strtotime($request->get('idDesde')));
                $datos[25] = date("d/m/Y", strtotime($request->get('idHasta')));
                $empresa =  Empresa::empresa()->first();
                Auth::user()->user_nombre;

                $ruta = public_path().'/PDF/'.$empresa->empresa_ruc;
                if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
                }
                $view =  \View::make('admin.formatosPDF.conciliacionBancariapdf',['user'=>Auth::user()->user_nombre,'empresa'=>$empresa,'datos'=>$datos,'banco'=>$cuentaBancaria->banco->bancoLista->banco_lista_nombre,'cuentaBancariaB'=>$cuentaBancaria->cuenta_bancaria_numero]);
                $nombreArchivo = 'REPORTE BANCARIO '.'-'.$cuentaBancaria->banco->bancoLista->banco_lista_nombre.'-'.$cuentaBancaria->cuenta_bancaria_numero.DateTime::createFromFormat('Y-m-d', $request->get('idDesde'))->format('d-m-Y').' AL '.DateTime::createFromFormat('Y-m-d', $request->get('idHasta'))->format('d-m-Y');
                
                return PDF::loadHTML($view)->setPaper('a4', 'landscape')->save('PDF/'.$empresa->empresa_ruc.'/'.$nombreArchivo.'.pdf')->download($nombreArchivo.'.pdf');
                }
       /* }catch(\Exception $ex){
            return redirect('conciliacionBancaria')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }*/
    }
    private function guardar(Request $request){
        try{            
            DB::beginTransaction();
            $cuentaBancaria = Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first();
            $general = new generalController();
            $cierre = $general->cierre($request->get('idHasta'));          
            if($cierre){
                return redirect('conciliacionBancaria')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');

            }
            $cierre = $general->cierre($request->get('idDesde'));          
            if($cierre){
                return redirect('conciliacionBancaria')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');

            }
            $items = $request->get('idonciliacion');
            if($request->get('idonciliacion')){
                for ($i = 0; $i < count($items); ++$i) {
                    $datosItem = explode('-', $items[$i]);
                    if($datosItem[1] == 'DEPOSITO'){
                        $deposito = Deposito::deposito($datosItem[0])->first();
                        if(!!$deposito){
                            $deposito->deposito_conciliacion = false;
                            $deposito->deposito_fecha_conciliacion = null;
                            $deposito->update();
                        }
                    }  
                    if($datosItem[1] == 'TRANSFERENCIA'){
                        $transferencia = Transferencia::Transferencia($datosItem[0])->first();
                        if(!!$transferencia){
                            $transferencia->transferencia_conciliacion = false;
                            $transferencia->transferencia_fecha_conciliacion = null;
                            $transferencia->update();
                        }
                    }  
                    if($datosItem[1] == 'CHEQUE'){
                        $cheque = Cheque::cheque($datosItem[0])->first();
                        if(!!$cheque){
                            $cheque->cheque_conciliacion = false;
                            $cheque->cheque_fecha_conciliacion = null;
                            $cheque->update();
                            
                        }
                    }  
                    if($datosItem[1] == 'NOTA DEBITO BANCO'){
                        $ncb = Nota_Debito_banco::NotaCreditoBanco($datosItem[0])->first();
                        if(!!$ncb){
                            $ncb->nota_conciliacion = false;
                            $ncb->nota_fecha_conciliacion = null;
                            $ncb->update();
                        }
                    }  
                    if($datosItem[1] == 'NOTA CREDITO BANCO'){
                        $ndb = Nota_Credito_banco::NotaCreditoBanco($datosItem[0])->first();
                        if(!!$ndb){
                            $ndb->nota_conciliacion = false;
                            $ndb->nota_fecha_conciliacion = null;
                            $ndb->update();
                        }
                    }              
                }
            }
            $seleccion = $request->get('chkConciliacion');
            $fechaCons = new DateTime( $request->get('idHasta') ); 
            if($request->get('chkConciliacion')){
                for ($i = 0; $i < count($seleccion); ++$i) {
                    $datos = explode('-', $seleccion[$i]);
                    if($datos[1] == 'DEPOSITO'){
                        $deposito = Deposito::deposito($datos[0])->first();
                        if(!!$deposito){
                            $deposito->deposito_conciliacion = true;
                            $deposito->deposito_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $deposito->update();
                        }
                    }             
                    if($datos[1] == 'TRANSFERENCIA'){
                        $transferencia = Transferencia::Transferencia($datos[0])->first();
                        if(!!$transferencia){
                            $transferencia->transferencia_conciliacion = true;
                            $transferencia->transferencia_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $transferencia->update();
                        }
                    }  
                    if($datos[1] == 'CHEQUE'){
                        $cheque = Cheque::cheque($datos[0])->first();
                        if(!!$cheque){
                            $cheque->cheque_conciliacion = true;
                            $cheque->cheque_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $cheque->update();
                        }
                    }  
                    if($datos[1] == 'NOTA DEBITO BANCO'){
                        $ncb = Nota_Debito_banco::NotaCreditoBanco($datos[0])->first();
                        if(!!$ncb){
                            $ncb->nota_conciliacion = true;
                            $ncb->nota_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $ncb->update();
                        }
                    }  
                    if($datos[1] == 'NOTA CREDITO BANCO'){
                        $ndb = Nota_Credito_banco::NotaCreditoBanco($datos[0])->first();
                        if(!!$ndb){
                            $ndb->nota_conciliacion = true;
                            $ndb->nota_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $ndb->update();
                        }
                    }    
                }
            }
            $items = $request->get('idonciliacionOtros');
            if($request->get('idonciliacionOtros')){
                for ($i = 0; $i < count($items); ++$i) {
                    $datosItem = explode('-', $items[$i]);
                    if($datosItem[1] == 'DEPOSITO'){
                        $deposito = Deposito::deposito($datosItem[0])->first();
                        if(!!$deposito){
                            $deposito->deposito_conciliacion = false;
                            $deposito->deposito_fecha_conciliacion = null;
                            $deposito->update();
                        }
                    }  
                    if($datosItem[1] == 'TRANSFERENCIA'){
                        $transferencia = Transferencia::Transferencia($datosItem[0])->first();
                        if(!!$transferencia){
                            $transferencia->transferencia_conciliacion = false;
                            $transferencia->transferencia_fecha_conciliacion = null;
                            $transferencia->update();
                        }
                    }  
                    if($datosItem[1] == 'CHEQUE'){
                        $cheque = Cheque::cheque($datosItem[0])->first();
                        if(!!$cheque){
                            $cheque->cheque_conciliacion = false;
                            $cheque->cheque_fecha_conciliacion = null;
                            $cheque->update();
                        }
                    }  
                    if($datosItem[1] == 'NOTA DEBITO BANCO'){
                        $ncb = Nota_Debito_banco::NotaCreditoBanco($datosItem[0])->first();
                        if(!!$ncb){
                            $ncb->nota_conciliacion = false;
                            $ncb->nota_fecha_conciliacion = null;
                            $ncb->update();
                        }
                    }  
                    if($datosItem[1] == 'NOTA CREDITO BANCO'){
                        $ndb = Nota_Credito_banco::NotaCreditoBanco($datosItem[0])->first();
                        if(!!$ndb){
                            $ndb->nota_conciliacion = false;
                            $ndb->nota_fecha_conciliacion = null;
                            $ndb->update();
                        }
                    }              
                }
            }
            $seleccion = $request->get('chkConciliacionOtros');
            $fechaCons = new DateTime( $request->get('idHasta') ); 
            if($request->get('chkConciliacionOtros')){
                for ($i = 0; $i < count($seleccion); ++$i) {
                    $datos = explode('-', $seleccion[$i]);
                    if($datos[1] == 'DEPOSITO'){
                        $deposito = Deposito::deposito($datos[0])->first();
                        if(!!$deposito){
                            $deposito->deposito_conciliacion = true;
                            $deposito->deposito_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $deposito->update();
                        }
                    }             
                    if($datos[1] == 'TRANSFERENCIA'){
                        $transferencia = Transferencia::Transferencia($datos[0])->first();
                        if(!!$transferencia){
                            $transferencia->transferencia_conciliacion = true;
                            $transferencia->transferencia_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $transferencia->update();
                        }
                    }  
                    if($datos[1] == 'CHEQUE'){
                        $cheque = Cheque::cheque($datos[0])->first();
                        if(!!$cheque){
                            $cheque->cheque_conciliacion = true;
                            $cheque->cheque_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $cheque->update();
                        }
                    }  
                    if($datos[1] == 'NOTA DEBITO BANCO'){
                        $ncb = Nota_Debito_banco::NotaCreditoBanco($datos[0])->first();
                        if(!!$ncb){
                            $ncb->nota_conciliacion = true;
                            $ncb->nota_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $ncb->update();
                        }
                    }  
                    if($datos[1] == 'NOTA CREDITO BANCO'){
                        $ndb = Nota_Credito_banco::NotaCreditoBanco($datos[0])->first();
                        if(!!$ndb){
                            $ndb->nota_conciliacion = true;
                            $ndb->nota_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                            $ndb->update();
                        }
                    }    
                }
            }
            /*foreach($movimientos as $movimiento){
                $fechaCons = new DateTime( $request->get('idHasta') ); 
                $movimientoAux = $movimiento;
                if ($request->get('chk-'.$movimiento->detalle_id) == "on"){
                    $movimientoAux->detalle_conciliacion = 1;
                    $movimientoAux->detalle_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                    $movimientoAux->update();
                }else{
                    $movimientoAux->detalle_conciliacion = 0;
                    $movimientoAux->detalle_fecha_conciliacion = null;
                    $movimientoAux->update();
                }
            }
            foreach($movimientosOtros as $movimiento){
                $fechaCons = new DateTime( $request->get('idHasta') ); 
                $movimientoAux = $movimiento;
                if ($request->get('chk-'.$movimiento->detalle_id) == "on"){
                    $movimientoAux->detalle_conciliacion = 1;
                    $movimientoAux->detalle_fecha_conciliacion = $fechaCons->format( 'Y-m-t' );
                    $movimientoAux->update();
                }else{
                    $movimientoAux->detalle_conciliacion = 0;
                    $movimientoAux->detalle_fecha_conciliacion = null;
                    $movimientoAux->update();
                }
            }*/
            $general->registrarAuditoria('Registro de conciliacion con fecha desde -> '.$request->get('idDesde').' hasta -> '.$request->get('idHasta'),0,'Registro de conciliacion con fecha desde -> '.$request->get('idDesde').' hasta -> '.$request->get('idHasta').' de banco -> '.$cuentaBancaria->banco->bancoLista->banco_lista_nombre.' con cuenta bancaria -> '.$cuentaBancaria->cuenta_bancaria_numero);
            DB::commit();
            return redirect('conciliacionBancaria')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('conciliacionBancaria')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }catch(TypeError $ex){
            DB::rollBack();
            return redirect('conciliacionBancaria')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }  
    /*private function consulta(Request $request){
        try{     
            $resumen = array();
            $cuentaBancaria = Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first();
            $movimientos = Detalle_Diario::MovimientosByCuenta($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'))->get();
            $movimientosOtros = Detalle_Diario::MovimientosByCuentaOtros($cuentaBancaria->cuenta_id,$request->get('idDesde'))->get();
            $saldoAntCont = Detalle_Diario::SaldoAnteriorCuenta($cuentaBancaria->cuenta_id,$request->get('idDesde'))->sum('detalle_debe') - Detalle_Diario::SaldoAnteriorCuenta($cuentaBancaria->cuenta_id,$request->get('idDesde'))->sum('detalle_haber');
            $saldoActCont = Detalle_Diario::SaldoActualCuenta($cuentaBancaria->cuenta_id,$request->get('idHasta'))->sum('detalle_debe') - Detalle_Diario::SaldoActualCuenta($cuentaBancaria->cuenta_id,$request->get('idHasta'))->sum('detalle_haber');
            $saldoAntCuenta = Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'DEPOSITO')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_debe') + 
                            Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'NOTA DE CRÉDITO')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_debe') -
                            Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'NOTA DE DÉBITO')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_haber') -
                            Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'CHEQUE')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_haber') -
                            Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'TRANSFERENCIA')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_haber') +
                            Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'TRANSFERENCIA')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_debe');
            $chequeGiradoNoCobrado = Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'CHEQUE')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_haber') + Detalle_Diario::MovimientosByTipoSaldo($cuentaBancaria->cuenta_id,$request->get('idDesde'),'CHEQUE')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_haber');
            array_push($resumen, 
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'DEPOSITO')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_debe'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'DEPOSITO')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_debe'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'NOTA DE CRÉDITO')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_debe'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'NOTA DE CRÉDITO')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_debe'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'NOTA DE DÉBITO')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_haber'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'NOTA DE DÉBITO')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_haber'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'CHEQUE')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_haber'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'CHEQUE')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_haber'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'TRANSFERENCIA')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_haber'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'TRANSFERENCIA')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_haber'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'TRANSFERENCIA')->where('detalle_diario.detalle_conciliacion','=','1')->sum('detalle_debe'),
            Detalle_Diario::MovimientosByTipo($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'),'TRANSFERENCIA')->where('detalle_diario.detalle_conciliacion','=','0')->sum('detalle_debe'));
            $saldoActCuenta = $saldoAntCont + $resumen[0] + $resumen[2] - $resumen[4] - $resumen[6] - $resumen[8] + $resumen[10] + $chequeGiradoNoCobrado;

            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.bancos.conciliacionBancaria.index',['resumen'=>$resumen,'saldoActCuenta'=>$saldoActCuenta,'saldoActCont'=>$saldoActCont,'chequeGiradoNoCobrado'=>$chequeGiradoNoCobrado,'saldoAntCuenta'=>$saldoAntCuenta,'saldoAntCont'=>$saldoAntCont,'bancoC'=>$cuentaBancaria->banco,'cuentaBancaria'=>$cuentaBancaria,'fechaI'=>$request->get('idDesde'),'fechaF'=>$request->get('idHasta'),'movimientosOtros'=>$movimientosOtros,'movimientos'=>$movimientos,'bancos'=>Banco::Bancos()->get(),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
           
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }
    }*/
    private function consulta(Request $request){
        try{ 
            $datos = [];
            $fecha_actual = strtotime(date($request->get('idHasta')));
            /*RESUMEN DE TOTALES*/
            $chequeGiradoNoCobrado= 0;
            $depositosConciliados = 0;
            $depositosNoConciliados =0;
            $ndConciliado = 0;
            $ndNoConciliado = 0;
            $ncConciliado = 0;
            $ncNoConciliado = 0;
            $chequesConciliados = 0;
            $chequesNoConciliados = 0;
            $transferenciasEgresosConciliadas =0;
            $transferenciasEgresosNoConciliadas =0;
            $transferenciaIngresosConciliados = 0;
            $transferenciaIngresosNoConciliados =0;
            $cuentaBancaria = Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first();        

            $saldoAnteriorContable = Detalle_Diario::SaldoAnteriorCuenta($cuentaBancaria->cuenta_id,$request->get('idDesde'))->sum('detalle_debe') - Detalle_Diario::SaldoAnteriorCuenta($cuentaBancaria->cuenta_id,$request->get('idDesde'))->sum('detalle_haber');
            $saldoContableActual = Detalle_Diario::SaldoActualCuenta($cuentaBancaria->cuenta_id,$request->get('idHasta'))->sum('detalle_debe') - Detalle_Diario::SaldoActualCuenta($cuentaBancaria->cuenta_id,$request->get('idHasta'))->sum('detalle_haber');

            $chequeGiradoNoCobrado = Cheque::ChequeSumaByCuenta($request->get('cuenta_id'),$request->get('idHasta'))
            ->where('cheque.cheque_conciliacion','=', false)->sum('cheque_valor');

            $depositosConciliados = Deposito::DepositosByCuenta($request->get('cuenta_id'))
            ->where('deposito.deposito_fecha','>=',$request->get('idDesde'))->where('deposito.deposito_fecha','<=',$request->get('idHasta'))
            ->where('deposito.deposito_conciliacion','=', true)->where('deposito.deposito_fecha_conciliacion','=',$request->get('idHasta'))
            ->where('deposito.deposito_tipo','<>', 'TRANSFERENCIA')
            ->sum('deposito_valor');

            $depositosNoConciliados = Deposito::DepositosByCuenta($request->get('cuenta_id'))
            ->where('deposito.deposito_fecha','>=',$request->get('idDesde'))->where('deposito.deposito_fecha','<=',$request->get('idHasta'))
            ->where('deposito.deposito_conciliacion','=', false)
            ->where('deposito.deposito_tipo','<>', 'TRANSFERENCIA')
            ->sum('deposito_valor');

            $depositosConciliadosOtros = Deposito::DepositosByCuenta($request->get('cuenta_id'))     
            ->where('deposito.deposito_tipo','<>', 'TRANSFERENCIA')      
            ->where('deposito.deposito_fecha','<',$request->get('idDesde'))
            ->where('deposito.deposito_fecha_conciliacion','=',$request->get('idHasta'))
            ->where('deposito.deposito_conciliacion','=', true)->sum('deposito_valor');

            $ndConciliado = Nota_Debito_banco::NDbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_debito_banco.nota_fecha','>=',$request->get('idDesde'))->where('nota_debito_banco.nota_fecha','<=',$request->get('idHasta'))            
            ->where('nota_debito_banco.nota_conciliacion','=', true)->where('nota_debito_banco.nota_fecha_conciliacion','=',$request->get('idHasta'))->sum('nota_debito_banco.nota_valor');

            $ndNoConciliado = Nota_Debito_banco::NDbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_debito_banco.nota_fecha','>=',$request->get('idDesde'))->where('nota_debito_banco.nota_fecha','<=',$request->get('idHasta')) 
            ->where('nota_debito_banco.nota_conciliacion','=', false)->sum('nota_debito_banco.nota_valor');

            $ndConciliadoOtros = Nota_Debito_banco::NDbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_debito_banco.nota_fecha','<',$request->get('idDesde'))
            ->where('nota_debito_banco.nota_fecha_conciliacion','=',$request->get('idHasta'))
            ->where('nota_debito_banco.nota_conciliacion','=', true)->sum('nota_debito_banco.nota_valor');

            $ncConciliado = Nota_Credito_banco::NCbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_credito_banco.nota_fecha','>=',$request->get('idDesde'))->where('nota_credito_banco.nota_fecha','<=',$request->get('idHasta'))            
            ->where('nota_credito_banco.nota_conciliacion','=', true)->where('nota_credito_banco.nota_fecha_conciliacion','=',$request->get('idHasta'))->sum('nota_credito_banco.nota_valor');

            $ncNoConciliado = Nota_Credito_banco::NCbancoByCuenta($request->get('cuenta_id'))   
            ->where('nota_credito_banco.nota_fecha','>=',$request->get('idDesde'))->where('nota_credito_banco.nota_fecha','<=',$request->get('idHasta'))
            ->where('nota_credito_banco.nota_conciliacion','=', false)->sum('nota_credito_banco.nota_valor');
            
            $ncConciliadoOtros = Nota_Credito_banco::NCbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_credito_banco.nota_fecha','<',$request->get('idDesde'))
            ->where('nota_credito_banco.nota_fecha_conciliacion','=',$request->get('idHasta'))
            ->where('nota_credito_banco.nota_conciliacion','=', true)->sum('nota_credito_banco.nota_valor');

            $chequesConciliados = Cheque::ChequeByCuenta($request->get('cuenta_id'))->where('cheque.cheque_fecha_emision','>=',$request->get('idDesde'))
            ->where('cheque.cheque_fecha_emision','<=',$request->get('idHasta'))->where('cheque.cheque_conciliacion','=', true)
            ->where('cheque.cheque_fecha_conciliacion','=',$request->get('idHasta'))->sum('cheque_valor');

            $chequesNoConciliados = Cheque::ChequeByCuenta($request->get('cuenta_id'))->where('cheque.cheque_fecha_emision','>=',$request->get('idDesde'))
            ->where('cheque.cheque_fecha_emision','<=',$request->get('idHasta'))->where('cheque.cheque_conciliacion','=', false)->sum('cheque_valor');

            /*ejemplo*/$chequesConciliadosOtros = Cheque::ChequeByCuenta($request->get('cuenta_id'))
            ->where('cheque.cheque_fecha_emision','<',$request->get('idDesde'))
            ->where('cheque.cheque_fecha_conciliacion','=',$request->get('idHasta'))
            ->where('cheque.cheque_conciliacion','=', true)->sum('cheque_valor');

            $transferenciasEgresosConciliadas = Transferencia::TransferenciaByCuenta($request->get('cuenta_id'))
            ->where('transferencia.transferencia_fecha','>=',$request->get('idDesde'))->where('transferencia.transferencia_fecha','<=',$request->get('idHasta'))
            ->where('transferencia.transferencia_conciliacion','=', true)->where('transferencia.transferencia_fecha_conciliacion','=',$request->get('idHasta'))->sum('transferencia_valor');

            $transferenciasEgresosNoConciliadas = Transferencia::TransferenciaByCuenta($request->get('cuenta_id'))
            ->where('transferencia.transferencia_fecha','>=',$request->get('idDesde'))->where('transferencia.transferencia_fecha','<=',$request->get('idHasta'))
            ->where('transferencia.transferencia_conciliacion','=', false)->sum('transferencia_valor');

            /*ejemplo*/$transferenciasEgresosConciliadasOtros = Transferencia::TransferenciaByCuenta($request->get('cuenta_id'))
            ->where('transferencia.transferencia_fecha','<',$request->get('idDesde'))
            ->where('transferencia.transferencia_fecha_conciliacion','=',$request->get('idHasta'))
            ->where('transferencia.transferencia_conciliacion','=', true)->sum('transferencia_valor');

            $transferenciaIngresosConciliados = Deposito::DepositosByCuenta($request->get('cuenta_id'))
            ->where('deposito.deposito_fecha','>=',$request->get('idDesde'))->where('deposito.deposito_fecha','<=',$request->get('idHasta'))
            ->where('deposito.deposito_tipo','=', 'TRANSFERENCIA')            
            ->where('deposito.deposito_conciliacion','=', true)->where('deposito.deposito_fecha_conciliacion','=',$request->get('idHasta'))->sum('deposito_valor');

            $transferenciaIngresosNoConciliados = Deposito::DepositosByCuenta($request->get('cuenta_id'))
            ->where('deposito.deposito_fecha','>=',$request->get('idDesde'))->where('deposito.deposito_fecha','<=',$request->get('idHasta'))
            ->where('deposito.deposito_tipo','=', 'TRANSFERENCIA')            
            ->where('deposito.deposito_conciliacion','=', false)->sum('deposito_valor');

             /*ejemplo*/$transferenciaIngresosConciliadosOtros = Deposito::DepositosByCuenta($request->get('cuenta_id'))
             ->where('deposito.deposito_tipo','=', 'TRANSFERENCIA')      
             ->where('deposito.deposito_fecha','<',$request->get('idDesde'))   
             ->where('deposito.deposito_fecha_conciliacion','=',$request->get('idHasta'))               
            ->where('deposito.deposito_conciliacion','=', true)->sum('deposito_valor');
            //SALDO DEL ESTADO DE CUENTA BANCO
            //$saldoEstadoCuenta = floatval($saldoContableActual) + (floatval($ndNoConciliado) + floatval($transferenciasEgresosNoConciliadas) + floatval($chequeGiradoNoCobrado)) - (floatval($depositosNoConciliados) + floatval($ncNoConciliado)+ floatval($transferenciaIngresosNoConciliados));
            //otro forma de calcular el estado de cuenta banco con el saldo inicial del mismo.
            $saldoInicialBanco = $cuentaBancaria->cuenta_bancaria_saldo_inicial;            
            $sumatoriaDepositosTransferenciasIngresos = Deposito::DepositoSumatoria($cuentaBancaria->cuenta_bancaria_id,$request->get('idHasta'))->first();
            if(isset($sumatoriaDepositosTransferenciasIngresos->sumadeposito)){
                $sumDepositosTransferencias = $sumatoriaDepositosTransferenciasIngresos->sumadeposito; 
            }else{                          
                $sumDepositosTransferencias = 0;
            }
            $sumatoriaNotasCreditos = Nota_Credito_banco::NotaCreditoSumatoria($cuentaBancaria->cuenta_bancaria_id,$request->get('idHasta'))->first();
            if(isset($sumatoriaNotasCreditos->sumanotacredito)){
                $sumNotasCreditos = $sumatoriaNotasCreditos->sumanotacredito; 
            }else{                          
                $sumNotasCreditos = 0;
            }
            $sumatoriaNotasDebito = Nota_Debito_banco::NotaDebitoSumatoria($cuentaBancaria->cuenta_bancaria_id, $request->get('idHasta'))->first();
            if(isset($sumatoriaNotasDebito->sumanotadebito)){
                $sumNotasDebito = $sumatoriaNotasDebito->sumanotadebito; 
            }else{                          
                $sumNotasDebito = 0;
            }
            $sumatoriaCheques = Cheque::ChequeSumatoria($cuentaBancaria->cuenta_bancaria_id,$request->get('idHasta'))->first();
            if(isset($sumatoriaCheques->sumacheque)){
                $sumCheque = $sumatoriaCheques->sumacheque; 
            }else{                          
                $sumCheque = 0;
            }
            $sumatoriaTransferenciasEgresos = Transferencia::TransferenciasSumtorias($cuentaBancaria->cuenta_bancaria_id,$request->get('idHasta'))->first();
            if(isset($sumatoriaTransferenciasEgresos->sumatransferencia)){
                $sumTransferencia = $sumatoriaTransferenciasEgresos->sumatransferencia; 
            }else{                          
                $sumTransferencia = 0;
            }

            $saldoEstadoCuenta = floatval($saldoInicialBanco) + floatval($sumDepositosTransferencias) + floatval($sumNotasCreditos) - floatval($sumNotasDebito) - floatval($sumCheque) - floatval($sumTransferencia);
            //$saldoEstadoCuenta = floatval($sumTransferencia);

            /*------------FIN---------------------------------------------------*/
            
            $conciliacionBancariaMatriz = [];
            $otrasconciliacionesBancariaMatriz = [];
            //POR CONCILIAR FECHA CONSULTADA            
            $depositos = Deposito::DepositosByCuenta($request->get('cuenta_id'))
            ->where('deposito.deposito_fecha','>=',$request->get('idDesde'))            
            ->where('deposito.deposito_fecha','<=',$request->get('idHasta'))
            ->where('deposito.deposito_tipo','<>', 'TRANSFERENCIA')->get();

            //TRANSFERENCIAS INGRESOS
            $transIngresos = Deposito::DepositosByCuenta($request->get('cuenta_id'))
            ->where('deposito.deposito_fecha','>=',$request->get('idDesde'))            
            ->where('deposito.deposito_fecha','<=',$request->get('idHasta'))
            ->where('deposito.deposito_tipo','=', 'TRANSFERENCIA')->get();

            $transferencias = Transferencia::TransferenciaByCuenta($request->get('cuenta_id'))
            ->where('transferencia.transferencia_fecha','>=',$request->get('idDesde'))->where('transferencia.transferencia_fecha','<=',$request->get('idHasta'))->get();

            $cheques = Cheque::ChequeByCuenta($request->get('cuenta_id'))
            ->where('cheque.cheque_fecha_emision','>=',$request->get('idDesde'))->where('cheque.cheque_fecha_emision','<=',$request->get('idHasta'))->get();

            $ndBancos = Nota_Debito_banco::NDbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_debito_banco.nota_fecha','>=',$request->get('idDesde'))->where('nota_debito_banco.nota_fecha','<=',$request->get('idHasta'))->get();

            $ncBancos = Nota_Credito_banco::NCbancoByCuenta($request->get('cuenta_id'))
            ->where('nota_credito_banco.nota_fecha','>=',$request->get('idDesde'))->where('nota_credito_banco.nota_fecha','<=',$request->get('idHasta'))->get();

            $count = 0;  
            foreach($cheques as $cheque){                    
                //CHEQUES
                $conciliacionBancariaMatriz[$count]['tabla'] = 'CHEQUE';
                $conciliacionBancariaMatriz[$count]['id'] = $cheque->cheque_id;
                $conciliacionBancariaMatriz[$count]['fecha'] = date("d/m/Y", strtotime($cheque->cheque_fecha_emision));
                $conciliacionBancariaMatriz[$count]['tipo'] = 'CHEQUE';
                $conciliacionBancariaMatriz[$count]['numero'] = $cheque->cheque_numero;
                $conciliacionBancariaMatriz[$count]['debito'] = $cheque->cheque_valor;
                $conciliacionBancariaMatriz[$count]['credito'] =0;
                $diarios = [];
                $countDiario = 0 ;
                foreach($cheque->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $conciliacionBancariaMatriz[$count]['diario'] = $diarios;
                $conciliacionBancariaMatriz[$count]['Beneficiario'] = $cheque->cheque_beneficiario;
                $conciliacionBancariaMatriz[$count]['referencia'] = $cheque->cheque_descripcion;
                //$conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $cheque->cheque_fecha_conciliacion;
                $conciliacionBancariaMatriz[$count]['conciliacion'] = $cheque->cheque_conciliacion;
                $conciliacionBancariaMatriz[$count]['bloqueo'] = false;
                if($cheque->cheque_conciliacion){
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = date("d/m/Y", strtotime($cheque->cheque_fecha_conciliacion));
                    $fecha_conciliacion = strtotime(date($cheque->cheque_fecha_conciliacion));
                    if($fecha_conciliacion > $fecha_actual){
                        $conciliacionBancariaMatriz[$count]['bloqueo'] = true;
                    }
                }else{
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $cheque->cheque_fecha_conciliacion;
                }  
                $count = $count + 1;
            }          
            foreach($depositos as $deposito){                    
                //DEPOSITOS
                $conciliacionBancariaMatriz[$count]['tabla'] = 'DEPOSITO';
                $conciliacionBancariaMatriz[$count]['id'] = $deposito->deposito_id;
                $conciliacionBancariaMatriz[$count]['fecha'] = date("d/m/Y", strtotime($deposito->deposito_fecha));
                $conciliacionBancariaMatriz[$count]['tipo'] = $deposito->deposito_tipo;
                $conciliacionBancariaMatriz[$count]['numero'] = $deposito->deposito_numero;
                $conciliacionBancariaMatriz[$count]['debito'] = 0;
                $conciliacionBancariaMatriz[$count]['credito'] = $deposito->deposito_valor;
                $diarios = [];
                $countDiario = 0 ;
                foreach($deposito->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $conciliacionBancariaMatriz[$count]['diario'] = $diarios;
                $conciliacionBancariaMatriz[$count]['Beneficiario'] = '';
                $conciliacionBancariaMatriz[$count]['referencia'] = $deposito->deposito_descripcion;
                //$conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $deposito->deposito_fecha_conciliacion;
                $conciliacionBancariaMatriz[$count]['conciliacion'] = $deposito->deposito_conciliacion;
                $conciliacionBancariaMatriz[$count]['bloqueo'] = false;
                if($deposito->deposito_conciliacion){
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = date("d/m/Y", strtotime($deposito->deposito_fecha_conciliacion));
                    $fecha_conciliacion = strtotime(date($deposito->deposito_fecha_conciliacion));
                    if($fecha_conciliacion > $fecha_actual){
                        $conciliacionBancariaMatriz[$count]['bloqueo'] = true;
                    }
                }else{
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $deposito->deposito_fecha_conciliacion;
                }
                $count = $count + 1;
            }
            foreach($transferencias as $transferencia){                    
                //TRANSFERENCIAS
                $conciliacionBancariaMatriz[$count]['tabla'] = 'TRANSFERENCIA';
                $conciliacionBancariaMatriz[$count]['id'] = $transferencia->transferencia_id;
                $conciliacionBancariaMatriz[$count]['fecha'] = date("d/m/Y", strtotime($transferencia->transferencia_fecha));
                $conciliacionBancariaMatriz[$count]['tipo'] = 'TRANSFERENCIA';
                $conciliacionBancariaMatriz[$count]['numero'] = '';
                $conciliacionBancariaMatriz[$count]['debito'] = $transferencia->transferencia_valor;
                $conciliacionBancariaMatriz[$count]['credito'] =0;
                $diarios = [];
                $countDiario = 0 ;
                foreach($transferencia->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $conciliacionBancariaMatriz[$count]['diario'] = $diarios;
                $conciliacionBancariaMatriz[$count]['Beneficiario'] = $transferencia->transferencia_beneficiario;
                $conciliacionBancariaMatriz[$count]['referencia'] = $transferencia->transferencia_descripcion;
                //$conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $transferencia->transferencia_fecha_conciliacion; 
                $conciliacionBancariaMatriz[$count]['conciliacion'] = $transferencia->transferencia_conciliacion;
                $conciliacionBancariaMatriz[$count]['bloqueo'] = false;
                if($transferencia->transferencia_conciliacion){
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = date("d/m/Y", strtotime($transferencia->transferencia_fecha_conciliacion));
                    $fecha_conciliacion = strtotime(date($transferencia->transferencia_fecha_conciliacion));
                    if($fecha_conciliacion > $fecha_actual){
                        $conciliacionBancariaMatriz[$count]['bloqueo'] = true;
                    }
                }else{
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $transferencia->transferencia_fecha_conciliacion;
                }                         
                          
                $count = $count + 1;
            } 
            foreach($transIngresos as $transIngreso){                    
                //transferencias ingresos
                $conciliacionBancariaMatriz[$count]['tabla'] = 'DEPOSITO';
                $conciliacionBancariaMatriz[$count]['id'] = $transIngreso->deposito_id;
                $conciliacionBancariaMatriz[$count]['fecha'] = date("d/m/Y", strtotime($transIngreso->deposito_fecha));
                $conciliacionBancariaMatriz[$count]['tipo'] = $transIngreso->deposito_tipo;
                $conciliacionBancariaMatriz[$count]['numero'] = $transIngreso->deposito_numero;
                $conciliacionBancariaMatriz[$count]['debito'] = 0;
                $conciliacionBancariaMatriz[$count]['credito'] = $transIngreso->deposito_valor;
                $diarios = [];
                $countDiario = 0 ;
                foreach($transIngreso->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $conciliacionBancariaMatriz[$count]['diario'] = $diarios;
                $conciliacionBancariaMatriz[$count]['Beneficiario'] = '';
                $conciliacionBancariaMatriz[$count]['referencia'] = $transIngreso->deposito_descripcion;
                //$conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $transIngreso->deposito_fecha_conciliacion;
                $conciliacionBancariaMatriz[$count]['conciliacion'] = $transIngreso->deposito_conciliacion;
                $conciliacionBancariaMatriz[$count]['bloqueo'] = false;
                if($transIngreso->deposito_conciliacion){
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = date("d/m/Y", strtotime($transIngreso->deposito_fecha_conciliacion));
                    $fecha_conciliacion = strtotime(date($transIngreso->deposito_fecha_conciliacion));
                    if($fecha_conciliacion > $fecha_actual){
                        $conciliacionBancariaMatriz[$count]['bloqueo'] = true;
                    }
                }else{
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $transIngreso->deposito_fecha_conciliacion;
                }
                $count = $count + 1;
            }           
            foreach($ndBancos as $ndBanco){                    
                //NOTAS DE DEBITO BANCO
                $conciliacionBancariaMatriz[$count]['tabla'] = 'NOTA DEBITO BANCO';
                $conciliacionBancariaMatriz[$count]['id'] = $ndBanco->nota_id;
                $conciliacionBancariaMatriz[$count]['fecha'] = date("d/m/Y", strtotime($ndBanco->nota_fecha));
                $conciliacionBancariaMatriz[$count]['tipo'] = 'ND';
                $conciliacionBancariaMatriz[$count]['numero'] = $ndBanco->nota_numero;
                $conciliacionBancariaMatriz[$count]['debito'] = $ndBanco->nota_valor;
                $conciliacionBancariaMatriz[$count]['credito'] = 0;
                $conciliacionBancariaMatriz[$count]['diario'] = $ndBanco->diario->diario_codigo;
                $conciliacionBancariaMatriz[$count]['Beneficiario'] = $ndBanco->nota_beneficiario;
                $conciliacionBancariaMatriz[$count]['referencia'] = $ndBanco->nota_descripcion;
                //$conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $ndBanco->nota_fecha_conciliacion;
                $conciliacionBancariaMatriz[$count]['conciliacion'] = $ndBanco->nota_conciliacion;
                $conciliacionBancariaMatriz[$count]['bloqueo'] = false;
                if($ndBanco->nota_conciliacion){
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = date("d/m/Y", strtotime($ndBanco->nota_fecha_conciliacion));
                    $fecha_conciliacion = strtotime(date($ndBanco->nota_fecha_conciliacion));
                    if($fecha_conciliacion > $fecha_actual){
                        $conciliacionBancariaMatriz[$count]['bloqueo'] = true;
                    }
                }else{
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $ndBanco->nota_fecha_conciliacion;

                }                              
                $count = $count + 1;
            }
            foreach($ncBancos as $ncBanco){                    
                //NOTAS DE CREDITO BANCO
                $conciliacionBancariaMatriz[$count]['tabla'] = 'NOTA CREDITO BANCO';
                $conciliacionBancariaMatriz[$count]['id'] = $ncBanco->nota_id;
                $conciliacionBancariaMatriz[$count]['fecha'] = date("d/m/Y", strtotime($ncBanco->nota_fecha));
                $conciliacionBancariaMatriz[$count]['tipo'] = 'NC';
                $conciliacionBancariaMatriz[$count]['numero'] = $ncBanco->nota_numero;
                $conciliacionBancariaMatriz[$count]['debito'] = 0;
                $conciliacionBancariaMatriz[$count]['credito'] = $ncBanco->nota_valor;
                $conciliacionBancariaMatriz[$count]['diario'] = $ncBanco->diario->diario_codigo;
                $conciliacionBancariaMatriz[$count]['Beneficiario'] = $ncBanco->nota_beneficiario;
                $conciliacionBancariaMatriz[$count]['referencia'] = $ncBanco->nota_descripcion;
                //$conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $ncBanco->nota_fecha_conciliacion; 
                $conciliacionBancariaMatriz[$count]['conciliacion'] = $ncBanco->nota_conciliacion;
                $conciliacionBancariaMatriz[$count]['bloqueo'] = false;
                if($ncBanco->nota_conciliacion){
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = date("d/m/Y", strtotime($ncBanco->nota_fecha_conciliacion));
                    $fecha_conciliacion = strtotime(date($ncBanco->nota_fecha_conciliacion));
                    if($fecha_conciliacion > $fecha_actual){
                        $conciliacionBancariaMatriz[$count]['bloqueo'] = true;
                    }
                }else{
                    $conciliacionBancariaMatriz[$count]['fechaConsiliacion'] = $ncBanco->nota_fecha_conciliacion;
                }   
                $count = $count + 1;
            }
            //POR CONCILIAR EN OTROS MESES
            $depositosOtros = Deposito::DepositosOtrosByCuenta($request->get('cuenta_id'))->where(function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('deposito.deposito_fecha','<',$request->get('idDesde'))->where('deposito.deposito_conciliacion','=', false)->where('deposito.deposito_tipo','<>', 'TRANSFERENCIA');
                })->orwhere(function($query) use ($request){
                    $query->where('deposito.deposito_fecha','<',$request->get('idDesde'))->where('deposito.deposito_fecha_conciliacion','=',$request->get('idHasta'))
                    ->where('deposito.deposito_conciliacion','=', true);
                });
            })->get();

            $transIngresosOtros = Deposito::DepositosOtrosByCuenta($request->get('cuenta_id'))->where(function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('deposito.deposito_fecha','<',$request->get('idDesde'))->where('deposito.deposito_conciliacion','=', false)->where('deposito.deposito_tipo','=', 'TRANSFERENCIA');
                })->orwhere(function($query) use ($request){
                    $query->where('deposito.deposito_fecha','<',$request->get('idDesde'))->where('deposito.deposito_fecha_conciliacion','=',$request->get('idHasta'))
                    ->where('deposito.deposito_conciliacion','=', true);
                });
            })->get();

             $transferenciasOtros = Transferencia::TransferenciaOtrosByCuenta($request->get('cuenta_id'))->where(function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('transferencia.transferencia_fecha','<',$request->get('idDesde'))->where('transferencia.transferencia_conciliacion','=', false);
                })->orwhere(function($query) use ($request){
                    $query->where('transferencia.transferencia_fecha','<',$request->get('idDesde'))->where('transferencia.transferencia_fecha_conciliacion','=',$request->get('idHasta'))
                    ->where('transferencia.transferencia_conciliacion','=', true);
                });
            })->get();
            
             $chequesOtros = Cheque::ChequeOtrosByCuenta($request->get('cuenta_id'))->where(function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('cheque.cheque_fecha_emision','<',$request->get('idDesde'))->where('cheque.cheque_conciliacion','=', false);
                })->orwhere(function($query) use ($request){
                    $query->where('cheque.cheque_fecha_emision','<',$request->get('idDesde'))->where('cheque.cheque_fecha_conciliacion','=',$request->get('idHasta'))
                    ->where('cheque.cheque_conciliacion','=', true);
                });
            })->get();
            
             $ndBancosOtros = Nota_Debito_banco::NDbancoOtrosByCuenta($request->get('cuenta_id'))->where(function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('nota_debito_banco.nota_fecha','<',$request->get('idDesde'))->where('nota_debito_banco.nota_conciliacion','=', false);
                })->orwhere(function($query) use ($request){
                    $query->where('nota_debito_banco.nota_fecha','<',$request->get('idDesde'))->where('nota_debito_banco.nota_fecha_conciliacion','=',$request->get('idHasta'))
                    ->where('nota_debito_banco.nota_conciliacion','=', true);
                });
            })->get();

             $ncBancosOtros = Nota_Credito_banco::NCbancoOtrosByCuenta($request->get('cuenta_id'))->where(function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('nota_credito_banco.nota_fecha','<',$request->get('idDesde'))->where('nota_credito_banco.nota_conciliacion','=', false);
                })->orwhere(function($query) use ($request){
                    $query->where('nota_credito_banco.nota_fecha','<',$request->get('idDesde'))->where('nota_credito_banco.nota_fecha_conciliacion','=',$request->get('idHasta'))
                    ->where('nota_credito_banco.nota_conciliacion','=', true);
                });
            })->get();

            $count2 = 0;   
            foreach($chequesOtros as $cheque){                    
                //CHEQUES
                $otrasconciliacionesBancariaMatriz[$count2]['tabla'] = 'CHEQUE';
                $otrasconciliacionesBancariaMatriz[$count2]['id'] = $cheque->cheque_id;
                $otrasconciliacionesBancariaMatriz[$count2]['fecha'] = date("d/m/Y", strtotime($cheque->cheque_fecha_emision));
                $otrasconciliacionesBancariaMatriz[$count2]['tipo'] = 'CHEQUE';
                $otrasconciliacionesBancariaMatriz[$count2]['numero'] = $cheque->cheque_numero;
                $otrasconciliacionesBancariaMatriz[$count2]['debito'] = $cheque->cheque_valor;
                $otrasconciliacionesBancariaMatriz[$count2]['credito'] = 0;
                $diarios = [];
                $countDiario = 0 ;
                foreach($cheque->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $otrasconciliacionesBancariaMatriz[$count2]['diario'] = $diarios;
                $otrasconciliacionesBancariaMatriz[$count2]['Beneficiario'] = $cheque->cheque_beneficiario;
                $otrasconciliacionesBancariaMatriz[$count2]['referencia'] = $cheque->cheque_descripcion;
                //$otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $cheque->cheque_fecha_conciliacion;
                $otrasconciliacionesBancariaMatriz[$count2]['conciliacion'] = $cheque->cheque_conciliacion; 
                if($cheque->cheque_conciliacion){
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = date("d/m/Y", strtotime($cheque->cheque_fecha_conciliacion));                    
                }else{
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $cheque->cheque_fecha_conciliacion;
                }                          
                $count2 = $count2 + 1;
            }         
            foreach($depositosOtros as $deposito){                    
                //DEPOSITOS
                $otrasconciliacionesBancariaMatriz[$count2]['tabla'] = 'DEPOSITO';
                $otrasconciliacionesBancariaMatriz[$count2]['id'] = $deposito->deposito_id;
                $otrasconciliacionesBancariaMatriz[$count2]['fecha'] = date("d/m/Y", strtotime($deposito->deposito_fecha));
                $otrasconciliacionesBancariaMatriz[$count2]['tipo'] = $deposito->deposito_tipo;
                $otrasconciliacionesBancariaMatriz[$count2]['numero'] = $deposito->deposito_numero;
                $otrasconciliacionesBancariaMatriz[$count2]['debito'] = 0;
                $otrasconciliacionesBancariaMatriz[$count2]['credito'] = $deposito->deposito_valor;
                $diarios = [];
                $countDiario = 0 ;
                foreach($deposito->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $otrasconciliacionesBancariaMatriz[$count2]['diario'] = $diarios;
                $otrasconciliacionesBancariaMatriz[$count2]['Beneficiario'] = '';
                $otrasconciliacionesBancariaMatriz[$count2]['referencia'] = $deposito->deposito_descripcion;
                //$otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $deposito->deposito_fecha_conciliacion;
                $otrasconciliacionesBancariaMatriz[$count2]['conciliacion'] = $deposito->deposito_conciliacion;
                if($deposito->deposito_conciliacion){
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = date("d/m/Y", strtotime($deposito->deposito_fecha_conciliacion));
                }else{
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $deposito->deposito_fecha_conciliacion;
                }                                     
                $count2 = $count2 + 1;
            }
            foreach($transferenciasOtros as $transferencia){                    
                //TRANSFERENCIAS
                $otrasconciliacionesBancariaMatriz[$count2]['tabla'] = 'TRANSFERENCIA';
                $otrasconciliacionesBancariaMatriz[$count2]['id'] = $transferencia->transferencia_id;
                $otrasconciliacionesBancariaMatriz[$count2]['fecha'] = date("d/m/Y", strtotime($transferencia->transferencia_fecha)); 
                $otrasconciliacionesBancariaMatriz[$count2]['tipo'] = 'TRANSFERENCIA';
                $otrasconciliacionesBancariaMatriz[$count2]['numero'] = '';
                $otrasconciliacionesBancariaMatriz[$count2]['debito'] = $transferencia->transferencia_valor;
                $otrasconciliacionesBancariaMatriz[$count2]['credito'] = 0;
                $diarios = [];
                $countDiario = 0 ;
                foreach($transferencia->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $otrasconciliacionesBancariaMatriz[$count2]['diario'] = $diarios;
                $otrasconciliacionesBancariaMatriz[$count2]['Beneficiario'] = $transferencia->transferencia_beneficiario;
                $otrasconciliacionesBancariaMatriz[$count2]['referencia'] = $transferencia->transferencia_descripcion;
                //$otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $transferencia->transferencia_fecha_conciliacion;
                $otrasconciliacionesBancariaMatriz[$count2]['conciliacion'] = $transferencia->transferencia_conciliacion;
                if($transferencia->transferencia_conciliacion){
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = date("d/m/Y", strtotime($transferencia->transferencia_fecha_conciliacion));
                }else{
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $transferencia->transferencia_fecha_conciliacion;
                } 
                $count2 = $count2 + 1;
            }
            foreach($transIngresosOtros as $transIngresosOtro){                    
                //transferencias ingresos
                $otrasconciliacionesBancariaMatriz[$count2]['tabla'] = 'DEPOSITO';
                $otrasconciliacionesBancariaMatriz[$count2]['id'] = $transIngresosOtro->deposito_id;
                $otrasconciliacionesBancariaMatriz[$count2]['fecha'] = date("d/m/Y", strtotime($transIngresosOtro->deposito_fecha)); 
                $otrasconciliacionesBancariaMatriz[$count2]['tipo'] = $transIngresosOtro->deposito_tipo;
                $otrasconciliacionesBancariaMatriz[$count2]['numero'] = $transIngresosOtro->deposito_numero;
                $otrasconciliacionesBancariaMatriz[$count2]['debito'] = 0;
                $otrasconciliacionesBancariaMatriz[$count2]['credito'] = $transIngresosOtro->deposito_valor;
                $diarios = [];
                $countDiario = 0 ;
                foreach($transIngresosOtro->detalleDiario as $detalle){
                    $diarios[$countDiario] = $detalle->diario->diario_codigo;
                    $countDiario ++;
                }
                $otrasconciliacionesBancariaMatriz[$count2]['diario'] = $diarios;
                $otrasconciliacionesBancariaMatriz[$count2]['Beneficiario'] = '';
                $otrasconciliacionesBancariaMatriz[$count2]['referencia'] = $transIngresosOtro->deposito_descripcion;
                //$otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $transIngresosOtro->deposito_fecha_conciliacion;
                $otrasconciliacionesBancariaMatriz[$count2]['conciliacion'] = $transIngresosOtro->deposito_conciliacion;
                if($transIngresosOtro->deposito_conciliacion){
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = date("d/m/Y", strtotime($transIngresosOtro->deposito_fecha_conciliacion)); 
                }else{
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $transIngresosOtro->deposito_fecha_conciliacion;
                }                           
                $count2 = $count2 + 1;
            }            
            foreach($ndBancosOtros as $ndBanco){                    
                //NOTAS DE DEBITO BANCO
                $otrasconciliacionesBancariaMatriz[$count2]['tabla'] = 'NOTA DEBITO BANCO';
                $otrasconciliacionesBancariaMatriz[$count2]['id'] = $ndBanco->nota_id;
                $otrasconciliacionesBancariaMatriz[$count2]['fecha'] = date("d/m/Y", strtotime($ndBanco->nota_fecha)); 
                $otrasconciliacionesBancariaMatriz[$count2]['tipo'] = 'ND';
                $otrasconciliacionesBancariaMatriz[$count2]['numero'] = $ndBanco->nota_numero;
                $otrasconciliacionesBancariaMatriz[$count2]['debito'] = $ndBanco->nota_valor;
                $otrasconciliacionesBancariaMatriz[$count2]['credito'] = 0;
                $otrasconciliacionesBancariaMatriz[$count2]['diario'] = $ndBanco->diario->diario_codigo;
                $otrasconciliacionesBancariaMatriz[$count2]['Beneficiario'] = $ndBanco->nota_beneficiario;
                $otrasconciliacionesBancariaMatriz[$count2]['referencia'] = $ndBanco->nota_descripcion;
                //$otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $ndBanco->nota_fecha_conciliacion;
                $otrasconciliacionesBancariaMatriz[$count2]['conciliacion'] = $ndBanco->nota_conciliacion;
                if($ndBanco->nota_conciliacion){
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = date("d/m/Y", strtotime($ndBanco->nota_fecha_conciliacion)); 
                }else{
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $ndBanco->nota_fecha_conciliacion;
                } 
                $count2 = $count2 + 1;
            }
            foreach($ncBancosOtros as $ncBanco){                    
                //NOTAS DE CREDITO BANCO
                $otrasconciliacionesBancariaMatriz[$count2]['tabla'] = 'NOTA CREDITO BANCO';
                $otrasconciliacionesBancariaMatriz[$count2]['id'] = $ncBanco->nota_id;
                $otrasconciliacionesBancariaMatriz[$count2]['fecha'] = date("d/m/Y", strtotime($ncBanco->nota_fecha)); 
                $otrasconciliacionesBancariaMatriz[$count2]['tipo'] = 'NC';
                $otrasconciliacionesBancariaMatriz[$count2]['numero'] = $ncBanco->nota_numero;
                $otrasconciliacionesBancariaMatriz[$count2]['debito'] = 0;
                $otrasconciliacionesBancariaMatriz[$count2]['credito'] = $ncBanco->nota_valor;
                $otrasconciliacionesBancariaMatriz[$count2]['diario'] = $ndBanco->diario->diario_codigo;
                $otrasconciliacionesBancariaMatriz[$count2]['Beneficiario'] = $ncBanco->nota_beneficiario;
                $otrasconciliacionesBancariaMatriz[$count2]['referencia'] = $ncBanco->nota_descripcion;
                //$otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $ncBanco->nota_fecha_conciliacion;
                $otrasconciliacionesBancariaMatriz[$count2]['conciliacion'] = $ncBanco->nota_conciliacion; 
                if($ncBanco->nota_conciliacion){
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = date("d/m/Y", strtotime($ncBanco->nota_fecha_conciliacion)); 
                }else{
                    $otrasconciliacionesBancariaMatriz[$count2]['fechaConsiliacion'] = $ncBanco->nota_fecha_conciliacion;
                }                           
                $count2 = $count2 + 1;
            }     
            $datos[0] = $saldoAnteriorContable;
            $datos[1] = $saldoContableActual;
            $datos[2] = $saldoEstadoCuenta;
            $datos[3] = $chequeGiradoNoCobrado;
            $datos[4] = $depositosConciliados;
            $datos[5] = $depositosNoConciliados;
            $datos[6] = $depositosConciliadosOtros;
            $datos[7] = $ndConciliado;
            $datos[8] = $ndNoConciliado;
            $datos[9] = $ncConciliado;
            $datos[10] = $ncNoConciliado;
            $datos[11] = $ncConciliadoOtros;
            $datos[12] = $ndConciliadoOtros;
            $datos[13] = $chequesConciliados;
            $datos[14] = $chequesNoConciliados;
            $datos[15] = $chequesConciliadosOtros;
            $datos[16] = $transferenciasEgresosConciliadas;
            $datos[17] = $transferenciasEgresosNoConciliadas;
            $datos[18] = $transferenciasEgresosConciliadasOtros;
            $datos[19] = $transferenciaIngresosConciliados;
            $datos[20] = $transferenciaIngresosNoConciliados;
            $datos[21] = $transferenciaIngresosConciliadosOtros;
            $datos[22] = $conciliacionBancariaMatriz;
            $datos[23] = $otrasconciliacionesBancariaMatriz;           
            return $datos;
        }catch(\Exception $ex){
            return redirect('conciliacionBancaria')->with('error2','Oucrrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }    
}
