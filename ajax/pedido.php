<?php
//Envio mediante AJAX de las funciones: Insertar, editar, listar y eliminar.

if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Pedido.php";
require_once "../modelos/DetallePedido.php";


//Si existe el objeto "idpedido" que se recibe por el método POST entonces se valida con la función "limpiarCadena()" y se guarda en la variable "$idpedido, de lo contrario se guarda una cadena vacía.
$idpedido = isset($_POST["idpedido"]) ? limpiarCadena($_POST["idpedido"]) : null;
$referencia_pedido = isset($_POST["referencia_pedido"]) ? limpiarCadena($_POST["referencia_pedido"]) : null;
$fecha_pedido = isset($_POST["fecha_pedido"]) ? limpiarCadena($_POST["fecha_pedido"]) : null;
$direccion_destino = isset($_POST["direccion_destino"]) ? limpiarCadena($_POST["direccion_destino"]) : null;
$documento_origen = isset($_POST["documento_origen"]) ? limpiarCadena($_POST["documento_origen"]) : null;
$estado_pedido = isset($_POST["estado_pedido"]) ? limpiarCadena($_POST["estado_pedido"]) : null;
$total_impuesto = isset($_POST["total_impuesto"]) ? limpiarCadena($_POST["total_impuesto"]) : null;
$total = isset($_POST["total_compra"]) ? limpiarCadena($_POST["total_compra"]) : null;
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : null;
$idusuario = $_SESSION["idusuario"];

// Detalle Pedido //
$cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : null;
$precio_unitario = isset($_POST["precio_unitario"]) ? $_POST["precio_unitario"] : null;
$impuesto = isset($_POST["impuesto"]) ? $_POST["impuesto"] : null;
$idproducto = isset($_POST["idproducto"]) ? $_POST["idproducto"] : null;

$detallePedido = new DetallePedido($cantidad,	$precio_unitario,	$impuesto, $idproducto);
$pedido = new Pedido(
	$idpedido, 
	$referencia_pedido, 
	$fecha_pedido, 
	$direccion_destino, 
	$documento_origen,
	$estado_pedido, 
	$total_impuesto, 
	$total, 
	$idproveedor, 
	$idusuario, 
	$detallePedido
);

//Se evalúa la operacion que se va a realizar para devolver los valores
switch ($_GET["op"]) {

	case 'guardaryeditar':
		if (empty($pedido->getIdPedido())) {
			$rspta = $pedido->insertar();
			echo $rspta ? "Pedido registrado" : "No se puedieron registrar todos los datos del pedido";
		} else {
			$rspta = $pedido->editar();
			echo $rspta ? "Pedido actualizado" : "No se puedieron actualizar todos los datos del pedido";
		}
	break;

	case 'anular':
		$rspta = $pedido->anular();
			echo $rspta ? "Pedido anulado" : "Pedido no se pudo anular";
	break;

	case 'activar':
		$rspta = $pedido->activar();
			echo $rspta ? "Pedido establecido como pendiente" : "Pedido no se pudo establecer como pendiente";
	break;

	case 'mostrar':
		$rspta = $pedido->mostrar();
		//Codificar el resultado utilizando json
		echo json_encode($rspta);
	break;

	case 'listarDetalles':
		//Recibimos el idpedido
		$pedido->setIdPedido($_GET['id']);
		$rspta = $pedido->listarDetalles();

		echo '<thead style="background-color:#A9D0F5">
            <th>Opciones</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unitario</th>
            <th>Impuesto</th>
            <th>Subtotal</th>
	        </thead>';

		$totalCompra = 0;
		$totalImpuesto = 0;
		while ($reg = $rspta->fetch_object()) {
			$subtotal = $reg->precio_unitario * $reg->cantidad;
			$subtotalImpuesto = ($subtotal * $reg->impuesto) / 100;
			
			echo "<tr class='filas'>
				<td></td>
				<td>{$reg->nombre}</td>
				<td>{$reg->cantidad}</td>
				<td>{$reg->precio_unitario}</td>
				<td>{$reg->impuesto}</td>
				<td>{$subtotal}</td>
			</tr>";
			/*$totalCompra += ($reg->precio_unitario * $reg->cantidad) * $reg->impuesto / 100;*/
			$totalImpuesto += $subtotalImpuesto;
			$totalCompra += $subtotal;

		}
		$totalCompra += $totalImpuesto;
		
		/*echo "<tfoot>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th><h5><strong>TOTAL:</strong></h5></th>
      <th>
      <h4 id='total'>AR$ {$totalCompra}</h4>
	      <input type='hidden' name='total_compra' id='total_compra' value='{$totalCompra}'>
	      <input type='hidden' name='total_impuesto' id='total_impuesto' value='{$totalImpuesto}'>
      </th> 
    </tfoot>";*/
    echo "<tfoot>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>
      	<h5 style='text-align: right;'>Impuestos:</h5> <br>
      	<h5 style='text-align: right;'>
      		<strong>TOTAL:</strong>
      	</h5>
      </th>
      <th>
      	<h5 id='impuestos'>AR$ {$totalImpuesto}</h5> </br>
	      <input type='hidden' name='total_impuesto' id='total_impuesto' value='{$totalImpuesto}'>
      	<h4 id='total'>AR$ {$totalCompra}</h4>
	      <input type='hidden' name='total_compra' id='total_compra' value='{$totalCompra}'>
      </th> 
    </tfoot>";
	break;

	case 'listar':
		$rspta = Pedido::listar();
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
					"0"=>(($reg->estado_pedido!='Anulado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idpedido.')" title="Ver"><i class="fa fa-eye"></i></button>'.
 					' <button class="btn btn-danger" onclick="anular('.$reg->idpedido.')" title="Anular"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idpedido.')" title="Ver"><i class="fa fa-eye"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idpedido.')" title="Borrador"><i class="fa fa-pencil"></i></button>').
 					" <a target='_blank' href='../reportes/exPedido.php?id={$reg->idpedido}'><button class='btn btn-info' title='Ver Pedido'><i class='fa fa-file'></i></button></a>",

					"1"=>$reg->referencia_pedido,
					// "2"=>$reg->fecha_pedido,
					"2"=>"{$date[2]}/{$date[1]}/{$date[0]}",
					"3"=>$reg->documento_origen,
					"4"=>$reg->total,
					"5"=>$reg->proveedor,
					/*"6"=>($reg->estado_pedido!='Anulado')?'<span class="label bg-green">'.$reg->estado_pedido.'</span>':
 				'<span class="label bg-red">'.$reg->estado_pedido.'</span>'
					);*/
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

	case 'selectProveedor':
		require_once "../modelos/Proveedor.php";
		$rspta = Proveedor::listar();

		echo '<option value="">Seleccione un Proveedor</option>';
		while ($reg = $rspta-> fetch_object()) {
			echo "<option value={$reg->idproveedor}>{$reg->nombre}</option>";
		}
	break;

	case 'listarProductos':
		require_once '../modelos/Producto.php';
		$rspta=Producto::listarActivos();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>"<button class='btn btn-warning' onclick=\"agregarDetalle({$reg->idproducto}, '{$reg->nombre}')\"><span class='fa fa-plus'></span></button>",
 				"1"=>$reg->nombre,
 				"2"=>$reg->categoria,
 				// "3"=>$reg->codigo_barra,
 				"3"=>$reg->sustancia_activa,
 				// "5"=>$reg->fecha_vencimiento,
 				"4"=>$reg->stock,
 				// "7"=>$reg->lote,
 				"5"=>$reg->proveedor,
 				"6"=>$reg->laboratorio,
 				"7"=>$reg->presentacion,
 				"8"=>"<img src='../files/productos/".$reg->imagen."' height='50px' width='50px' >"
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data
 		);
 		echo json_encode($results);
	break;

	case 'ultimaRef':
	$rspta = Pedido::ultimaRef();
	//Codificar el resultado utilizando json
	echo json_encode($rspta);
	break;
}
?>