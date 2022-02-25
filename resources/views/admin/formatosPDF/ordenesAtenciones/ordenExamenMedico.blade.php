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
                    <td class="center letra22 negrita">ORDEN PARA EXAMENES DE LABORATORIOS</td>
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

    <br>


    @foreach($tipos as $tipoE)
        <table style="border-collapse: collapse;">
            <thead style="border: 1px solid">
                <tr class=""> 
                    <th style="border: 0px"class="text-center">{{$tipoE}}</th>   
                </tr>
            </thead>
            <tbody>
                <?php $c=0; ?>
                @foreach($ordenExamen->detalle as $fila)
                    
                    <?php
                        $examen=$fila->examen($fila->examen_id)->first();
                        $producto=$examen->producto($examen->producto_id)->first();
                        $tipo=$examen->tipoExamen($examen->tipo_id)->first();
                        $c++;
                    ?>
                    @if($tipoE==$tipo->tipo_nombre)
                        <tr class="letra12" style="padding-left: 15px; border: 1px solid">                  
                            <td style="padding-left: 15px; border: 1px solid"> {{$producto->producto_codigo}} - {{$producto->producto_nombre }}</td>
                        </tr> 
                    @endif
                @endforeach  
            </tbody>
        </table>
        <br><br>
    @endforeach

    <div class="col-md-12">
        <span>Observacion:</span>
        <p>{{$ordenExamen->orden_otros}}</p>
    </div>
@endsection 