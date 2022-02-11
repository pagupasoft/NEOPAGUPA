@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Punto de Emision</h3>
        <button onclick='window.location = "{{ url("puntoEmision") }}";' class="btn btn-default btn-sm float-right"><i class="fa fa-undo"></i>&nbsp;Atras</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sucursal</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$puntoEmision->sucursal_codigo}} - {{$puntoEmision->sucursal_nombre}}</label>
                </div>
            </div>    
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Código</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$puntoEmision->punto_serie}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Descripcion</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$puntoEmision->punto_descripcion}}</label>
                </div>
            </div>             
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    @if($puntoEmision->punto_estado=="1")
                    <i class="fa fa-check-circle neo-verde"></i>
                    @else
                    <i class="fa fa-times-circle neo-rojo"></i>
                    @endif
                </div>
            </div>
           
        </div>
        <!-- /.card-body -->        
        <!-- /.card-footer -->
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection