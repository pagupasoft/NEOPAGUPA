<table class="invisible">
    <tbody id="plantillaItemingreso">
        <tr id="row_{ID}">
            <td>{Dcantidad}<input class="invisible" name="Dcantidad[]" value="{Dcantidad}" /></td>
            <td>{Dcodigo}<input class="invisible" name="DprodcutoID[]" value="{DprodcutoID}" />
            <input class="invisible" name="Dcodigo[]" value="{Dcodigo}" />
            </td>
            <td>{Dnombre}<input class="invisible" name="Dnombre[]" value="{Dnombre}" /></td>
            <td>{Ddescripcion}<input class="invisible" name="Ddescripcion[]" value="{Ddescripcion}" /></td>
            </td>
            <td>{Dconsumo}<input class="invisible" name="Dconsumo[]" value="{Dconsumo}" />
                <input class="invisible" name="Didconsumo[]" value="{Didconsumo}" />
            </td>
            <td>{Dpu}<input class="invisible" name="Dpu[]" value="{Dpu}" /></td>   
            <td>{Dtotal}<input class="invisible" name="Dtotal[]" value="{Dtotal}" /></td>
            <td><a onclick="eliminarItem({ID},  {Dtotal});" class="btn btn-danger waves-effect"
                    style="padding: 2px 8px;">X</a></td>
        </tr>
    </tbody>
</table>