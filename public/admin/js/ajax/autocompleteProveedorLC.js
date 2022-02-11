(function() {
	$("#buscarProveedor").autocomplete({
		source: function(request, response){
			var localObj = window.location;
			var contextPath = localObj.pathname.split("/")[1];
			if(contextPath=='public'){
				contextPath="/"+contextPath;
			}else{
				contextPath='';
			}
			$.ajax({
				url: contextPath+"/proveedor/searchN/"+request.term,
				dataType: "json",
				type: "GET",
				data: {
					buscar: request.term
				},
				success: function(data){
					response($.map(data, function(proveedor){
						return {
							label: proveedor.proveedor_nombre,
                            nombre: proveedor.proveedor_nombre,
							ruc: proveedor.proveedor_ruc,
							id: proveedor.proveedor_id,
                            direccion: proveedor.proveedor_direccion,
						};
					}));
				},
			});
		},
		select: function(event, ui){
			$("#buscarProveedor").val(ui.item.nombre);
			$("#idRUC").val(ui.item.ruc);
			$("#proveedorID").val(ui.item.id);
            $("#idDireccion").val(ui.item.direccion);
			return false;
		}
	});
})();