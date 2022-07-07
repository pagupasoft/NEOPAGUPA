@extends ('admin.layouts.formatoPDFDiario')
@section('contenido')
    @section('titulo')
        <tr><td colspan="2" class="centrar letra15 negrita">
            COMPROBANTE DE INGRESO DE BODEGA
        </td></tr>
    @endsection
    <table style="white-space: normal!important;">
        <tr class="letra12">
            <td class="negrita" style="width: 105px;">INGRESO BODEGA:</td>
            <td>{{ $ingreso->diario->diario_numero_documento }}</td>
            <td class="negrita" style="width: 125px;">PROVEEDOR:</td>
            <td>{{ $ingreso->proveedor->proveedor_nombre }}</td>
        </tr>
        <tr class="letra12">
            <td class="negrita" style="width: 105px;">FECHA:</td>
            <td>{{ DateTime::createFromFormat('Y-m-d', $ingreso->diario->diario_fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr class="letra12">
            <td class="negrita" style="width: 125px;">NUMERO DIARIO:</td>
            <td>{{ $ingreso->diario->diario_codigo }}</td>
        </tr>
    </table>
    <br>
    <table style="white-space: normal!important; border-collapse: collapse;">
        <thead>
            <tr class="centrar letra12">
                <th class="borde-izquierdo cabecera-diario izquierda">CANTIDAD</th>    
                <th class="cabecera-diario izquierda">CODIGO</th>
                <th class="cabecera-diario izquierda">PRODUCTO</th>
                <th class="cabecera-diario">P.U.</th>
                <th class="borde-derecho cabecera-diario">TOTAL</th>   
            </tr>
        </thead>
        <tbody>
            @if(isset($ingreso->diario))
                <?php $total = 0; ?>
                <tr><td colspan="5" style="padding-top: 5px;"></td></tr>
                @foreach($ingreso->detalles as $detalle)
                    <?php $total = $total + $detalle->detalle_ingreso_total; ?>
                    <tr class="letra10">
                        <td class="detalle-diario dereche">{{ $detalle->detalle_ingreso_cantidad }}</td>
                        <td class="detalle-diario ">{{ $detalle->producto->producto_codigo }}</td>
                        <td class="detalle-diario ">{{ $detalle->producto->producto_nombre }}</td>
                        <td  class="detalle-diario dereche">{{ number_format($detalle->detalle_ingreso_precio_unitario, 2) }}</td>
                        <td  class="detalle-diario dereche">{{ number_format($detalle->detalle_ingreso_total, 2) }}</td>
                    </tr>
                @endforeach
                <tr><td colspan="5" style="padding-top: 5px;"></td></tr>
                <tr class="letra12">
                    <td style="border-top: 1px solid black;"></td>
                    <td style="border-top: 1px solid black;"></td>
                    <td style="border-top: 1px solid black;"></td>
                    <td class="foot-diario centrar">TOTAL</td>
                    <td class="foot-diario dereche">{{ number_format($total,2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <table style="padding-top: 70px;">
        <tr class="letra12">
            <td style="padding-right: 15px; padding-left: 15px;"></td>    
            <td class="centrar" style="border-top: 1px solid black; width: 20%;white-space: pre-wrap;">Elaborado por: <br> {{ Auth::user()->user_nombre }}</td>
            <td style="padding-right: 15px; padding-left: 15px;"></td>    
            <td class="centrar" style="border-top: 1px solid black;width: 20%;">Revisado por:<br><br></td>
            <td style="padding-right: 15px; padding-left: 15px;"></td>
        </tr>
    </table>
@endsection