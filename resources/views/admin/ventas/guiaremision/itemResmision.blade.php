<table class="invisible">
    <tbody id="plantillaItemFactura">
        <tr id="row_{ID}">
            <td>{Dcantidad}<input class="invisible" name="Dcantidad[]" value="{Dcantidad}" /></td>
            <td>{Dcodigo}<input class="invisible" name="DprodcutoID[]" value="{DprodcutoID}" /><input class="invisible" name="Dcodigo[]" value="{Dcodigo}" /></td>
            <td>{Dnombre}<input class="invisible" name="Dnombre[]" value="{Dnombre}" /></td>
            <td><a onclick="eliminarItem({ID});" class="btn btn-danger waves-effect"
                    style="padding: 2px 8px;">X</a></td>
        </tr>
    </tbody>
</table>