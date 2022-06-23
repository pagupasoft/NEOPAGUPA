<?php

namespace App\Console\Commands;

use App\Http\Controllers\generalController;
use App\Models\Amortizacion_Seguros;
use App\Models\Detalle_Amortizacion;
use App\Models\Detalle_Diario;
use App\Models\Detalle_Prestamo;
use App\Models\Diario;
use App\Models\Prestamo_Banco;
use App\Models\Producto;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Testeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testeo:actualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $prestamos=DB::table('detalle_prestamo')->join('prestamo_banco','prestamo_banco.prestamo_id','=','detalle_prestamo.prestamo_id')->join('banco','banco.banco_id','=','prestamo_banco.banco_id')->join('banco_lista','banco.banco_lista_id','=','banco_lista.banco_lista_id')->get();
            $fechaactual=date('Y-m-d');
            foreach ($prestamos as $prestamo) {
                if ($prestamo->diario_id==null) {
                    if ($prestamo->detalle_fecha<=$fechaactual) {
                        $fecha =$prestamo->detalle_fecha;
                        $mes  = DateTime::createFromFormat('Y-m-d', $fecha)->format('m');
                        $ano  = DateTime::createFromFormat('Y-m-d', $fecha)->format('y');
                        $secuencialDiario=DB::table('diario')->where('empresa_id', '=', $prestamo->empresa_id)->where('diario_tipo', '=', 'CIPB')->where('diario_mes', '=', $mes)->where('diario_ano', '=', DateTime::createFromFormat('Y-m-d', $fecha)->format('Y'))->max('diario_secuencial');
                        $sec = 1;
                        if ($secuencialDiario) {
                            $sec = $secuencialDiario +1;
                        }
                        $codigoDiario = 'CIPB'.$mes.$ano.substr(str_repeat(0, 7).$sec, - 7);
                        $valor=$prestamo->detalle_total;
                        $detalle=Detalle_Prestamo::findOrFail($prestamo->detalle_id);
                    
                        $prestamoref=Prestamo_Banco::findOrFail($prestamo->prestamo_id);

                        $general = new generalController();
                        $diario = new Diario();
                        $diario->diario_codigo = $codigoDiario;
                        $diario->diario_fecha = $fecha;
                        $diario->diario_referencia = 'COMPROBANTE DE INTERES DE PRESTAMO BANCARIO';
                        $diario->diario_tipo_documento = 'INTERES DE PRESTAMO BANCARIO';
                        $diario->diario_numero_documento = $mes.$ano.substr(str_repeat(0, 7).$sec, - 7);
                        $diario->diario_beneficiario = strtoupper($prestamo->banco_lista_nombre);
                        ;
                        $diario->diario_tipo ='CIPB';
                        $diario->diario_secuencial = substr($codigoDiario, 8);
                        $diario->diario_mes = DateTime::createFromFormat('Y-m-d', $fecha)->format('m');
                        $diario->diario_ano = DateTime::createFromFormat('Y-m-d', $fecha)->format('Y');
                        $diario->diario_comentario = 'COMPROBANTE DE INTERES DE PRESTAMO BANCARIO: '.strtoupper($prestamo->banco_lista_nombre) ;
                        $diario->diario_cierre = 0;
                        $diario->diario_estado = 1;
                        $diario->empresa_id = $prestamo->empresa_id;
                        $diario->sucursal_id = $prestamo->sucursal_id;
                        $diario->save();
                        $detalle->diario()->associate($diario);
                        $general->registrarAuditoria('Registro de Diario de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo, 'Tipo de Diario -> '.$diario->diario_referencia.'');
          
                        $detalleDiario = new Detalle_Diario();
                        $detalleDiario->detalle_debe = $valor;
                        $detalleDiario->detalle_haber = 0.00 ;
                        $detalleDiario->detalle_comentario = 'P/R DEL PAGO DEL INTERES DEL '.strtoupper($prestamoref->banco->bancoLista->banco_lista_nombre). ' CON MONTO DE $'.$prestamoref->prestamo_monto;
                        $detalleDiario->detalle_tipo_documento = 'INTERES DE PRESTAMO BANCARIO';
                        $detalleDiario->detalle_numero_documento = $codigoDiario;
                        $detalleDiario->detalle_conciliacion = '0';
                        $detalleDiario->detalle_estado = '1';
                        $detalleDiario->cuenta_id = $prestamoref->cuentadebe->cuenta_id;
                        $diario->detalles()->save($detalleDiario);
                        $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo, 'En la cuenta del debe -> '.$prestamoref->cuentadebe->cuenta_numero.' con el valor de: -> '.$valor);
            
                        $detalleDiario = new Detalle_Diario();
                        $detalleDiario->detalle_debe = 0.00;
                        $detalleDiario->detalle_haber = $valor;
                        $detalleDiario->detalle_comentario = 'P/R EL RECONOCIMIENTO DE GASTOS DEL PRESTAMO DEL'.strtoupper($prestamoref->banco->bancoLista->banco_lista_nombre). ' CON MONTO DE $'.$prestamoref->prestamo_monto;
                        $detalleDiario->detalle_tipo_documento = 'INTERES DE PRESTAMO BANCARIO';
                        $detalleDiario->detalle_numero_documento = $codigoDiario;
                        $detalleDiario->detalle_conciliacion = '0';
                        $detalleDiario->detalle_estado = '1';
                        $detalleDiario->cuenta_id = $prestamoref->cuentahaber->cuenta_id;
                        $diario->detalles()->save($detalleDiario);
                        $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo, 'En la cuenta del haber -> '.$prestamoref->cuentahaber->cuenta_numero.' con el valor de: -> '.$valor);
            
                        $detalle->save();

                        $prestamoref->prestamo_total_interes=(DB::table('detalle_prestamo')->where('diario_id', '!=', null)->where('prestamo_id', '=', $prestamoref->prestamo_id)->sum('detalle_valor_interes'));
                        $prestamoref->prestamo_pago_total=$prestamoref->prestamo_total_interes+$prestamoref->prestamo_monto;
                        $prestamoref->save();
                    }
                }
            }
            $detalles=DB::table('detalle_amortizacion')->join('amortizacion_seguros','amortizacion_seguros.amortizacion_id','=','detalle_amortizacion.amortizacion_id')->get();
            $fechaactual=date('Y-m-d');
            foreach ($detalles as $detalle) {
                if ($detalle->diario_id==null) {
                    if ($detalle->detalle_fecha<=$fechaactual) {
                        $detalleref=Detalle_Amortizacion::findOrFail($detalle->detalle_id);
                    
                        $seguroref=Amortizacion_Seguros::findOrFail($detalle->amortizacion_id);
                    
                        $fecha =$detalle->detalle_fecha;
                        $mes  = DateTime::createFromFormat('Y-m-d', $fecha)->format('m');
                        $ano  = DateTime::createFromFormat('Y-m-d', $fecha)->format('y');
                        $secuencialDiario=DB::table('diario')->where('empresa_id', '=', $seguroref->sucursal->empresa_id)->where('diario_tipo', '=', 'CASE')->where('diario_mes', '=', $mes)->where('diario_ano', '=', DateTime::createFromFormat('Y-m-d', $fecha)->format('Y'))->max('diario_secuencial');
                        $sec = 1;
                        if ($secuencialDiario) {
                            $sec = $secuencialDiario +1;
                        }
                        $codigoDiario = 'CASE'.$mes.$ano.substr(str_repeat(0, 7).$sec, - 7);
                        $valor=$detalle->detalle_valor;

                        $general = new generalController();
                        $diario = new Diario();
                        $diario->diario_codigo = $codigoDiario;
                        $diario->diario_fecha = $fecha;
                        $diario->diario_referencia = 'COMPROBANTE DE AMORTIZACION DE SEGURO';
                        $diario->diario_tipo_documento = 'AMORTIZACION DE SEGURO';
                        $diario->diario_numero_documento = $mes.$ano.substr(str_repeat(0, 7).$sec, - 7);
                        $diario->diario_beneficiario = $seguroref->transaccionCompra->proveedor->proveedor_nombre;
                        
                        $diario->diario_tipo ='CASE';
                        $diario->diario_secuencial = substr($codigoDiario, 8);
                        $diario->diario_mes = DateTime::createFromFormat('Y-m-d', $fecha)->format('m');
                        $diario->diario_ano = DateTime::createFromFormat('Y-m-d', $fecha)->format('Y');
                        $diario->diario_comentario = 'COMPROBANTE DE AMORTIZACION DE SEGURO CON FACTURA N°: '. $seguroref->transaccionCompra->transaccion_numero ;
                        $diario->diario_cierre = 0;
                        $diario->diario_estado = 1;
                        $diario->empresa_id = $seguroref->sucursal->empresa_id;
                        $diario->sucursal_id = $seguroref->sucursal_id;
                        $diario->save();
                        $detalleref->diario()->associate($diario);
                        $general->registrarAuditoria('Registro de Diario de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo, 'Tipo de Diario -> '.$diario->diario_referencia.'');
                        foreach ($seguroref->transaccionCompra->detalles as $tcdetalle) {
                            $producto=Producto::findOrFail($tcdetalle->producto->producto_id);
                        }
                       

                        $detalleDiario = new Detalle_Diario();
                        $detalleDiario->detalle_debe = $valor;
                        $detalleDiario->detalle_haber = 0.00 ;
                        $detalleDiario->detalle_comentario = 'P/R AMORTIZACION AL GASTO SEGURO '.$seguroref->transaccionCompra->proveedor->proveedor_nombre. ' '.$producto->producto_nombre;
                        $detalleDiario->detalle_tipo_documento = 'AMORTIZACION DE SEGURO';
                        $detalleDiario->detalle_numero_documento = $codigoDiario;
                        $detalleDiario->detalle_conciliacion = '0';
                        $detalleDiario->detalle_estado = '1';
                        $detalleDiario->cuenta_id = $seguroref->cuentadebe->cuenta_id;
                        $diario->detalles()->save($detalleDiario);
                        $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo, 'En la cuenta del debe -> '.$seguroref->cuentadebe->cuenta_numero.' con el valor de: -> '.$valor);
            
                        $detalleDiario = new Detalle_Diario();
                        $detalleDiario->detalle_debe = 0.00;
                        $detalleDiario->detalle_haber = $valor;
                        $detalleDiario->detalle_comentario = 'P/R AMORTIZACION AL GASTO SEGURO '.$seguroref->transaccionCompra->proveedor->proveedor_nombre. ' '.$producto->producto_nombre;
                        $detalleDiario->detalle_tipo_documento = 'AMORTIZACION DE SEGURO';
                        $detalleDiario->detalle_numero_documento = $codigoDiario;
                        $detalleDiario->detalle_conciliacion = '0';
                        $detalleDiario->detalle_estado = '1';
                        $detalleDiario->cuenta_id =  $producto->cuentaGasto->cuenta_id;
                        $diario->detalles()->save($detalleDiario);
                        $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diario->diario_codigo, $diario->diario_codigo, 'En la cuenta del haber -> '.$producto->cuentaGasto->cuenta_numero.' con el valor de: -> '.$valor);
            
                        $detalleref->save();

                        $seguroref->amortizacion_pago_total=(DB::table('detalle_amortizacion')->where('diario_id', '!=', null)->where('amortizacion_id', '=', $seguroref->amortizacion_id)->sum('detalle_valor'));
                        $seguroref->save();
                    }
                       
                }
            }
            
            

        
            
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        // El método line () es un método que viene con la clase de comando, que puede generar nuestra información personalizada
        $this->line('calculate Data Success!');
    }
}
