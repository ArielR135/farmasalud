<?php
//Envio mediante AJAX de las funciones: Insertar, editar, listar y eliminar.

require_once "../modelos/Proveedor.php";

$proveedor = new Proveedor();

//Si existe el objeto "idproveedor" que se recibe por el método POST entonces se valida con la función "limpiarCadena()" y se guarda en la variable "$idproveedor, de lo contrario se guarda una cadena vacía.
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$cuit_cuil = isset($_POST["cuit_cuil"]) ? limpiarCadena($_POST["cuit_cuil"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$sitio_web = isset($_POST["sitio_web"]) ? limpiarCadena($_POST["sitio_web"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$ciudad = isset($_POST["ciudad"]) ? limpiarCadena($_POST["ciudad"]) : "";
$provincia = isset($_POST["provincia"]) ? limpiarCadena($_POST["provincia"]) : "";
$codigo_postal = isset($_POST["codigo_postal"]) ? limpiarCadena($_POST["codigo_postal"]) : "";
$pais = isset($_POST["pais"]) ? limpiarCadena($_POST["pais"]) : "";

//Se evalúa la operacion que se va a realizar para devolver los valores
switch ($_GET["op"]) {

	case 'guardaryeditar':
		if (empty($idproveedor)) {
			$rspta = $proveedor->insertar($nombre, $cuit_cuil, $email, $sitio_web, $telefono, $direccion, $ciudad, $provincia, $codigo_postal, $pais);
			echo $rspta ? "Proveedor registrado" : "Proveedor no se pudo registrar";
		} else {
			$rspta = $proveedor->editar($idproveedor, $nombre, $cuit_cuil, $email, $sitio_web, $telefono, $direccion, $ciudad, $provincia, $codigo_postal, $pais);
			echo $rspta ? "Proveedor actualizado" : "Proveedor no se pudo actualizar";
		}
		break;

	case 'desactivar':
		$rspta = $proveedor->desactivar($idproveedor);
			echo $rspta ? "Proveedor desactivado" : "Proveedor no se pudo desactivar";
		break;

	case 'activar':
		$rspta = $proveedor->activar($idproveedor);
			echo $rspta ? "Proveedor activado" : "Proveedor no se pudo activar";
		break;

	case 'mostrar':
		$rspta = $proveedor->mostrar($idproveedor);
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
		break;

	case 'listar':
		$rspta = $proveedor->listar();
		//Vamos a declarar un array
		$data = Array();

		while ($reg=$rspta->fetch_object()) {
			$data[] = array(
					"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idproveedor.')" title="Desactivar"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idproveedor.')" title="Activar"><i class="fa fa-check"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->cuit_cuil,
					"3"=>$reg->email,
					"4"=>$reg->sitio_web,
					"5"=>$reg->telefono,
					"6"=>$reg->direccion,
					"7"=>$reg->ciudad,
					"8"=>$reg->provincia,
					"9"=>$reg->codigo_postal,
					"10"=>$reg->pais,
					"11"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
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