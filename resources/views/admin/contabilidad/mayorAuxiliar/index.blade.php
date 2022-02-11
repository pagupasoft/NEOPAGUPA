@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Mayor Auxiliar</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ url("mayorAuxiliar") }} ">
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
                <label for="sucursal_id" class="col-sm-1 col-form-label"><center>Sucursal:</center></label>
                <div class="col-sm-4">
                    <select class="custom-select select2" id="sucursal_id" name="sucursal_id" require>
                        <option value="0">Todas</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{$sucursal->sucursal_id}}" @if(isset($sucursalC)) @if($sucursalC == $sucursal->sucursal_id) selected @endif @endif>{{$sucursal->sucursal_nombre}}</option>
                        @endforeach
                    </select> 
                </div>
                <div class="col-sm-1">
                    <button type="submit" id="buscar" name="buscar" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <button type="submit" id="pdf" name="pdf" class="btn btn-secondary"><i class="fas fa-print"></i></button>
                </div>
            </div>
            <div class="form-group row">
                <label for="nombre_cuenta" class="col-sm-1 col-form-label"><center>Cuenta Inicio</center></label>
                <div class="col-sm-5">
                    <select class="custom-select select2" id="cuenta_inicio" name="cuenta_inicio" require>
                        @foreach($cuentas as $cuenta)
                            <option value="{{$cuenta->cuenta_numero}}" @if(isset($ini)) @if($ini == $cuenta->cuenta_numero) selected @endif @endif>{{$cuenta->cuenta_numero.' - '.$cuenta->cuenta_nombre}}</option>
                        @endforeach
                    </select>                    
                </div>
                <label for="nombre_cuenta" class="col-sm-1 col-form-label"><center>Cuenta Fin</center></label>
                <div class="col-sm-5">
                    <select class="custom-select select2" id="cuenta_fin" name="cuenta_fin" require>
                        @foreach($cuentas as $cuenta)
                            <option value="{{$cuenta->cuenta_numero}}" @if(isset($fin)) @if($fin == $cuenta->cuenta_numero) selected @endif @else @if($cuentaFinal == $cuenta->cuenta_id) selected @endif @endif>{{ $cuenta->cuenta_numero.' - '.$cuenta->cuenta_nombre }}</option>
                        @endforeach
                    </select>                    
                </div>
            </div>
            <hr>
            <table id="example4" class="table table-bordered table-hover table-responsive sin-salto">
                <thead>
                    <tr class="text-center neo-fondo-tabla">
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Documento</th>
                        <th>Número</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>Saldo</th>
                        <th>Beneficiario</th>
                        <th>Diario</th>
                        <th>Comentario</th>
                        <th>Sucursal</th>  
                    </tr>
                </thead>
                <tbody>
                    <?php $saldo=0.0; $totaldebe=0.0; $totalhaber=0.0;?>
                    @if(isset($datos))
                    @for ($i = 1; $i <= count($datos); ++$i)    
                        <tr>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['tot'] == '1') {{ $datos[$i]['cod'] }} @endif<input type="hidden" name="idCod[]" value="{{ $datos[$i]['cod'] }}"/><input type="hidden" name="idTot[]" value="{{ $datos[$i]['tot'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['tot'] == '1') {{ $datos[$i]['nom'] }} @endif<input type="hidden" name="idNom[]" value="{{ $datos[$i]['nom'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['fec'] }}<input type="hidden" name="idFec[]" value="{{ $datos[$i]['fec'] }}"/></td>
                            <td class="text-center" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['doc'] }}<input type="hidden" name="idDoc[]" value="{{ $datos[$i]['doc'] }}"/></td>
                            <td class="text-center" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['num'] }}<input type="hidden" name="idNum[]" value="{{ $datos[$i]['num'] }}"/></td>
                            <td class="derecha-texto" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['deb'] <> '') {{ number_format($datos[$i]['deb'],2) }} @endif<input type="hidden" name="idDeb[]" value="{{ $datos[$i]['deb'] }}"/></td>
                            <td class="derecha-texto" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['hab'] <> '') {{ number_format($datos[$i]['hab'],2) }} @endif<input type="hidden" name="idHab[]" value="{{ $datos[$i]['hab'] }}"/></td>
                            <td class="derecha-texto" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>@if($datos[$i]['act'] <> '') {{ number_format($datos[$i]['act'],2) }} @endif<input type="hidden" name="idAct[]" value="{{ $datos[$i]['act'] }}"/></td>
                            <td class="text-center" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['ben'] }}<input type="hidden" name="idBen[]" value="{{ $datos[$i]['ben'] }}"/></td>
                            <td class="text-center" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif><a href="{{ url("asientoDiario/ver/{$datos[$i]['dia']}") }}" target="_blank">{{ $datos[$i]['dia'] }}</a><input type="hidden" name="idDia[]" value="{{ $datos[$i]['dia'] }}"/></td>
                            <td @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['com'] }}<input type="hidden" name="idCom[]" value="{{ $datos[$i]['com'] }}"/></td>
                            <td class="text-center" @if($datos[$i]['tot'] == '1') style="background:  #70B1F7;" @endif @if($datos[$i]['tot'] == '2') style="background:  #C0C4C5;" @endif>{{ $datos[$i]['suc'] }}<input type="hidden" name="idSuc[]" value="{{ $datos[$i]['suc'] }}"/></td>
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