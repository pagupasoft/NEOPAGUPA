@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Valor de Venta</h3>
        <!-- <button onclick='window.location = "{{ url("ventaActivo") }}";' class="btn btn-default btn-sm float-right"><i class="fa fa-undo"></i>&nbsp;Atras</button>
         --> 
         <button  type="button" onclick="history.back()" class="btn btn-default btn-sm float-right"><i class="fa fa-undo"></i>&nbsp;Atras</button>     
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <div class="form-group row">
                <label class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$ventaActivo->venta_fecha}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$ventaActivo->venta_descripcion}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Valor</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$ventaActivo->venta_monto}}</label>
                </div>
            </div>                                                 
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    @if($ventaActivo->venta_estado=="1")
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