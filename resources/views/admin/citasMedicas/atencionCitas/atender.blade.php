@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ url("atencionCitas") }}" enctype="multipart/form-data">
@csrf
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title negrita">
           Atencion de citas
        </h3>
        <div class="float-right">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
            <button onclick='window.location = "{{ url("atencionCitas") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
        <div class="col-sm-12">

            <input class="invisible" id="paciente_id" name="paciente_id" value="{{$ordenAtencion->paciente->paciente_id}}">
            <input class="invisible" id="orden_id" name="orden_id" value="{{$ordenAtencion->orden_id}}">
            <input class="invisible" id="expediente_id" name="expediente_id" value="{{$ordenAtencion->expediente->expediente_id}}">
            <div class="form-group row">
                <label for="sucursal" class="col-sm-1 col-form-label">Paciente:</label>
                <div class="col-sm-4">
                    <label  class="form-control" >{{$ordenAtencion->paciente->paciente_apellidos.' '.$ordenAtencion->paciente->paciente_nombres}}</label>
                </div>
                <label for="fecha_hora" class="col-sm-1 col-form-label">Fecha/Hora:</label>
                <div class="col-sm-2">
                <input type="date" class="form-control" id="fecha" name="fecha" value="{{$ordenAtencion->orden_fecha}}" readonly>
                </div>
                <div class="col-sm-1">
                <input type="time" class="form-control" id="hora" name="hora" value="{{$ordenAtencion->orden_hora}}" readonly>
                </div>
                <label for="especialidad" class="col-sm-1 col-form-label">Especialidad:</label>
                <div class="col-sm-2">
                    @foreach($especialidades as $especialidad)
                        @if($especialidad->especialidad_id == $ordenAtencion->especialidad_id)
                            <label  class="form-control" >{{$especialidad->especialidad_nombre}}</label>
                        @endif
                    @endforeach
                </div>
            </div> 
            <div class="form-group row">
                <label for="Servicio" class="col-sm-1 col-form-label">Consulta:</label>
                <div class="col-sm-4">
                    <label class="form-control">{{$ordenAtencion->producto->producto_nombre}}</label>
                </div>
                <label for="seguro" class="col-sm-1 col-form-label">Aseguradora:</label>
                <div class="col-sm-3">
                    <label class="form-control">{{$ordenAtencion->paciente->aseguradora->cliente_nombre}}</label>
                </div>
                <label for="tipo_atencion" class="col-sm-1 col-form-label">Tipo Seguro:</label>
                <div class="col-sm-2">
                <label class="form-control">{{$ordenAtencion->tipoSeguro->tipo_codigo}} - {{$ordenAtencion->tipoSeguro->tipo_nombre}}</label>
                </div>
            </div> 
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="observacion" class="col-sm-5 col-form-label">Observación de la cita:</label>
                    <textarea class="form-control" readonly>{{$ordenAtencion->orden_observacion}} </textarea>
                </div>
            </div> 
        </div>
            <div class="col-sm-1">
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
                    @if($ordenAtencion->orden_iess=="1")
                        <li class="nav-item">
                            <a class="nav-link btn btn-app2 redondo" id="prescripcion-tab" data-toggle="tab" href="#prescripcion" role="tab" aria-controls="prescripcion" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-prescription-bottle-alt"></i> Prescripción</a>
                        </li>
                    @endif
                    <li class="nav-item">
                    <a class=" nav-link btn btn-app2 redondo" id="examenes-tab" data-toggle="tab" href="#examenes" role="tab" aria-controls="examenes" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-microscope"></i> Exámenes</a>
                    </li>
                    <li class="nav-item">
                    <a class=" nav-link btn btn-app2 redondo" id="imagenes-tab" data-toggle="tab" href="#imagenes" role="tab" aria-controls="imagenes" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-id-card-alt"></i> Imágenes</a>
                    </li>  
                    @if($ordenAtencion->orden_iess=="1")
                        <li class="nav-item">
                            <a class="nav-link btn btn-app2 redondo" id="subirimagenes-tab" data-toggle="tab" href="#subirimagenes" role="tab" aria-controls="Subirimagenes" aria-selected="false"><span class="badge bg-purple"></span><i class="fas fa-file-upload"></i> Subir imagenes</a>
                        </li>
                    @endif           
                </ul>
            </div>    
           
            <div class="col-sm-11">
                
                <div class="form-group">
                    <div class="well listview-pagupa">
                        <div class="tab-content" id="myTabContent">
                            <!--Informacion-->
                            <div class="tab-pane fade show" id="adicional" role="tabpanel" aria-labelledby="adicional-tab">
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
                            
                            <!-- /.tab-contentt --> 
                            <div class="tab-pane fade show" id="signos" role="tabpanel" aria-labelledby="signos-tab">  
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
                                        </div>
                                    @endif
                                   
                                @endif    
                                </div>
                            </div>
    	                    <!-- /.tab-contentt -->                               
                            <!--Diagnóstico-->
                            <div class="tab-pane fade show" id="diagnostico" role="tabpanel" aria-labelledby="diagnostico-tab">  
                                <br> 
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label  class="col-sm-12 col-form-label">Diagnóstico (CIE 10)</label>
                                        <div class="select2-purple">
                                            <select class="select2" id="select22" name="DenfermedadId[]" multiple="multiple" data-placeholder="Selecione el Diagnóstico" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                                @foreach($enfermedades as $enfermedad)
                                                    <option value="{{$enfermedad->enfermedad_id}}">{{$enfermedad->enfermedad_codigo}} - {{$enfermedad->enfermedad_nombre}}</option>
                                                @endforeach
                                        
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
                            <div class="tab-pane fade show" id="prescripcion" role="tabpanel" aria-labelledby="prescripcion-tab">
                                <br>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-5 col-lg-6" style="margin-bottom: 0px;">
                                        <label>Nombre de Producto</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input id="codigoProducto" name="idProducto" type="hidden">
                                                <input id="idProductoID" name="idProductoID" type="hidden">
                                                <input id="idmedicamento" name="idmedicamento" type="hidden">
                                                <input id="buscarProducto" name="buscarProducto" type="text" class="form-control"
                                                    placeholder="Buscar producto" >
                                                <span id="errorStock" class="text-danger invisible">El producto no tiene stock
                                                    disponible.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin-bottom: 0px;">
                                        <label>Disponible</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input id="id_disponible" name="id_disponible" type="number" class="form-control"
                                                    placeholder="Disponible" value="0" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin-bottom: 0px;">
                                        <label>Cantidad</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input id="id_cantidad"
                                                    name="id_cantidad" type="number" class="form-control" placeholder="Cantidad"
                                                    value="1" min="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <a onclick="agregarItemPrescripcion()" class="btn btn-primary btn-venta"><i
                                                class="fas fa-plus"></i></a>
                                    </div> 
                                </div>
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
                            <!-- /.tab-contentt -->
                            <!--Examenes-->
                            <div class="tab-pane direct fade show direct-chat-messages" id="examenes" role="tabpanel" aria-labelledby="examenes-tab">
                                <br>
                                <div class="row">                                           
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
                            <div class="tab-pane fade show" id="imagenes" role="tabpanel" aria-labelledby="imagenes-tab">
                                <br>

                                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                                        <div class="table-responsive">
                                            @include ('admin.citasMedicas.atencionCitas.itemImagen')

                                            <div class="row">
                                                <div class="col-xs-6 col-sm-6 col-md-5 col-lg-6" style="margin-bottom: 0px;">
                                                    <label class="ml-5">Busqueda:</label>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-1 text-right">
                                                                <input id="idImagen" name="idImagen" type="hidden" value="0">
                                                                <buttom id="btAnadirImagen" class="btn btn-success btn-sm mt-1" onclick="agregarImagen()"><i class="fa fa-plus"></i></buttom>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input id="buscarImagen" name="buscarImagen" type="text" class="form-control" placeholder="Buscar Imagen" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table id="cargarItemImagen" class="table table-striped table-hover" style="margin-bottom: 6px;">
                                                <thead>
                                                    <tr class="letra-blanca fondo-azul-claro text-center">
                                                        <th></th>
                                                        <th>Imagenes</th>
                                                        <th>Indicaciones</th>
                                                        <!--th><a class="btn btn-default btn-sm float-right" style="padding: 2px 8px;" data-toggle="modal" data-target="#modal-imagenes"><i class="fa fa-plus"></i></a></th-->
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
                                                                        @foreach($imagenes as $imagen)
                                                                            <?php $cont2 = $cont2 + 1;?>

                                                                            <tr class="text-left"  id="row_<?php echo $cont2; ?>">                                                                                
                                                                                <td>
                                                                                    <a class="btn btn-success btn-sm" onclick="agregarItemImagen(<?php echo $cont2; ?>)"><i class="fa fa-plus"></i></a>&nbsp;&nbsp;{{ $imagen->imagen_nombre }}                                                                               
                                                                                    <input class="invisible"  id="imagenNombreAux_<?php echo $cont2; ?>" name="imagenNombreAux[]" value="{{ $imagen->imagen_nombre }}" />
                                                                                    <input class="invisible" id="imagenIdAux_<?php echo $cont2; ?>" name="imagenIdAux[]" value="{{ $imagen->imagen_id }}" />
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
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
                            <!-- /.tab-contentt -->
                            <!--Imagenes-->
                            
                            <div class="tab-pane fade show" id="subirimagenes" role="tabpanel" aria-labelledby="subirimagenes-tab">  
                                <br> 

                                <div id="actions" class="row">
                                    <div class="offset-md-2 col-lg-8">
                                        <div class="btn-group w-100">
                                            <span class="btn btn-success col fileinput-button">
                                                <i class="fas fa-plus"></i>
                                                <span>Para añadir imagenes del Portapapeles pulse:  Ctrl+V</span>
                                            </span>
                                        </div>
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <div class="fileupload-process w-100">
                                                <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="table table-striped files" id="previews">
                                            <div id="template" style="visibility: hidden" class="mt-2">
                                                <div class="col-md-12" id="marco:,">
                                                    <div class="row">
                                                        <button onclick="borrarMarcoImagen('marcodelete:,')" class="btn btn-xs btn-danger">  <i class="fa fa-trash" aria-hidden="true"></i>  </button>
                                                        &nbsp&nbsp&nbsp
                                                        <img style="max-height:45px; border: 1px solid" src="data:," alt="" data-dz-thumbnail />
                                                        <div class="col d-flex align-items-center">
                                                            <p class="mb-0">
                                                            <span class="lead" data-dz-name></span>
                                                            (<span>tamano:,</span>)
                                                            </p>
                                                            <strong class="error text-danger">error:,</strong>
                                                        </div>

                                                        <!--input type="file" name="imageFile[]" id="imageFile_0:," style="visibility: hidden"-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-contentt -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card-body -->
</div>
</form>


<!-- /.card -->
<style type="text/css">   
   .mayus { text-transform: uppercase;}    
</style> 

@section('scriptAjax')
<script src="{{ asset('admin/js/ajax/autocompleteProductoMedicamento.js') }}"></script>
<script src="{{ asset('admin/js/ajax/autocompleteImagen.js') }}"></script>

@endsection

<script>
    cantidadImagenes=0;

    //const fileInput = document.getElementById("tttt");

    function borrarMarcoImagen(id){
        $("#"+id).remove();
    }

    window.addEventListener('paste', e => {
        cantidadImagenes++;
        
        var fileInput = document.createElement('input')
        $(fileInput).attr('id', 'imagefile_'+cantidadImagenes)
        $(fileInput).attr('name', 'imagefile[]')
        $(fileInput).attr('type', 'file')
        $(fileInput).css('visibility', 'hidden')

        fileInput.files = e.clipboardData.files;
        const file = e.clipboardData.files[0];
        
        
        size=bytesToSize(file.size)
        type=file.type

        var linea = $("#template").html();

        
        if(type=="image/png" || type=="image/jpg"){
            linea = linea.replace(/imageFile_0:,/g, 'imagefile_'+cantidadImagenes);
            linea = linea.replace(/marcodelete:,/g, "marco"+cantidadImagenes);

            fileInput.files = e.clipboardData.files;
            const objectURL = window.URL.createObjectURL(fileInput.files[0])
            linea = linea.replace(/data:,/g, objectURL);
            linea = linea.replace(/tamano:,/g, size);
            linea = linea.replace(/error:,/g, '');
            linea = linea.replace(/marco:,/g, "marco"+cantidadImagenes);

            $("#previews").append(linea);
            $("#marco"+cantidadImagenes).append(fileInput);
           
            
            
            //console.log(bytesToSize(file));
            //console.log(linea+"    "+JSON.stringify(e.clipboardData.files))
        }
        else{
            linea = linea.replace(/tamano:,/g, '0.00Kb');
            linea = linea.replace(/error:,/g, 'No es imagen!');
        }

        //console.log(linea)

    });

    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    
</script>
<script>
    var id_item = 0;       
    var idMed = 1;       
    function agregarItemDiagnostico(idDiag) {    
        var linea = $("#plantillaItemEnfermedad").html();
        linea = linea.replace(/{ID}/g, id_item);
        linea = linea.replace(/{DenfermedadNombre}/g, document.getElementById("enfermedadNombreAux_"+idDiag).value);
        linea = linea.replace(/{DenfermedadId}/g, document.getElementById("enfermedadIdAux_"+idDiag).value);
        linea = linea.replace(/{DobservacionEnfer}/g, "");
        linea = linea.replace(/{DcboxCasoN}/g);
        linea = linea.replace(/{DcboxDefinitivo}/g);
        linea = linea.replace(/{DcboxCasoNEstado}/g, 0);
        linea = linea.replace(/{DcboxDefinitivoEstado}/g, 0);

        $("#cargarItemDiagnostico tbody").append(linea);
        id_item = id_item + 1; 
        $('#modal-diagnostico').modal('hide');
    }
    
    function eliminarItem(id) {
        $("#row_" + id).remove();
        //id_item = id_item - 1;
    }

    var id_itemM = 0;  
    function agregarItemPrescripcion() {   
        if (document.getElementById("id_disponible").value > 0) {
            if (Number(document.getElementById("id_disponible").value) > Number(document.getElementById("id_cantidad").value)) {
                var linea = $("#plantillaItemMedicamento").html();
                linea = linea.replace(/{ID}/g, id_itemM);
                linea = linea.replace(/{PmedicinaNombre}/g, document.getElementById("buscarProducto").value);
                linea = linea.replace(/{PproductoId}/g, document.getElementById("idProductoID").value);
                linea = linea.replace(/{PmedicinaId}/g,document.getElementById("idmedicamento").value);
                linea = linea.replace(/{Pcantidad}/g,document.getElementById("id_cantidad").value);
                linea = linea.replace(/{Pindicaciones}/g, "");

                $("#cargarItemPrescripcion tbody").append(linea);
                id_itemM = id_itemM + 1; 
                $('#modal-prescripcion').modal('hide');
            
                resetearCampos();
            }
        }
    }
    function resetearCampos() {
        document.getElementById("id_cantidad").value = 1;
        document.getElementById("codigoProducto").value = "";
        document.getElementById("idProductoID").value = "";
        document.getElementById("buscarProducto").value = "";
        document.getElementById("id_disponible").value = "0";

    }

    var id_itemI = 0; 
    function agregarItemImagen(idImag) {    
        var linea = $("#plantillaItemImagen").html();
        linea = linea.replace(/{ID}/g, id_itemI);
        linea = linea.replace(/{ImagenNombre}/g, document.getElementById("imagenNombreAux_"+idImag).value);
        linea = linea.replace(/{ImagenId}/g, document.getElementById("imagenIdAux_"+idImag).value);
        linea = linea.replace(/{Iobservacion}/g, "");

        $("#cargarItemImagen tbody").append(linea);
        id_itemI = id_itemI + 1; 
        $('#modal-imagenes').modal('hide');
    }

    function agregarImagen() { 
        id=$("#idImagen").val();
        nombreImagen=$("#buscarImagen").val();

        if(parseInt(id)>0){

            var linea = $("#plantillaItemImagen").html();
            linea = linea.replace(/{ID}/g, id);
            linea = linea.replace(/{ImagenNombre}/g, nombreImagen);
            linea = linea.replace(/{ImagenId}/g, id);
            linea = linea.replace(/{Iobservacion}/g, "");

            $("#cargarItemImagen tbody").append(linea);
            //id_itemI = id_itemI + 1; 
            //$('#modal-imagenes').modal('hide');

            $('#idImagen').val('0');
            $('#buscarImagen').val('');
        }
    }

    /*
    <tbody id="plantillaItemImagen">
        <tr class="text-center" id="row_{ID}">
            <td>
                <a onclick="eliminarItem({ID});" class="btn btn-danger waves-effect" style="padding: 2px 8px;">X</a>
            </td>
            <td>
                {ImagenNombre}<input class="invisible" name="ImagenNombre[]" value="{ImagenNombre}"/>
                <input class="invisible" name="ImagenId[]" value="{ImagenId}"/>
            </td>
            <td>
                <input class="form-control" name="Iobservacion[]" value="{Iobservacion}">
            </td>           
            <td></td>
            <td></td>
        </tr>
    </tbody>
    */

    var id_itemP = 0; 
    function agregarItemFacturacion(id_itemP) {    
        var linea = $("#plantillaItemFacturacion").html();
        linea = linea.replace(/{ID}/g, id_itemP);
        linea = linea.replace(/{FproductoNombre}/g, document.getElementById("productoNombreAux_"+id_itemP).value);
        linea = linea.replace(/{FproductoId}/g, document.getElementById("productoIdAux_"+id_itemP).value);
        linea = linea.replace(/{FprocedimientoAId}/g, document.getElementById("procedimientoAIdAux_"+id_itemP).value);
        linea = linea.replace(/{Fobservacion}/g, "");
        linea = linea.replace(/{Fcosto}/g, document.getElementById("productoCostoAux_"+id_itemP).value);
        
        $("#cargarItemFacturacion tbody").append(linea);
        id_itemP = id_itemP + 1; 
        $('#modal-facturacion').modal('hide');
    }
</script>

@endsection


