@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Ver Grupo de Activo</h3>
     <!--  <button onclick='window.location = "{{ url("grupoActivo") }}";' class="btn btn-default btn-sm float-right"><i class="fa fa-undo"></i>&nbsp;Atras</button>
        -->    
        <button  type="button" onclick="history.back()" class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>     
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Sucursal</label>
            <div class="col-sm-10">
                <label class="form-control">{{$grupoActivo->sucursal->sucursal_nombre}}</label>
            </div>
        </div>        
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-10">
                <label class="form-control">{{$grupoActivo->grupo_nombre}}</label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Porcentaje</label>
            <div class="col-sm-10">
                <label class="form-control">{{$grupoActivo->grupo_porcentaje}}%</label>
            </div>
        </div>             
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Cuenta Gasto</label>
            <div class="col-sm-10">
                <label class="form-control">{{$grupoActivo->cuentaGasto->cuenta_numero.'  - '.$grupoActivo->cuentaGasto->cuenta_nombre}}</label>
            </div>
        </div> 
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Cuenta Depreciacion</label>
            <div class="col-sm-10">
                <label class="form-control">{{$grupoActivo->cuentaDepreciacion->cuenta_numero.'  - '.$grupoActivo->cuentaDepreciacion->cuenta_nombre}}</label>
            </div>
        </div>  
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Estado</label>
            <div class="col-sm-10">
                @if($grupoActivo->grupo_estado=="1")
                    <i class="fa fa-check-circle neo-verde"></i>
                @else
                    <i class="fa fa-times-circle neo-rojo"></i>
                @endif
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection