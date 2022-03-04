@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ route('cuentaBancaria.guardarConfCheque', [$cuentaBancaria->cuenta_bancaria_id]) }}">
@csrf
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Configuracion de Cheque</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button type="button" onclick='window.location = "{{ url("cuentaBancaria") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <div id="id1" draggable="true" ondragstart="onDragStart(event)" style="position:absolute;left:650px;top: 500px;cursor:pointer;width:400px;height:50px; font-weight: bold;">JUAN PIWABE ORTIZ</div>
            <input id="idBeneficiariox" name="idBeneficiariox" type="hidden">
            <input id="idBeneficiarioy" name="idBeneficiarioy" type="hidden">
            <div id="id2" draggable="true" ondragstart="onDragStart(event)" style="position:absolute;left:650px;top: 550px;cursor:pointer;width:100px;height:50px; font-weight: bold;">15805.89</div>
            <input id="idValorx" name="idValorx" type="hidden">
            <input id="idValory" name="idValory" type="hidden">
            <div id="id3" draggable="true" ondragstart="onDragStart(event)" style="position:absolute;left:650px;top: 600px;cursor:pointer;width:200px;height:18px; font-weight: bold;">MACHALA, 27/12/2021</div>
            <input id="idFechax" name="idFechax" type="hidden">
            <input id="idFechay" name="idFechay" type="hidden">
            <div id="id4" draggable="true" ondragstart="onDragStart(event)" style="position:absolute;left:650px;top: 650px;cursor:pointer;width:450px;height:50px; font-weight: bold;">QUINCE MIL OCHOCIENTOS CINCO CON 89/100</div>
            <input id="idLetrasx" name="idLetrasx" type="hidden">
            <input id="idLetrasy" name="idLetrasy" type="hidden">

            <div id="contenedor" style="position:absolute;left:5px;top:90px;border:2px solid #91A9C9; cursor:pointer;width: 99%;height: 250%;" ondrop="drop_handler(event)" ondragover="dragover_handler(event)"></div>

        </div>            
    </div>   
</form>
<script type="text/javascript">    
    let offsetX;
    let offsetY;
    var objeto=''; 

    @if(isset($chequeImpresion)) 
        @if($chequeImpresion)
            /*beneficiario*/
            xAnterior = '<?=$chequeImpresion->chequei_beneficiariox?>';
            xAnterior = Number(xAnterior);
            yAnterior = '<?=$chequeImpresion->chequei_beneficiarioy?>';
            yAnterior = Number(yAnterior);
            document.getElementById("idBeneficiariox").value = xAnterior;
            document.getElementById("idBeneficiarioy").value = yAnterior;
            var d = document.getElementById('id1');
            d.style.position = "absolute";
            d.style.left = xAnterior+'px';
            d.style.top = yAnterior+'px';
            contenedor.appendChild(document.getElementById("id1"));  
            /*************/   
            /*valor*/
            xAnterior = '<?=$chequeImpresion->chequei_valorx?>';
            xAnterior = Number(xAnterior);
            yAnterior = '<?=$chequeImpresion->chequei_valory?>';
            yAnterior = Number(yAnterior);
            document.getElementById("idValorx").value = xAnterior;
            document.getElementById("idValory").value = yAnterior;
            var d = document.getElementById('id2');
            d.style.position = "absolute";
            d.style.left = xAnterior+'px';
            d.style.top = yAnterior+'px';
            contenedor.appendChild(document.getElementById("id2"));  
            /*************/ 
            /*fecha*/
            xAnterior = '<?=$chequeImpresion->chequei_fechax?>';
            xAnterior = Number(xAnterior);
            yAnterior = '<?=$chequeImpresion->chequei_fechay?>';
            yAnterior = Number(yAnterior);
            document.getElementById("idFechax").value = xAnterior;
            document.getElementById("idFechay").value = yAnterior;
            var d = document.getElementById('id3');
            d.style.position = "absolute";
            d.style.left = xAnterior+'px';
            d.style.top = yAnterior+'px';
            contenedor.appendChild(document.getElementById("id3"));  
            /*************/ 
            /*letras*/
            xAnterior = '<?=$chequeImpresion->chequei_letrasx?>';
            xAnterior = Number(xAnterior);
            yAnterior = '<?=$chequeImpresion->chequei_letrasy?>';
            yAnterior = Number(yAnterior);
            document.getElementById("idLetrasx").value = xAnterior;
            document.getElementById("idLetrasy").value = yAnterior;
            var d = document.getElementById('id4');
            d.style.position = "absolute";
            d.style.left = xAnterior+'px';
            d.style.top = yAnterior+'px';
            contenedor.appendChild(document.getElementById("id4"));  
            /*************/   
        @endif
    @endif
    
    function onDragStart(ev){       
        const rect = ev.target.getBoundingClientRect();
        offsetX = ev.clientX - rect.x;
        offsetY = ev.clientY - rect.y;
        objeto = ev.path[0].id;
    }
    function drop_handler(ev){
        ev.preventDefault();
        const left = parseInt(contenedor.style.left);
        const top = parseInt(contenedor.style.top);
        if(objeto == 'id1'){
            id1.style.position = 'absolute';
            id1.style.left = ev.clientX - left - offsetX + 'px';
            id1.style.top = ev.clientY - top - offsetY + 'px';
            contenedor.appendChild(document.getElementById("id1"));           
            document.getElementById("idBeneficiariox").value = ev.clientX - left - offsetX;
            document.getElementById("idBeneficiarioy").value = ev.clientY - top - offsetY;

        }
        if(objeto == 'id2'){
            id2.style.position = 'absolute';
            id2.style.left = ev.clientX - left - offsetX + 'px';
            id2.style.top = ev.clientY - top - offsetY + 'px';
            contenedor.appendChild(document.getElementById("id2"));
            document.getElementById("idValorx").value = ev.clientX - left - offsetX;
            document.getElementById("idValory").value = ev.clientY - top - offsetY;
        }   
        if(objeto == 'id3'){
            id3.style.position = 'absolute';
            id3.style.left = ev.clientX - left - offsetX + 'px';
            id3.style.top = ev.clientY - top - offsetY + 'px';
            contenedor.appendChild(document.getElementById("id3"));
            document.getElementById("idFechax").value = ev.clientX - left - offsetX;
            document.getElementById("idFechay").value = ev.clientY - top - offsetY;                
        } 
        if(objeto == 'id4'){
            id4.style.position = 'absolute';
            id4.style.left = ev.clientX - left - offsetX + 'px';
            id4.style.top = ev.clientY - top - offsetY + 'px';
            contenedor.appendChild(document.getElementById("id4"));
            document.getElementById("idLetrasx").value = ev.clientX - left - offsetX;
            document.getElementById("idLetrasy").value = ev.clientY - top - offsetY;    
        } 
    }
    function dragover_handler(ev){
        ev.preventDefault();
        ev.dataTransfer.dropEffect = "move";
    }
    function agregahijos(){         
        alert('entro');
        cont = 1;
        if(objeto == 'id1' && cont == 1){
            alert('entro');
            contenedor.append(document.getElementById("id1"));
        }
    }
</script>
@endsection