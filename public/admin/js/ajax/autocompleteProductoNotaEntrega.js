(function() {
    $("#buscarProducto").autocomplete({
        source: function(request, response){
            var localObj = window.location;
            var contextPath = localObj.pathname.split("/")[1];
            if(contextPath=='public'){
                contextPath="/"+contextPath;
            }else{
                contextPath='';
            }
            $.ajax({
                url: contextPath+"/producto/searchN/"+request.term,
                dataType: "json",
                type: "GET",
                data: {
                    buscar: request.term,
                },
                success: function(data){
                    response($.map(data, function(producto){
                        return {
                            nombre: producto.producto_nombre,
                            label: producto.producto_nombre,
                            codigo: producto.producto_codigo,
                            precio : producto.producto_precio1,
                            id: producto.producto_id,
                            stock : producto.producto_stock,
                            tipo: producto.producto_tipo,
                            empresa: producto.empresa_estado_cambiar_precio
                        };
                    }));
                },
            });
        },
        select: function(event, ui){
                $("#codigoProducto").val(ui.item.codigo);
                $("#idProductoID").val(ui.item.id);
                $("#id_pu").val( Number(ui.item.precio).toFixed(2));
                $("#buscarProducto").val(ui.item.nombre);
                $("#descripcionProducto").val(ui.item.nombre);
                $("#id_disponible").val(ui.item.stock);
                if(ui.item.tipop == 1 && ui.item.empresa == "1"){
                    document.getElementById("id_pu").readOnly = true;
                    
                }else{
                    document.getElementById("id_pu").readOnly = false;
                    
                }
                calcularTotal();
        }
    });
})();