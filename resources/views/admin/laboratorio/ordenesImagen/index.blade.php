@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Atencion de Analisis de Imagenes</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ url("ordenesImagen") }}">
        @csrf
            <div class="form-group row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="fecha_desde" class="col-sm-2 col-form-label">Desde:</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"  value='<?php if(isset($fecI)){echo $fecI;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>'>
                        </div>
                        <label for="fecha_desde" class="col-sm-2 col-form-label">Hasta:</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"  value='<?php if(isset($fecF)){echo $fecF;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>'>
                        </div>
                    </div>
                </div>
                <label for="idBanco" class="col-lg-1 col-md-1 col-form-label">Sucursal :</label>
                <div class="col-lg-4 col-md-4">
                    <select class="custom-select select2" id="sucursal_id" name="sucursal_id" required>
                        @foreach($sucursales as $sucursal)
                        <option value="{{$sucursal->sucursal_id}}" @if(isset($sucurslaC)) @if($sucurslaC == $sucursal->sucursal_id) selected @endif @endif>{{$sucursal->sucursal_nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">
                    <button type="submit" id="buscar" name="buscar" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th>Acción</th>
                    <th>Codigo</th>
                    <th>Número</th> 
                    <th>Paciente</th> 
                    <th>Fecha</th> 
                    <th>Otros Examenes</th>                                                                                       
                </tr>
            </thead>
            <tbody>

            @foreach($ordenesImagen as $ordenImagen)
                <tr class="text-center">
                    <td>
                        {{--$ordenImagen->orden_estado--}}  {{--$ordenImagen->orden_id--}}
                        @if($ordenImagen->orden_estado == 1)
                            <a class="btn btn-xs btn-outline-danger " style="padding: 2px 8px;" data-toggle="tooltip" data-placement="top" title="No Facturado">
                                <i class="fas fa-info-circle"></i> Pendiente
                            </a>
                        @elseif($ordenImagen->orden_estado==2)
                            <a href="{{ url("ordenImagen/{$ordenImagen->orden_id}/subirImagenes") }}" class="btn btn-xs btn-primary " style="padding: 2px 8px;" data-toggle="tooltip" data-placement="top" title="Subir Resultados">
                                <i class="fas fa-edit"></i> Subir
                            </a>
                        @else
                            <a class="btn btn-xs btn-outline-success" style="padding: 2px 8px;" data-toggle="tooltip" data-placement="top" title="Subidos">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="{{ url("ordenImagen/{$ordenImagen->orden_id}/verResultadosImagen") }}" class="btn btn-xs btn-primary " style="padding: 2px 8px;" data-toggle="tooltip" data-placement="top" title="ver Resultados">
                                <i class="fa fa-folder-open"></i>
                            </a>
                        @endif
                    </td>
                        
                    <td>{{ $ordenImagen->expediente->ordenAtencion->orden_codigo }}</td>
                    <td>{{ $ordenImagen->expediente->ordenAtencion->orden_numero }}</td>
                    <td>{{ $ordenImagen->expediente->ordenAtencion->paciente->paciente_apellidos}} <br>
                        {{ $ordenImagen->expediente->ordenAtencion->paciente->paciente_nombres }} </td>
                    <td>{{ $ordenImagen->expediente->ordenAtencion->orden_fecha }}</td>
                    <td>{{ $ordenImagen->expediente->ordenAtencion->orden_otros }}</td>                                         
                </tr>
                 
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.modal -->
@endsection