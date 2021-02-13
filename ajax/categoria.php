<?php
//Envio mediante AJAX de las funciones: Insertar, editar, listar y eliminar.

require_once "../modelos/Categoria.php";

$categoria = new Categoria();

//Si existe el objeto "idcategoria" que se recibe por el método POST entonces se valida con la función "limpiarCadena()" y se guarda en la variable "$idcategoria, de lo contrario se guarda una cadena vacía.
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

//Se evalúa la operacion que se va a realizar para devolver los valores
switch ($_GET["op"]) {

	case 'guardaryeditar':
		if (empty($idcategoria)) {
			$rspta = $categoria->insertar($nombre, $descripcion);
			echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
		} else {
			$rspta = $categoria->editar($idcategoria, $nombre, $descripcion);
			echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
		}
		break;

	case 'desactivar':
		$rspta = $categoria->desactivar($idcategoria);
			echo $rspta ? "Categoría desactivada" : "Categoría no se pudo desactivar";
		break;

	case 'activar':
		$rspta = $categoria->activar($idcategoria);
			echo $rspta ? "Categoría activada" : "Categoría no se pudo activar";
		break;

	case 'mostrar':
		$rspta = $categoria->mostrar($idcategoria);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		break;

	case 'listar':
		$rspta = $categoria->listar();
		//Vamos a declarar un array
		$data = Array();

		while ($reg=$rspta->fetch_object()) {
			$data[] = array(
					"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')" title="Desactivar"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.')" title="Activar"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->descripcion,
					"3"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
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