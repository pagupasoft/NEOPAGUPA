@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Bodega</h3>
        <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Nombre de Bodega</th>
                    <th>Descripcion</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Fax</th>
                    <th>Ciudad</th>
                    <th>Sucursal</th>                    
                </tr>
            </thead> 
            <tbody>
                @foreach($bodegas as $bodega)
                <tr class="text-center">
                    <td>
                        <a href="{{ url("bodega/{$bodega->bodega_id}/edit") }}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="{{ url("bodega/{$bodega->bodega_id}") }}" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="top" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{ url("bodega/{$bodega->bodega_id}/eliminar") }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td>{{ $bodega->bodega_nombre}}</td>
                    <td>{{ $bodega->bodega_descripcion}}</td>
                    <td>{{ $bodega->bodega_direccion}}</td>  
                    <td>{{ $bodega->bodega_telefono}}</td>  
                    <td>{{ $bodega->bodega_fax}}</td>  
                    <td>{{ $bodega->ciudad->ciudad_nombre}}</td> 
                    <td>{{ $bodega->sucursal->sucursal_nombre}}</td>                                     
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
<div class="modal fade" id="modal-nuevo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">Nueva Bodega</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("bodega") }}">
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="bodega_nombre" class="col-sm-3 col-form-label">Nombre de Bodega</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="bodega_nombre" name="bodega_nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bodega_descripcion" class="col-sm-3 col-form-label">Descripcion</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="bodega_descripcion" name="bodega_descripcion" placeholder="Descripcion"  required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bodega_direccion" class="col-sm-3 col-form-label">Direccion</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="bodega_direccion" name="bodega_direccion" placeholder="Direccion" value="S/D" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bodega_telefono" class="col-sm-3 col-form-label">Telefono</label>
                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="bodega_telefono" name="bodega_telefono" placeholder="02345678" value="0" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bodega_fax" class="col-sm-3 col-form-label">Fax</label>
                            <div class="col-sm-9">
                                <input type="tel" class="form-control" id="bodega_fax" name="bodega_fax" placeholder="234511678" value="0" required>
                            </div>
                        </div>                                      
                        <div class="form-group row">
                            <label for="ciudad_id" class="col-sm-3 col-form-label">Ciudad</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="ciudad_id" name="ciudad_id" required>
                                    <option value="" label>--Seleccione una opcion--</option>
                                    @foreach($ciudades as $ciudad)
                                        <option value="{{$ciudad->ciudad_id}}">{{$ciudad->ciudad_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sucursal_id" class="col-sm-3 col-form-label">Sucursal</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="sucursal_id" name="sucursal_id" require>
                                    <option value="" label>--Seleccione una opcion--</option>
                                    @foreach($sucursales as $sucursal)
                                        <option value="{{$sucursal->sucursal_id}}">{{$sucursal->sucursal_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection