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
        <tr class="text-center">
            <th>Cedula</th>
            <th>Nombre</th>
            @if(isset($resultado))
            <th>{{ $resultado[1]->rubro_descripcion}}</th>  
           
            @endif 
            <th>Total A Pagar</th> 
          
        </tr>
    </thead>
    <tbody>
      
    </tbody>
</table>