@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Despacho de Prescripciones</h3>
        
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form class="form-horizontal" method="POST" action="{{ url("receta") }}">
        @csrf
        <div class="col-md-12">
            <div class="form-group row">
                <!--div class="offset-sm-1 col-sm-11">
                    
                </div-->
                <div class="col-sm-12">
                    <div class="row mb-2">
                        <label for="fecha_desde" class="col-sm-1 col-form-label text-right">Desde :</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"  value='<?php if(isset($fDesde)){echo $fDesde;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>' required>
                        </div>
                        <label for="fecha_hasta" class="col-sm-1 col-form-label text-right">Hasta :</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"  value='<?php if(isset($fHasta)){echo $fHasta;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>' required>
                        </div>

                        <div class="custom-control custom-checkbox col-sm2 pt-2">
                            <input class="custom-control-input" type="checkbox" id="incluirFechas" name="incluirFechas" value="1" <?php if(isset($fechasI)) echo "checked"; ?>>
                            <label for="incluirFechas" class="custom-control-label">Todo</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="pacientes" class="col-sm-1 col-form-label text-right">Pacientes:</label>
                        <div class="col-sm-3">
                            <select class="custom-select select2" id="pacienteID" name="pacienteID" require>
                                <option value="0">Todos</option>
                                @foreach($pacientes as $paciente)
                                    <option value="{{$paciente->paciente_id}}" @if(isset($pacienteID)) @if($pacienteID == $paciente->paciente_id) selected @endif @endif>{{$paciente->paciente_apellidos}} {{$paciente->paciente_nombres}}</option>
                                @endforeach
                            </select>                    
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="row">
                                <label class="col-sm-3  col-form-label text-right" for="estado">Estado:</label>
                                <div class="col-sm-9">
                                    <select class="custom-select select2" id="estado" name="estado" required>
                                        <option value="3" @if(isset($prescripcionE)) @if($prescripcionE == 3) selected @endif @endif selected>Todas</option>
                                        <option value="0" @if(isset($prescripcionE)) @if($prescripcionE == 0) selected @endif @endif>Anulados</option>
                                        <option value="1" @if(isset($prescripcionE)) @if($prescripcionE == 1) selected @endif @else @endif>Atendidos</option>
                                        <option value="2" @if(isset($prescripcionE)) @if($prescripcionE == 2) selected @endif @endif>Entregado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="buscar" name="buscar" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Orden</th>
                    <th>Cedula</th> 
                    <th>Paciente</th> 
                    <th>Fecha</th> 
                    <th>Hora</th>
                    <th>Estado</th>    
                    <th>Observacion</th>                                                                                       
                </tr>
            </thead>
            <tbody>
            @foreach($prescripciones as $prescripcion)
                <tr class="text-center">
                    <td>
                        <a href="{{ url("receta/{$prescripcion->orden_id}")}}" style="width:30px" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Ver Prescripción"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        
                        @if($prescripcion->prescripcion_estado==2)
                            <a target="_blank" href="{{ url("receta/imprimir/{$prescripcion->orden_id}")}}" style="width:35px" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top" title="Imprimir Prescripción"><i class="fa fa-print" aria-hidden="true"></i></a>
                        @endif
                    </td>
                    <td>{{ $prescripcion->orden_numero }}</td>
                    <td>{{ $prescripcion->paciente_cedula }}</td>
                    <td>{{ $prescripcion->paciente_apellidos}} <br>
                        {{ $prescripcion->paciente_nombres }} </td>
                    <td>{{ $prescripcion->orden_fecha }}</td>
                    <td>{{ $prescripcion->orden_hora }}</td>
                    <td>
                        @if ($prescripcion->prescripcion_estado==0 )
                            <a class="btn btn-xs btn-outline-danger">ANULADO</a>
                        @elseif ( $prescripcion->prescripcion_estado==1 )
                            <a class="btn btn-xs btn-outline-primary">ATENDIDO</a>
                        @else
                            <a class="btn btn-xs btn-outline-success">ENTREGADO</a>
                        @endif
                    </td>
                    <td>{{ $prescripcion->orden_observacion }}</td>                                         
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.modal -->
@endsection