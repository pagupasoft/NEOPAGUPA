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
                <div class="col-sm-3">
                    <select class="custom-select select2" id="proveedorID" name="proveedorID" require>
                        <option value="0">Todos</option>
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
            <div class="form-group row">
                    <label for="idValor" class="col-sm-1 col-form-label"><center>Total Seleccionado</center></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="idValor" name="idValor" placeholder="0.00">
                    </div> 
                    <label for="idCaja" class="col-sm-1 col-form-label">Caja</label>
                    <div class="col-sm-3">
                        <select class="custom-select select2" id="idCaja" name="idCaja" required>
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
            <div class="form-group row">                    
                    <label for="idFechaCruze" class="col-sm-1 col-form-label"><center>Fecha Cruze:</center></label>
                    <div class="col-sm-3">                
                         <input type="date" class="form-control" id="idFechaCruze" name="idFechaCruze"  value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' required>
                    </div>  
                    <label for="banco_id" class="col-sm-1 col-form-label">Banco</label>
                    <div class="col-sm-3">
                        <select class="custom-select" id="banco_id" name="banco_id" onchange="cargarCuenta();" required>
                            <option value="" label>--Seleccione una opcion--</option>
                            @foreach($bancos as $banco)
                            <option value="{{$banco->banco_id}}">{{$banco->bancoLista->banco_lista_nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="cuenta_id" class="col-sm-1 col-form-label"># de Cuenta</label>
                    <div class="col-sm-3">
                        <select class="custom-select" id="cuenta_id" name="cuenta_id" required>
                            <option value="" label>--Seleccione una opcion--</option>
                        </select>
                    </div>
            </div>            
            <div class="card-body table-responsive p-0" style="height: 350px;">
                <table id="example4" class="table table-head-fixed text-nowrap">
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
                    @if(isset($anticiposProveedoresMatriz))
                        @for ($i = 1; $i <= count($anticiposProveedoresMatriz); ++$i)               
                        <tr class="text-left">
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" onchange="calcularSeleccion('{{ $anticiposProveedoresMatriz[$i]['ID'] }}','{{$i-1}}');" id="{{ $anticiposProveedoresMatriz[$i]['ID']}}" name="{{ $anticiposProveedoresMatriz[$i]['ID']}}" value="{{$anticiposProveedoresMatriz[$i]['ID']}}">
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
                        @endfor
                    @endif                
                    </tbody>
                </table>
            </div>                     
        </form>           
    </div>     
</div>
<script type="text/javascript">

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

            /*document.getElementById("idValorSeleccionado").value = 0.00;
            for (var i = 1; i < id_item; i++) {
                document.getElementById("idValorSeleccionado").value = Number(Number(document.getElementById("idValorSeleccionado").value) + Number($("input[name='Ddescontar[]']")[i].value)).toFixed(2);
            }
            document.getElementById("idValorCheque").value = document.getElementById("idValorSeleccionado").value;
            if(document.getElementById("idValorSeleccionado").value > 0){
                document.getElementById("guardarID").disabled = false;
            }else{
                document.getElementById("guardarID").disabled = true;
            }*/
        } 
</script>
@endsection