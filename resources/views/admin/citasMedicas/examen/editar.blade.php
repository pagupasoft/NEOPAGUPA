@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ url('ordenExamen') }}/{{ $ordenExamen->orden_id }}/editar">

@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Editar Examen</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button type="button" onclick='history.back();' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($tipoExamenes as $tipoExamen)
                <div class="col-sm-3">
                    <label for="conciencia" class="col-sm-7 col-form-label mayus" value="{{$tipoExamen->tipo_id}}">{{$tipoExamen->tipo_nombre}}</label>
                    @foreach($examenes as $exams)
                        @if($tipoExamen->tipo_id == $exams->tipo_id)
                            <div class="form-group row">
                                <div class="col-sm-1">
                                    <div class="form-check ">
                                        <input 
                                            style="width:20px; height:20px;" 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="laboratorio[]" 
                                            id="laboratorio{{ $exams->examen_id }}" 
                                            value="{{$exams->examen_id}}"

                                            @foreach($examen as $exa)
                                                @if($exa->examen_id==$exams->examen_id) 
                                                    checked
                                                @endif
                                            @endforeach
                                        >
                                        <input class="invisible"  value="{{$exams->examen_id}}" >
                                    </div>
                                </div>
                                <label for="laboratorio{{ $exams->examen_id }}" class="col-sm-7"  value="{{$exams->producto_id}}">{{$exams->producto_nombre}}</label>
                            </div>
                        @endif 
                    @endforeach
                </div>     
                @endforeach 
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label for="observacion" class="col-sm-5 col-form-label">Otros:</label>
                        <textarea class="form-control" id="otros_examenes"   name="otros_examenes" >{{ $ordenExamen->orden_otros }}</textarea>
                    </div>  
                </div>  
            </div>
        </div>            
    </div>
</form>
@endsection