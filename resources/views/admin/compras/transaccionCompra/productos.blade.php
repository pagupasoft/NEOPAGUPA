@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ url("producto/compra") }}">
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Configurar Codigos de Producto</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button type="button" onclick='window.location = "{{ url("producto") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </div>
        </div>
       
            <div class="form-group row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                    <div id="mien" class="table-responsive">
                        <table id="cargarItemPrecio" class="table table-striped table-hover" style="margin-bottom: 6px;">
                            <thead>
                                <tr class="letra-blanca fondo-azul-claro text-center">                                                
                                    <th>Codigo</th>
                                    <th>Proveedor</th> 
                                                                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; ?>
                                @if(isset($matriz))
                                    @for ($i = 1; $i <= count($matriz); ++$i)
                                    <tr class="text-center" >
                                        <td>{{ $matriz[$i]}}<input class="invisible" name="DLdias[]" value="{{ $matriz[$i] }}"/></td>
                                        <td><input type="text" class="form-control centrar-texto" name="DLvalor[]" value=""/>  <input class="invisible" name="idpr[]" value=""/></td>      
                                        
                                    </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
               
    </div>
</form>

<script type="text/javascript">
    
</script>
@endsection