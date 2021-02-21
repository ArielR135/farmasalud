var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e) {
		guardaryeditar(e);
	})
}

//Función limpiar
function limpiar() {
	$("#idproveedor").val("");
	$("#nombre").val("");
    $("#cuit_cuil").val("");
    $("#email").val("");
    $("#sitio_web").val("");
    $("#telefono").val("");
    $("#direccion").val("");
    $("#ciudad").val("");
    $("#provincia").val("");
    $("#codigo_postal").val("");
    $("#pais").val("");
}

//Función mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
	} else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarForm
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función listar
function listar() {
	tabla = $('#tbllistado').dataTable({
			"aProcessing": true, //Activamos el procesamiento del datatables
			"aServerSide": true, //Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip', //Definimos los elementos del control de tabla
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdf'
			],
			"ajax": {
				url: '../ajax/proveedor.php?op=listar',
				type: "get",
				dataType: "json",
				error: function(e) {
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 5, //Paginación
			"order": [[0, "desc"]] //Ordenar (columna, orden)
	}).DataTable();
}

//Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/proveedor.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function(datos) {
			bootbox.alert(datos);
			mostrarform(false);
			tabla.ajax.reload();
		}
	});
	limpiar();
}

function mostrar(idproveedor) {
	$.post("../ajax/proveedor.php?op=mostrar",{idproveedor : idproveedor}, function(data, status) {
		data = JSON.parse(data);
		mostrarform(true);

		$("#idproveedor").val(data.idproveedor);
		$("#nombre").val(data.nombre);
		$("#cuit_cuil").val(data.cuit_cuil);
    $("#email").val(data.email);
    $("#sitio_web").val(data.sitio_web);
    $("#telefono").val(data.telefono);
    $("#direccion").val(data.direccion);
    $("#ciudad").val(data.ciudad);
    $("#provincia").val(data.provincia);
    $("#codigo_postal").val(data.codigo_postal);
    $("#pais").val(data.pais);
	})
}

//Función para desactivar registros
function desactivar(idproveedor) {
	bootbox.confirm("¿Está Seguro de desactivar el Proveedor?", function(result) {
		if(result) {
    	$.post("../ajax/proveedor.php?op=desactivar", {idproveedor : idproveedor}, function(e) {
    		bootbox.alert(e);
        tabla.ajax.reload();
    	});	
    }
	})
}

//Función para eliminar registros
function eliminar(idproveedor) {
	bootbox.confirm("¿Está Seguro de eliminar el proveedor?", function(result) {
		if(result) {
    	$.post("../ajax/proveedor.php?op=eliminar", {idproveedor : idproveedor}, function(e) {
    		bootbox.alert(e);
        tabla.ajax.reload();
    	});	
    }
	})
}

init();