@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Departamento</h3>
        <button onclick='window.location = "{{ url("departamento") }}";' class="btn btn-default btn-sm float-right"><i class="fa fa-undo"></i>&nbsp;Atras</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-10">
                <label class="form-control">{{$departamento->departamento_nombre}}</label>
            </div>
        </div>
        <div class="form-group row">
            <label for="sucursal_id" class="col-sm-2 col-form-label">Sucursal</label>
            <div class="col-sm-10">                        
                <label class="form-control">{{$departamento->sucursal->sucursal_nombre}}</label>                          
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-10">
                @if($departamento->departamento_estado=="1")
                    <i class="fa fa-check-circle neo-verde"></i>
                @else
                    <i class="fa fa-times-circle neo-rojo"></i>
                @endif
            </div>
        </div>                
        <!-- /.card-footer -->
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection