@extends ('admin.layouts.formatoPDF')
@section('contenido')
    @section('titulo')
        <tr><td colspan="2" class="centrar letra20 negrita">ESTADO DE SITUACIÓN FINANCIERA</td></tr>
        <tr><td colspan="2" class="centrar letra15">DEL {{ $desde }} AL {{ $hasta }}</td></tr>
    @endsection
    <hr>
    <table>
        <tbody>
            @if(isset($datos))
                @for ($i = 1; $i <= count($datos); ++$i)    
                <tr class="letra12">
                    <td align="left" class="espacio{{$datos[$i]['nivel']}}">@if($datos[$i]['nivel'] == 1) <b> {{ $datos[$i]['numero'] }} {{ $datos[$i]['nombre'] }} </b> @else {{ $datos[$i]['numero'] }} {{ strtoupper($datos[$i]['nombre']) }} @endif</td>
                    <td align="right">@if($datos[$i]['nivel'] > 1) $ {{ number_format($datos[$i]['total'],2) }} @endif</td>
                    <td align="right"><b>@if($datos[$i]['nivel'] == 1) $ {{ number_format($datos[$i]['total'],2) }} @endif</b></td>
                </tr>
                @endfor
               
            @endif
        </tbody>
    </table>
    <hr>
    <br>
    <br>
    <table>
        <tr><td class="interlinea"><b>TOTAL ACTIVO : </b>$ {{ number_format($totAct,2) }}</td></tr>
        <tr><td class="interlinea"><b>TOTAL PASIVO : </b>$ {{ number_format($totPas,2) }}</td></tr>
        <tr><td class="interlinea"><b>TOTAL PATRIMONIO : </b>$ {{ number_format($totPat,2) }}</td></tr>
    </table>
@endsection