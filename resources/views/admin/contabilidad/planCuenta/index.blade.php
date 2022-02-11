@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Plan de Cuentas</h3>
        <div class="float-right">
            <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
            <a class="btn btn-success btn-sm" href="{{ url("excelCuenta")}}"><i class="fas fa-file-excel"></i>&nbsp;Cargar Excel</a>  
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       <!--- <ul id="tree2">
            <?php //echo $arbol; ?>
        </ul>-->
    <HR>
        <table id="example4" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Cuenta</th>       
                </tr>
            </thead> 
            <tbody>
                @foreach($cuentas as $cuenta)
                <tr>
                    <td >
                        <a href="{{ url("cuenta/{$cuenta->cuenta_id}/edit") }}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Ediar"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="{{ url("cuenta/{$cuenta->cuenta_id}/eliminar") }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        @if($cuenta->detallescontable == 0) <a href="{{ url("cuenta/{$cuenta->cuenta_id}/subcuenta") }}" class="btn btn-xs btn-secondary"  data-toggle="tooltip" data-placement="top" title="Añadir Cuenta"><i class="fa fa-tasks" aria-hidden="true"></i></a>@endif
                    </td>
                    <td class="espacio{{$cuenta->cuenta_nivel}}">{{ $cuenta->cuenta_numero.'  - '.$cuenta->cuenta_nombre}}</td>                                 
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
                <h4 class="modal-title">Nueva Cuenta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("cuenta") }} ">
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="cuenta_nivel" class="col-sm-3 col-form-label">Nivel</label>
                            <div class="col-sm-9">
                                <input type="hidden"id="cuenta_nivel" name="cuenta_nivel" value="1">
                                <label class="form-control">1</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cuenta_numero" class="col-sm-3 col-form-label">Numero</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="cuenta_numero" name="cuenta_numero" placeholder="Ej. 1.1.1.1" value="{{ $secuencial }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cuenta_nombre" class="col-sm-3 col-form-label">Nombre</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="cuenta_nombre" name="cuenta_nombre" placeholder="Nombre" required>
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