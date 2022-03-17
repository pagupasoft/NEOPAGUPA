@extends ('admin.layouts.admin')
@section('principal')

<style>
    a:hover{
        cursor: pointer
    }

    .visible{
        display: block;
    }

    .oculto{
        display: none;
    }

    .active-timeline{
        background-color: #e5a946 !important;
    }
</style>


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
                <div class="form-group tab-group">
                    <div class="tab-pane col-12 oculto" id="adicional" role="tabpanel" aria-labelledby="adicional-tab">
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

                    <div class="tab-pane col-12 oculto" id="signos" role="tabpanel" aria-labelledby="signos-tab">  
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
                                <div>
                                    </div>
                                
                            @endif    
                        </div>
                    </div>

                    <div class="tab-pane col-12 oculto" id="diagnostico" role="tabpanel" aria-labelledby="diagnostico-tab">  
                        <br> 
                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label  class="col-sm-12 col-form-label">Diagnóstico (CIE 10)</label>
                                <div class="select2-purple">
                                    <select class="select2" id="select22" name="DenfermedadId[]" multiple="multiple" data-placeholder="Selecione el Diagnóstico" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                        <option value=3423 selected>swdas</option>
                                        <option value=333 selected>ssssss</option>
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

                    <div class="tab-pane col-12 oculto" id="prescripcion" role="tabpanel" aria-labelledby="prescripcion-tab">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
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

                    <div class="tab-pane col-12 oculto" id="examenes" role="tabpanel" aria-labelledby="examenes-tab">
                        <br>
                        <div class="row">  
                            <iframe style='display:none' id='frame' width='100%' height='100%' frameborder='0'></iframe>
                        <div>
                    </div>
                    
                    <div class="tab-pane col-12 oculto" id="imagenes" role="tabpanel" aria-labelledby="imagenes-tab">
                        <br>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                                <div class="table-responsive">
                                @include ('admin.citasMedicas.atencionCitas.itemImagen')
                                    <table id="cargarItemImagen" class="table table-striped table-hover" style="margin-bottom: 6px;">
                                        <thead>
                                            <tr class="letra-blanca fondo-azul-claro text-center">
                                                <th>Imagenes</th>
                                                <th>Indicaciones</th>
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
            </div>
         
            <div class="col-3" style="max-height: 80vh; ; overflow-y: scroll">
                <!-- Timeline -->
                <ul class="timeline">
                    @foreach($historial as $historiales)
                    <a  class="card" onclick="cargarConsultaMedica({{ $historiales->orden_id }})">
                        <li id="timeline{{ $historiales->orden_id }}" class="timeline-item bg-white rounded ml-3 p-4 shadow">
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
<!-- /.card -->

<script>
    var borrada=false
    aditional = document.getElementById("adicional")
    signos = document.getElementById("signos")
    diagnostico = document.getElementById("diagnostico")
    prescripcion = document.getElementById("prescripcion")
    examenes = document.getElementById("examenes")
    imagenes = document.getElementById("imagenes")

    aditionalTab = document.getElementById("adicional-tab")
    signosTab = document.getElementById("signos-tab")
    diagnosticoTab = document.getElementById("diagnostico-tab")
    prescripcionTab = document.getElementById("prescripcion-tab")
    examenesTab = document.getElementById("examenes-tab")
    imagenesTab = document.getElementById("imagenes-tab")


    aditionalTab.addEventListener("click", function(){
        ocultarTodos();
        aditional.classList.remove("oculto")
    });

    signosTab.addEventListener("click", function(){
        ocultarTodos();
        signos.classList.remove("oculto")
    });

    diagnosticoTab.addEventListener("click", function(){
        ocultarTodos();
        diagnostico.classList.remove("oculto")
    });

    prescripcionTab.addEventListener("click", function(){
        ocultarTodos();
        prescripcion.classList.remove("oculto")
    });

    examenesTab.addEventListener("click", function(){
        ocultarTodos();
        examenes.classList.remove("oculto")
    });

    imagenesTab.addEventListener("click", function(){
        ocultarTodos();
        imagenes.classList.remove("oculto")
    });

    function ocultarTodos(){
        aditional.classList.add("oculto")
        signos.classList.add("oculto")
        diagnostico.classList.add("oculto")
        prescripcion.classList.add("oculto")
        examenes.classList.add("oculto")
        imagenes.classList.add("oculto")
    }
</script>
@endsection


    <script>
        function cargarConsultaMedica($id) {
            //control.classList.add('active')
            cargarDatos($id);

            $(".timeline-item").removeClass('active-timeline');
            $("#timeline"+$id).addClass('active-timeline')
        }

        function cargarDatos($id){
            if(!borrada){
                $('#plantillaItemImagen tr td:first').remove();
                $('#plantillaItemMedicamento tr td:first').remove();

                borrada=true
            }

            $.ajax({
                async: false,
                url: '{{ url("historialClinico/") }}/'+$id+'/informacion',
                dataType: "json",
                type: "GET",
                data: {
                    "orden_id": $id,
                },                      
                success: function(data){ 
                    console.log("Cargando "+$id)
                    console.log(data)

                   
                   
                    /////////////////////     diagnostico  //////////////////////////////////
                    select = document.getElementById("select22")
                    observacion_diagnostico = document.getElementById("diagnostico_observacion")
                    observacion_diagnostico.value=""
                    
                    /////limpiar select
                    for (let i = select.options.length; i >= 0; i--) {
                        select.remove(i);
                    }

                    if(data.diagnostico){
                        observacion_diagnostico.value=data.diagnostico.diagnostico_observacion
                        
                        //agregar enfermedades
                        if(data.diagnostico.detallediagnostico){
                            data.diagnostico.detallediagnostico.forEach(det=>{
                                const option = document.createElement('option');
                                
                                option.value =  det.enfermedad.enfermedad_id;
                                option.text = det.enfermedad.enfermedad_nombre;
                                option.selected=true

                                select.appendChild(option);
                            })
                        }
                    }







                    /////////////////////     prescripcion  //////////////////////////////////
                    
                    observacion_prescripcion = document.getElementById("observacion_prescripcion")
                    observacion_prescripcion.value=""

                    recomendacion_prescripcion = document.getElementById("recomendacion_prescripcion")
                    recomendacion_prescripcion.value=""


                    ////borrar tabla de prescripcion
                    $('#cargarItemPrescripcion tbody tr').remove();
                    console.log('borrando')

                    if(data.prescripcion){
                        observacion_prescripcion.value=data.prescripcion.prescripcion_observacion
                        recomendacion_prescripcion.value=data.prescripcion.prescripcion_recomendacion
                        
                        //agregar medicamentos
                        if(data.prescripcion.pres_medicamento){
                            data.prescripcion.pres_medicamento.forEach(det=>{
                                var linea = $("#plantillaItemMedicamento").html();
                                //linea = linea.replace(/{ID}/g, id_itemM);
                                linea = linea.replace(/{PmedicinaNombre}/g, det.medicamento.producto.producto_nombre);
                                //linea = linea.replace(/{PproductoId}/g, document.getElementById("idProductoID").value);
                                linea = linea.replace(/{PmedicinaId}/g, det.medicamento_id);
                                linea = linea.replace(/{Pcantidad}/g, det.prescripcionm_cantidad);
                                linea = linea.replace(/{Pindicaciones}/g, det.prescripcionm_indicacion);

                                $("#cargarItemPrescripcion tbody").append(linea);
                                //id_itemM = id_itemM + 1; 
                                //$('#modal-prescripcion').modal('hide');
                            
                                //resetearCampos();
                            })
                        }
                    }




                    ////////////////////////  orden de Imagenes  ///////////////////////////////////////
                    observacion_imagen = document.getElementById("otros_imagen")
                    observacion_imagen.value=""

                    ////borrar tabla
                    $('#cargarItemImagen tbody tr').remove();
                    

                    if(data.imagen){
                        observacion_imagen.value=data.imagen.orden_observacion

                        if(data.imagen.detalle_imagen){
                            data.imagen.detalle_imagen.forEach(det=>{
                                var linea = $("#plantillaItemImagen").html();

                                console.log(linea)

                                linea = linea.replace(/{ImagenNombre}/g, det.imagen.imagen_nombre);
                                linea = linea.replace(/{ImagenId}/g, det.imagen_id);
                                linea = linea.replace(/{Iobservacion}/g, det.detalle_indicacion);

                                $("#cargarItemImagen tbody").append(linea);
                            })
                        }
                    }
                    
                    //https://neopagupa.com/public/analisisLaboratorio/23/resultados

                    ////////////////////////  Analisis de Laboratorio  /////////////////////////////
                    observacion_imagen = document.getElementById("otros_imagen")
                    observacion_imagen.value=""

                    ////borrar tabla
                    $('#cargarItemImagen tbody tr').remove();
                    

                    if(data.examen){
                        console.log(data.examen)
                        console.log(data.orden_estado)
                        if(data.examen.orden_estado==2 || data.examen.orden_estado==3){
                            var frame = $('#frame');
                            var url = "/analisisLaboratorio/"+data.examen.analisis.analisis_laboratorio_id+"/resultados";
                            frame.attr('src',url).show();
                        }
                        else{

                        }
                    }
                },
                error: function(data) { 
                    console.log("error "+$id)
                    console.log(data);       
                },
            });
        }
    
    </script>

