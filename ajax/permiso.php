<?php
//Envio mediante AJAX de las funciones: Insertar, editar, listar y eliminar.

require_once "../modelos/Permiso.php";

$permiso = new Permiso();

//Se evalúa la operacion que se va a realizar para devolver los valores
switch ($_GET["op"]) {

	case 'listar':
		$rspta = $permiso->listar();
		//Vamos a declarar un array
		$data = Array();

		while ($reg=$rspta->fetch_object()) {
			$data[] = array(
					"0"=>$reg->nombre
					);
		}

		$results = array(
				"sEcho"=>1, //Informacion para el datatables.
				"iTotalRecords"=>count($data), //Enviamos el total de registros al data table.
				"iTotalDisplayRecords"=>count($data), //Enviamos el total de registros a visualizar.
				"aaData"=>$data, //Eviamos todos los registro del arreglo.
				);
		echo json_encode($results);
		break;
}
 ?>