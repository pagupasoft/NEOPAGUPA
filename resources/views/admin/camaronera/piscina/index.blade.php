@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Piscina</h3>
        <button class="btn btn-default btn-sm float-right" data-toggle="modal" data-target="#modal-nuevo"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1" class="table table-bordered table-hover table-responsive sin-salto">
            <thead>
                <tr class="text-center neo-fondo-tabla">
                    <th></th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Largo</th>
                    <th>Ancho</th>
                    <th>Altura Maxima de Colummna Agua</th>
                    <th>Area Espejo Agua</th>
                    <th>Volumen Agua</th>
                    <th>Declinacion</th>
                    <th>Numero Entradas Agua</th>
                    <th>Numero Salidas Agua</th>   
                    <th>Estado</th>                         
                </tr>
            </thead>            
            <tbody>
                @foreach($piscinas as $piscina)
                <tr class="text-center">
                    <td>
                        <a href="{{ url("piscina/{$piscina->piscina_id}/edit") }}" class="btn btn-xs btn-primary"  data-toggle="tooltip" data-placement="top" title="Ediar"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="{{ url("piscina/{$piscina->piscina_id}") }}" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="top" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <a href="{{ url("piscina/{$piscina->piscina_id}/eliminar") }}" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td>{{ $piscina->piscina_codigo}}</td>  
                    <td>{{ $piscina->piscina_nombre}}</td>   
                    <td>{{ $piscina->tipopiscina->tipo_nombre}}</td>  
                    <td>{{ $piscina->piscina_largo}}</td>
                    <td>{{ $piscina->piscina_ancho}}</td>  
                    <td>{{ $piscina->piscina_columna_agua}}</td>
                    <td>{{ $piscina->piscina_espejo_agua}}</td>  
                    <td>{{ $piscina->piscina_volumen_agua}}</td>  
                    <td>{{ $piscina->piscina_declinacion}}</td>
                    <td>{{ $piscina->piscina_entrada_agua}}</td>    
                    <td>{{ $piscina->piscina_salida_agua}}</td>    
                    <td>{{ $piscina->piscina_tipo_estado}}</td>    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
<div class="modal fade" id="modal-nuevo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h4 class="modal-title">Nueva Piscina</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ url("piscina") }}">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                   
                        <div class="form-group row">
                            <label for="idCodigo" class="col-sm-3 col-form-label">Codigo</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="idCodigo" name="idCodigo" placeholder="Codigo" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idNombre" class="col-sm-3 col-form-label">Nombre</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="idNombre" name="idNombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idTipo" class="col-sm-3 col-form-label">Tipo Piscina</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="idTipo" name="idTipo" required>
                                    <option value="" label>--Seleccione una opcion--</option>
                                    @foreach($tipos as $tipo)
                                    <option value="{{$tipo->tipo_id}}">{{$tipo->tipo_nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                                          
                        <div class="form-group row">
                            <label for="idLargo" class="col-sm-3 col-form-label">Largo (Mts)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idLargo" name="idLargo" placeholder="Largo" onclick="calculo();" onkeyup="calculo();" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idAncho" class="col-sm-3 col-form-label">Ancho (Mts)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idAncho" name="idAncho" placeholder="Ancho" onclick="calculo();" onkeyup="calculo();" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idAltura" class="col-sm-3 col-form-label">Altura Maxima de Columna de Agua (Mts)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idAltura" name="idAltura" placeholder="Altura Maxima de Columna de Agua" onclick="calculo();" onkeyup="calculo();" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idArea" class="col-sm-3 col-form-label">Area de Espejo de Agua (Mts)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idArea" name="idArea" placeholder="Area de Espejo de Agua" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idArea" class="col-sm-3 col-form-label">Volumen de Agua (Gts)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idVolumen" name="idVolumen" placeholder="Area de Espejo de Agua" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idDeclinacion" class="col-sm-3 col-form-label">Declinacion (%)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idDeclinacion" name="idDeclinacion" placeholder="Declinacion" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idEntradas" class="col-sm-3 col-form-label">Numero de Entradas de Agua</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idEntradas" name="idEntradas" placeholder="Numero de Entradas de Agua" required>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label for="idSalidas" class="col-sm-3 col-form-label">Numero de Salidas de Agua</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" step="any" id="idSalidas" name="idSalidas" placeholder="Numero de Salidas de Agua" required>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label for="idTipoEstado" class="col-sm-3 col-form-label">Tipo Piscina</label>
                            <div class="col-sm-9">
                                <select class="custom-select select2" id="idTipoEstado" name="idTipoEstado" onchange="extraer();" required>
                                    <option value="Seca" label>Seca</option>
                                    <option value="En Preparacion" label>En Preparacion</option>
                                    <option value="En Produccion" label>En Produccion</option>
                                </select>
                               
                            </div>
                        </div>                                                               
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    function calculo(){
        document.getElementById("idArea").value = (document.getElementById("idLargo").value*document.getElementById("idAncho").value*document.getElementById("idAltura").value*264.2);
        document.getElementById("idVolumen").value = (document.getElementById("idLargo").value*document.getElementById("idAncho").value)/10000;
    }
</script>
@endsection