@extends ('admin.layouts.admin')
@section('principal')

<style>
    a:hover{
        cursor: pointer
    }
</style>
<form class="form-horizontal" method="POST" action="/historialClinico">
@csrf
<div class="card card-secondary">
    <div class="card-header">
        <div class="float-right">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
            <button onclick="window.location = '/historialClinico';" class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    
        <div class="row">
            <div class="col-lg-12"> 
                <div class="form-group row">
                    <label for="sucursal" class="col-sm-1 col-form-label">Paciente:</label>
                    <div class="col-sm-4">
                        <label  class="form-control" >{{$paciente->paciente_apellidos.' '.$paciente->paciente_nombres}}</label>
                    </div>
                    <label for="seguro" class="col-sm-1 col-form-label">Aseguradora:</label>
                    <div class="col-sm-3">
                        <label class="form-control">{{$paciente->aseguradora->cliente_nombre}}</label>
                    </div>
                </div> 
                
            </div>
        </div>
        <div class="row">
            <div class="col-1">
                <ul class="nav flex-column2 nav-tabs h-100" id="myTab" role="tablist" aria-orientation="vertical">
                    <li class="nav-item">
                        <a class="nav-link btn btn-app2 redondo" id="adicional-tab" data-toggle="tab" href="#adicional" role="tab" aria-controls="adicional" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-info-circle"></i> Informacion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-app2 redondo" id="signos-tab" data-toggle="tab" href="#signos" role="tab" aria-controls="signos" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-stethoscope"></i> Signos Vitales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-app2 redondo" id="diagnostico-tab" data-toggle="tab" href="#diagnostico" role="tab" aria-controls="diagnostico" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-stethoscope"></i> Diagnóstico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-app2 redondo" id="prescripcion-tab" data-toggle="tab" href="#prescripcion" role="tab" aria-controls="prescripcion" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-prescription-bottle-alt"></i> Prescripción</a>
                    </li>
                    <li class="nav-item">
                    <a class=" nav-link btn btn-app2 redondo" id="examenes-tab" data-toggle="tab" href="#examenes" role="tab" aria-controls="examenes" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-microscope"></i> Exámenes</a>
                    </li>
                    <li class="nav-item">
                    <a class=" nav-link btn btn-app2 redondo" id="imagenes-tab" data-toggle="tab" href="#imagenes" role="tab" aria-controls="imagenes" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-id-card-alt"></i> Imágenes</a>
                    </li>             
                </ul>
            </div>  
            <div class="col-8" style="height: 80vh; overflow-y: scroll"> 
                <div class="form-group">
                    <div class="well listview-pagupa">
                        <div class="tab-content" id="myTabContent">
                            <!--Informacion-->
                            <div class="tab-pane fade" id="adicional" role="tabpanel" aria-labelledby="adicional-tab">
                                <br>
                                <?php $count=1;?>
                                @if(isset($cespecialidad))
                                    @foreach($cespecialidad as $cespecialidades)  
                                        @if(($count % 2) != 0)
                                        <div class="form-group row">
                                        @endif
                                    
                                            <label for="id{{$cespecialidades->configuracion_nombre}}" class="col-sm-2 col-form-label">{{$cespecialidades->configuracion_nombre}}:</label>
                                            <div class="col-sm-3">    
                                                <input @if($cespecialidades->configuracion_tipo==1) type="text" @endif @if($cespecialidades->configuracion_tipo==2) type="number" min="0"  step="0.01" required @endif class="form-control" id="id{{$cespecialidades->configuracion_nombre}}" name="valor[]" value="" required> 
                                                <input type="hidden" name="nombre[]" value="{{$cespecialidades->configuracion_nombre}}">  
                                                <input type="hidden" name="tipo[]" value="{{$cespecialidades->configuracion_tipo}}">
                                                <input type="hidden" name="medida[]" value="{{$cespecialidades->configuracion_medida}}">
                                                <input type="hidden" name="ide[]" value="{{$cespecialidades->configuracion_id}}">      
                                            </div>
                                            <label for="id{{$cespecialidades->configuracion_nombre}}" class="col-sm-1 col-form-label">{{$cespecialidades->configuracion_medida}}</label>           
                                        @if(($count % 2) == 0)        
                                        </div>
                                        @endif
                                        <?php $count++;?>     
                                    @endforeach 
                                    @if((($count-1) % 2) != 0)
                                        </div>
                                    @endif
                                
                                @endif
                            </div>   
                        </div>
                        
                        <!-- /.tab-contentt --> 
                        <div class="tab-pane fade" id="signos" role="tabpanel" aria-labelledby="signos-tab">  
                            <br> 
                            <div class="col-12 col-sm-12">
                            <?php $count=1;?>
                            @if(isset($signoVital))
                                @foreach($signoVital as $signoVitales)  
                                    @if(($count % 2) != 0)
                                        <div class="form-group row">
                                    @endif
                                            <label for="id{{$signoVitales->signo_nombre}}" class="col-sm-2 col-form-label">{{$signoVitales->signo_nombre}}:</label>
                                            <div class="col-sm-3">    
                                                <input @if($signoVitales->signo_tipo==1) type="text" @endif @if($signoVitales->signo_tipo==2) type="number" min="0"  step="0.01" required @endif class="form-control" id="id{{$signoVitales->signo_nombre}}" name="svalor[]" value="{{$signoVitales->signo_valor}}" required> 
                                                <input type="hidden" name="side[]" value="{{$signoVitales->signo_id}}">      
                                            </div>  
                                            <label for="id{{$signoVitales->signo_nombre}}" class="col-sm-1 col-form-label">{{$signoVitales->signo_medida}}</label>                   
                                    @if(($count % 2) == 0)        
                                        </div>
                                    @endif
                                    <?php $count++;?> 
                                @endforeach 
                                @if((($count-1) % 2) != 0)
                                    <!--/div-->
                                @endif
                            @endif    
                        </div>
                    </div>
                        <!-- /.tab-contentt -->                               
                        <!--Diagnóstico-->
                    <div class="tab-pane fade" id="diagnostico" role="tabpanel" aria-labelledby="diagnostico-tab">  
                        <br> 
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label  class="col-sm-12 col-form-label">Diagnóstico (CIE 10)</label>
                                <div class="select2-purple">
                                    <select class="select2" id="select22" name="DenfermedadId[]" multiple="multiple" data-placeholder="Selecione el Diagnóstico" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                        @if(isset($enfermedades))
                                            @foreach($enfermedades as $enfermedad)
                                                <option value="{{$enfermedad->enfermedad_id}}">{{$enfermedad->enfermedad_codigo}} - {{$enfermedad->enfermedad_nombre}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="observacion" class="col-sm-5 col-form-label">Observación:</label>
                                    <textarea class="form-control" id="diagnostico_observacion"   name="diagnostico_observacion" > </textarea>
                                </div>
                            </div>     
                        </div>
                    </div>
                        <!-- /.tab-contentt -->

                        <!--Prescripcion-->
                    <div class="tab-pane fade" id="prescripcion" role="tabpanel" aria-labelledby="prescripcion-tab">
                        <br>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                            @include ('admin.citasMedicas.atencionCitas.itemMedicamento')
                                <div class="table-responsive">
                                    <table id="cargarItemPrescripcion" class="table table-striped table-hover" style="margin-bottom: 6px;">
                                        <thead>
                                            <tr class="letra-blanca fondo-azul-claro text-center">
                                                <th></th>
                                                <th>Medicinas y dietas</th>
                                                <th>Cant</th>
                                                <th>Indicaciones</th>  
                                                <th><a class="btn btn-default btn-sm float-right" style="padding: 2px 8px;" data-toggle="modal" data-target="#modal-prescripcion"><i class="fa fa-plus"></i></a></th>                                              
                                                <th width="10"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    <div class="modal fade" id="modal-prescripcion">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-secondary">
                                                    <h4 class="modal-title">Medicamentos</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="container">
                                                    <div class="container">
                                                        <br>
                                                        <table id="example2" class="table table-bordered table-hover sin-salto" style="margin-bottom: 2px;">
                                                            <thead>
                                                                <tr class="letra-blanca fondo-azul-claro text-left">
                                                                    <th>Nombre</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $cont1 = 1;?>
                                                                @if(isset($medicamentos))
                                                                @foreach($medicamentos as $medicamento)
                                                                    <tr class="text-left"  id="row_<?php echo $cont1; ?>">                                                                                
                                                                        <td>
                                                                            <a class="btn btn-success btn-sm" onclick="agregarItemPrescripcion(<?php echo $cont1; ?>)"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;{{ $medicamento->medicamento_nombre }}                                                                               
                                                                            <input class="invisible"  id="medicamentoNombreAux_<?php echo $cont1; ?>" name="medicamentoNombreAux[]" value="{{ $medicamento->medicamento_nombre }}" />
                                                                            <input class="invisible" id="medicamentoIdAux_<?php echo $cont1; ?>" name="medicamentoIdAux[]" value="{{ $medicamento->medicamento_id }}" />
                                                                        </td>
                                                                        <?php $cont1 = $cont1 + 1;?>                                   
                                                                    </tr>
                                                                @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <div class="form-group row">
                                        <label for="recomendacion_prescripcion" class="col-sm-6 col-form-label">Recomendaciones:</label>
                                        <label for="observacion_prescripcion" class="col-sm-6 col-form-label">Observaciones:</label>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">                                
                                            <textarea type="text" class="form-control" id="recomendacion_prescripcion" name="recomendacion_prescripcion"  placeholder="Recomendaciones"></textarea>
                                        </div>
                                        <div class="col-sm-6">                                
                                            <textarea type="text" class="form-control" id="observacion_prescripcion" name="observacion_prescripcion"  placeholder="Observaciones"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- /.tab-contentt -->
                        <!--Examenes-->
                    <div class="tab-pane direct fade direct-chat-messages" id="examenes" role="tabpanel" aria-labelledby="examenes-tab">
                        <br>
                        <div class="row">  
                        @if(isset($tipoExamenes))                                   
                            @foreach($tipoExamenes as $tipoExamen) 
                            <div class="col-sm-3">                                                                                       
                                <label for="conciencia" class="col-sm-7 col-form-label mayus" value="{{$tipoExamen->tipo_id}}">{{$tipoExamen->tipo_nombre}}</label>
                                @foreach($examenes as $examen)                                                            
                                    @if($tipoExamen->tipo_id == $examen->tipo_id)
                                    <div class="form-group row">
                                        <div class="col-sm-1">                                
                                            <div class="form-check ">
                                            <input style="width:20px; height:20px;" class="form-check-input" type="checkbox" name="laboratorio[]"  id="laboratorio[]" value="{{$examen->examen_id}}" >
                                                <input class="invisible"  value="{{$examen->examen_id}}" >
                                            </div>
                                        </div>   
                                        <label for="orientado" class="col-sm-7"  value="{{$examen->producto_id}}">{{$examen->producto_nombre}}</label>
                                    </div> 
                                    
                                    @endif 
                                @endforeach
                                
                            </div>     
                            @endforeach 
                        @endif
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="observacion" class="col-sm-5 col-form-label">Otros:</label>
                                    <textarea class="form-control" id="otros_examenes"   name="otros_examenes" > </textarea>
                                </div>  
                            </div>  
                        </div>
                    </div>
                        <!-- /.tab-contentt -->
                        <!--Imagenes-->
                    <div class="tab-pane fade" id="imagenes" role="tabpanel" aria-labelledby="imagenes-tab">
                        <br>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                                <div class="table-responsive">
                                @include ('admin.citasMedicas.atencionCitas.itemImagen')
                                    <table id="cargarItemImagen" class="table table-striped table-hover" style="margin-bottom: 6px;">
                                        <thead>
                                            <tr class="letra-blanca fondo-azul-claro text-center">
                                                <th></th>
                                                <th>Imagenes</th>
                                                <th>Indicaciones</th>
                                                <th><a class="btn btn-default btn-sm float-right" style="padding: 2px 8px;" data-toggle="modal" data-target="#modal-imagenes"><i class="fa fa-plus"></i></a></th>                                             
                                                <th width="10"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    <div class="modal fade" id="modal-imagenes">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-secondary">
                                                    <h4 class="modal-title">Imagenes</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="container">
                                                    <div class="container">
                                                        <br>
                                                        <table id="example3" class="table table-bordered table-hover sin-salto" style="margin-bottom: 2px;">
                                                            <thead>
                                                                <tr class="letra-blanca fondo-azul-claro text-left">
                                                                    <th>Nombre</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $cont2 = 1;?>
                                                                @if(isset($imagenes))
                                                                @foreach($imagenes as $imagen)
                                                                    <tr class="text-left"  id="row_<?php echo $cont2; ?>">                                                                                
                                                                        <td>
                                                                            <a class="btn btn-success btn-sm" onclick="agregarItemImagen(<?php echo $cont2; ?>)"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;{{ $imagen->imagen_nombre }}                                                                               
                                                                            <input class="invisible"  id="imagenNombreAux_<?php echo $cont2; ?>" name="imagenNombreAux[]" value="{{ $imagen->imagen_nombre }}" />
                                                                            <input class="invisible" id="imagenIdAux_<?php echo $cont2; ?>" name="imagenIdAux[]" value="{{ $imagen->imagen_id }}" />
                                                                        </td>
                                                                        <?php $cont2 = $cont2 + 1;?>                                   
                                                                    </tr>
                                                                @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="observacion" class="col-sm-5 col-form-label">Otros:</label>
                                    <textarea class="form-control" id="otros_imagen"   name="otros_imagen" > </textarea>
                                </div>  
                            </div> 
                        </div>
                    </div>
                </div>
                </div>
            </div>
         
            <div class="col-3" style="max-height: 80vh; ; overflow-y: scroll">
                <!-- Timeline -->
                <ul class="timeline">
                    @foreach($historial as $historiales)
                    <a  class="card" onclick="cargarConsultaMedica({{ $historiales->orden_id }})">
                        <li class="timeline-item bg-white rounded ml-3 p-4 shadow active">
                            <div class="timeline-arrow"></div>
                            <h5 class="h7 mb-0 text-center">
                                @if(isset($historiales->medico->empleado)) 
                                    {{$historiales->medico->empleado->empleado_nombre}} 
                                @elseif(isset($historiales->medico->proveedor)) 
                                    {{$historiales->medico->proveedor->proveedor_nombre}} 
                                @endif 
                                
                                <br>
                                
                                {{$historiales->especialidad->especialidad_nombre}}
                            </h5>
                            <span class="small text-center"><i class="fa fa-clock-o mr-1"></i>{{$historiales->orden_fecha}} {{$historiales->orden_hora}}</span>
                        </li>
                    </a> 
                    @endforeach
                    
                    
                </ul><!-- End -->

            </div>
        </div>
    </div>
</div>
        
</form>
<!-- /.card -->
@endsection

<script>
    function cargarConsultaMedica($id) {
        cargarDatosInfo($id);
    }

    function cargarDatos(){
        $.ajax({
            async: false,
            url: '{{ url("historialClinico/") }}/'+$id+'/informacion',
            dataType: "json",
            type: "GET",
            data: {},                      
            success: function(data){ 
                console.log("Cargando")
                console.log(data)  
                
            },
            error: function(data) { 
                console.log(data);       
            },
        });
    }
</script>

<script>
function make(e) {
        // ...  your function code
        // e.preventDefault();   // use this to NOT go to href site
    }
</script>
