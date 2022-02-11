@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Reporte de Utilidad por Producto</h3>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ url("utilidadProducto") }}">
        @csrf
            <div class="form-group row">
                <div class="col-sm-5">
                    <div class="row">
                        <label for="fecha_desde" class="col-sm-2 col-form-label"><center>Desde :</center></label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"  value='<?php if(isset($fecI)){echo $fecI;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>' required>
                        </div>
                        <label for="fecha_hasta" class="col-sm-2 col-form-label"><center>Hasta :</center></label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"  value='<?php if(isset($fecF)){echo $fecF;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>' required>
                        </div>
                    </div>
                </div>
                <label for="nombre_cuenta" class="col-sm-1 col-form-label"><center>Producto:</center></label>
                <div class="col-sm-4">
                    <select class="custom-select select2" id="productoID" name="productoID" require>
                        <option value="0">Todos</option>
                        @foreach($productos as $producto)
                            <option value="{{$producto->producto_id}}" @if(isset($productoC)) @if($productoC == $producto->producto_id) selected @endif @endif>{{$producto->producto_nombre}}</option>
                        @endforeach
                    </select>                    
                </div>
                <div class="col-sm-2 centrar-texto">
                    <button type="submit" id="buscar" name="buscar" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <button type="submit" id="pdf" name="pdf" class="btn btn-secondary"><i class="fas fa-print"></i></button>
                </div>
            </div>
            <hr>
            @if(isset($datos))
            <table id="example4" class="table table-bordered table-hover table-responsive sin-salto">
                <thead>
                    <tr class="text-center">
                        <th>Grupo</th>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Venta Costo</th>
                        <th>Venta</th>
                        <th>Utilidad Generada</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= count($datos); ++$i)    
                    <tr>
                        <td>{{ $datos[$i]['gru'] }}</td>
                        <td>{{ $datos[$i]['cod'] }}</td>
                        <td>{{ $datos[$i]['nom'] }}</td>
                        <td class="centrar-texto">{{ $datos[$i]['can'] }}</td>
                        <td class="centrar-texto">{{ number_format($datos[$i]['vec'],2) }}</td>
                        <td class="centrar-texto">{{ number_format($datos[$i]['ven'],2) }}</td>
                        <td class="centrar-texto">{{ number_format($datos[$i]['uti'],2) }}</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
            @endif
            @if(isset($datosP))
            <table id="example5" class="table table-bordered table-hover table-responsive sin-salto">
                <thead>
                    <tr class="text-center">
                        <th colspan="3"></th>
                        <th class="fondo-naranja letra-blanca" colspan="3">ENTRADAS</th>
                        <th class="fondo-azul-claro letra-blanca" colspan="3">SALIDAS</th>
                        <th class="fondo-verde letra-blanca" colspan="3">SALDOS</th>
                        <th colspan="5"></th>
                    </tr>
                    <tr class="text-center">
                        <th>Documento</th>
                        <th>Numero</th>
                        <th>Fecha</th>
                        <th class="fondo-naranja-claro">Cantidad</th>
                        <th class="fondo-naranja-claro">Precio</th>
                        <th class="fondo-naranja-claro">Total</th>
                        <th class="fondo-celeste">Cantidad</th>
                        <th class="fondo-celeste">Precio</th>
                        <th class="fondo-celeste">Total</th>
                        <th class="fondo-verde-claro">Cantidad</th>
                        <th class="fondo-verde-claro">Costo</th>
                        <th class="fondo-verde-claro">Total</th>
                        <th>Diario Costo</th> 
                        <th>Diario</th>
                        <th>Transaccion</th>
                        <th>Cliente/Proveedor</th> 
                        <th>Descripción</th> 
                    </tr>
                </thead>
                <tbody>
                    @if(isset($datos))
                        @for ($i = 1; $i <= count($datos); ++$i)    
                            <tr class="centrar-texto">
                                <td>{{ $datos[$i]['doc'] }}<input type="hidden" name="idDoc[]" value="{{ $datos[$i]['doc'] }}"/></td>
                                <td>{{ $datos[$i]['num'] }}<input type="hidden" name="idNum[]" value="{{ $datos[$i]['num'] }}"/></td>
                                <td>{{ $datos[$i]['fec'] }}<input type="hidden" name="idFec[]" value="{{ $datos[$i]['fec'] }}"/></td>
                                <td>@if($datos[$i]['can1'] <> 0) {{ $datos[$i]['can1'] }} @endif<input type="hidden" name="idCan1[]" value="{{ $datos[$i]['can1'] }}"/></td>
                                <td>@if($datos[$i]['pre1'] <> 0) {{ number_format($datos[$i]['pre1'],4) }} @endif<input type="hidden" name="idPre1[]" value="{{ $datos[$i]['pre1'] }}"/></td>
                                <td>@if($datos[$i]['tot1'] <> 0) {{ number_format($datos[$i]['tot1'],2) }} @endif<input type="hidden" name="idTot1[]" value="{{ $datos[$i]['tot1'] }}"/></td>
                                <td>@if($datos[$i]['can2'] <> 0) {{ $datos[$i]['can2'] }} @endif<input type="hidden" name="idCan2[]" value="{{ $datos[$i]['can2'] }}"/></td>
                                <td>@if($datos[$i]['pre2'] <> 0) {{ number_format($datos[$i]['pre2'],4) }} @endif<input type="hidden" name="idPre2[]" value="{{ $datos[$i]['pre2'] }}"/></td>
                                <td>@if($datos[$i]['tot2'] <> 0) {{ number_format($datos[$i]['tot2'],2) }} @endif<input type="hidden" name="idTot2[]" value="{{ $datos[$i]['tot2'] }}"/></td>
                                <td>{{ $datos[$i]['can3'] }}<input type="hidden" name="idCan3[]" value="{{ $datos[$i]['can3'] }}"/></td>
                                <td>{{ number_format($datos[$i]['pre3'],4) }}<input type="hidden" name="idPre3[]" value="{{ $datos[$i]['pre3'] }}"/></td>
                                <td>{{ number_format($datos[$i]['tot3'],2) }}<input type="hidden" name="idTot3[]" value="{{ $datos[$i]['tot3'] }}"/></td>
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
            @endif
        </form>
    </div>
</div>
@endsection