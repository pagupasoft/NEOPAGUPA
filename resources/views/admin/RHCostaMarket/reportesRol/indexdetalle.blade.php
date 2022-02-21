@extends ('admin.layouts.admin')
@section('principal')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Lista de Roles</h3>    
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form class="form-horizontal"  method="POST" action="{{ url("rolreporteDetallado") }} ">
        @csrf 
            <div class="float-right">
                    <button type="submit" id="pdf" name="pdf" class="btn btn-secondary"><i class="fas fa-print"></i></button>

                   
            </div>   
            <div class="form-group row">
                <label for="fecha_desde" class="col-sm-1 col-form-label"><center>Fecha:</center></label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde"  value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' >
                </div>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta"  value='<?php echo(date("Y")."-".date("m")."-".date("d")); ?>' >
                </div>
                
                <label for="nombre_empleado" class="col-sm-1 col-form-label"><center>empleado:</center></label>
                <div class="col-sm-5">
                    <select class="custom-select select2" id="nombre_empleado" name="nombre_empleado" >
                        <option value="0" label>--TODOS--</option>                       
                        @foreach($empleado as $empleado)
                            <option  value="{{$empleado->empleado_id}}">{{$empleado->empleado_nombre}}</option>
                        @endforeach
                    </select>                                     
                </div> 
                <div class="col-sm-1">
                <button type="submit" id="buscar" name="buscar" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>   
            </div>
            <div class="card-body table-responsive p-0" style="height: 600px;">
                <table id="example1" class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th>Cedula</th>
                            <th>Nombre</th> 
                            @foreach($rubros as $rubro)
                                @if($rubro->rubro_tipo =='2')
                                <th>{{ $rubro->rubro_descripcion}}</th>  
                                @endif 
                            @endforeach
                            <th>Total Ingresos</th> 
                            @foreach($rubros as $rubro)
                                @if($rubro->rubro_tipo =='1')
                                <th>{{ $rubro->rubro_descripcion}}</th>  
                                @endif 
                            @endforeach
                            <th>Total Egresos</th> 
                            <th>Total</th>
                            @foreach($rubros as $rubro) 
                                @if($rubro->rubro_tipo =='3')
                                <th>{{ $rubro->rubro_descripcion}}</th>  
                                @endif 
                            @endforeach
                            @foreach($rubros as $rubro) 
                                @if($rubro->rubro_tipo =='4')
                                    <th>{{ $rubro->rubro_descripcion}}</th>  
                                @endif  
                            @endforeach
                            <th>Total A Pagar</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datos))
                            @for ($i = 1; $i <= count($datos); ++$i)  
                            <tr>  
                                <td class="text-center">{{ $datos[$i]['cedula'] }}</td>
                                <td class="text-center">{{ $datos[$i]['nombre'] }}</td>
                                @foreach($rubros as $rubro)
                                    @if($rubro->rubro_tipo =='2')
                                        <td class="text-center">{{$datos[$i][$rubro->rubro_nombre]}}</td>  
                                    @endif 
                                @endforeach
                                <td class="text-center">{{ $datos[$i]['totalingresos'] }}</td>
                                @foreach($rubros as $rubro)
                                    @if($rubro->rubro_tipo =='1')
                                        <td class="text-center">{{$datos[$i][$rubro->rubro_nombre]}}</td>  
                                    @endif 
                                @endforeach
                                <td class="text-center">{{ $datos[$i]['totalegresos'] }}</td>
                                <td class="text-center">{{ $datos[$i]['totalingresos']-$datos[$i]['totalegresos'] }}</td>
                                @foreach($rubros as $rubro) 
                                    @if($rubro->rubro_tipo =='3')
                                        <td class="text-center">{{ $datos[$i][$rubro->rubro_nombre]}}</td>
                                    @endif  
                                @endforeach
                                @foreach($rubros as $rubro) 
                                    @if($rubro->rubro_tipo =='4')
                                        <td class="text-center">{{ $datos[$i][$rubro->rubro_nombre]}}</td>
                                    @endif  
                                @endforeach
                                <td class="text-center">{{ $datos[$i]['total']}}</td>
                            </tr>
                            @endfor
                        @endif     
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
   <?php
  
    if(isset($fechahasta)){  
        ?>
         document.getElementById("fecha_hasta").value='<?php echo($fechahasta); ?>';
         <?php
    }
    if(isset($fechadesde)){  
        ?>
         document.getElementById("fecha_desde").value='<?php echo($fechadesde); ?>';
         <?php
    }
    if(isset($nombre_empleado)){  
    ?>
         document.getElementById("nombre_empleado").value='<?php echo($nombre_empleado); ?>';
         <?php
    }
   
    ?>
</script>
@endsection