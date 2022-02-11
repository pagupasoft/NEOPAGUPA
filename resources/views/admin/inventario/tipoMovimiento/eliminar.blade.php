@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">¿Esta seguro de eliminar este Tipo de Movimiento de Egreso?</h3>
        <div class="float-right">
            <form class="form-horizontal" method="POST" action="{{ route('tipoMovimiento.destroy', [$tipo->tipo_id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Eliminar</button>
                <button type="button" onclick='window.location = "{{ url("tipoMovimiento") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Sucursal</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$tipo->sucursal->sucursal_nombre}}</label>
                </div>
            </div> 
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nombre de Movimiento</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$tipo->tipo_nombre}}</label>
                </div>
            </div>       
            @if(Auth::user()->empresa->empresa_contabilidad == '1')
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Cuenta Contable</label>
                <div class="col-sm-10">                        
                    <label class="form-control">{{$tipo->cuenta->cuenta_numero.' - '.$tipo->cuenta->cuenta_nombre}}</label>                          
                </div>
            </div>     
            @endif        
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    @if($tipo->tipo_estado=="1")
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