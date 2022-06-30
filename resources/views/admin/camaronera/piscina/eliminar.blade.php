@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">¿Esta seguro de eliminar la Piscina?</h3>
        <div class="float-right">
            <form class="form-horizontal" method="POST" action="{{ route('piscina.destroy', [$piscina->piscina_id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Eliminar</button>
                <button type="button" onclick='window.location = "{{ url("piscina") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">   
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tipo</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_tipo}}</label>
                </div>
            </div>         
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Codigo</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_codigo}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_nombre}}</label>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Largo</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_largo}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ancho</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_ancho}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Altura Maxima de Colummna Agua</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_columna_agua}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Area Espejo Agua</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_espejo_agua}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Volumen Agua</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_volumen_agua}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Declinacion</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_declinacion}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Numero Entradas Agua</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_entrada_agua}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Numero Salidas Agua</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_salida_agua}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$piscina->piscina_tipo_estado}}</label>
                </div>
            </div>
        </div>        
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
@endsection