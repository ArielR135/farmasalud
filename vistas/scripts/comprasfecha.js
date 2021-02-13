var tabla;

//Función que se ejecuta al inicio
function init() {
	listar();
	$("#fechaInicio").change(listar);
	$("#fechaFin").change(listar);
}

//Función listar
function listar() {
	let fechaInicio = $("#fechaInicio").val();
	let fechaFin = $("#fechaFin").val();

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
				url: '../ajax/consultas.php?op=comprasfecha',
				data: {
					fechaInicio,
					fechaFin
				},
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

init();