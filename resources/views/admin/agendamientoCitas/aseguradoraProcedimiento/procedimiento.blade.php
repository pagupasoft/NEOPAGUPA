@extends ('admin.layouts.admin')
@section('principal')
<meta name="csrf-token" content="{{ csrf_token() }}">
<form class="form-horizontal" method="POST" action="{{ route('aseguradoraProcedimiento.guardarProcedimiento', [$cliente->cliente_id]) }}">
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Asignar Procedimiento</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button type="button" onclick='window.location = "{{ url("aseguradoraProcedimiento") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </div>
        </div>
        <div class="card-body">
            <input class="invisible" id="cliente_id" name="cliente_id" value="{{$cliente->cliente_id}}" />
            <div class="form-group row">
                <label for="cliente_nombre" class="col-sm-3 col-form-label">Nombre de la Aseguradora</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="cliente_nombre" value="{{$cliente->cliente_nombre}}" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="especialidad_id" class="col-sm-3 col-form-label">Especialidad</label>
                <div class="col-sm-9">
                    <select class="custom-select select2" id="especialidad_id" name="especialidad_id" onchange="eliminarItem();" required>
                        <option value="" label>--Seleccione una opcion--</option>                               
                            @foreach($especialidades as $especialidad)
                                <option value="{{$especialidad->especialidad_id}}">{{$especialidad->especialidad_nombre}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0px;">
                    <div id="mien" class="table-responsive">
                        @include ('admin.agendamientoCitas.aseguradoraProcedimiento.itemProcedimiento')
                        <table id="cargarItemProcedimiento" class="table table-striped table-hover" style="margin-bottom: 6px;">
                            <thead>
                                <tr class="letra-blanca fondo-azul-claro text-center">
                                    <!--<th><input id="cbox1_" name="cbox1_" style="margin-bottom: 3px;"  type="checkbox" onclick="selectAll();" /></th>-->
                                    <th></th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Especialidad</th>  
                                    <th>Valor</th>
                                    <th>Precio Aseg</th> 
                                    <th>Código Tarifario</th>                                              
                                </tr>
                            </thead>
                            <tbody>
                                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</form>
<script>        
    var cont = 0;
    /* ----------------------- */
       
    /* ----------------------- */
    window.onload = function load(){
        cargarProcedimiento();
    }

    function eliminarItem(){
        if(cont > 0){
            for(var i=0; i<cont; i++){
                $("#row_"+i).remove();
            }
            cargarProcedimiento();   
        }else{
            cargarProcedimiento(); 
                      
        }
    }      
     
    function cargarProcedimiento(){       
        $.ajax({
            url: '{{ url("aseguradoraProcedimiento/searchN") }}'+ '/' +document.getElementById("especialidad_id").value,
            dataType: "json",
            type: "GET",
            data: {
                buscar: document.getElementById("especialidad_id").value
            },                      
            success: function(data){                    
                for (var i=0; i<data.length; i++) {
                    var coma = ",";
                    var cod = data[i].procedimiento_id;    
                    var value = 0; 
                    var costo = 0; 
                    var codigoT = 0; 
                    var aux=cargarProcedimientoAsignados(cod);
                    var costo =  extraerValor(aux,coma);
                    var codigoT =  extraerCodigo(aux,coma);
                    
                    
                    var linea = $("#plantillaItemProcedimiento").html();                              
                    linea = linea.replace(/{Pcheckbox}/g, 0);
                    linea = linea.replace(/{PIDE}/g, data[i].producto_id);
                    linea = linea.replace(/{ID}/g, (i));
                    linea = linea.replace(/{check}/g, (i+1));
                    linea = linea.replace(/{Pcodigo}/g, data[i].producto_codigo);
                    linea = linea.replace(/{Pnombre}/g, data[i].producto_nombre);
                    linea = linea.replace(/{Pprocedimiento}/g, cod);
                    linea = linea.replace(/{Pespecialidad}/g, document.getElementById("especialidad_id").value);
                    linea = linea.replace(/{Pcliente_id}/g, document.getElementById("cliente_id").value);
                    var combo = document.getElementById("especialidad_id");
                    var especialidadNombre = combo.options[combo.selectedIndex].text; 
                    linea = linea.replace(/{PespecialidadN}/g, especialidadNombre);
                    linea = linea.replace(/{Pprecio}/g, data[i].producto_precio_costo);
                    linea = linea.replace(/{Pcosto}/g, costo);
                    linea = linea.replace(/{PcodigoT}/g, codigoT);
                    linea = linea.replace(/{activar}/g, );
                    if(costo>0){
                        linea = linea.replace(/{Pcosto}/g, costo);
                        linea = linea.replace(/{PcodigoT}/g, codigoT);
                    }
                   
                    
                    $("#cargarItemProcedimiento tbody").append(linea);      
                    if (costo>0) {
                        var pala= "#Pcheckbox"+i;
                        $(pala).prop('checked', true); 
                        unlockRow(i);
                    }               
                }
                
                cont = data.length
            },
        });
    }        
    function cargarProcedimientoAsignados(cod){ 
        var auxiliarValor = 0; 
        var auxiliarCodigo = 0; 
         
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ url("procedimientosAsignados/searchN") }}',
            dataType: "json",
            async: false,
            type: "POST",
            data: {
                procedimiento: cod,
                aseguradora: document.getElementById("cliente_id").value
            },            
            success: function(data) { 
                if(data != ''){
                    if (data[0].procedimientoA_valor > 0){
                        auxiliarValor = data[0].procedimientoA_valor;     
                        auxiliarCodigo = data[0].procedimientoA_codigo;                                   
                    } 
                }        
            },
            error: function(){ 
                alert("error petición ajax");
            },
            
        });
        return auxiliarValor+','+auxiliarCodigo;          
    } 

    function extraerValor(cadena,separador) {
        var valor = 0;
        var arrayDeCadenas = cadena.split(separador);

        for (var i=0; i < arrayDeCadenas.length; i++) {
            valor = arrayDeCadenas[0];
            console.log(arrayDeCadenas[0]);
        }
        return valor;
    }

    function extraerCodigo(cadena,separador) {
        var codigo = 0;
        var arrayDeCadenas = cadena.split(separador);

        for (var i=0; i < arrayDeCadenas.length; i++) {
            codigo = arrayDeCadenas[1];
            console.log(arrayDeCadenas[1]);
        }
        return codigo;
    }

    function unlockRow(id){                 

            if ($("#Pcheckbox"+id).is(':checked') ) {
                $("#Pcosto"+id).css("background-color", "white");
                $("#Pcosto"+id).attr("readonly",false);
                $("#PcodigoT"+id).css("background-color", "white");
                $("#PcodigoT"+id).attr("readonly",false);
            } else {
                $("#Pcosto"+id).css("background-color", "#e9ecef;");
                $("#PcodigoT"+id).css("background-color", "#e9ecef;");
                $("#Pcosto"+id).attr("readonly",true); 
                $("#PcodigoT"+id).attr("readonly",true);                

                document.getElementById("Pcosto"+id).value = "0";
                document.getElementById("PcodigoT"+id).value = "0";
            }
   
    }
</script>
@endsection