@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST"  action="{{ url("producto/compra") }} ">
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Configurar Codigos de Producto</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="producto_nombre" class="col-sm-2 col-form-label">Proveedor: </label>
                <div class="col-sm-9">
                    <input type="hidden" id="idproveedor" name="idproveedor" value="{{$poveedorXML->proveedor_id}}" required>
                    <input type="hidden" id="punto" name="punto" value="{{$punto}}" required>
                    <input type="hidden" id="clave" name="clave" value="{{$clave}}" required>
                    <label for="dias" class="col-form-label">{{$poveedorXML->proveedor_nombre}} </label>
                </div>
            </div>  
            <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                    <div id="mien" class="table-responsive">
                        <table id="cargarItemPrecio" class="table table-striped table-hover" style="margin-bottom: 6px;">
                            <thead>
                                <tr class="letra-blanca fondo-azul-claro text-center">                                                
                                    <th>Codigo Proveedor</th>
                                    <th>Descripcion Proveedor</th>
                                    <th>Codigo</th> 
                                    <th>Descripcion</th>                                        
                                </tr>
                            </thead>
                            <tbody>
                               
                                @if(isset($datos))
                                    @for($i = 1; $i <= count($datos); ++$i)
                                    <tr class="text-center">
                                        <td>{{$datos[$i]['codigo']}}<input class="invisible" name="DLdias[]" value="{{ $datos[$i]['codigo']}}"/></td>
                                        <td>{{$datos[$i]['descripcion']}}</td>
                                        <td>
                                            <select class="custom-select select2" id="productos{{$i}}" name="productos[]" onchange="ShowSelected({{$i}});" >
                                                <option value="" label>--Seleccione una opcion--</option>
                                                    @foreach($productos as $producto)
                                                        <option value="{{$producto->producto_id}}" >{{$producto->producto_nombre}}</option>
                                                    @endforeach
                                            </select>
                                        </td>   
                                        <td><input id="codigoProducto{{$i}}" name="codigoProducto[]" class="form-control" readonly>
                                        </td> 
                                    </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>      
    </div>
</form>


<script type="text/javascript">
function ShowSelected(ide)
{
    var prod="productos"+ide;
    var cod="codigoProducto"+ide;
    document.getElementById(cod).value="";
    $.ajax({
        url: '{{ url("buscarProducto/searchN") }}' + '/' + document.getElementById(prod).value,
        dataType: "json",
        type: "GET",
        async: false,
        data: {
            buscar: document.getElementById(prod).value
        },
        success: function(data){
            
            for (var i=0; i<data.length; i++) {
                document.getElementById(cod).value = data[i].producto_codigo;
            }           
        },
    });
}
</script>
@endsection