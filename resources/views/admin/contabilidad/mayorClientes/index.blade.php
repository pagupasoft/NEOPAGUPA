@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Mayor de Clientes</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form-horizontal" method="POST"  action="{{ url("mayorClientes") }} ">
        @csrf
            <div class="form-group row">
                <label for="fecha_desde" class="col-sm-1 col-form-label"><center>Desde:</center></label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"  value='<?php if(isset($fDesde)){echo $fDesde;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>' required>
                </div>
                <label for="fecha_hasta" class="col-sm-1 col-form-label"><center>Hasta:</center></label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"  value='<?php if(isset($fHasta)){echo $fHasta;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>' required>
                </div>
                <label for="nombre_cuenta" class="col-sm-1 col-form-label"><center>Cliente:</center></label>
                <div class="col-sm-5">
                    <select class="custom-select select2" id="clienteID" name="clienteID" required>
                        @foreach($clientes as $cliente)
                            <option value="{{$cliente->cliente_id}}" @if(isset($clienteC)) @if($clienteC == $cliente->cliente_id) selected @endif @endif>{{$cliente->cliente_nombre}}</option>
                        @endforeach
                    </select>                    
                </div>
            </div>
            <div class="form-group row">
                <label for="cuenta_id" class="col-sm-1 col-form-label"><center>Cuenta : </center></label>
                <div class="col-sm-10">
                    <select class="custom-select select2" id="cuenta_id" name="cuenta_id" required>
                        <option value="0" label>Todas</option>
                        @foreach($cuentas as $cuenta)
                            <option value="{{$cuenta->cuenta_id}}" @if(isset($cuentaC)) @if($cuentaC == $cuenta->cuenta_id) selected @endif @endif>{{$cuenta->cuenta_numero.' - '.$cuenta->cuenta_nombre}}</option>
                        @endforeach
                    </select>                    
                </div>
                <div class="col-sm-1">
                    <button type="submit" id="buscar" name="buscar" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <button type="submit" id="pdf" name="pdf" class="btn btn-secondary"><i class="fas fa-print"></i></button>
                </div>
            </div>
            <hr>
            <table id="example4" class="table table-bordered table-hover table-responsive sin-salto">
                <thead>
                    <tr class="text-center neo-fondo-tabla">
                        <th>Fecha</th>
                        <th>Documento</th>
                        <th>Número</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>Saldo</th>
                        <th>Diario</th>
                        <th>Comentario</th>
                        <th>Sucursal</th>  
                    </tr>
                </thead>
                <tbody>
                    <?php $saldo=0.0; $totaldebe=0.0; $totalhaber=0.0;?>
                    @if(isset($datos))
                    @for ($i = 1; $i <= count($datos); ++$i)    
                        <tr class="text-center">
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['fec'] }}<input type="hidden" name="idFec[]" value="{{ $datos[$i]['fec'] }}"/><input type="hidden" name="idTot[]" value="{{ $datos[$i]['tot'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['doc'] }}<input type="hidden" name="idDoc[]" value="{{ $datos[$i]['doc'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['num'] }}<input type="hidden" name="idNum[]" value="{{ $datos[$i]['num'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['deb'] <> '') {{ number_format($datos[$i]['deb'],2) }} @endif<input type="hidden" name="idDeb[]" value="{{ $datos[$i]['deb'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['hab'] <> '') {{ number_format($datos[$i]['hab'],2) }} @endif<input type="hidden" name="idHab[]" value="{{ $datos[$i]['hab'] }}"/></td>
                            <td  @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['act'] <> '') {{ number_format($datos[$i]['act'],2) }} @endif<input type="hidden" name="idAct[]" value="{{ $datos[$i]['act'] }}"/></td>
                            <td  @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif><a href="{{ url("asientoDiario/ver/{$datos[$i]['dia']}") }}" target="_blank">{{ $datos[$i]['dia'] }}</a><input type="hidden" name="idDia[]" value="{{ $datos[$i]['dia'] }}"/></td>
                            <td  @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['com'] }}<input type="hidden" name="idCom[]" value="{{ $datos[$i]['com'] }}"/></td>
                            <td  @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['suc'] }}<input type="hidden" name="idSuc[]" value="{{ $datos[$i]['suc'] }}"/></td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        </form>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection