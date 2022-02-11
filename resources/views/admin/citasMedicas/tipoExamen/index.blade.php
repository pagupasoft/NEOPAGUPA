@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Tipo Examen</h3>
        <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nombre</th>
                    <th>Tipo Muestra</th>
                    <th>Tipo Recipiente</th>                                           
                </tr>
            </thead> 
            <tbody>
                @foreach($tiposExamen as $tipoExamen)
                <tr class="text-center neo-fondo-tabla">
                    <td>
                        <a href="{{ url("tipoExamen/{$tipoExamen->tipo_id}/edit")}}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Ediar"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="{{ url("tipoExamen/{$tipoExamen->tipo_id}")}}" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="top" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{ url("tipoExamen/{$tipoExamen->tipo_id}/eliminar")}}" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td>{{ $tipoExamen->tipo_nombre}}</td>            
                    <td>{{ $tipoExamen->tipomuestra->tipo_nombre}}</td>    
                    <td>{{ $tipoExamen->tiporecipiente->tipo_nombre}}</td>                             
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
                <h4 class="modal-title">Nuevo Tipo Examen</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("tipoExamen") }}">
                @csrf
                <div class="modal-body">
                    <div class="card-body">                        
                        <div class="form-group row">
                            <label for="tipo_nombre" class="col-sm-3 col-form-label">Nombre</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="tipo_nombre" name="tipo_nombre" placeholder="Tipo" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tipo_muestra" class="col-sm-3 col-form-label">Tipo de muestra</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="tipo_muestra" name="tipo_muestra" required>
                                    <option value="" label>--Seleccione una opcion--</option>                                    
                                    @foreach($tipomuestras as $tipomuestra)                                                                                      
                                        <option value="{{ $tipomuestra->tipo_muestra_id}}">{{ $tipomuestra->tipo_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>           
                        
                        
                        <div class="form-group row">
                            <label for="tipo_recipiente" class="col-sm-3 col-form-label">Tipo de recipiente</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="tipo_recipiente" name="tipo_recipiente" required>
                                    <option value="" label>--Seleccione una opcion--</option>                                    
                                    @foreach($tiporecipientes as $tiporecipiente)                                                                                      
                                        <option value="{{ $tiporecipiente->tipo_recipiente_id}}">{{ $tiporecipiente->tipo_nombre}}</option>
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