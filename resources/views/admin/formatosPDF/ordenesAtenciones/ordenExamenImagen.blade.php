@extends ('admin.layouts.formatoPDF')
@section('contenido')
    @section('titulo')
    <tr>
        <td colspan="2">
            <table>
                <tr>
                    <td class="centrar letra12 negrita">RECLAMO N°: {{ $orden->orden_reclamo}}</td>
                    <td class="centrar letra12 negrita">ORDEN MEDICA N°: {{ $orden->orden_numero}}</td>
                </tr>
                <tr>
                    <td class="text-center letra22 negrita">ORDEN DE TOMA DE IMAGENES ECOGRÁFICAS</td>
                </tr>
            </table>
        </td>
    @endsection
    <table>
        <tr class="letra12">
            <td class="negrita" style="width: 105px;">FECHA:</td>
            <td>{{ $orden->orden_fecha }}</td>
            <td class="negrita" style="width: 125px;">Médico:</td>
            <td> @if(isset($orden->medico->empleado)) {{$orden->medico->empleado->empleado_nombre}} @else @if(isset($orden->medico->proveedor)) {{$orden->medico->proveedor->proveedor_nombre}} @endif @endif</td>
        </tr>
        <tr class="letra12">
            <td class="negrita" style="width: 105px;">Paciente:</td>
            <td>{{ $orden->paciente->paciente_apellidos}} {{ $orden->paciente->paciente_nombres}}</td>
            <td class="negrita" style="width: 125px;">Especialidad:</td>
            <td>{{ $orden->especialidad->especialidad_nombre}}</td>
        </tr>  
        <tr class="letra12">
            <td class="negrita" style="width: 125px;">Cedula:</td>
            <td>{{ $orden->paciente->paciente_cedula}}</td>
            <td class="negrita" style="width: 105px;">Aseguradora:</td>
            <td>{{ $orden->cliente->cliente_nombre }}</td>
        </tr> 
        <tr class="letra12">
            <td class="negrita" style="width: 125px;">Compañia:</td>
            <td>{{ $empresa->empresa_nombreComercial }}</td>
            <td class="negrita" style="width: 105px;">Cobertura:</td>
            <td>100 %</td>
        </tr>   
       
    </table>

    <br>

    <table style="white-space: normal!important;" id="tabladetalle">>
        <thead>
            <tr style="border: 1px solid black;" class="centrar letra10">                
                <th class="letra12">Examen de Imagenes</th>
            </tr>
        </thead>
        <tbody>           
            @foreach($ordenImagen->detalleImagen as $fila)        
            <tr class="letra12" style="border: 1px solid black;">                   
                <td style="border: 0px" align="left">{{ $fila->imagen($fila->imagen_id)->first()->imagen_nombre }}
                &nbsp; &nbsp;&nbsp; &nbsp;
                {{ $fila->detalle_indicacion }}</td>
            </tr>  
            @endforeach  
        </tbody>
    </table>
    <br><br>
    <div class="col-md-12">
        <span>Observacion:</span>
        <p>{{$ordenImagen->orden_observacion}}</p>
    </div>
@endsection