@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ url("conciliacionBancaria") }}">
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Conciliación Bancaria</h3>
            <div class="float-right">
                <button type="submit" id="guardar" name="guardar" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar Conciliación"><i class="fa fa-save"></i>&nbsp;Guardar</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group row">
                        <label for="idBanco" class="col-lg-1 col-md-1 col-form-label">Banco :</label>
                        <div class="col-lg-2 col-md-2">
                            <select class="custom-select" id="banco_id" name="banco_id" onclick="cargarCuenta();"
                                required>
                                <option value="" label>--Seleccione una opcion--</option>
                                @foreach($bancos as $banco)
                                <option value="{{$banco->banco_id}}" @if(isset($bancoC))  @if($banco->banco_id == $bancoC->banco_id) selected @endif @endif>{{$banco->bancoLista->banco_lista_nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="idBanco" class="col-lg-1 col-md-1 col-form-label">Cuenta :</label>
                        <div class="col-lg-2 col-md-2">
                            <select class="custom-select" id="cuenta_id" name="cuenta_id" onclick="cargarContable();"
                                required>
                                <option value="" label>--Seleccione una opcion--</option>
                                @if(isset($cuentaBancaria)) <option value="{{ $cuentaBancaria->cuenta_bancaria_id }}" selected>{{ $cuentaBancaria->cuenta_bancaria_numero }}</option> @endif
                            </select>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <label for="idDesde" class="col-lg-2 col-md-2 col-form-label">
                                    <center>Desde :</center>
                                </label>
                                <div class="col-lg-4 col-md-4">
                                    <input type="date" class="form-control" id="idDesde" name="idDesde" 
                                    @if(isset($fechaI)) value='{{ $fechaI }}'  @else 
                                    value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' @endif required>
                                </div>
                                <label for="idHasta" class="col-lg-2 col-md-2 col-form-label">
                                    <center>Hasta :</center>
                                </label>
                                <div class="col-lg-4 col-md-4">
                                    <input type="date" class="form-control" id="idHasta" name="idHasta"
                                    @if(isset($fechaF)) value='{{ $fechaF }}'  @else 
                                    value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' @endif required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 centrar-texto">
                            <button type="submit" id="buscar" name="buscar" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Buscar"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class=" row">
                        <div class="col-lg-5 col-md-5">
                            <div class=" row"><label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;</label></div>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Anterior Contable :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoAnteriorContable)) value='{{ number_format($saldoAnteriorContable,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <hr>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Cheques Girados y No Cobrados :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($chequeGiradoNoCobrado)) value='{{ number_format($chequeGiradoNoCobrado,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <hr>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Contable Actual :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoContableActual)) value='{{ number_format($saldoContableActual,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Estado de Cuenta :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoEstadoCuenta)) value='{{ number_format($saldoEstadoCuenta,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label"></label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4"><center><label for="idDesde">Conciliado</label></center></div>
                                                <div class="col-lg-4 col-md-4"><center><label for="idDesde">No Conciliado</label></center></div>
                                                <div class="col-lg-4 col-md-4"><center><label for="idDesde">Conciliado Otros</label></center></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label">&nbsp;&nbsp;+ Depositos :</label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($depositosConciliados)) value='{{ number_format($depositosConciliados,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($depositosNoConciliados)) value='{{ number_format($depositosNoConciliados,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($depositosConciliadosOtros)) value='{{ number_format($depositosConciliadosOtros,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label">&nbsp;&nbsp;+ Notas de Crédito :</label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($ncConciliado)) value='{{ number_format($ncConciliado,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($ncNoConciliado)) value='{{ number_format($ncNoConciliado,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                <input type="text" class="form-control derecha-texto" @if(isset($ncConciliadoOtros)) value='{{ number_format($ncConciliadoOtros,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label">&nbsp;&nbsp;- Notas de Débito :</label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($ndConciliado)) value='{{ number_format($ndConciliado,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($ndNoConciliado)) value='{{ number_format($ndNoConciliado,2) }}' @else value='0.00' @endif readonly>    
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($ndConciliadoOtros)) value='{{ number_format($ndConciliadoOtros,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label">&nbsp;&nbsp;- Cheques Egresos :</label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($chequesConciliados)) value='{{ number_format($chequesConciliados,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($chequesNoConciliados)) value='{{ number_format($chequesNoConciliados,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($chequesConciliadosOtros)) value='{{ number_format($chequesConciliadosOtros,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label">&nbsp;&nbsp;- Transferencias Egresos :</label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($transferenciasEgresosConciliadas)) value='{{ number_format($transferenciasEgresosConciliadas,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($transferenciasEgresosNoConciliadas)) value='{{ number_format($transferenciasEgresosNoConciliadas,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($transferenciasEgresosConciliadasOtros)) value='{{ number_format($transferenciasEgresosConciliadasOtros,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="row">
                                        <label for="idDesde" class="col-lg-4 col-md-4 col-form-label">&nbsp;&nbsp;+ Transferencias Ingresos :</label>
                                        <div class="col-lg-8 col-md-8">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($transferenciaIngresosConciliados)) value='{{ number_format($transferenciaIngresosConciliados,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($transferenciaIngresosNoConciliados)) value='{{ number_format($transferenciaIngresosNoConciliados,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control derecha-texto" @if(isset($transferenciaIngresosConciliadosOtros)) value='{{ number_format($transferenciaIngresosConciliadosOtros,2) }}' @else value='0.00' @endif readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <?php $saldo = 0; ?>
            <div class="card-body table-responsive p-0" style="height: 400px;">
            <table id="example4" class="table table-head-fixed text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th>Cons.</th>
                        <th>Fecha</th>
                        <th>Fecha Cons.</th>
                        <th>Tipo</th>
                        <th>Numero</th>
                        <th>Crédito</th>
                        <th>Débito</th>
                        <th>Saldo</th>
                        <th>Diario</th>
                        <th>Beneficiario</th>
                        <th>Referencia</th>                                               
                    </tr>
                </thead>
                <tbody>                   
                    @if(isset($conciliacionBancariaMatriz))
                        @for ($i = 0; $i < count($conciliacionBancariaMatriz); ++$i)        
                        <?php $saldo = $saldo + $conciliacionBancariaMatriz[$i]['credito'] - $conciliacionBancariaMatriz[$i]['debito']; ?>       
                        <tr class="text-center">
                            <td>
                                <input type="hidden" name="idonciliacion[]" value="{{$conciliacionBancariaMatriz[$i]['id'].'-'.$conciliacionBancariaMatriz[$i]['tabla']}}"/>
                                @if($conciliacionBancariaMatriz[$i]['bloqueo'] == false) 
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chk-{{ $conciliacionBancariaMatriz[$i]['id'] }}" name="chkConciliacion[]" value="{{$conciliacionBancariaMatriz[$i]['id'].'-'.$conciliacionBancariaMatriz[$i]['tabla']}}" @if($conciliacionBancariaMatriz[$i]['conciliacion'] == true) checked @endif>
                                    <label for="chk-{{ $conciliacionBancariaMatriz[$i]['id'] }}" class="custom-control-label"></label>
                                </div>
                                @endif
                            </td>
                            <td>{{ $conciliacionBancariaMatriz[$i]['fecha'] }}</td>
                            <td>{{ $conciliacionBancariaMatriz[$i]['fechaConsiliacion']}}</td>
                            <td>{{ $conciliacionBancariaMatriz[$i]['tipo'] }}</td>
                            <td>{{ $conciliacionBancariaMatriz[$i]['numero']}}</td>
                            <td>{{ number_format($conciliacionBancariaMatriz[$i]['credito'],2)}}</td>
                            <td>{{ number_format($conciliacionBancariaMatriz[$i]['debito'],2)}}</td>    
                            <td>{{ $saldo}}</td>                           
                            <td>{{ $conciliacionBancariaMatriz[$i]['diario']}}</td>
                            <td>{{ $conciliacionBancariaMatriz[$i]['Beneficiario']}}</td>
                            <td>{{ $conciliacionBancariaMatriz[$i]['referencia']}}</td>                            
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
            </div>
            <hr>
            <center><h3><b>POR CONCILIAR EN OTROS MESES</b><h3></center>
            <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th>Cons.</th>
                        <th>Fecha</th>
                        <th>Fecha Cons.</th>
                        <th>Tipo</th>
                        <th>Numero</th>
                        <th>Crédito</th>
                        <th>Débito</th>
                        <th>Saldo</th>
                        <th>Diario</th>
                        <th>Beneficiario</th>
                        <th>Referencia</th>                                               
                    </tr>
                </thead>
                <tbody>
                @if(isset($otrasconciliacionesBancariaMatriz))
                        @for ($c = 0; $c < count($otrasconciliacionesBancariaMatriz); ++$c)      
                        <?php $saldo = $saldo + $otrasconciliacionesBancariaMatriz[$c]['credito'] - $otrasconciliacionesBancariaMatriz[$c]['debito']; ?>         
                        <tr class="text-center">
                            <td>
                                <input type="hidden" name="idonciliacionOtros[]" value="{{$otrasconciliacionesBancariaMatriz[$c]['id'].'-'.$otrasconciliacionesBancariaMatriz[$c]['tabla']}}"/>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chk2-{{ $otrasconciliacionesBancariaMatriz[$c]['id'] }}" name="chkConciliacionOtros[]" value="{{$otrasconciliacionesBancariaMatriz[$c]['id'].'-'.$otrasconciliacionesBancariaMatriz[$c]['tabla']}}" @if($otrasconciliacionesBancariaMatriz[$c]['conciliacion'] == true) checked @endif >
                                    <label for="chk2-{{ $otrasconciliacionesBancariaMatriz[$c]['id'] }}" class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['fecha'] }}</td>
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['fechaConsiliacion']}}</td>
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['tipo'] }}</td>
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['numero']}}</td>
                            <td>{{ number_format($otrasconciliacionesBancariaMatriz[$c]['credito'],2)}}</td>
                            <td>{{ number_format($otrasconciliacionesBancariaMatriz[$c]['debito'],2)}}</td>    
                            <td>{{ $saldo}}</td>                              
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['diario']}}</td>
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['Beneficiario']}}</td>
                            <td>{{ $otrasconciliacionesBancariaMatriz[$c]['referencia']}}</td>                            
                        </tr>
                        @endfor
                    @endif                    
                </tbody>
            </table>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
function cargarCuenta(){
    $.ajax({
        url: '{{ url("cuentaBancaria/searchN") }}'+ '/' +document.getElementById("banco_id").value,
        dataType: "json",
        type: "GET",
        data: {
            buscar: document.getElementById("banco_id").value
        },
        success: function(data){
            document.getElementById("cuenta_id").innerHTML = "<option value='' label>--Seleccione una opcion--</option>";
            for (var i=0; i<data.length; i++) {
                document.getElementById("cuenta_id").innerHTML += "<option value='"+data[i].cuenta_bancaria_id+"'>"+data[i].cuenta_bancaria_numero+"</option>";
            }           
        },
    });
}
</script>
@endsection