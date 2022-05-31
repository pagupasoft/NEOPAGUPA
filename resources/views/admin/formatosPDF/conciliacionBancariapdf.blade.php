@extends ('admin.layouts.encabezadoConciliacionBancariapdf')
@section('contenido')
    @section('titulo')
        <tr><td colspan="2" class="centrar letra15 negrita">CONCILIACION BANCARIA</td></tr>
        <tr><td colspan="2" class="centrar letra15 negrita">{{ $banco }} - Cuenta bancaria: {{$cuentaBancariaB}} </td></tr>banco
        <tr><td colspan="2" class="centrar letra11">FECHA: {{ $datos[24] }} AL {{ $datos[25]}}</td></tr>
    @endsection
    <?php 
    $saldo = 0; 
    if(isset($datos[0])){
        $saldo = $datos[0];
    }
    ?>  
    <br>   
    <table>
        <tr class="letra12">
            <td class="negrita" style="width: 105px;">SALDO ANTERIOR : </td>
            <td>{{ $saldo }}</td>            
        </tr>
    </table>  
    <br>
    <table style="white-space: normal!important;" id="tabladetalle">
        <thead>
        <tr style="border: 1px solid black;" class="centrar letra12">                            
            <th width="7%" style="background:  #A7CCF3;">Fecha</th>                           
            <th style="background:  #A7CCF3;">Tipo</th>
            <th style="background:  #A7CCF3;">Numero</th>
            <th style="background:  #A7CCF3;">Crédito</th>
            <th style="background:  #A7CCF3;">Débito</th>
            <th style="background:  #A7CCF3;">Conc.</th>
            <th style="background:  #A7CCF3;">Saldo</th>
            <th style="background:  #A7CCF3;">Referencia</th>                                               
        </tr>
        </thead>
        <tbody>
            @if(isset($datos[22]))
                @for ($i = 0; $i < count($datos[22]); ++$i)        
                <?php $saldo = $saldo + $datos[22][$i]['credito'] - $datos[22][$i]['debito']; ?>       
                <tr class="letra10">                                
                    <td style="border-bottom: 1px solid black; ">{{ $datos[22][$i]['fecha'] }}</td>                                
                    <td style="border-bottom: 1px solid black; " width="7%">{{ $datos[22][$i]['tipo'] }}</td>
                    <td style="border-bottom: 1px solid black; " width="7%">{{ $datos[22][$i]['numero']}}</td>
                    <td style="text-align: right; border-bottom: 1px solid black; ">{{ number_format($datos[22][$i]['credito'],2,'.','')}}</td>
                    <td style="text-align: right; border-bottom: 1px solid black; ">{{ number_format($datos[22][$i]['debito'],2,'.','')}}</td>
                    <td style="text-align: center; border-bottom: 1px solid black;" width="3%">
                        @if($datos[22][$i]['conciliacion'] == true)
                            X
                        @endif
                    </td> 
                    <td style="text-align: right; border-bottom: 1px solid black; ">$ {{number_format($saldo,2)}}</td>  
                    <td style="border-bottom: 1px solid black; " width="45%">{{ $datos[22][$i]['referencia']}}</td>                            
                </tr>
                @endfor
            @endif
        </tbody>
    </table>
    <br>
    <table>
        <tr class="letra14">
            <td class="negrita" style="width: 105px;"><center>POR CONCILIAR EN OTROS MESES</center></td>                       
        </tr>
    </table>
    <br>
    <table style="white-space: normal!important;" id="tabladetalle">
        <thead>
        <tr style="border: 1px solid black;" class="centrar letra12">                            
            <th width="7%" style="background:  #A7CCF3;">Fecha</th>                           
            <th style="background:  #A7CCF3;">Tipo</th>
            <th style="background:  #A7CCF3;">Numero</th>
            <th style="background:  #A7CCF3;">Crédito</th>
            <th style="background:  #A7CCF3;">Débito</th>
            <th style="background:  #A7CCF3;">Conc.</th>
            <th style="background:  #A7CCF3;">Saldo</th>
            <th style="background:  #A7CCF3;">Referencia</th>                                               
        </tr>
        </thead>
        <tbody>
            @if(isset($datos[23]))
                @for ($i = 0; $i < count($datos[23]); ++$i)        
                <?php $saldo = $saldo + $datos[23][$i]['credito'] - $datos[23][$i]['debito']; ?>       
                <tr class="letra10">                                
                    <td style="border-bottom: 1px solid black; ">{{ $datos[23][$i]['fecha'] }}</td>                                
                    <td style="border-bottom: 1px solid black; " width="7%">{{ $datos[23][$i]['tipo'] }}</td>
                    <td style="border-bottom: 1px solid black; " width="7%">{{ $datos[23][$i]['numero']}}</td>
                    <td style="text-align: right; border-bottom: 1px solid black; ">{{ number_format($datos[23][$i]['credito'],2,'.','')}}</td>
                    <td style="text-align: right; border-bottom: 1px solid black; ">{{ number_format($datos[23][$i]['debito'],2,'.','')}}</td>
                    <td style="text-align: center; border-bottom: 1px solid black;" width="3%">
                        @if($datos[23][$i]['conciliacion'] == true)
                            X
                        @endif
                    </td> 
                    <td style="text-align: right; border-bottom: 1px solid black; ">$ {{number_format($saldo,2)}}</td>  
                    <td style="border-bottom: 1px solid black; " width="45%">{{ $datos[23][$i]['referencia']}}</td>                            
                </tr>
                @endfor
            @endif
        </tbody>
    </table>
    <div style="page-break-inside: avoid;">
    <br>
    <table class="conBorder" style="padding-left: 5px; padding-right: 560px;">
        <tr class="letra12">            
            <td > SALDO ANTERIOR</td>
            <td>$ {{ number_format($datos[0],2)}}</td>
        </tr>
        <tr class="letra12">
            <td > DEPOSITOS</td>
            <td>$ {{ number_format($datos[4],2)}}</td>
        </tr>
        <tr class="letra12">
            <td > NOTAS DE CREDITO</td>
            <td>$ {{ number_format($datos[9],2)}}</td>
        </tr>
        <tr class="letra12">
            <td > NOTAS DE DEBITO</td>
            <td>$ {{ number_format($datos[7],2)}}</td>
        </tr>
        <tr class="letra12">
            <td > CHEQUES EGRESOS</td>
            <td>$ {{ number_format($datos[13],2)}}</td>
        </tr>    
        <tr class="letra12">
            <td > SALDO ACTUAL</td>
            <td>$ {{ number_format($datos[1],2)}}</td>
        </tr>
        <tr class="letra12">
            <td> CHEQUES GIRADOS Y NO COBRADOS</td>
            <td>$ {{ number_format($datos[3],2)}}</td>
        </tr>
        <tr class="letra12">
            <td > SALDO SEGUN ESTADO DE CUENTA</td>
            <td>$ {{ number_format($datos[2],2)}}</td>
        </tr>
</table>
    <br>
    <br>
    <br>    
    <table class="table" style="padding-left: 5px; padding-right: 500px;">
        <thead>
            <tr class="letra11">
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr class="letra11">
            <td><center>----------------------------</center></td>
            <td><center>----------------------------</center></td>
            <td><center>----------------------------</center></td>
            </tr>
            <tr class="letra11">
            <td class="centrar" style="width: 20%;" ><center>GERENTE</center></td>
            <td class="centrar" style="width: 20%;"><center>{{ $user }}</center></td>
            <td class="centrar" style="width: 20%;"><center>CONTADOR</center></td>
            </tr>
        </tbody>
    </table>  
@endsection