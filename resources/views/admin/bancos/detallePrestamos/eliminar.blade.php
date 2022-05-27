@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">¿Esta seguro de eliminar el Interes?</h3>
        <div class="float-right">
            <form class="form-horizontal" method="POST" action="{{ route('detalleprestamos.destroy', [$detalle->detalle_id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Eliminar</button>
                <button type="button" onclick='window.location = "{{ url("detalleprestamos/{$detalle->prestamo_id}") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <h5 class="form-control" style="color:#fff; background:#17a2b8;">Datos del Prestamo</h5>
        <div class="card-body">
        
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Banco</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$detalle->prestamo->banco->bancoLista->banco_lista_nombre }}</label>
                </div>
            </div>  
            <div class="form-group row">
                <label for="pais_id" class="col-sm-2 col-form-label">Monto</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$detalle->prestamo->prestamo_monto}}</label>                          
                </div>
            </div>
            <div class="form-group row">
                <label for="pais_id" class="col-sm-2 col-form-label">Fecha Finalizacion</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$detalle->prestamo->prestamo_fin}}</label>                          
                </div>
            </div>
           
            <div class="form-group row">
                <label for="ciudad_id" class="col-sm-2 col-form-label">Interes</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{ $detalle->prestamo->prestamo_interes }}</label>                          
                </div>
            </div>
                     
        </div>
        <h5 class="form-control" style="color:#fff; background:#17a2b8;">Datos del Interes</h5>
        <div class="card-body">
        
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$detalle->prestamo->banco->bancoLista->banco_lista_nombre }}</label>
                </div>
            </div>  
            <div class="form-group row">
                <label for="pais_id" class="col-sm-2 col-form-label">Monto</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$detalle->prestamo->prestamo_monto}}</label>                          
                </div>
            </div>
            <div class="form-group row">
                <label for="pais_id" class="col-sm-2 col-form-label">Fecha Finalizacion</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$detalle->detalle_fecha}}</label>                          
                </div>
            </div>
           
            <div class="form-group row">
                <label for="ciudad_id" class="col-sm-2 col-form-label">Interes</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{ $detalle->detalle_total }}</label>                          
                </div>
            </div>
                     
        </div>
        <!-- /.card-body -->    
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection