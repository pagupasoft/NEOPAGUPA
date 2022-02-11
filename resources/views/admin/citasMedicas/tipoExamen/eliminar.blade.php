@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">¿Esta seguro de eliminar este tipo examen?</h3>
        <div class="float-right">
            <form class="form-horizontal" method="POST" action="{{ route('tipoExamen.destroy', [$tipoExamen->tipo_id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;Eliminar</button>
                <button type="button" onclick='window.location = "{{ url("tipoExamen") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </form>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <label class="form-control">{{$tipoExamen->tipo_nombre}}</label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tipo de Muestra</label>
                <div class="col-sm-10">
                    @foreach($tipomuestras as $tipomuestra)
                        @if($tipomuestra->tipo_muestra_id == $tipoExamen->tipo_muestra_id)
                            <label class="form-control">{{$tipomuestra->tipo_nombre}}</label>                     
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tipo de Recipiente</label>
                <div class="col-sm-10">
                    @foreach($tiporecipientes as $tiporecipiente)
                        @if($tiporecipiente->tipo_recipiente_id == $tipoExamen->tipo_recipiente_id)
                            <label class="form-control">{{$tiporecipiente->tipo_nombre}}</label>                     
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    @if($tipoExamen->tipo_estado=="1")
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