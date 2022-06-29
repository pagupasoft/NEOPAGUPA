@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Ordenes de Mantenimiento</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">  
                    <th></th>
                    <th>Fecha</th>
                    <th>Numero</th>
                    <th>Tipo</th>  
                    <th>Cliente</th>
                    <th>Estado</th>
                </tr>
            </thead>            
            <tbody>
                @foreach($ordenes as $orden)
                <tr class="text-center">
                    <td>
                        @if($orden->orden_estado==1)
                            <a href="{{ url("orden/{$orden->orden_id}/comprobarStock") }}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Comprobar Stock"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                    </td>
                    
                    <td>{{ $orden->orden_fecha_inicio}}</td>
                    <td>{{ $orden->orden_id}}</td> 
                    <td>{{ $orden->tipo->tipo_nombre}}</td> 
                    <td>{{ $orden->cliente->cliente_nombre}}</td>
                    <td>
                        @if($orden->orden_estado==0)  ANULADA @endif
                        @if($orden->orden_estado==1)  CREADA @endif
                        @if($orden->orden_estado==2)  GENERADA @endif
                        @if($orden->orden_estado==3)  EN PROCESO @endif
                        @if($orden->orden_estado==4)  FINALIZADA @endif
                    </td>
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
                <h4 class="modal-title">Nuevo Banco</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("banco") }}">
                @csrf
                <div class="modal-body">
                    <div class="card-body">                        
                        <div class="form-group row">
                            <label for="idLista" class="col-sm-3 col-form-label">Nombre</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="idLista" name="idLista" require>
                                    
                                </select>
                            </div>
                        </div>
                            <div class="form-group row">
                                <label for="idDireccion" class="col-sm-3 col-form-label">Direccion</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="idDireccion" name="idDireccion" placeholder="Direccion" value="S/D" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="idTelefono" class="col-sm-3 col-form-label">Telefono</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="idTelefono" name="idTelefono" placeholder="Telefono" value="0" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="idEmail" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="idEmail" name="idEmail" placeholder="SIN@CORREO" value="SIN@CORREO" required>
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
   
</div>
<!-- /.modal -->
@endsection