@extends ('admin.layouts.admin')
@section('principal')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card card-primary ">
    <form method="POST"  action="{{ url("contabilizado/extraer") }} "> 
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
                                    <div class="col-lg-1"
                                       >
                                        <button type="submit" id="extraerID" name="extraerID" class="btn btn-default btn-sm float-left"><i class="fas fa-plus"></i>Cargar</button > 
                                        <button type="submit" id="guardarID" name="guardarID" class="btn btn-default btn-sm float-left"><i class="fas fa-save"></i>Guardar</button > 
                                   
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
                                        <th class="ingresos text-center-encabesado" colspan="6">Ingresos</th>                        
                                        <th class=" egresos text-center-encabesado" colspan="13">Egresos </th>
                                        <th class="provisiones text-center-encabesado" colspan="8">Provisiones </th>
                                                        
                                    </tr>  
                                    <tr >
                                        <th  class="text-center-encabesado">Empleado </th>
                                        <th class="text-center-encabesado">Sueldo</th>                        
                                        <th class="text-center-encabesado">Horas Extras </th>
                      
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
                                        <th class="text-center-encabesado">Dec. Tercero ACU</th>
                                        <th class="text-center-encabesado">Dec. Cuarto</th>
                                        <th class="text-center-encabesado">Dec. Cuarto ACU</th>
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
                                    <td width="150" class="text-center"><input type="hidden" class="form-controltext"   name="empleadoid[]" value="{{$roles->empleado_id}}" required readonly> {{$roles->empleado_nombre}}</td>
                                    <td width="150" class="text-center">{{$roles->sueldos}}</td>
                                    <td width="150" class="text-center">{{$roles->extras }}</td>
             
                                    <td width="150" class="text-center">{{$roles->transporte }}</td>
                                    <td width="150" class="text-center">{{$roles->otrabonifi}} </td>
                                    <td width="150" class="text-center">{{$roles->otrosingresos}} </td> 
                                    <td  width="150" class="text-center">{{number_format($roles->ingresos, 2)}}</td>
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
                                    <td width="150" class="text-center">{{number_format($roles->egresos, 2)}}</td>

                                    <td width="150" class="text-center">{{$roles->patronal}}</td>
                                    <td width="150" class="text-center">{{$roles->vacaciones}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->tercero, 2)}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->terceroacum, 2)}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->cuarto, 2)}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->cuartoacum, 2)}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->fondo_reserva, 2)}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->fondoacumula, 2)}}</td>

                                    
                                    <td width="150" class="text-center">{{$roles->iecesecap}}</td>
                                    <td width="150" class="text-center">{{number_format($roles->liquido_pagar, 2)}}</td>

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
                                         <input type="hidden" name="idtipo[]" id="idtipo[]" value="{{ $datos[$i]['idtipo'] }}">
                                         <input type="hidden" name="tipo[]" id="tipo[]" value="{{ $datos[$i]['tipo'] }}">
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">sueldo </td>
                                        <td>{{ $datos[$i]['sueldos'] }} </td>
                                        <input type="hidden" name="vsueldo[]" id="vsueldo[]" value="{{ $datos[$i]['sueldos'] }}">
                                        <td class="encabesado-izquierda">extsalud </td>
                                        <td>{{ $datos[$i]['extsalud'] }} </td>
                                        <input type="hidden" name="vextsalud[]" id="vextsalud[]" value="{{ $datos[$i]['extsalud'] }}">
                                        <td class="encabesado-izquierda">Apor. Patro. </td>
                                        <td>{{ $datos[$i]['patronal'] }} </td>
                                        <input type="hidden" name="vpatronal[]" id="vpatronal[]" value="{{ $datos[$i]['patronal'] }}">
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Horas Extras </td>
                                        <td>{{ $datos[$i]['extras'] }}</td>
                                        <input type="hidden" name="vextras[]" id="vextras[]" value="{{ $datos[$i]['extras'] }}">
                                        <td class="encabesado-izquierda">leysal </td>
                                        <td>{{ $datos[$i]['leysal'] }} </td>
                                        <input type="hidden" name="vleysal[]" id="vleysal[]" value="{{ $datos[$i]['leysal'] }}">
                                        <td class="encabesado-izquierda">Vacaciones </td>
                                        <td> {{ $datos[$i]['vacaciones'] }}</td>
                                        <input type="hidden" name="vvacaciones[]" id="vvacaciones[]" value="{{ $datos[$i]['vacaciones'] }}">
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Transporte</td>
                                        <td>{{ $datos[$i]['transporte'] }} </td>
                                        <input type="hidden" name="vtransporte[]" id="vtransporte[]" value="{{ $datos[$i]['transporte'] }}">
                                        <td class="encabesado-izquierda"> Prestamos Quirografarios</td>
                                        <td>{{ $datos[$i]['ppqq'] }} </td>
                                        <input type="hidden" name="vppqq[]" id="vppqq[]" value="{{ $datos[$i]['ppqq'] }}">
                                        <td class="encabesado-izquierda">Deci. Tercero </td>
                                        <td>{{ $datos[$i]['tercero'] }}  </td>
                                        <input type="hidden" name="vtercero[]" id="vtercero[]" value="{{ $datos[$i]['tercero'] }}">
                                    
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Otras Bonificaciones </td>
                                        <td>{{ $datos[$i]['otrabonifi'] }} </td>
                                        <input type="hidden" name="votrabonifi[]" id="votrabonifi[]" value="{{ $datos[$i]['otrabonifi'] }}">
                                        <td class="encabesado-izquierda"> Hipoteca</td>
                                        <td>{{ $datos[$i]['hipoteca'] }} </td>
                                        <input type="hidden" name="vhipoteca[]" id="vhipoteca[]" value="{{ $datos[$i]['hipoteca'] }}">
                                        <td class="encabesado-izquierda">Deci. Cuarto</td>
                                        <td>{{ $datos[$i]['cuarto'] }} </td>
                                        <input type="hidden" name="vcuarto[]" id="vcuarto[]" value="{{ $datos[$i]['cuarto'] }}">
                                        
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda">Otras Ingresos  </td>
                                        <td>{{ $datos[$i]['otrosingresos'] }} </td>
                                        <input type="hidden" name="votrosingresos[]" id="votrosingresos[]" value="{{ $datos[$i]['otrosingresos'] }}">
                                        <td class="encabesado-izquierda">Comisariato</td>
                                        <td>{{ $datos[$i]['comisariato'] }} </td>
                                        <input type="hidden" name="vcomisariato[]" id="vcomisariato[]" value="{{ $datos[$i]['comisariato'] }}">
                                        <td class="encabesado-izquierda">F. Reserva</td>
                                        <td>{{ $datos[$i]['fondo_reserva'] }} </td>
                                        <input type="hidden" name="vfondo_reserva[]" id="vfondo_reserva[]" value="{{ $datos[$i]['fondo_reserva'] }}">
                                        
                                    </tr>                    
                                    <tr>
                                        <td class="encabesado-izquierda"> Total Ingresos</td>
                                        <td>{{ $datos[$i]['ingresos'] }} </td>
                                        <td class="encabesado-izquierda">IESS Asum.</td>
                                        <td>{{ $datos[$i]['asumido'] }} </td>
                                       
                                        <input type="hidden" name="vasumido[]" id="vasumido[]" value="{{ $datos[$i]['asumido'] }}">
                                        <td class="encabesado-izquierda">F. Reserva(Acu)</td>
                                        <td>{{ $datos[$i]['fondo_reservaACU'] }}</td>
                                        <input type="hidden" name="vfondoacumula[]" id="vfondoacumula[]" value="{{ $datos[$i]['fondo_reservaACU'] }}">
                                    </tr>
                                    <tr>
                                        <td class="encabesado-izquierda"></td>
                                        <td> </td>
                                        <input type="hidden" name="vingresos[]" id="vingresos[]" value="{{ $datos[$i]['ingresos'] }}">
                                        <td class="encabesado-izquierda">Apor. Perso.</td>
                                        <td>{{ $datos[$i]['personal'] }} </td>
                                        <input type="hidden" name="vpersonal[]" id="vpersonal[]" value="{{ $datos[$i]['personal'] }}">
                                        <td class="encabesado-izquierda"> IESCE/SECAP</td>
                                        <td> {{ $datos[$i]['iecesecap'] }}</td>
                                        <input type="hidden" name="viecesecap[]" id="viecesecap[]" value="{{ $datos[$i]['iecesecap'] }}">
                                    
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Anticipos</td>
                                        <td>{{ $datos[$i]['anticipo'] }} </td>
                                        <input type="hidden" name="vanticipo" id="vanticipo" value="{{ $datos[$i]['anticipo'] }}">
                                        <td class="encabesado-izquierda">D. Tercero(Acu)</td>
                                        <td>{{ $datos[$i]['terceroACU'] }} </td>
                                        <input type="hidden" name="vterceroacu" id="vterceroacu" value="{{ $datos[$i]['terceroACU'] }}">
                                    
                                    </tr>
                                    
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Impu. Renta</td>
                                        <td>{{ $datos[$i]['impu_renta'] }} </td>
                                        <input type="hidden" name="vimpu_renta[]" id="vimpu_renta[]" value="{{ $datos[$i]['impu_renta'] }}">
                                        <td class="encabesado-izquierda">D. Cuarto(Acu)</td>
                                        <td>{{ $datos[$i]['cuartoACU'] }} </td>
                                        <input type="hidden" name="vcuartoacu" id="vcuartoacu" value="{{ $datos[$i]['cuartoACU'] }}">
                                    
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td> </td>
                                        <td class="encabesado-izquierda">Multas</td>
                                        <td> {{ $datos[$i]['multas'] }}</td>
                                        <input type="hidden" name="vmultas[]" id="vmultas[]" value="{{ $datos[$i]['multas'] }}">
                                        <<td class="encabesado-izquierda">Liquido Pagar</td>
                                        <td>{{ $datos[$i]['liquido_pagar'] }} </td>
                                        <input type="hidden" name="vliquido_pagar" id="vliquido_pagar" value="{{ $datos[$i]['liquido_pagar'] }}">
                                    
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Otros Egresos</td>
                                        <td>{{ $datos[$i]['otrosegre'] }} </td>
                                        <input type="hidden" name="votrosegre[]" id="votrosegre[]" value="{{ $datos[$i]['otrosegre'] }}">
                                        <td></td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td> </td>
                                        <td></td>
                                        <td class="encabesado-izquierda">Total Egresos</td>
                                        <td>{{ $datos[$i]['egresos'] }} </td>
                                        <input type="hidden" name="vegresos[]" id="vegresos[]" value="{{ $datos[$i]['egresos'] }}">
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
    </form>
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