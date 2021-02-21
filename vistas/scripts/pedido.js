var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e) {
		guardaryeditar(e);	
	});

	// Cargamos los items al select proveedor
	$.get("../ajax/pedido.php?op=selectProveedor", function(r) {
		$("#idproveedor").html(r);
		$('#idproveedor').selectpicker('refresh');
	});
}

//Función limpiar
function limpiar() {
	$("#idpedido").val("");
	// Obtenemos ultima referencia y lo formateamos
	$.get({
	  url: "../ajax/pedido.php?op=ultimaRef",// mandatory
	  success: function(r) {
			data = JSON.parse(r);
			$("#referencia_pedido").val(nuevaRef(data.referencia_pedido));
		},
	  async:false // to make it synchronous
	});
	$("#fecha_pedido").val("");
	$("#direccion_destino").val("Bella Vista, Av. Siempre Viva 123");
	$("#documento_origen").val("");
	$("#estado_pedido").val("");
	$('#estado_pedido').selectpicker('refresh');
	$("#idproveedor").val("");
	$('#idproveedor').selectpicker('refresh');
	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("AR$ 0,00");
	
	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
   $('#fecha_pedido').val(today);
}

// Formatea nueva referencia
function nuevaRef(num) {
	num++;
	numLength = String(num).length;
	for (let i = 1; i < 7 - numLength; i++) {
		num = "0" + num;
	}
	return num;
}

//Función mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnAgregar").hide();
		listarProductos();
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarProd").show();
		activarCampos(true);
	}	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnAgregar").show();
		$("#btnEditar").hide();
	}
}

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función Listar los registros
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
					url: '../ajax/pedido.php?op=listar',
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

//Función para listar los registros activos
function listarProductos() {
	tabla=$('#tblproductos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            /*'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'*/
		        ],
		"ajax":
				{
					url: '../ajax/pedido.php?op=listarProductos',
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
	// $("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);
	// console.log('FORM:', formData);
	$.ajax({
		url: "../ajax/pedido.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          // tabla.ajax.reload();
	          listar();
	    }

	});
	limpiar();
}

function mostrar(idpedido) {
	$.post("../ajax/pedido.php?op=mostrar",{idpedido : idpedido}, function(data, status) {
		data = JSON.parse(data);		
		mostrarform(true);
		$("#idpedido").val(data.idpedido);
		$("#referencia_pedido").val(data.referencia_pedido);
		// console.log('REF:', data.referencia_pedido);
		$("#fecha_pedido").val(data.fecha_pedido);
		$("#direccion_destino").val(data.direccion_destino);
		$("#documento_origen").val(data.documento_origen);
		$("#estado_pedido").val(data.estado_pedido);
		// console.log('ESTADO:', data.estado_pedido);
		$('#estado_pedido').selectpicker('refresh');
		$("#idproveedor").val(data.idproveedor);
		$('#idproveedor').selectpicker('refresh');
		$("#total_compra").val(data.total);
		$("#total_impuesto").val(data.total_impuesto);
		activarCampos(false);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarProd").hide();
		$("#btnEditar").show();
 	});

 	$.get("../ajax/pedido.php?op=listarDetalles&id="+idpedido,function(r){
	        $("#detalles").html(r);
	});
}

function activarCampos(flag) {
	if (flag) {
		// $("#referencia_pedido").removeAttr("disabled");
		$("#fecha_pedido").removeAttr("disabled");
		$("#direccion_destino").removeAttr("disabled");
		$("#documento_origen").removeAttr("disabled");
		$("#estado_pedido").removeAttr("disabled");
		$("#idproveedor").removeAttr("disabled");
		$('#idproveedor').selectpicker('refresh');
		$("#btnEditar").hide();
	}	else {
		// $("#referencia_pedido").attr("disabled", true);
		$("#fecha_pedido").attr("disabled", true);
		$("#direccion_destino").attr("disabled", true);
		$("#documento_origen").attr("disabled", true);
		$("#estado_pedido").attr("disabled", true);
		$("#idproveedor").attr("disabled", true);
		// $('#idproveedor').selectpicker('refresh');
	}
}

function editar() {
	activarCampos(true);
	$("#btnGuardar").show();
}

//Función para anular registros
function anular(idpedido) {
	bootbox.confirm("¿Está seguro de anulr el pedido?", function(result) {
		if(result) {
        	$.post("../ajax/pedido.php?op=anular", {idpedido : idpedido}, function(e) {
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para establecer como borrador los registros
function activar(idpedido) {
	bootbox.confirm("¿Está seguro de establecer como pendiente el pedido?", function(result) {
		if(result) {
        	$.post("../ajax/pedido.php?op=activar", {idpedido : idpedido}, function(e) {
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Declaración de variables necesarias para trabajar con las compras y sus detalles
var cont = 0;
var detalles = 0;
// $("#btnGuardar").hide();

function agregarDetalle(idproducto,producto) {
  if (idproducto != "") {
  	// var subtotal = cantidad * precio_unitario;
  	var fila = `<tr class="filas" id="fila${cont}">
  	<td><button type="button" class="btn btn-danger fa fa-close" onclick="eliminarDetalle(${cont})"></button></td>
  	<td><input type="hidden" name="idproducto[]" value="${idproducto}">${producto}</td>
  	<td><input type="number" name="cantidad[]" id="cantidad[]" value="1" min="1" onchange="modificarSubototales()"></td>
  	<td><input type="number" name="precio_unitario[]" id="precio_unitario[]" value="0" min="0" onchange="modificarSubototales()"></td>
  	<td><input type="number" name="impuesto[]" id="impuesto[]" value="21" min="0" onchange="modificarSubototales()"></td>
  	<td><span name="subtotal" id="subtotal${cont}">0</span></td>
  	</tr>`;
  	cont++;
  	detalles = detalles + 1;
  	$('#detalles').append(fila);
  	modificarSubototales();
  } else {
  	alert("Error al ingresar el detalle, revisar los datos del producto");
  }
}

function modificarSubototales() {
	let cant = document.getElementsByName("cantidad[]");
  let prec = document.getElementsByName("precio_unitario[]");
  let sub = document.getElementsByName("subtotal");
  // let imp = document.getElementsByName("impuesto[]");

  for (let i = 0; i <cant.length; i++) {
  	sub[i].value = cant[i].value * prec[i].value;
  	// sub[i].value += sub[i].value * imp[i].value / 100;
  	sub[i].innerHTML = sub[i].value;
  }
  calcularTotales();
}

function calcularTotales() {
	let sub = document.getElementsByName("subtotal");
	let imp = document.getElementsByName("impuesto[]");
	let total = 0.0;
	let totalImp = 0.0;
	for (let i = 0; i < sub.length; i++) {
		total += sub[i].value;
		totalImp += sub[i].value * imp[i].value / 100;
	}
	// $("#total").html("AR$ " + total);
 //  $("#total_compra").val(total);

  // let imp = document.getElementsByName("impuesto[]");
  // let totalImp = 0.0;
  // let iva = 0.0;
 //  for (let i = 0; i < imp.length; i++) {
	// 	totalImp += Number(imp[i].value);
	// }
	// iva = totalImp / total * 100;
	// console.log("IVA: ", iva);
	$("#impuestos").html('AR$ '+totalImp);
  $("#total_impuesto").val(totalImp);
  $("#total").html(`AR$ ${total+totalImp}`);
  $("#total_compra").val(total+totalImp);

  evaluar();
}

function evaluar() {
	if (detalles > 0) {
    $("#btnGuardar").show();
  } else {
    $("#btnGuardar").hide(); 
    cont = 0;
  }
}

function eliminarDetalle(indice) {
	$("#fila"+indice).remove();
	calcularTotales();
	detalles = detalles - 1;
	evaluar();
}

init();