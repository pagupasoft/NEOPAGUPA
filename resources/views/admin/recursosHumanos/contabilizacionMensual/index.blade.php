@extends ('admin.layouts.admin')
@section('principal')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card card-primary ">
    
    @csrf
        <div class="row">
            
            <!-- Tabla de detalles -->
            <div class="col-sm-12">
                
                <div  class="row">  
                    <div  class="col-md-12">
                            <div class="card-body " >  
                                <div class="row clearfix form-horizontal">
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label  "
                                       >
                                        <label >Mes y año</label>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" >  
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="month" name="fechames" id="fechames" class="form-control" value='<?php echo(date("Y")."-".date("m")); ?>' onclick="dias();" onchange="dias();" onkeyup="dias();">
                                                <input type="hidden" id="fecha_desde" name="fecha_desde" >
                                                <input type="hidden" id="fecha_hasta" name="fecha_hasta" >
                                                
                                            </div>
                                            
                                        </div> 
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 "
                                       >
                                        <button type="submit" id="extraerID" name="extraerID" class="btn btn-default btn-sm float-left"><i class="fas fa-plus"></i>Cargar</button > 
                                    </div>
                                    
                                  

                                   
                                </div>  
                                                                  
                            </div>      
                    </div>
                </div> 
                  
               
                <div class="col-sm-12">
                        <div class="tabladetallerol card-body table-responsive p-0" style="height: 350px;">        
                            <table  class="tabladetallerol table table-bordered table-head-fixed text-nowrap">
                                <thead>
                                    <tr >
                                        
                                        <th  class=" text-center-encabesado"> </th>
                                        <th class="ingresos text-center-encabesado" colspan="7">Ingresos</th>                        
                                        <th class=" egresos text-center-encabesado" colspan="13">Egresos </th>
                                        <th class="provisiones text-center-encabesado" colspan="8">Provisiones </th>
                                                        
                                    </tr>  
                                    <tr >
                                        <th  class="text-center-encabesado">Empleado </th>
                                        <th class="text-center-encabesado">Sueldo</th>                        
                                        <th class="text-center-encabesado">Horas Extras </th>
                                        <th class="text-center-encabesado">Bonificaciones </th>
                                        <th class="text-center-encabesado">Viaticos </th>                       
                                        <th class="text-center-encabesado">otros Bonificaciones </th>
                                        <th class="text-center-encabesado">otros Ingresos </th>
                                        <th class="text-center-encabesado">Total Ingresos </th>

                                        <th class="text-center-encabesado">EXT. Salud</th>
                                        <th class="text-center-encabesado">Ley salud</th>
                                        <th class="text-center-encabesado">Comisariato </th>      
                                        <th class="text-center-encabesado">PPQQ </th>
                                        <th class="text-center-encabesado">Prestamos Hipotecarios</th>
                                        <th class="text-center-encabesado">Prestamos </th>
                                        <th class="text-center-encabesado">Multas</th>
                                        <th class="text-center-encabesado">IESS Asum.</th>
                                        <th class="text-center-encabesado">Apr. Perso</th>
                                        <th class="text-center-encabesado">Anticipos</th>
                                        <th class="text-center-encabesado">Imp. Renta</th>
                                        <th class="text-center-encabesado">Otros Egresos</th>
                                        <th class="text-center-encabesado">Total Egresos</th>

                                        <th class="text-center-encabesado">Apr. Patro</th>
                                        <th class="text-center-encabesado">Vacaciones</th>
                                        <th class="text-center-encabesado">Dec. Tercero</th>
                                        <th class="text-center-encabesado">Dec. Cuarto</th>
                                        <th class="text-center-encabesado">F. Reser.</th>
                                        <th class="text-center-encabesado">F. Reser. Acu.</th>
                                        <th class="text-center-encabesado">IECE / SETEC</th>
                                        <th class="text-center-encabesado">Liq. Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($rol))
                                @if(count($rol)>0)
                                    @foreach($rol as $roles)
                                    <tr >
                                    <td width="150" class="text-center"><input type="hidden" class="form-controltext"   name="idrol[]" value="" required readonly> {{$roles->empleado_nombre}}</td>
                                    <td width="150" class="text-center">{{$roles->sueldos}}</td>
                                    <td width="150" class="text-center">{{$roles->extras }}</td>
                                    <td width="150" class="text-center">{{$roles->bonificaciones}}</td>
                                    <td width="150" class="text-center">{{$roles->transporte }}</td>
                                    <td width="150" class="text-center">{{$roles->otrabonifi}} </td>
                                    <td width="150" class="text-center">{{$roles->otrosingresos}} </td> 
                                    <td  width="150" class="text-center">{{$roles->ingresos}}</td>
                                    <td width="150" class="text-center">{{$roles->extsalud}} </td>
                                    <td width="150" class="text-center">{{$roles->leysal }}</td>
                                
                                    <td width="150" class="text-center">{{$roles->comisariato}}</td>
                                    <td width="150" class="text-center">{{$roles->ppqq}} </td>
                                    <td width="150" class="text-center">{{$roles->hipoteca}}</td> 
                                    <td width="150" class="text-center">{{$roles->prestamos}} </td>
                                    <td width="150" class="text-center">{{$roles->multas }}</td>
                                    <td width="150" class="text-center">{{$roles->asumido }}</td>
                                    <td width="150" class="text-center">{{$roles->personal }}</td>
                                    <td width="150" class="text-center">{{$roles->anticipo }}</td>
                                    <td width="150" class="text-center">{{$roles->impu_renta }}</td>

                                    <td width="150" class="text-center">{{$roles->otrosegre}} </td>
                                    <td width="150" class="text-center">{{$roles->egresos }}</td>

                                    <td width="150" class="text-center">{{$roles->patronal}}</td>
                                    <td width="150" class="text-center">{{$roles->vacaciones}}</td>
                                    <td width="150" class="text-center">{{$roles->tercero}}</td>
                                    <td width="150" class="text-center">{{$roles->cuarto}}</td>
                                    <td width="150" class="text-center">{{$roles->fondo_reserva}}</td>
                                    <td width="150" class="text-center">{{$roles->acumula}}</td>
                                    <td width="150" class="text-center">{{$roles->iecesecap}}</td>
                                    <td width="150" class="text-center">{{$roles->liquido_pagar}}</td>

                                    </tr>       
                                    @endforeach
                                @endif
                                @endif
                                </tbody>
                            </table>
                        </div>
                    
                </div>
                <div class="col-sm-12">
                        <div class="tabladetallerol card-body table-responsive p-0" style="height: 350px;">        
                            <table  class="tabladetallerol table table-bordered table-head-fixed text-nowrap">
                                <thead>
                                    <tr >       
                                        <th  class="ingresos text-center-encabesado " colspan="2"> Ingresos</th>
                                        <th class="egresos text-center-encabesado" colspan="2">Egresos</th>                        
                                        <th class="provisiones text-center-encabesado" colspan="2">Provisiones </th>               
                                    </tr>  
                                </thead>
                                <tbody>
                                @if(isset($rol))
                                @if(count($rol)>0)
                                    @for ($i = 1; $i <= count($datos); ++$i)  
                                    <tr >       
                                         <td class="tipo text-center-encabesado"colspan="6"> {{ $datos[$i]['tipo'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">sueldo </td>
                                        <td>{{ $datos[$i]['sueldos'] }} </td>
                                        <td class="encabesado-izquierda">extsalud </td>
                                        <td>{{ $datos[$i]['extsalud'] }} </td>
                                        <td class="encabesado-izquierda">Apor. Patro. </td>
                                        <td>{{ $datos[$i]['patronal'] }} </td>
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">bonificaciones </td>
                                        <td>{{ $datos[$i]['bonificaciones'] }} </td>
                                        <td class="encabesado-izquierda">leysal </td>
                                        <td>{{ $datos[$i]['leysal'] }} </td>
                                        <td class="encabesado-izquierda">Vacaciones </td>
                                        <td> {{ $datos[$i]['vacaciones'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Horas Extras </td>
                                        <td>{{ $datos[$i]['extras'] }} </td>
                                        <td class="encabesado-izquierda"> Prestamos Quirografarios</td>
                                        <td>{{ $datos[$i]['ppqq'] }} </td>
                                        <td class="encabesado-izquierda">Deci. Tercero </td>
                                        <td>{{ $datos[$i]['tercero'] }}  </td>
                                    
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Transporte </td>
                                        <td>{{ $datos[$i]['transporte'] }} </td>
                                        <td class="encabesado-izquierda"> Hipoteca</td>
                                        <td>{{ $datos[$i]['hipoteca'] }} </td>
                                        <td class="encabesado-izquierda">Deci. Cuarto</td>
                                        <td>{{ $datos[$i]['cuarto'] }} </td>
                                        
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Otras Bonificaciones </td>
                                        <td>{{ $datos[$i]['otrabonifi'] }} </td>
                                        <td class="encabesado-izquierda">Comisariato</td>
                                        <td>{{ $datos[$i]['comisariato'] }} </td>
                                        <td class="encabesado-izquierda">F. Reserva</td>
                                        <td>{{ $datos[$i]['fondo_reserva'] }} </td>
                                        
                                    </tr>                    
                                    <tr>
                                        <td class="encabesado-izquierda">Otras Ingresos  </td>
                                        <td>{{ $datos[$i]['otrosingresos'] }}</td>
                                        <td class="encabesado-izquierda">IESS Asum.</td>
                                        <td>{{ $datos[$i]['asumido'] }} </td>
                                        <td class="encabesado-izquierda">F. Reserva(Acu)</td>
                                        <td>{{ $datos[$i]['acumula'] }}</td>
                                    
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda"> Total Ingresos</td>
                                        <td>{{ $datos[$i]['ingresos'] }} </td>
                                        <td class="encabesado-izquierda">Apor. Perso.</td>
                                        <td>{{ $datos[$i]['personal'] }} </td>
                                        <td class="encabesado-izquierda"> IESCE/SECAP</td>
                                        <td> {{ $datos[$i]['iecesecap'] }}</td>
                                    
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Anticipos</td>
                                        <td>{{ $datos[$i]['anticipo'] }} </td>
                                        <td class="encabesado-izquierda">Liquido Pagar</td>
                                        <td>{{ $datos[$i]['liquido_pagar'] }} </td>
                                    
                                    </tr>
                                    
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Impu. Renta</td>
                                        <td>{{ $datos[$i]['impu_renta'] }} </td>
                                        <td></td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td> </td>
                                        <td class="encabesado-izquierda">Multas</td>
                                        <td> {{ $datos[$i]['multas'] }}</td>
                                        <td></td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Otros Egresos</td>
                                        <td>{{ $datos[$i]['otrosegre'] }} </td>
                                        <td></td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Total Egresos</td>
                                        <td>{{ $datos[$i]['egresos'] }} </td>
                                        <td></td>
                                        <td> </td>
                                    </tr>
                                    @endfor
                                @endif 
                                @endif 
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
        
        
   
</div>

<script type="text/javascript">
function cargarmetodo() {
    dias();

}
function nuevo() {  
    document.getElementById("guardarID").disabled = false;
    document.getElementById("cancelarID").disabled = false;
 
}
function obtenerNombreMes (numero) {
  let miFecha = new Date();
  if (0 < numero && numero <= 12) {
    miFecha.setMonth(numero - 1);
    return new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(miFecha);
  } else {
    return null;
  }
}


function dias(){
    let fecha2 = new Date(document.getElementById("fechames").value);
    fecha2.setMinutes(fecha2.getMinutes() + fecha2.getTimezoneOffset());
    var anioactual = fecha2.getFullYear();
    var _mesactual = fecha2.getMonth()+1;
    var diasMes = new Date(anioactual, _mesactual, 0).getDate();
    if (_mesactual < 10) //ahora le agregas un 0 para el formato date
    {
    var mesactual = "0" + _mesactual;
    } else {
    var mesactual = _mesactual;
    }
   
    let fecha_minimo = anioactual + '-' + mesactual + '-01'; 
    let fecha_maximo = anioactual + '-' + mesactual + '-' + diasMes; 

    document.getElementById("fecha_desde").value = fecha_minimo;
    
    document.getElementById("fecha_hasta").value = fecha_maximo;

}


</script>
@endsection