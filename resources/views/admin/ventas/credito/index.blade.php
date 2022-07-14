@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Credito</h3>
        <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Monto</th>                         
                </tr>
            </thead> 
            <tbody>
                @foreach($creditos as $credito)
                <tr class="text-center">
                    <td>
                        <a href="{{ url("credito/{$credito->credito_id}/edit") }}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="{{ url("credito/{$credito->credito_id}") }}" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="top" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{ url("credito/{$credito->credito_id}/eliminar") }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td>{{ $credito->credito_nombre}}</td>
                    <td>{{ $credito->credito_descripcion }}</td> 
                    <td>{{ $credito->credito_monto }}</td>  
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
                <h4 class="modal-title">Nuevo Credito</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("credito") }}">
                @csrf
                <div class="modal-body">
                    <div class="card-body">                        
                        <div class="form-group row">
                            <label for="credito_nombre" class="col-sm-3 col-form-label">Nombre</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="credito_nombre" name="credito_nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="credito_descripcion" class="col-sm-3 col-form-label">Descripcion</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="credito_descripcion" name="credito_descripcion" placeholder="Ingrese aqui una descripcion" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="credito_monto" class="col-sm-3 col-form-label">Monto</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="credito_monto" name="credito_monto" step="00.0001" placeholder="00.0000" required>
                            </div>
                        </div>
                    </div>                    
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