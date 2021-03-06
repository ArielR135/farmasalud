<?php 
require_once "../modelos/Producto.php";

$producto = new Producto();

$idproducto=isset($_POST["idproducto"])? limpiarCadena($_POST["idproducto"]):null;
$codigo_barra=isset($_POST["codigo_barra"])? limpiarCadena($_POST["codigo_barra"]):null;
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):null;
$sustancia_activa=isset($_POST["sustancia_activa"])? limpiarCadena($_POST["sustancia_activa"]):null;
$fecha_vencimiento=isset($_POST["fecha_vencimiento"])? limpiarCadena($_POST["fecha_vencimiento"]):null;
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):null;
$lote=isset($_POST["lote"])? limpiarCadena($_POST["lote"]):null;
$laboratorio=isset($_POST["laboratorio"])? limpiarCadena($_POST["laboratorio"]):null;
$presentacion=isset($_POST["presentacion"])? limpiarCadena($_POST["presentacion"]):null;
$imagen = isset($_FILES['imagen']) ? $_FILES['imagen'] : Array();
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):null;
$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):null;

switch ($_GET["op"]) {

	case 'guardaryeditar':
		$producto->setCodigoBarra($codigo_barra);
		$producto->setNombre($nombre);
		$producto->setDescripcion($descripcion);
		$producto->setSustanciaActiva($sustancia_activa);
		$producto->setFechaVencimiento($fecha_vencimiento);
		$producto->setStock($stock);
		$producto->setLote($lote);
		$producto->setLaboratorio($laboratorio);
		$producto->setPresentacion($presentacion);
		$producto->setIdCategoria($idcategoria);
		$producto->setIdProveedor($idproveedor);
		if (!file_exists($imagen['tmp_name']) || !is_uploaded_file($imagen['tmp_name'])) {
			$producto->setImagen($_POST["imagenactual"]);
			//$imagen="";
		}
		else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($imagen['type'] == "image/jpg" || $imagen['type'] == "image/jpeg" || $imagen['type'] == "image/png")
			{
				$producto->setImagen(round(microtime(true)) . '.' . end($ext));
				move_uploaded_file($imagen["tmp_name"], "../files/productos/" . $producto->getImagen());
			}
		}
		if (empty($idproducto)) {
			$rspta=$producto->insertar();
			echo $rspta ? "Producto registrado" : "Producto no se pudo registrar";
		}
		else {
			$rspta=$producto->editar($idproducto);
			echo $rspta ? "Producto actualizado" : "Producto no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$producto->desactivar($idproducto);
 		echo $rspta ? "Producto Desactivado" : "Producto no se puede desactivar";
		break;

	case 'activar':
		$rspta=$producto->activar($idproducto);
 		echo $rspta ? "Producto activado" : "Producto no se puede activar";
		break;

	case 'mostrar':
		$rspta=$producto->mostrar($idproducto);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
		break;

	case 'listar':
		$rspta=$producto->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$date = explode("-", $reg->fecha_vencimiento);
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproducto.')" title="Ver y editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idproducto.')" title="Desactivar"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idproducto.')" title="Ver y editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idproducto.')" title="Activar"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->categoria,
 				"3"=>$reg->codigo_barra,
 				"4"=>$reg->sustancia_activa,
 				// "5"=>$reg->fecha_vencimiento,
 				"5"=>"{$date[2]}-{$date[1]}-{$date[0]}",
 				"6"=>$reg->stock,
 				"7"=>$reg->lote,
 				"8"=>$reg->proveedor,
 				"9"=>$reg->laboratorio,
 				"10"=>$reg->presentacion,
 				"11"=>"<img src='../files/productos/".$reg->imagen."' height='50px' width='50px' >",
 				"12"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

		break;

	case "selectCategoria":
		require_once "../modelos/Categoria.php";
		$categoria = new Categoria();

		$rspta = $categoria->select();
		echo '<option value="">Seleccione una Categoría</option>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
				}
		break;

	case "selectProveedor":
		require_once "../modelos/Proveedor.php";
		$proveedor = new Proveedor();

		$rspta = $proveedor->listar();
		echo '<option value="">Seleccione un Proveedor</option>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idproveedor . '>' . $reg->nombre . '</option>';
				}
		break;
}
?>