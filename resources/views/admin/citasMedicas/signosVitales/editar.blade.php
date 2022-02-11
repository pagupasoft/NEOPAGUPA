@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ route('signosVitales.update', [$signoVital->signo_id]) }}">
@method('PUT')
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Editar Signos Vitales</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button type="button" onclick='window.location = "{{ url("signosVitales") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha:</label>
                <div class="col-sm-4">    
                    @foreach($expedientes as $expediente)
                        @if($expediente->expediente_id == $signoVital->expediente_id)
                            @foreach($ordenesAtencion as $ordenA)
                                @if($ordenA->orden_id == $expediente->orden_id)
                                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{$ordenA->orden_fecha}}" readonly>              
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
                <label for="hora" class="col-sm-2 col-form-label">Hora:</label>
                <div class="col-sm-4">                                                          
                    @foreach($expedientes as $expediente)
                        @if($expediente->expediente_id == $signoVital->expediente_id)
                            @foreach($ordenesAtencion as $ordenA)
                                @if($ordenA->orden_id == $expediente->orden_id)
                                    <input type="time" class="form-control" id="hora" name="hora" value="{{$ordenA->orden_hora}}" readonly>                                    
                                @endif
                            @endforeach
                        @endif
                    @endforeach           
                </div>
            </div>
            <div class="form-group row">
                <label for="medico" class="col-sm-2 col-form-label">Médico tratante:</label>
                <div class="col-sm-10"> 
                    @foreach($expedientes as $expediente)
                        @if($expediente->expediente_id == $signoVital->expediente_id)
                            @foreach($ordenesAtencion as $ordenA)
                                @if($ordenA->orden_id == $expediente->orden_id)
                                    @if($ordenA->empleado_id != null )
                                        @foreach($empleados as $empleado)
                                            @if($ordenA->empleado_id == $empleado->empleado_id)
                                                <input type="text" class="form-control" id="medico" name="medico"  placeholder="Médico" value="{{$empleado->empleado_nombre}}" readonly>                  
                                            @endif
                                        @endforeach
                                    @elseif($ordenA->proveedor_id != null )
                                        @foreach($proveedores as $proveedor)
                                            @if($ordenA->proveedor_id == $proveedor->proveedor_id)
                                                <input type="text" class="form-control" id="medico" name="medico"  placeholder="Médico" value="{{$proveedor->proveedor_nombre}}" readonly>                      
                                            @endif
                                        @endforeach
                                    @endif 
                                @endif
                            @endforeach
                        @endif
                    @endforeach  
                </div>
            </div>
            <div class="form-group row">
                <label for="medico" class="col-sm-2 col-form-label">Paciente:</label>
                <div class="col-sm-10"> 
                    @foreach($expedientes as $expediente)
                        @if($expediente->expediente_id == $signoVital->expediente_id)
                            @foreach($ordenesAtencion as $ordenA)
                                @if($ordenA->orden_id == $expediente->orden_id)
                                <input type="text" class="form-control" id="medico" name="medico"  placeholder="Médico" value="{{$ordenA->paciente_apellidos.' '.$ordenA->paciente_nombres}}" readonly>                      
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="form-group row">
                <label for="signo_pas" class="col-sm-2 col-form-label">PAS: (mmHg)</label>              
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_pas" name="signo_pas"  placeholder="Signo pas" value="{{$signoVital->signo_pas}}" required>
                </div>
                <label for="signo_pad" class="col-sm-2 col-form-label">PAD: (mmHg)</label>
                <div class="col-sm-4">                                                          
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_pad" name="signo_pad" placeholder="Signo pad" value="{{$signoVital->signo_pad}}" required>                  
                </div>
            </div>
            <div class="form-group row">
                <label for="signo_fc" class="col-sm-2 col-form-label">FC: (lpm)</label>
                <div class="col-sm-4">                                                          
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_fc" name="signo_fc" placeholder="Signo fc" value="{{$signoVital->signo_fc}}" required>                  
                </div>
                <label for="signo_fr" class="col-sm-2 col-form-label">FR: (rpm)</label>
                <div class="col-sm-4">                                                          
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_fr" name="signo_fr" placeholder="Signo fr" value="{{$signoVital->signo_fr}}" required>                  
                </div>
            </div> 
            <div class="form-group row">
                <label for="signo_temp" class="col-sm-2 col-form-label">Temp.: (°C)</label>
                <div class="col-sm-4">
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_temp" name="signo_temp" placeholder="Signo temp" value="{{$signoVital->signo_temp}}" required>
                </div>
                <label for="signo_peso" class="col-sm-2 col-form-label">Peso: (Kg)</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_peso" name="signo_peso"  placeholder="Signo peso" value="{{$signoVital->signo_peso}}" required>
                </div>
            </div> 
            <div class="form-group row">
                <label for="signo_estatura" class="col-sm-2 col-form-label">Estatura: (cm)</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_estatura" name="signo_estatura"  placeholder="Signo estatura" value="{{$signoVital->signo_estatura}}" required>
                </div>
                <label for="signo_imc" class="col-sm-2 col-form-label">IMC</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_imc" name="signo_imc"  placeholder="Signo imc" value="{{$signoVital->signo_imc}}" required>
                </div>
            </div>  
            <div class="form-group row">
                <label for="signo_satO2" class="col-sm-2 col-form-label">SatO2: %</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_satO2" name="signo_satO2"  placeholder="Signo sat O2" value="{{$signoVital->signo_satO2}}" required>
                </div>
                <label for="signo_cefalico" class="col-sm-2 col-form-label">Perímetro  cefálico (cm):</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_cefalico" name="signo_cefalico"  placeholder="Signo cefalico" value="{{$signoVital->signo_cefalico}}" required>
                </div>
            </div> 
            <div class="form-group row">
                <label for="signo_toraxico" class="col-sm-2 col-form-label">Perímetro toráxico (cm):</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_toraxico" name="signo_toraxico"  placeholder="Signo traxico" value="{{$signoVital->signo_toraxico}}" required>
                </div>
                <label for="signo_abdominal" class="col-sm-2 col-form-label">Perímetro abdominal (cm):</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_abdominal" name="signo_abdominal"  placeholder="Signo abdominal" value="{{$signoVital->signo_abdominal}}" required>
                </div>
            </div> 
            <div class="form-group row">
                <label for="signo_inspiracion" class="col-sm-2 col-form-label">Inspiración (cm):</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_inspiracion" name="signo_inspiracion"  placeholder="Signo inspiracion" value="{{$signoVital->signo_inspiracion}}" required>
                </div>
                <label for="signo_esperacion" class="col-sm-2 col-form-label">Esperación (cm):</label>
                <div class="col-sm-4">                                
                    <input type="number" min="0"  step="0.5" class="form-control" id="signo_esperacion" name="signo_esperacion"  placeholder="Signo esperacion" value="{{$signoVital->signo_esperacion}}" required>
                </div>
  
            </div>
            <div class="form-group row">
                <label for="idEstado" class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-9">
                    <div class="col-form-label custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        @if($signoVital->signo_estado=="1")
                        <input type="checkbox" class="custom-control-input" id="idEstado" name="idEstado" checked>
                        @else
                        <input type="checkbox" class="custom-control-input" id="idEstado" name="idEstado">
                        @endif
                        <label class="custom-control-label" for="idEstado"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection