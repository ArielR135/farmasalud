var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e) {
		guardaryeditar(e);	
	})

	//Cargamos los items al select categoria
	$.get("../ajax/producto.php?op=selectCategoria", function(r) {
	            $("#idcategoria").html(r);
	            $('#idcategoria').selectpicker('refresh');
	});
	//Cargamos los items al select proveedor
	$.get("../ajax/producto.php?op=selectProveedor", function(r) {
	            $("#idproveedor").html(r);
	            $('#idproveedor').selectpicker('refresh');
	});

	$("#imagenmuestra").hide();
}

//Función limpiar
function limpiar() {
	$("#codigo_barra").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#sustancia_activa").val("");
	$("#idcategoria").val("");
	$('#idcategoria').selectpicker('refresh');
	$("#fecha_vencimiento").val("");
	$("#stock").val("");
	$("#lote").val("");
	$("#idproveedor").val("");
	$('#idproveedor').selectpicker('refresh');
	$("#laboratorio").val("");
	$("#presentacion").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#imagen").val("");
	$("#imagenmuestra").hide();
	$("#print").hide();
	$("#idproducto").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) 	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar() {
	tabla=$('#tbllistado').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/producto.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 10,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/producto.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idproducto) {
	$.post("../ajax/producto.php?op=mostrar",{idproducto : idproducto}, function(data, status) {
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idcategoria").val(data.idcategoria);
		$('#idcategoria').selectpicker('refresh');
		$("#idproveedor").val(data.idproveedor);
		$('#idproveedor').selectpicker('refresh');
		$("#codigo_barra").val(data.codigo_barra);
		$("#nombre").val(data.nombre);
		$("#stock").val(data.stock);
		$("#descripcion").val(data.descripcion);
		$("#sustancia_activa").val(data.sustancia_activa);
		$("#fecha_vencimiento").val(data.fecha_vencimiento);
		$("#lote").val(data.lote);
		$("#laboratorio").val(data.laboratorio);
		$("#presentacion").val(data.presentacion);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/productos/"+data.imagen);
		$("#imagenactual").val(data.imagen);
 		$("#idproducto").val(data.idproducto);
 		generarbarcode();

 	})
}

//Función para desactivar registros
function desactivar(idproducto) {
	bootbox.confirm("¿Está Seguro de desactivar el producto?", function(result) {
		if(result) {
        	$.post("../ajax/producto.php?op=desactivar", {idproducto : idproducto}, function(e) {
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idproducto) {
	bootbox.confirm("¿Está Seguro de activar el producto?", function(result) {
		if(result) {
        	$.post("../ajax/producto.php?op=activar", {idproducto : idproducto}, function(e) {
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//función para generar el código de barras
function generarbarcode() {
	codigo=$("#codigo_barra").val();
	JsBarcode("#barcode", codigo);
	$("#print").show();
}

//Función para imprimir el Código de barras
function imprimir() {
	$("#print").printArea();
}

init();