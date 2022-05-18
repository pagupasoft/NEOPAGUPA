<table>
    <tr>
        <td colspan="10" style="text-align: center;">NEOPAGUPA | Sistema Contable</td>
    </tr>
    <tr>
        <td colspan="10" style="text-align: center;">Rol Detallado Empleado</td>
    </tr>
</table>
<table>
    <thead>
        <?php $rol=$datos[1]; $datos=$datos[2];   ?>
        <tr class="text-center">
            <th></th>  
            <th colspan="7">Ingresos</th>  
            <th colspan="13">Egresos</th> 
            <th colspan="9">Beneficios</th> 
            <th ></th> 
        </tr>   
        <tr class="text-center">
        <th  >Empleado </th>
                <th  >Sueldo</th>                        
                <th  >Horas Extras </th>
                <th  >Vacaciones </th> 
                <th  >Viaticos </th>                       
                <th  >otros Bonificaciones </th>
                <th  >otros Ingresos </th>
                <th  >Total Ingresos </th>

                <th  >EXT. Salud</th>
                <th  >Ley salud</th>
                <th  >Comisariato </th>      
                <th  >PPQQ </th>
                <th  >Prestamos Hipotecarios</th>
                <th  >Prestamos </th>
                <th  >Multas</th>
                <th  >IESS Asum.</th>
                <th  >Apr. Perso</th>
                <th  >Anticipos</th>
                <th  >Imp. Renta</th>
                <th  >Otros Egresos</th>
                <th  >Total Egresos</th>

                <th  >Apr. Patro</th>
                <th  >Vacaciones Pagadas</th>
                <th  >Dec. Tercero</th>
                <th  >Dec. Cuarto</th>
                <th  >F. Reser.</th>
                <th  >IECE / SETEC</th>
                
                <th  >Liq. Pagar</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($rol))
            @if(count($rol)>0)
                @foreach($rol as $roles)
                <tr>
                <td >  {{$roles->empleado_nombre}}</td>
                <td >{{number_format($roles->sueldos, 2)}}</td>
                <td >{{number_format($roles->extras , 2)}}</td>
                <td >{{number_format($roles->vacaciones , 2)}}</td>
                <td >{{number_format($roles->transporte , 2)}}</td>
                <td >{{number_format($roles->otrabonifi, 2)}} </td>
                <td >{{number_format($roles->otrosingresos, 2)}} </td> 
                <td  >{{number_format($roles->ingresos, 2)}}</td>
                <td >{{number_format($roles->extsalud, 2)}} </td>
                <td >{{number_format($roles->leysal, 2) }}</td>
            
                <td >{{number_format($roles->comisariato, 2)}}</td>
                <td >{{number_format($roles->ppqq, 2)}} </td>
                <td >{{number_format($roles->hipoteca, 2)}}</td> 
                <td >{{number_format($roles->prestamos, 2)}} </td>
                <td >{{number_format($roles->multas , 2)}}</td>
                <td >{{number_format($roles->asumido , 2)}}</td>
                <td >{{number_format($roles->personal, 2) }}</td>
                <td >{{number_format($roles->anticipo, 2) }}</td>
              
                <td >{{number_format($roles->impu_renta, 2) }}</td>

                <td >{{number_format($roles->otrosegre, 2)}} </td>
                <td >{{number_format($roles->egresos, 2)}}</td>
                <td >{{number_format($roles->patronal, 2)}}</td>
                <td >{{number_format($roles->vacacionespag, 2)}}</td>
                <td  @if(number_format($roles->tercero, 2)>0) style="background:  #70B1F7;"  @else style="background:  #B1E2DD;"  @endif  >@if(number_format($roles->tercero, 2)>0) {{number_format($roles->tercero, 2)}} @else {{number_format($roles->terceroacum, 2)}}  @endif</td>                   
                <td  @if(number_format($roles->cuarto, 2)>0) style="background:  #70B1F7;"  @else style="background:  #B1E2DD;"  @endif  >@if(number_format($roles->cuarto, 2)>0) {{number_format($roles->cuarto, 2)}} @else {{number_format($roles->cuartoacum, 2)}}  @endif</td>                   
                <td  @if(number_format($roles->fondo_reserva, 2)>0) style="background:  #70B1F7;"  @else style="background:  #B1E2DD;"  @endif  >@if(number_format($roles->fondo_reserva, 2)>0) {{number_format($roles->fondo_reserva, 2)}} @else {{number_format($roles->fondoacumula, 2)}}  @endif</td>
                <td >{{number_format($roles->iecesecap, 2)}}</td>
                
                <td >{{number_format($roles->liquido_pagar, 2)}}</td>

                </tr>       
                @endforeach
            @endif
        @endif 
    </tbody>
</table>