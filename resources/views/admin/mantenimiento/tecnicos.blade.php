@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Técnicos de Mantenimiento</h3>
        <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">  
                    <th></th>
                    <th>Nombres</th>
                    <th>Correo</th>
                    <th>Teléfono</th>  
                    <th>Estado</th>
                </tr>
            </thead>            
            <tbody>
                @foreach($tecnicos as $tecnico)
                <tr class="text-center">
                    <td>
                        
                    </td>
                    
                    <td>{{ $tecnico->empleado->empleado_nombre }}</td>
                    <td>{{ $tecnico->user->user_mail }}</td> 
                    <td>{{ $tecnico->empleado->empleado_telefono }}</td> 
                    <!--td>{{-- $tecnico->user->roles->rol_nombre --}}</td-->
                    <td>@if($tecnico->empleado->empleado_estado==1) ACTIVO @else INACTIVO @endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<div class="modal fade" id="modal-nuevo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">Nuevo Técnico</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("agregartecnicomantenimiento") }}">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="empleado_id" class="col-sm-2 col-form-label">Empleado</label>
                            <div class="col-sm-8">
                                <select class="custom-select select2" id="empleado_id" name="empleado_id" required>
                                    <option value="" label>--Seleccione una opcion--</option>                                    
                                    @foreach($empleados as $empleado)                                                                                      
                                        <option value="{{$empleado->empleado_id}}">{{$empleado->empleado_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="usuario_id" class="col-sm-2 col-form-label">Usuario</label>
                            <div class="col-sm-8">
                                <select class="custom-select select2" id="usuario_id" name="usuario_id" required>
                                    <option value="" label>--Seleccione una opcion--</option>
                                    @foreach($usuarios as $usuario)
                                        <?php $existe = false ?>
                                        @foreach($tecnicos as $tecnico)
                                            @if($tecnico->user->user_id == $usuario->user_id)
                                                <?php $existe = true ?>
                                            @endif
                                        @endforeach
                                        @if(!$existe)
                                            <option value="{{$usuario->user_id}}">{{$usuario->user_username}}</option>
                                        @endif
                                    @endforeach
                                </select>
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
@endsection