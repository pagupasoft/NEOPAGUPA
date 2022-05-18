@extends ('admin.layouts.formatoPDF')
@section('contenido')
    @section('titulo')
        <tr><td colspan="2" class="centrar letra15 negrita">Reporte de Empleados</td></tr>
        <tr><td colspan="2" class="centrar letra15 borde-gris">FECHA :  {{ $desde }}  AL {{ $hasta }} </td></tr>
    @endsection

    <table style="white-space: normal!important;" id="tabladetalle">
        <thead>
            <tr style="border: 1px solid black;" class="centrar letra12">
                <th></th>
                <th colspan="7">Ingresos</th>  
                <th colspan="13">Egresos</th> 
                <th colspan="9">Beneficios</th> 
                <th ></th> 
            </tr>
            <tr style="border: 1px solid black;" class="centrar letra12">
                <th style="font-size: 5px;">Empleado </th>
                <th style="font-size: 5px;">Sueldo</th>                        
                <th style="font-size: 5px;">Horas Extras </th>
                <th style="font-size: 5px;">Vacaciones </th> 
                <th style="font-size: 5px;">Viaticos </th>                       
                <th style="font-size: 5px;">otros Bonificaciones </th>
                <th style="font-size: 5px;">otros Ingresos </th>
                <th style="font-size: 5px;">Total Ingresos </th>

                <th style="font-size: 5px;">EXT. Salud</th>
                <th style="font-size: 5px;">Ley salud</th>
                <th style="font-size: 5px;">Comisariato </th>      
                <th style="font-size: 5px;">PPQQ </th>
                <th style="font-size: 5px;">Prestamos Hipotecarios</th>
                <th style="font-size: 5px;">Prestamos </th>
                <th style="font-size: 5px;">Multas</th>
                <th style="font-size: 5px;">IESS Asum.</th>
                <th style="font-size: 5px;">Apr. Perso</th>
                <th style="font-size: 5px;">Anticipos</th>
                <th style="font-size: 5px;">Imp. Renta</th>
                <th style="font-size: 5px;">Otros Egresos</th>
                <th style="font-size: 5px;">Total Egresos</th>

                <th style="font-size: 5px;">Apr. Patro</th>
                <th style="font-size: 5px;">Vacaciones Pagadas</th>
                <th style="font-size: 5px;">Dec. Tercero</th>
                <th style="font-size: 5px;">Dec. Cuarto</th>
                <th style="font-size: 5px;">F. Reser.</th>
                <th style="font-size: 5px;">IECE / SETEC</th>
                
                <th style="font-size: 5px;">Liq. Pagar</th>
            </tr>
            
           
        </thead>
        <tbody>
            @if(isset($rol))
                @if(count($rol)>0)
                    @foreach($rol as $roles)
                    <tr style="font-size: 7px; border: 1px solid black;">
                    <td style="border: 1px solid black;"><input type="hidden" class="form-controltext"   name="empleadoid[]" value="{{$roles->empleado_id}}" required readonly> {{$roles->empleado_nombre}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->sueldos, 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->extras , 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->vacaciones , 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->transporte , 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->otrabonifi, 2)}} </td>
                    <td style="border: 1px solid black;">{{number_format($roles->otrosingresos, 2)}} </td> 
                    <td  style="border: 1px solid black;">{{number_format($roles->ingresos, 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->extsalud, 2)}} </td>
                    <td style="border: 1px solid black;">{{number_format($roles->leysal, 2) }}</td>
                
                    <td style="border: 1px solid black;">{{number_format($roles->comisariato, 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->ppqq, 2)}} </td>
                    <td style="border: 1px solid black;">{{number_format($roles->hipoteca, 2)}}</td> 
                    <td style="border: 1px solid black;">{{number_format($roles->prestamos, 2)}} </td>
                    <td style="border: 1px solid black;">{{number_format($roles->multas , 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->asumido , 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->personal, 2) }}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->anticipo, 2) }}</td>
                  
                    <td style="border: 1px solid black;">{{number_format($roles->impu_renta, 2) }}</td>

                    <td style="border: 1px solid black;">{{number_format($roles->otrosegre, 2)}} </td>
                    <td style="border: 1px solid black;">{{number_format($roles->egresos, 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->patronal, 2)}}</td>
                    <td style="border: 1px solid black;">{{number_format($roles->vacacionespag, 2)}}</td>
                    <td  @if(number_format($roles->tercero, 2)>0) style="background:  #70B1F7;"  @else style="background:  #B1E2DD;"  @endif  >@if(number_format($roles->tercero, 2)>0) {{number_format($roles->tercero, 2)}} @else {{number_format($roles->terceroacum, 2)}}  @endif</td>                   
                    <td  @if(number_format($roles->cuarto, 2)>0) style="background:  #70B1F7;"  @else style="background:  #B1E2DD;"  @endif  >@if(number_format($roles->cuarto, 2)>0) {{number_format($roles->cuarto, 2)}} @else {{number_format($roles->cuartoacum, 2)}}  @endif</td>                   
                    <td  @if(number_format($roles->fondo_reserva, 2)>0) style="background:  #70B1F7;"  @else style="background:  #B1E2DD;"  @endif  >@if(number_format($roles->fondo_reserva, 2)>0) {{number_format($roles->fondo_reserva, 2)}} @else {{number_format($roles->fondoacumula, 2)}}  @endif</td>
                    <td style="border: 1px solid black;">{{number_format($roles->iecesecap, 2)}}</td>
                    
                    <td style="border: 1px solid black;">{{number_format($roles->liquido_pagar, 2)}}</td>

                    </tr>       
                    @endforeach
                @endif
            @endif 
        </tbody>
    </table>
   
<style>
    tbody {
   border-top: 1px solid #000;
   border-bottom: 1px solid #000;
}
tbody th, tfoot th {
   border: 0;
}
th.name {
   width: 25%;
}
th.location {
   width: 20%;
}
th.lasteruption {
   width: 30%;
}
th.eruptiontype {
   width: 25%;
}
tfoot {
   text-align: center;
   color: #555;
   font-size: 0.8em;
}
</style>
@endsection