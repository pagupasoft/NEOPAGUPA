<style>
    @page {
        margin-top: 0px;
        margin-bottom: 0px;
        margin-left: 0px;
        margin-right: 0px;
    }
</style>
<body>

    <div id="id1" style="overflow:hidden !important; font-size: 13px; position:absolute;left:{{$chequeImpresion->chequei_beneficiariox}}px;top: {{$chequeImpresion->chequei_beneficiarioy}}px;cursor:pointer;width:340px;height:18px;">@if(isset($cheque->cheque_beneficiario)){{$cheque->cheque_beneficiario}} @else Juan Piwabe Ortiz @endif</div>

    <div id="id2" style="position:absolute;left:{{$chequeImpresion->chequei_valorx}}px;top: {{$chequeImpresion->chequei_valory}}px;cursor:pointer;width:100px;height:18px;">@if(isset($cheque->cheque_beneficiario)){{number_format($cheque->cheque_valor,2,'.',',')}} @else 15805.89 @endif</div>

    <div id="id3"  style="font-size: 13px; position:absolute;left:{{$chequeImpresion->chequei_fechax}}px;top: {{$chequeImpresion->chequei_fechay}}px;cursor:pointer;width:200px;height:18px;">@if(isset($cheque->cheque_beneficiario)){{$cheque->empresa->empresa_ciudad.', '.DateTime::createFromFormat('Y-m-d', $cheque->cheque_fecha_pago)->format('Y/m/d')}} @else 27/12/2021 @endif</div>

    <div id="id4" style=" font-size: 13px;position:absolute;left:{{$chequeImpresion->chequei_letrasx}}px;top: {{$chequeImpresion->chequei_letrasy}}px;cursor:pointer;width:450px;height:36px;">@if(isset($cheque->cheque_beneficiario)) {{str_replace('DOLARES',' ',$cheque->cheque_valor_letras)}} @else Quince mil ochocientos cinco con 89/100 dolares @endif</div>

</body>