@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">¿Esta seguro de eliminar esta Bodeguero?</h3>
        <div class="float-right">
            <form class="form-horizontal" method="POST" action="{{ route('bodeguero.destroy', [$bodeguero->bodeguero_id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Eliminar</button>
                <!-- 
                <button type="button" onclick='window.location = "{{ url("bodeguero") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
                --> 
                <button  type="button" onclick="history.back()" class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="card-body">
        <div class="form-group row">
                <label class="col-sm-2 col-form-label">Cedula</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$bodeguero->bodeguero_cedula}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$bodeguero->bodeguero_nombre}}</label>
                </div>
            </div>            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Direccion</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$bodeguero->bodeguero_direccion}}</label>                          
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Telefono</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$bodeguero->bodeguero_telefono}}</label>                          
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$bodeguero->bodeguero_email}}</label>                          
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Fecha Ingreso</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$bodeguero->bodeguero_fecha_ingreso}}</label>                          
                </div>
            </div>
            @if($bodeguero->bodeguero_fecha_salida!=Null)
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Fecha Salida</label>
                    <div class="col-sm-10">                        
                        <label class="form-control">{{$bodeguero->bodeguero_fecha_salida}}</label>                          
                    </div>
                </div>
            @endif  
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Bodega</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$bodeguero->bodega->bodega_nombre}}</label>                          
                </div>
            </div> 
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    @if($bodeguero->bodeguero_estado=="1")
                        <i class="fa fa-check-circle neo-verde"></i>
                    @else
                        <i class="fa fa-times-circle neo-rojo"></i>
                    @endif
                </div>
            </div>            
        </div>
        <!-- /.card-body -->    
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection