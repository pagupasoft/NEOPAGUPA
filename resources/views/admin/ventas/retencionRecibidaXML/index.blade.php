@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Cargar Retenciones Recibidas</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ url("retencionRecibidaXML") }} " enctype="multipart/form-data"> 
        @csrf
            <div class="form-group row">
                <label for="idDescripcion" class="col-sm-1 col-form-label"><center>Archivo SRI : </center></label>
                <div class="col-sm-10">
                    <input type="file" id="file_sri" name="file_sri" class="form-control" required/>               
                </div>
                <div class="col-sm-1">
                    <center><button type="submit" class="btn btn-primary"><i class="fa fa-spinner"></i>&nbsp;&nbsp;Procesar</button></center>
                </div>
            </div>             
        </form>
        <hr>
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Numero</th>
                    <th>Clave de Acceso</th>
                </tr>
            </thead>
            <tbody> 
                @if(isset($datos))
                    @for ($i = 1; $i <= count($datos); ++$i)   
                    <tr class="text-center">
                        <td>
                        @if($datos[$i]['estado'] == 'cargada')
                            <i class="fa fa-circle neo-azul"></i>
                        @endif
                        @if($datos[$i]['estado'] == 'si')
                            <i class="fa fa-check neo-verde"></i>
                        @endif
                        @if($datos[$i]['estado'] == 'no')
                            <i class="fa fa-times neo-rojo"></i>
                        @endif
                        </td>
                        <td>{{ $datos[$i]['cliente'] }}</td>
                        <td>{{ $datos[$i]['fecha'] }}</td>
                        <td>{{ $datos[$i]['numero'] }}</td>
                        <td>{{ $datos[$i]['clave'] }}</td>
                    </tr>   
                    @endfor
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection
