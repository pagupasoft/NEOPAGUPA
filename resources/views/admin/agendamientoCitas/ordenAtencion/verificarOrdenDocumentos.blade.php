@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Ordenes de Atencion - Verificador de Documentos</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form-horizontal" method="GET" action="{{ url("verificarDocumentos") }}">
            @csrf
            
            <div class="form-group row">
                <label for="fecha_desde" class="col-sm-1 col-form-label">Medicos:</label>

                <div class="col-sm-4">
                    <select name="medico_id" class="form-control">
                        <option value=0 @if($seleccionado==0) selected @endif >Todos</option>

                        @foreach($medicos as $medico)
                            <option value="{{ $medico->medico_id }}" @if($seleccionado==$medico->medico_id) selected @endif>{{ $medico->empleado->empleado_nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
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
        <table id="example1" class="table table-bordered table-hover">
            <thead>
                <tr class="text-center  neo-fondo-tabla">
                    <th></th>   
                    <th>Fecha/Hora</th>
                    <th>Paciente</th>
                    <th>Especialidad</th>
                    <th>Medico</th>

                    @foreach($documentos as $doc)
                        <th>{{ $doc->documento_nombre }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @if(isset($ordenesAtencion))
                @foreach($ordenesAtencion as $ordenAtencion)
                    <tr class="text-center">
                        <td></td>
                        <td>
                            {{ $ordenAtencion->orden_fecha }} <br>
                            {{ $ordenAtencion->orden_hora }}
                        </td>
                        <!--td>{{-- $ordenAtencion->orden_numero --}}</td-->
                        <td>
                            {{ $ordenAtencion->paciente->paciente_apellidos}} <br>
                            {{ $ordenAtencion->paciente->paciente_nombres }}
                        </td>
                        <!--td>
                            {{ $ordenAtencion->sucursal_nombre }}  
                        </td-->
                        <td>@if(isset($ordenAtencion->especialidad->especialidad_nombre )) {{$ordenAtencion->especialidad->especialidad_nombre}} @endif</td>
                        <td>
                            @if(isset($ordenAtencion->medico->proveedor))
                                {{$ordenAtencion->medico->proveedor->proveedor_nombre}}
                            @endif
                            @if(isset($ordenAtencion->medico->empleado))
                                {{$ordenAtencion->medico->empleado->empleado_nombre}}
                            @endif
                        </td>
                        
                        @foreach($documentos as $docEmp)
                            <td>
                                @foreach($ordenAtencion->documentos as $docOrden)
                                    @if($docEmp->documento_id==$docOrden->documento_id)
                                        <a class="btn btn-warning" href="{{ $docOrden->doccita_url }}" target="_blank"><i class="fas fa-eye"></i> Ver</a>
                                        @break
                                    @endif
                                @endforeach
                            </td>
                        @endforeach
                    </tr> 
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection