@extends ('admin.layouts.admin')
@section('principal')

<div class="card card-primary card-outline">
    <form class="form-horizontal" method="POST" action="{{ route('proforma.update', [$proforma->proforma_id]) }}">
        @method('PUT')
        @csrf
        <div class="card-header">
            <div class="row">
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                    <h2 class="card-title"><b>Editar Proforma</b></h2>
                </div>
                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                    <div class="float-right">
                        
                        <button id="guardarID" type="submit" class="btn btn-success btn-sm" ><i
                                class="fa fa-save"></i><span> Guardar</span></button>
                        <button type="button" id="cancelarID" onclick='window.location = "{{ url("listaProforma") }}";' name="cancelarID" 
                            class="btn btn-danger btn-sm not-active-neo" ><i
                                class="fas fa-times-circle"></i><span> Cancelar</span></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row clearfix form-horizontal">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label ">
                            <label>NUMERO</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="proforma_id" name="proforma_id" value="{{ $proforma->proforma_id }}" type="hidden">
                                    
                                    <input id="proforma_estado" name="proforma_estado" value="{{ $proforma->proforma_estado }}" type="hidden">
                                    
                                    <input type="text" id="proforma_serie" name="proforma_serie" value="{{$proforma->proforma_serie}}"
                                        class="form-control derecha-texto negrita " placeholder="Serie" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="proforma_numero" name="proforma_numero"
                                        value="{{substr(str_repeat(0, 9). $proforma->proforma_numero , - 9)}}" class="form-control  negrita " placeholder="Numero"
                                        required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label" style="padding-left: 55px;">
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label derecha-texto">
                            <label>TOTAL</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="idTotalFactura" name="idTotalFactura"
                                        class="form-control campo-total-global derecha-texto" placeholder="Total"
                                        disabled style="background-color: black" value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix form-horizontal">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>CLIENTE :</label>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <input id="clienteID" name="clienteID" value="{{ $proforma->cliente->cliente_id }}" type="hidden">
                                <input id="buscarCliente" name="buscarCliente"  type="text" class="form-control "
                                    placeholder="Cliente" value="{{ $proforma->cliente->cliente_nombre }}" required >
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>Fecha :</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="date" id="factura_fecha" name="factura_fecha" class="form-control "
                                        placeholder="Seleccione una fecha..."
                                        value='{{ $proforma->proforma_fecha }}' required />
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row clearfix form-horizontal">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>RUC/CI :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="idCedula" name="idCedula" class="form-control "
                                        placeholder="Ruc" value="{{ $proforma->cliente->cliente_cedula }}" disabled required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>TIPO :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="idTipoCliente" name="idTipoCliente" class="form-control "
                                        placeholder="Tipo" value="{{ $proforma->cliente->tipoCliente->tipo_cliente_nombre }}" disabled required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  form-control-label  centrar-texto"
                            style="margin-bottom : 0px;">
                            <div class="form-group">
                                <label>BODEGA</label>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row clearfix form-horizontal">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>DIRECCION :</label>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="idDireccion" name="idDireccion" class="form-control "
                                        placeholder="Direccion" value="{{ $proforma->cliente->cliente_direccion }}"  disabled required>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 alinear-izquierda" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <select id="bodega_id" name="bodega_id" class="form-control show-tick"
                                    data-live-search="true" disabled>
                                   
                                    <option value="{{ $proforma->bodega->bodega_nombre }}">{{ $proforma->bodega->bodega_nombre }}</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix form-horizontal">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>TELEFONO :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="idTelefono" name="idTelefono" class="form-control "
                                        placeholder="Telefono" value="{{ $proforma->cliente->cliente_telefono }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>% IVA :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <select class="form-control" id="factura_porcentaje_iva" name="factura_porcentaje_iva"
                                    data-live-search="true" onclick="seleccionarIva()" disabled>
                                   
                                    <option value="{{ $proforma->proforma_porcentaje_iva }}">{{ $proforma->proforma_porcentaje_iva }}%
                                    </option>
                                   
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row clearfix form-horizontal">
                        
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label  "
                            style="margin-bottom : 0px;">
                            <label>LUGAR :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-bottom : 0px;">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="factura_lugar" name="factura_lugar" class="form-control "
                                        value="Machala" placeholder="Lugar" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5" style="margin-bottom: 0px;">
                            <label>Nombre de Producto</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="codigoProducto" name="idProducto" type="hidden">
                                    <input id="idProductoID" name="idProductoID" type="hidden">
                                    <input id="buscarProducto" name="buscarProducto" type="text" class="form-control"
                                        placeholder="Buscar producto" >
                                    <span id="errorStock" class="text-danger invisible">El producto no tiene stock
                                        disponible.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-bottom: 0px;">
                            <center>
                                <label>Iva</label>
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <div class="custom-control custom-checkbox">
                                        <input id="tieneIva" name="tieneIva" type="checkbox"
                                            class="custom-control-input" disabled />
                                        <label for="tieneIva" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </center>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-bottom: 0px;">
                            <label>Disponible</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="id_disponible" name="id_disponible" type="number" class="form-control"
                                        placeholder="Disponible" value="0" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-bottom: 0px;">
                            <label>Cantidad</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input onchange="calcularTotal()" onkeyup="calcularTotal()" id="id_cantidad"
                                        name="id_cantidad" type="number" class="form-control" placeholder="Cantidad"
                                        value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-bottom: 0px;">
                            <label>Precio</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input onchange="calcularTotal()" onkeyup="calcularTotal()" id="id_pu" name="id_pu"
                                        type="text" class="form-control" placeholder="Precio" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-bottom: 0px;">
                            <label>Desc. %</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="id_descuento" name="id_descuento" type="text" class="form-control"
                                        placeholder="Descuento" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-bottom: 0px;">
                            <label>Total</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="id_total" name="id_total" type="text" class="form-control"
                                        placeholder="Total" value="0.00" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                            <a onclick="agregarItem();" class="btn btn-primary btn-venta"><i
                                    class="fas fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                            <div class="table-responsive">
                                @include ('admin.ventas.facturas.itemFactura')
                                <table id="cargarItemFactura"
                                    class="table table-striped table-hover boder-sar tabla-item-factura"
                                    style="margin-bottom: 6px;">
                                    <thead>
                                        <tr class="letra-blanca fondo-azul-claro">
                                            <th>Cantidad</th>
                                            <th>Codigo</th>
                                            <th>Producto</th>
                                            <th>Con Iva</th>
                                            <th>Iva</th>
                                            <th>P.U.</th>
                                            <th>Descuento</th>
                                            <th>Total</th>
                                            <th width="40"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                            <div class="card card-primary card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist"
                                        style="border-bottom: 1px solid #c3c4c5;">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-otros-tab"
                                                data-toggle="pill" href="#custom-tabs-four-otros" role="tab"
                                                aria-controls="custom-tabs-four-otros"
                                                aria-selected="false"><b>Otros</b></a>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-four-otros"
                                            role="tabpanel" aria-labelledby="custom-tabs-four-otros-tab">
                                            <div class="row clearfix form-horizontal">
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label  "
                                                    style="margin-bottom : 0px;">
                                                    <label>Comentario:</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"
                                                    style="margin-bottom : 0px;">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <textarea id="factura_comentario" name="factura_comentario"
                                                                rows=3 class="form-control "
                                                                placeholder="Escribir aqui.."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <table class="table table-totalVenta">
                                <tr>
                                    <td class="letra-blanca fondo-azul-claro negrita" width="90">Sub-Total
                                    </td>
                                    <td id="subtotal" width="100" class="derecha-texto negrita">0.00</td>
                                    <input id="idSubtotal" name="idSubtotal" type="hidden" />
                                </tr>
                                <tr>
                                    <td class="letra-blanca fondo-azul-claro negrita">Descuento</td>
                                    <td id="descuento" class="derecha-texto negrita">0.00</td>
                                    <input id="idDescuento" name="idDescuento" type="hidden" />
                                </tr>
                                <tr>
                                    <td id="porcentajeIva" class="letra-blanca fondo-azul-claro negrita">Tarifa 12 %
                                    </td>
                                    <td id="tarifa12" class="derecha-texto negrita">0.00</td>
                                    <input id="idTarifa12" name="idTarifa12" type="hidden" />
                                </tr>
                                <tr>
                                    <td class="letra-blanca fondo-azul-claro negrita">Tarifa 0%</td>
                                    <td id="tarifa0" class="derecha-texto negrita">0.00</td>
                                    <input id="idTarifa0" name="idTarifa0" type="hidden" />
                                </tr>
                                <tr>
                                    <td id="iva12" class="letra-blanca fondo-azul-claro negrita">Iva 12 %</td>
                                    <td id="iva" class="derecha-texto negrita">0.00</td>
                                    <input id="idIva" name="idIva" type="hidden" />
                                </tr>
                                <tr>
                                    <td class="letra-blanca fondo-azul-claro negrita">Total</td>
                                    <td id="total" class="derecha-texto negrita">0.00</td>
                                    <input id="idTotal" name="idTotal" type="hidden" />
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /.card -->
@section('scriptAjax')
<script src="{{ asset('admin/js/ajax/autocompleteCliente.js') }}"></script>
<script src="{{ asset('admin/js/ajax/autocompleteProducto.js') }}"></script>
@endsection
<script type="text/javascript">
function cargarmetodo() {
    seleccionarIva();

    <?php
    
        foreach ($proforma->detalles as $x) {
            ?>
            document.getElementById("id_total").value='<?php echo($x->producto->producto_precio_costo * $x->detalle_cantidad); ?>';
            document.getElementById("id_descuento").value='0';
            document.getElementById("id_cantidad").value='<?php echo $x->detalle_cantidad; ?>';
            document.getElementById("codigoProducto").value='<?php echo $x->producto->producto_codigo; ?>';
            document.getElementById("idProductoID").value='<?php echo $x->producto->producto_id; ?>';
            document.getElementById("buscarProducto").value='<?php echo $x->producto->producto_nombre; ?>';
            document.getElementById("id_pu").value='<?php echo $x->producto->producto_precio_costo; ?>';
            if ('<?php echo $x->producto->producto_tiene_iva; ?>'=='1') {
                document.getElementById("tieneIva").checked=true;
            } else {
                document.getElementById("tieneIva").checked=false;
            }

            agregarItem();

    <?php
        }
    
    ?>
}
var id_item = 1;
document.getElementById("idTarifa0").value = 0;
document.getElementById("idTarifa12").value = 0;
var combo = document.getElementById("factura_porcentaje_iva");
var porcentajeIva = <?php echo $proforma->proforma_porcentaje_iva; ?>;
porcentajeIva = parseFloat(porcentajeIva) / 100;

function nuevo() {
    $('#factura_porcentaje_iva').css('pointer-events', 'none');
    $('#bodega_id').css('pointer-events', 'none');
    // document.getElementById("bodega_id").disabled  = true;
    document.getElementById("guardarID").disabled = false;
    document.getElementById("cancelarID").disabled = false;
    document.getElementById("nuevoID").disabled = true;
    document.getElementById("buscarProducto").disabled = false;
    //document.getElementById("factura_porcentaje_iva").disabled  = true;
    document.getElementById("buscarCliente").disabled = false;
}

function agregarItem() {
    if ( document.getElementById("id_total").value > 0) {
        total = Number(document.getElementById("id_total").value);
        descuento = Number(total * (document.getElementById("id_descuento").value / 100));
        var linea = $("#plantillaItemFactura").html();
        linea = linea.replace(/{ID}/g, id_item);
        linea = linea.replace(/{Dcantidad}/g, document.getElementById("id_cantidad").value);
        linea = linea.replace(/{Dcodigo}/g, document.getElementById("codigoProducto").value);
        linea = linea.replace(/{DprodcutoID}/g, document.getElementById("idProductoID").value);
        linea = linea.replace(/{Dnombre}/g, document.getElementById("buscarProducto").value);
        if (document.getElementById("tieneIva").checked) {
            linea = linea.replace(/{Diva}/g, "SI");
            linea = linea.replace(/{DViva}/g, Number((total - descuento) * porcentajeIva).toFixed(2));
            iva = "SI";
        } else {
            linea = linea.replace(/{Diva}/g, "NO");
            linea = linea.replace(/{DViva}/g, "0.00");
            iva = "NO";
        }
        linea = linea.replace(/{Dpu}/g, document.getElementById("id_pu").value);
        linea = linea.replace(/{Ddescuento}/g, Number(descuento).toFixed(2));
        linea = linea.replace(/{Dtotal}/g, Number(total - descuento).toFixed(2));
        linea = linea.replace(/{Dtotal2}/g, Number(total).toFixed(2));
        $("#cargarItemFactura tbody").append(linea);
        id_item = id_item + 1;
        cargarTotales(iva, total, descuento);
        resetearCampos();
    }
}

function agregarItem2(id,cantidad,codigoproducto,nombre,tieneIva,precio,total) {
    
    total = Number(total);
    descuento = Number(0);
    var linea = $("#plantillaItemFactura").html();
    linea = linea.replace(/{ID}/g, id_item);
    linea = linea.replace(/{Dcantidad}/g,cantidad);
    linea = linea.replace(/{Dcodigo}/g, codigoproducto);
    linea = linea.replace(/{DprodcutoID}/g, id);
    linea = linea.replace(/{Dnombre}/g, nombre);
    
    if (tieneIva=='1') {
        linea = linea.replace(/{Diva}/g, "SI");
        linea = linea.replace(/{DViva}/g, Number((total - descuento) * porcentajeIva).toFixed(2));
        iva = "SI";
    } else {
        linea = linea.replace(/{Diva}/g, "NO");
        linea = linea.replace(/{DViva}/g, "0.00");
        iva = "NO";
    }
    
    linea = linea.replace(/{Dpu}/g, precio);
    linea = linea.replace(/{Ddescuento}/g, Number(descuento).toFixed(2));
    linea = linea.replace(/{Dtotal}/g, Number(total - descuento).toFixed(2));
    linea = linea.replace(/{Dtotal2}/g, Number(total).toFixed(2));
    $("#cargarItemFactura tbody").append(linea);
    id_item = id_item + 1;
   
   
    cargarTotales(iva, total, descuento);
   
    resetearCampos();

}

function cargarTotales(iva, total, descuento) {
    var subtotal = Number(Number(document.getElementById("subtotal").innerHTML) + total).toFixed(2);
    document.getElementById("subtotal").innerHTML = subtotal;
    document.getElementById("idSubtotal").value = subtotal;
  
    var tarifa12 = Number(Number(document.getElementById("tarifa12").innerHTML) + total - descuento).toFixed(2);
    var tarifa0 = Number(Number(document.getElementById("tarifa0").innerHTML) + total - descuento).toFixed(2);
   
    var descuento = Number(Number(document.getElementById("descuento").innerHTML) + descuento).toFixed(2);
    document.getElementById("descuento").innerHTML = descuento;
    document.getElementById("idDescuento").value = descuento;
    
    if (iva == "SI") {
        document.getElementById("tarifa12").innerHTML = tarifa12;
        document.getElementById("idTarifa12").value = tarifa12;
    } else {
        document.getElementById("tarifa0").innerHTML = tarifa0;
        document.getElementById("idTarifa0").value = tarifa0;
    }
  
    calcularTotales();
    resetearCampos();
}

function calcularTotales() {
    var iva = Number(Number(document.getElementById("tarifa12").innerHTML) * porcentajeIva).toFixed(2); 
    document.getElementById("iva").innerHTML = iva; 
    document.getElementById("idIva").value = iva;
    var total = Number(Number(document.getElementById("tarifa12").innerHTML) + Number(document.getElementById("tarifa0")
        .innerHTML) + Number(document.getElementById("iva").innerHTML)).toFixed(2);
    document.getElementById("total").innerHTML = total;
    
    document.getElementById("idTotal").value = total;
    document.getElementById("idTotalFactura").value = total;
}

function resetearCampos() {
    document.getElementById("id_cantidad").value = 1;
    document.getElementById("codigoProducto").value = "";
    document.getElementById("idProductoID").value = "";
    document.getElementById("buscarProducto").value = "";
    document.getElementById("id_disponible").value = "0";
    document.getElementById("id_pu").value = "0.00";
    document.getElementById("id_descuento").value = "0.00";
    document.getElementById("id_total").value = "0.00";
    document.getElementById("tieneIva").checked=false;
}

function eliminarItem(id, iva, total, descuento) {
    cargarTotales(iva, total * (-1), descuento * (-1));
    $("#row_" + id).remove();

}

function calcularTotal() {
    document.getElementById("buscarProducto").classList.remove('is-invalid');
    document.getElementById("errorStock").classList.add('invisible');
    if (parseFloat(document.getElementById("id_cantidad").value) > parseFloat(document.getElementById("id_disponible")
            .value)) {
        document.getElementById("id_cantidad").value = 1;
        document.getElementById("buscarProducto").classList.add('is-invalid');
        document.getElementById("errorStock").classList.remove('invisible');
    }
    document.getElementById("id_total").value = Number(document.getElementById("id_cantidad").value * document
        .getElementById("id_pu").value).toFixed(2);
}

function seleccionarIva() {
    var combo = document.getElementById("factura_porcentaje_iva");
    porcentajeIva = combo.options[combo.selectedIndex].text;
    porcentajeIva = parseFloat(porcentajeIva) / 100;
    document.getElementById("porcentajeIva").innerHTML = "Tarifa " + combo.options[combo.selectedIndex].text;
    document.getElementById("iva12").innerHTML = "Iva " + combo.options[combo.selectedIndex].text;
}

function calcularFecha() {
    let hoy = new Date();
    let semMiliSeg = 1000 * 60 * 60 * 24 * document.getElementById("factura_dias_plazo").value;
    let suma = hoy.getTime() + semMiliSeg;
    let fecha = new Date(suma);
    document.getElementById("factura_fecha_termino").value = fecha.getFullYear() + '-' + ponerCeros(fecha.getMonth() +
        1) + '-' + ponerCeros(fecha.getDate());
}

function ponerCeros(num) {
    num = num + '';
    while (num.length <= 1) {
        num = '0' + num;
    }
    return num;
}
</script>
@endsection
