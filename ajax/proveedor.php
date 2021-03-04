<?php
//Envio mediante AJAX de las funciones: Insertar, editar, listar y eliminar.

require_once "../modelos/Proveedor.php";

$proveedor = new Proveedor();

//Si existe el objeto "idproveedor" que se recibe por el método POST entonces se valida con la función "limpiarCadena()" y se guarda en la variable "$idproveedor, de lo contrario se guarda una cadena vacía.
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : null;
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : null;
$cuit_cuil = isset($_POST["cuit_cuil"]) ? limpiarCadena($_POST["cuit_cuil"]) : null;
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : null;
$sitio_web = isset($_POST["sitio_web"]) ? limpiarCadena($_POST["sitio_web"]) : null;
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : null;
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : null;
$ciudad = isset($_POST["ciudad"]) ? limpiarCadena($_POST["ciudad"]) : null;
$provincia = isset($_POST["provincia"]) ? limpiarCadena($_POST["provincia"]) : null;
$codigo_postal = isset($_POST["codigo_postal"]) ? limpiarCadena($_POST["codigo_postal"]) : null;
$pais = isset($_POST["pais"]) ? limpiarCadena($_POST["pais"]) : null;

//Se evalúa la operacion que se va a realizar para devolver los valores
switch ($_GET["op"]) {

	case 'guardaryeditar':
		$proveedor->setNombre($nombre);
		$proveedor->setCuitCuil($cuit_cuil);
		$proveedor->setEmail($email);
		$proveedor->setSitioWeb($sitio_web);
		$proveedor->setTelefono($telefono);
		$proveedor->setDireccion($direccion);
		$proveedor->setCiudad($ciudad);
		$proveedor->setProvincia($provincia);
		$proveedor->setCodigoPostal($codigo_postal);
		$proveedor->setPais($pais);
		if (empty($idproveedor)) {
			$rspta = $proveedor->insertar();
			echo $rspta ? "Proveedor registrado" : "Proveedor no se pudo registrar";
		} else {
			$rspta = $proveedor->editar($idproveedor);
			echo $rspta ? "Proveedor actualizado" : "Proveedor no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$proveedor->eliminar($idproveedor);
 		echo $rspta ? "Proveedor eliminado" : "Proveedor no se pudo eliminar";
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
					"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idproveedor.')" title="Editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="eliminar('.$reg->idproveedor.')" title="Eliminar"><i class="fa fa-trash"></i></button>',
					"1"=>$reg->nombre,
					"2"=>$reg->cuit_cuil,
					"3"=>$reg->email,
					"4"=>$reg->sitio_web,
					"5"=>$reg->telefono,
					"6"=>$reg->direccion,
					"7"=>$reg->ciudad,
					"8"=>$reg->provincia,
					"9"=>$reg->codigo_postal,
					"10"=>$reg->pais
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