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
                        <div class="col-lg-6 col-md-6">
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Anterior Contable :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoAntCont)) value='{{ number_format($saldoAntCont,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Anterior Estado de Cuenta :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoAntCuenta)) value='{{ number_format($saldoAntCuenta,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <hr>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Cheques Girados y No Cobraos :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($chequeGiradoNoCobrado)) value='{{ number_format($chequeGiradoNoCobrado,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <hr>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Contable Actual :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoActCont)) value='{{ number_format($saldoActCont,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class=" row">
                                <label for="idDesde" class="col-lg-7 col-md-7 col-form-label">&nbsp;&nbsp;Saldo Estado de Cuenta :</label>
                                <div class="col-lg-5 col-md-5">
                                    <input type="text" class="form-control derecha-texto" @if(isset($saldoActCuenta)) value='{{ number_format($saldoActCuenta,2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label"></label>
                                <div class="col-lg-3 col-md-3"><center><label for="idDesde">Conciliado</label></center></div>
                                <div class="col-lg-3 col-md-3"><center><label for="idDesde">No Conciliado</label></center></div>
                            </div>
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label">&nbsp;&nbsp;+ Depositos :</label>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[0],2) }}' @else value='0.00' @endif readonly>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[1],2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label">&nbsp;&nbsp;+ Notas de Crédito :</label>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[2],2) }}' @else value='0.00' @endif readonly>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[3],2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label">&nbsp;&nbsp;- Notas de Débito :</label>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[4],2) }}' @else value='0.00' @endif readonly>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[5],2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label">&nbsp;&nbsp;- Cheques Egresos :</label>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[6],2) }}' @else value='0.00' @endif readonly>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[7],2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label">&nbsp;&nbsp;- Transferencias Egresos :</label>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[8],2) }}' @else value='0.00' @endif readonly>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[9],2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label for="idDesde" class="col-lg-5 col-md-5 col-form-label">&nbsp;&nbsp;+ Transferencias Ingresos :</label>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[10],2) }}' @else value='0.00' @endif readonly>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" class="form-control derecha-texto" @if(isset($resumen)) value='{{ number_format($resumen[11],2) }}' @else value='0.00' @endif readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <table class="table table-bordered table-hover table-responsive sin-salto">
                <thead>
                    <tr class="text-center">
                        <th>Cons.</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Numero</th>
                        <th>Crédito</th>
                        <th>Débito</th>
                        <th>Diario</th>
                        <th>Beneficiario</th>
                        <th>Referencia</th>
                        <th>Fecha Cons.</th>                        
                    </tr>
                </thead>
                <tbody>
                    @if(isset($movimientos))
                        @foreach($movimientos as $movimiento)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chk-{{ $movimiento->detalle_id }}" name="chk-{{ $movimiento->detalle_id }}" @if($movimiento->detalle_conciliacion == 1) checked @endif >
                                        <label for="chk-{{ $movimiento->detalle_id }}" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>{{ $movimiento->diario->diario_fecha }}</td>
                                <td>{{ $movimiento->detalle_tipo_documento }}</td>
                                <td>{{ $movimiento->diario->diario_numero_documento }}</td>
                                <td>{{ number_format($movimiento->detalle_debe, 2) }}</td>
                                <td>{{ number_format($movimiento->detalle_haber, 2) }}</td>
                                <td><a href="{{ url("asientoDiario/ver/{$movimiento->diario->diario_codigo}")}}" target="_blank">{{ $movimiento->diario->diario_codigo }}</a></td>
                                <td>{{ $movimiento->diario->diario_beneficiario }}</td>
                                <td>{{ $movimiento->detalle_comentario }}</td>
                                <td>{{ $movimiento->detalle_fecha_conciliacion }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr>
            <center><h3><b>POR CONCILIAR EN OTROS MESES</b><h3></center>
            <table class="table table-bordered table-hover table-responsive sin-salto">
                <thead>
                    <tr class="text-center">
                        <th>Cons.</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Numero</th>
                        <th>Crédito</th>
                        <th>Débito</th>
                        <th>Diario</th>
                        <th>Beneficiario</th>
                        <th>Referencia</th>
                        <th>Fecha Cons.</th>                        
                    </tr>
                </thead>
                <tbody>
                    @if(isset($movimientosOtros))
                        @foreach($movimientosOtros as $movimiento)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chk-{{ $movimiento->detalle_id }}" name="chk-{{ $movimiento->detalle_id }}" @if($movimiento->detalle_conciliacion == 1) checked @endif >
                                        <label for="chk-{{ $movimiento->detalle_id }}" class="custom-control-label"></label>
                                    </div>
                                </td>
                                <td>{{ $movimiento->diario->diario_fecha }}</td>
                                <td>{{ $movimiento->detalle_tipo_documento }}</td>
                                <td></td>
                                <td>{{ number_format($movimiento->detalle_debe, 2) }}</td>
                                <td>{{ number_format($movimiento->detalle_haber, 2) }}</td>
                                <td>{{ $movimiento->diario->diario_codigo }}</td>
                                <td></td>
                                <td>{{ $movimiento->detalle_comentario }}</td>
                                <td>{{ $movimiento->detalle_fecha_conciliacion }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
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