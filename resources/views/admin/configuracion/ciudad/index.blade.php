@extends ('admin.layouts.admin')
@section('principal')

<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Ciudad</h3>
        <div class="float-right">
        <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        <a class="btn btn-success btn-sm" href="{{ url("excelCiudad") }}"><i class="fas fa-file-excel"></i>&nbsp;Cargar Excel</a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Nombre de Ciudad</th>
                    <th>Codigo de Ciudad</th>
                    <th>Provincia</th>
                </tr>
            </thead> 
            <tbody>
                @foreach($ciudades as $ciudad)
                <tr class="text-center">
                    <td>
                        <a href="{{ url("ciudad/{$ciudad->ciudad_id}/edit") }}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Ediar"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="{{ url("ciudad/{$ciudad->ciudad_id}") }}" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="top" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{ url("ciudad/{$ciudad->ciudad_id}/eliminar") }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td>{{ $ciudad->ciudad_nombre}}</td>
                    <td>{{ $ciudad->ciudad_codigo}}</td>      
                    <td>{{ $ciudad->provincia->provincia_nombre }}</td>                                
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
                <h4 class="modal-title">Nueva Ciudad</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST"  action="{{ url("ciudad") }} " >
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="ciudad_nombre" class="col-sm-3 col-form-label">Nombre de Ciudad</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="ciudad_nombre" name="ciudad_nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ciudad_codigo" class="col-sm-3 col-form-label">Codigo de Ciudad</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="ciudad_codigo" name="ciudad_codigo" placeholder="Ej. 100001" required>
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label for="provincia_id" class="col-sm-3 col-form-label">Nombre de Provincia</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="provincia_id" name="provincia_id" require>
                                    @foreach($provincias as $provincia)
                                        <option value="{{$provincia->provincia_id}}">{{$provincia->provincia_nombre}}</option>
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