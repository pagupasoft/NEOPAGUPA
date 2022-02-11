@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST"  action="{{ url("actualizarCostos") }} " >
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Actualizar Costos</h3>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="fecha_desde" class="col-sm-1 col-form-label">Desde : </label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"  value='<?php if(isset($fecI)){echo $fecI;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>'>
                </div>
                <label for="fecha_desde" class="col-sm-1 col-form-label">Hasta : </label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"  value='<?php if(isset($fecF)){echo $fecF;}else{ echo(date("Y")."-".date("m")."-".date("d"));} ?>'>
                </div>
                <div class="col-sm-1">
                    <button type="submit" id="actualizar" name="Actualizar" class="btn btn-success" onclick="validacion();"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection