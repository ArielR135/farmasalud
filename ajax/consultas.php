<?php
//Envio mediante AJAX de las funciones: listar.

require_once "../modelos/Consultas.php";

$consulta = new Consultas();

//Se evalúa la operacion que se va a realizar para devolver los valores
switch ($_GET["op"]) {

	case 'comprasfecha':
		$fechaInicio = $_REQUEST["fechaInicio"];
		$fechaFin = $_REQUEST["fechaFin"];
		$rspta = $consulta->comprasFecha($fechaInicio, $fechaFin);
		//Vamos a declarar un array
		$data = Array();

		while ($reg=$rspta->fetch_object()) {

			switch ($reg->estado_pedido) {
				case 'Aceptado':
					$estado = '<span class="label bg-green">Aceptado</span>';
				break;
				case 'Pendiente':
					$estado = '<span class="label bg-yellow">Pendiente</span>';
				break;
				case 'Anulado':
					$estado = '<span class="label bg-red">Anulado</span>';
				break;
				default:
					$estado = '<span class="label bg-red">Sin estado</span>';
				break;
			}

			$date = explode("-", $reg->fecha_pedido);
			$data[] = array(
					"0"=>"{$date[2]}/{$date[1]}/{$date[0]}",
					"1"=>$reg->referencia_pedido,
					"2"=>$reg->nombre_usuario,
					"3"=>$reg->proveedor,
					"4"=>$reg->total,
					"5"=>$reg->total_impuesto,
					"6"=>$estado
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