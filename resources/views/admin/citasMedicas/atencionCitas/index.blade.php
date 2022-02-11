@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Atencion de Citas Medicas</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Codigo</th>
                    <th>Número</th> 
                    <th>Paciente</th> 
                    <th>Fecha</th> 
                    <th>Hora</th>   
                    <th>Observacion</th>                                                                                       
                </tr>
            </thead>
            <tbody>
            @foreach($ordenesAtencion as $ordenAtencion)
                @foreach($mespecialidadM as $mespe)
                    @if($mespe->mespecialidad_id == $ordenAtencion->mespecialidad_id && $ordenAtencion->orden_estado == 3)
                    <tr class="text-center">
                        <td>                        
                            <a href="{{ url("atencionCitas/{$ordenAtencion->orden_id}/atender")}}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Atender"><i class="fa fa-calendar-check"></i></a> 
                        </td>    
                            <td>{{ $ordenAtencion->orden_codigo }}</td>
                            <td>{{ $ordenAtencion->orden_numero }}</td>
                            <td>{{ $ordenAtencion->paciente_apellidos}} <br>
                                {{ $ordenAtencion->paciente_nombres }} </td>
                            <td>{{ $ordenAtencion->orden_fecha }}</td>
                            <td>{{ $ordenAtencion->orden_hora }}</td>
                            <td>{{ $ordenAtencion->orden_observacion }}</td>                                         
                    </tr>
                    @endif
                @endforeach 
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.modal -->
@endsection