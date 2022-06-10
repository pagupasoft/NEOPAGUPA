@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST" action="{{ route('piscina.update', [$piscina->piscina_id]) }}">
@method('PUT')
@csrf
<div class="card card-secondary">
    <div class="card-header">
            <h3 class="card-title">Editar Piscina</h3>
            <div class="float-right">
                <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i>&nbsp;Guardar</button>
                <button type="button" onclick='window.location = "{{ url("piscina") }}";' class="btn btn-default btn-sm"><i class="fa fa-undo"></i>&nbsp;Atras</button>
            </div>
        </div>
    <!-- /.card-header -->
    <div class="card-body">            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Codigo</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="idCodigo" name="idCodigo" placeholder="Codigo" value="{{$piscina->piscina_codigo}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="idNombre" name="idNombre" placeholder="Codigo" value="{{$piscina->piscina_nombre}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tipo</label>
                <div class="col-sm-10">
                    <select class="custom-select select2" id="idTipo" name="idTipo" required>
                        <option value="" label>--Seleccione una opcion--</option>
                        @foreach($tipos as $tipo)
                        <option value="{{$tipo->tipo_id}}" @if($piscina->tipo_id==$tipo->tipo_id) selected @endif>{{$tipo->tipo_nombre}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Largo</label>
                <div class="col-sm-10">

                    <input type="number" class="form-control" id="idLargo" name="idLargo" placeholder="Codigo"  value="{{$piscina->piscina_largo}}"  onclick="calculo();" onkeyup="calculo();" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ancho</label>
                <div class="col-sm-10">
                  
                    <input type="number" class="form-control" id="idAncho" name="idAncho" placeholder="Codigo"  value="{{$piscina->piscina_ancho}}" onclick="calculo();" onkeyup="calculo();"  required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Altura Maxima de Colummna Agua</label>
                <div class="col-sm-10">

                    <input type="number" class="form-control" id="idAltura" name="idAltura" placeholder="Codigo" value="{{$piscina->piscina_columna_agua}}"  onclick="calculo();" onkeyup="calculo();" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Area Espejo Agua</label>
                <div class="col-sm-10">
       
                    <input type="number" class="form-control" id="idArea" name="idArea" placeholder="Codigo" value="{{$piscina->piscina_espejo_agua}}" readonly   required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Volumen Agua</label>
                <div class="col-sm-10">
        
                    <input type="number" class="form-control" id="idVolumen" name="idVolumen" placeholder="Codigo" value="{{$piscina->piscina_volumen_agua}}" readonly required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Declinacion</label>
                <div class="col-sm-10">
 
                    <input type="number" class="form-control" id="idDeclinacion" name="idDeclinacion" placeholder="Codigo" value="{{$piscina->piscina_declinacion}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Numero Entradas Agua</label>
                <div class="col-sm-10">
                
                    <input type="number" class="form-control" id="idEntradas" name="idEntradas" placeholder="Codigo" value="{{$piscina->piscina_entrada_agua}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Numero Salidas Agua</label>
                <div class="col-sm-10">
   
                    <input type="number" class="form-control" id="idSalidas" name="idSalidas" placeholder="Numero Salidas Agua" value="{{$piscina->piscina_salida_agua}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Estado</label>
                <div class="col-sm-10">
                    <select class="custom-select select2" id="idTipoEstado" name="idTipoEstado" onchange="extraer();" required>
                        <option value="Seca" @if($piscina->piscina_salida_agua=='Seca') selected @endif>Seca</option>
                        <option value="En Preparacion" @if($piscina->piscina_salida_agua=='En Preparacion') selected @endif>En Preparacion</option>
                        <option value="En Produccion" @if($piscina->piscina_salida_agua=='En Produccion') selected @endif>En Produccion</option>
                    </select>
                </div>
            </div>
        </div>        
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
</form>
<script>
    function calculo(){
        document.getElementById("idArea").value = (document.getElementById("idLargo").value*document.getElementById("idAncho").value*document.getElementById("idAltura").value*264.2);
        document.getElementById("idVolumen").value = (document.getElementById("idLargo").value*document.getElementById("idAncho").value)/10000;
    }
</script>
@endsection