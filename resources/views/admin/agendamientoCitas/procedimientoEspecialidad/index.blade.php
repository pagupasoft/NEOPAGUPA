@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Procedimiento Especialidad</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">  
                    <th></th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Valor</th>
                    <th>Dscripcion</th>                                                              
                </tr>
            </thead>            
            <tbody>            
                @foreach($productos as $producto)
                <tr class="text-center">
                    <td>
                        <a href="{{ url("procedimientoEspecialidad/{$producto->producto_id}/especialidad")}}" class="btn btn-xs btn-secondary"  data-toggle="tooltip" data-placement="top" title="Asignar Especialidades"><i class="fa fa-tasks" aria-hidden="true"></i></a>
                    </td>
                    <td>{{ $producto->producto_codigo }}</td>
                    <td>{{ $producto->producto_nombre }}</td>
                    <td><?php echo '$' . number_format($producto->producto_precio_costo, 2)?></td>
                    <td>@if($producto->producto_tipo == '1') ARTICULO @else SERVICIO  @endif</td>                                                      
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
@endsection