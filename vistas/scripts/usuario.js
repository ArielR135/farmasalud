var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e) {
		guardaryeditar(e);	
	})

	$("#imagenmuestra").hide();

	// Mostramos los permisos
	$.get("../ajax/usuario.php?op=permisos&id=", function(r) {
		$("#permisos").html(r);
	});
}

//Función limpiar
function limpiar() {
	$("#idusuario").val("");
	$("#nombre_usuario").val("");
	$("#contraseña").val("");
	$("#nombre").val("");
	$("#apellido").val("");
	$("#dni").val("");
	$("#rol").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#imagen").val("");
	$("#imagenmuestra").hide();
	$("input[type=checkbox]").prop("checked", false);
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
					url: '../ajax/usuario.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
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

function mostrar(idusuario) {
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status) {
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idusuario").val(data.idusuario);
		$("#nombre_usuario").val(data.nombre_usuario);
		$("#contraseña").val(data.contraseña);
		$("#nombre").val(data.nombre);
		$("#apellido").val(data.apellido);
		$("#dni").val(data.dni);
        $("#rol").val(data.rol);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
		$("#imagenactual").val(data.imagen);
	})
	 
	 $.post("../ajax/usuario.php?op=permisos&id="+idusuario, function(r) {
		$("#permisos").html(r);
	});
}

//Función para desactivar registros
function desactivar(idusuario) {
	bootbox.confirm("¿Está Seguro de desactivar el usuario?", function(result) {
		if(result) {
        	$.post("../ajax/usuario.php?op=desactivar", {idusuario : idusuario}, function(e) {
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idusuario) {
	bootbox.confirm("¿Está Seguro de activar el usuario?", function(result) {
		if(result) {
        	$.post("../ajax/usuario.php?op=activar", {idusuario : idusuario}, function(e) {
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();