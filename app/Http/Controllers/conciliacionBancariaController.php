<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\Cuenta_Bancaria;
use App\Models\Detalle_Diario;
use App\Models\Punto_Emision;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if (isset($_POST['buscar'])){
            return $this->consulta($request);
        }
        if (isset($_POST['guardar'])){
            return $this->guardar($request);
        }
    }
    private function guardar(Request $request){
        try{            
            DB::beginTransaction();
            $cuentaBancaria = Cuenta_Bancaria::CuentaBancaria($request->get('cuenta_id'))->first();
            $movimientos = Detalle_Diario::MovimientosByCuenta($cuentaBancaria->cuenta_id,$request->get('idDesde'),$request->get('idHasta'))->get();
            $movimientosOtros = Detalle_Diario::MovimientosByCuentaOtros($cuentaBancaria->cuenta_id,$request->get('idDesde'))->get();
            $general = new generalController();
            $cierre = $general->cierre($request->get('idHasta'));          
            if($cierre){
                return redirect('conciliacionBancaria')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');

            }
            $cierre = $general->cierre($request->get('idDesde'));          
            if($cierre){
                return redirect('conciliacionBancaria')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');

            }

            foreach($movimientos as $movimiento){
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
            }
            DB::commit();
            return redirect('conciliacionBancaria')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('conciliacionBancaria')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }  
    private function consulta(Request $request){
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
    }
}
