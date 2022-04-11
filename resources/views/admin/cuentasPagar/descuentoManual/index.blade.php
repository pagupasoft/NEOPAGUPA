@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Descuento Manual Anticipo Proveedores</h3>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ url("descuentoManualProveedores") }}">
        @csrf
        <div class="form-group row">
                <label for="nombre_cuenta" class="col-sm-1 col-form-label"><center>Proveedor:</center></label>
                <div class="col-sm-7">
                    <select class="custom-select select2" id="proveedorID" name="proveedorID" require>
                    <option value="" label>--Seleccione una opcion--</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{$proveedor->proveedor_id}}" @if(isset($proveedorS)) @if($proveedorS == $proveedor->proveedor_id) selected @endif @endif>{{$proveedor->proveedor_nombre}}</option>
                        @endforeach
                    </select>                    
                </div>
                <label for="idDesde" class="col-sm-1 col-form-label"><center>Sucursal</center></label>
                <div class="col-sm-3">
                    <select id="sucursalID" name="sucursalID" class="form-control show-tick"
                        data-live-search="true">
                        <option value="0">Todos</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->sucursal_id }}"@if(isset($sucursalS)) @if($sucursalS == $sucursal->sucursal_id) selected @endif @endif>{{ $sucursal->sucursal_nombre }}</option>
                        @endforeach
                    </select>
                </div> 
            </div>
            <div class="form-group row">                
                <label for="idDesde" class="col-sm-1 col-form-label"><center>Desde:</center></label>
                <div class="col-sm-3">
                @if(isset($fechaselect2))
                    <input type="date" class="form-control" id="idDesde" name="idDesde"  value="{{$fechaselect2}}" required>
                @else
                    <input type="date" class="form-control" id="idDesde" name="idDesde"  value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' required>
                @endif
                </div>
                <label for="idHasta" class="col-sm-1 col-form-label"><center>Hasta:</center></label>
                <div class="col-sm-3">
                @if(isset($fechaselect))
                    <input type="date" class="form-control" id="idHasta" name="idHasta"  value="{{$fechaselect}}" required>
                @else
                    <input type="date" class="form-control" id="idHasta" name="idHasta"  value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' required>
                @endif
                </div>               
                    <div class="col-sm-1">
                    <button type="submit" id="buscarReporte" name="buscarReporte" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>                                  
            </div>
            <div id = "DivCruze" class="form-group row">
                <div class="col-sm-7">
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title">FORMA DE CRUCE</h3>
                    </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="BANCO" onclick="myFunctionDivBanco();" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">CRUZAR CON BANCO</label>                
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="CAJA" onclick="myFunctionDivCaja();">
                                        <label class="form-check-label" for="flexRadioDefault1">CRUZAR CON CAJA</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <div class="col-sm-11">
                                            <select class="custom-select" id="banco_id" name="banco_id" onchange="cargarCuenta();">
                                                <option value="" label>--Seleccione una opcion--</option>
                                                @foreach($bancos as $banco)
                                                <option value="{{$banco->banco_id}}">{{$banco->bancoLista->banco_lista_nombre}}</option>
                                                @endforeach
                                            </select>                                        
                                        </div>
                                    </div>                                    
                                    <div class="form-group row"> 
                                    <div class="col-sm-11">
                                        <select class="custom-select" id="cuenta_id" name="cuenta_id">
                                            <option value="" label>--Seleccione una opcion--</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row"> 
                                        <div class="col-sm-11">                              
                                            <select class="custom-select select2" id="idCaja" name="idCaja" disabled>
                                                <option value="" label>--Seleccione una caja--</option>
                                                @if($cajasxusuario)
                                                @foreach($cajas as $caja)
                                                @if($caja->caja_id == $cajasxusuario->caja_id)
                                                <option value="{{$caja->caja_id}}" selected>{{$caja->caja_nombre}}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select> 
                                        </div>                                                       
                                    </div>
                                </div>
                            </div>                                                   
                        </div>                        
                </div> 
                </div>
                    <div class="col-sm-5">
                    <div class="card card-info">
                        <div class="card-header">
                        <h3 class="card-title">TOTAL SELECCIONADO</h3>
                        </div>
                            <div class="card-body">
                                <div class="form-group row">
                                        <label for="idValorSeleccionado" class="col-sm-6 col-form-label">Total Seleccionado</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="idValorSeleccionado" name="idValorSeleccionado" readonly placeholder="0.00">
                                        </div>
                                    </div>  
                                    <div class="form-group row">                                    
                                        <label for="idFechaCruze" class="col-sm-6 col-form-label">Fecha Cruze:</label>
                                        <div class="col-sm-6">                
                                            <input type="date" class="form-control" id="idFechaCruze" name="idFechaCruze"  value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' required>
                                        </div>  
                                </div>
                                </div>
                                <div class="card-footer">
                            <button type="submit" name="cruzarAnticipos" class="btn btn-info">CRUZAR</button>
                        </div>
                     </div>
                    </div>
                </div>
            </div>
            </div>           
                <div class="card-body table-responsive p-0" style="height: 350px;">
                    <table id="cargarItemFactura" class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>  
                                <th></th>
                                <th>Proveedor</th>
                                <th>Monto</th>
                                <th>Saldo</th> 
                                <th>Valor a Cruzar</th>
                                <th>Diario</th>     
                                <th>Fecha</th>
                            </tr>
                        </thead>            
                        <tbody>
                        <?php $contador = 0; ?>
                        @if(isset($anticiposProveedoresMatriz))                            
                            @for ($i = 1; $i <= count($anticiposProveedoresMatriz); ++$i)                  
                            <tr class="text-left">
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" onchange="calcularSeleccion('{{ $anticiposProveedoresMatriz[$i]['ID'] }}','{{$i-1}}');" id="{{ $anticiposProveedoresMatriz[$i]['ID']}}" name="check{{$contador}}" value="{{$anticiposProveedoresMatriz[$i]['ID']}}">
                                        <label for="{{ $anticiposProveedoresMatriz[$i]['ID'] }}" class="custom-control-label"></label>
                                    </div>                               
                                </td> 
                                <td>{{ $anticiposProveedoresMatriz[$i]['Proveedor']}}</td>
                                <td> {{ number_format( $anticiposProveedoresMatriz[$i]['Valor'],2,'.','')}}</td>
                                <td>{{ number_format($anticiposProveedoresMatriz[$i]['Saldo'],2,'.','')}}<input type="hidden" name="Dsaldo[]" value="{{number_format($anticiposProveedoresMatriz[$i]['Saldo'],2,'.','')}}" readonly/></td>
                                <td><input style="width: 110px !important;" class="text-center" name="Ddescontar[]" value="0.00" onkeyup="totalSeleccion('{{$i-1}}');" readonly/></td>
                                <td>{{ $anticiposProveedoresMatriz[$i]['Diario']}}</td>
                                <td>{{ $anticiposProveedoresMatriz[$i]['Fecha'] }}</td>
                            </tr>
                            <?php $contador++; ?>                  
                            @endfor
                        @endif                
                        </tbody>
                    </table>
        </form>           
    </div>     
</div>
<script type="text/javascript">
    id_item = '<?=$contador?>';
    id_item = Number(id_item);

    
function myFunctionDivBanco(){
    document.getElementById("idCaja").disabled=true;
    document.getElementById("banco_id").disabled=false;
    document.getElementById("cuenta_id").disabled=false;

}
function myFunctionDivCaja(){
    document.getElementById("idCaja").disabled=false;
    document.getElementById("banco_id").disabled=true;
    document.getElementById("cuenta_id").disabled=true;
}
function cargarCuenta() {
        $.ajax({
            url: '{{ url("cuentaBancaria/searchN") }}'+ '/' +document.getElementById("banco_id").value,
            dataType: "json",
            type: "GET",
            data: {
                buscar: document.getElementById("banco_id").value
            },
            success: function(data) {
                document.getElementById("cuenta_id").innerHTML = "<option value='' label>--Seleccione una opcion--</option>";
                for (var i = 0; i < data.length; i++) {
                    document.getElementById("cuenta_id").innerHTML += "<option value='" + data[i].cuenta_bancaria_id + "'>" + data[i].cuenta_bancaria_numero + "</option>";
                }
            },
        });
    }

    function calcularSeleccion(codigo,id){
        if(document.getElementById(codigo).checked){
            $("input[name='Ddescontar[]']")[id].readOnly = false;
            $("input[name='Ddescontar[]']")[id].value = Number(Number($("input[name='Ddescontar[]']")[id].value) + Number($("input[name='Dsaldo[]']")[id].value)).toFixed(2);
        }else{
            $("input[name='Ddescontar[]']")[id].readOnly = true;
            $("input[name='Ddescontar[]']")[id].value = Number(0.00).toFixed(2);
        }
        totalSeleccion(id);
    }
    function totalSeleccion(id){
            if(Number($("input[name='Ddescontar[]']")[id].value) < 0){
                $("input[name='Ddescontar[]']")[id].value = Number(Number($("input[name='Dsaldo[]']")[id].value)).toFixed(2);
            }
            if(Number($("input[name='Ddescontar[]']")[id].value) > Number($("input[name='Dsaldo[]']")[id].value)){
                $("input[name='Ddescontar[]']")[id].value = Number(Number($("input[name='Dsaldo[]']")[id].value)).toFixed(2);
            }

            document.getElementById("idValorSeleccionado").value = 0.00;
            for (var i = 0; i < id_item; i++) {
                document.getElementById("idValorSeleccionado").value = Number(Number(document.getElementById("idValorSeleccionado").value) + Number($("input[name='Ddescontar[]']")[i].value)).toFixed(2);
            }            
        } 
</script>
@endsection