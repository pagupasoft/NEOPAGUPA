@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Analisis de Laboratario</h3>
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Sucursal</th> 
                    <th>Codigo</th>
                    <th>Paciente</th> 
                    <th>Fecha</th> 
                    <th>Otros Examenes</th>                                                                                       
                </tr>
            </thead>
            <tbody>
                <?php $count=1;?>
            @foreach($analisisuser as $ordenanalisisuser)
                @if($ordenanalisisuser->analisis_estado == 3)  
                    @foreach($ordenanalisisuser->detalles as $detalles)
                        @if($detalles->detalle_estado=='1')
                            <?php $count=2;?>
                        @endif
                    @endforeach
                    <tr class="text-center">
                        <td> 
                            <a href="{{ url("analisisLaboratorio/{$ordenanalisisuser->orden->orden_id}/imprimirorden") }}" target="_blank" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Imprimir Orden"><i class="fa fa-print"></i></a>
                            
                            @if($ordenanalisisuser->analisis_estado == 3)   
                                <a href="{{ url("analisisLaboratorio/{$ordenanalisisuser->analisis_laboratorio_id}/resultados") }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Ver Resultados"><i class="fa fa-vial"></i></a> 

                                @if($count=='1')   
                                    <a href="{{ url("analisisLaboratorio/{$ordenanalisisuser->analisis_laboratorio_id}/enviar") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Enviar por Correo"><i class="fa fa-envelop"></i></a> 
                                @endif
                            @else
                                <a class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Sin Datos Aún"><i class="fas fa-exclamation-circle"></i>&nbsp; PENDIENTE</a> 
                            @endif
                        </td>    
                            <td>{{ $ordenanalisisuser->sucursal->sucursal_nombre }}</td>
                            <td>{{ $ordenanalisisuser->analisis_numero }}</td>
                            <td>{{ $ordenanalisisuser->orden->expediente->ordenatencion->paciente->paciente_apellidos}} {{ $ordenanalisisuser->orden->expediente->ordenatencion->paciente->paciente_nombres }}<br>
                            <td>{{ $ordenanalisisuser->analisis_fecha }}</td>
                            <td>{{ $ordenanalisisuser->analisis_otros }}</td>                                         
                    </tr>
                @endif       
            @endforeach
            @foreach($analisis as $ordenanalisis)
                @if($ordenanalisis->analisis_estado == 2 || $ordenanalisis->analisis_estado == 3     )   
                    <tr class="text-center">
                        <td> 
                            <a href="{{ url("analisisLaboratorio/{$ordenanalisis->orden->orden_id}/imprimirorden") }}" target="_blank" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="top" title="Imprimir Orden"><i class="fa fa-print"></i></a>  
                            @if($ordenanalisis->analisis_estado == 3)   
                                <a href="{{ url("analisisLaboratorio/{$ordenanalisis->analisis_laboratorio_id}/resultados") }}" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Ver Resultados"><i class="fa fa-vial"></i></a> 
                                <a href="{{ url("analisisLaboratorio/{$ordenanalisis->analisis_laboratorio_id}/enviar") }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Enviar por Correo"><i class="fa fa-envelop"></i></a> 
                            @else
                                <a class="btn btn-xs btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Sin Datos Aún"><i class="fas fa-exclamation-circle"></i>&nbsp; PENDIENTE</a> 
                            @endif
                        </td>    
                            <td>{{ $ordenanalisis->sucursal->sucursal_nombre }}</td>
                            <td>{{ $ordenanalisis->analisis_numero }}</td>
                            <td>{{ $ordenanalisis->orden->expediente->ordenatencion->paciente->paciente_apellidos}} {{ $ordenanalisis->orden->expediente->ordenatencion->paciente->paciente_nombres }}<br>
                            <td>{{ $ordenanalisis->analisis_fecha }}</td>
                            <td>{{ $ordenanalisis->analisis_otros }}</td>                                         
                    </tr>
                @endif       
            @endforeach
            
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.modal -->
@endsection