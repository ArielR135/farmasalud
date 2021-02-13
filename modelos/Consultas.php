<?php 
//Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas {

	//Implementamos nuestro constructor
	public function __construct() {

	}

	//Implementar un método para listar los registros
	public function comprasFecha($fechaInicio, $fechaFin) {
		$sql = "SELECT p.fecha_pedido, p.referencia_pedido, u.nombre_usuario, pv.nombre as proveedor, p.total, p.total_impuesto, p.estado_pedido
		FROM pedidos as p
		INNER JOIN proveedores as pv ON p.idproveedor = pv.idproveedor
		INNER JOIN usuarios as u ON p.idusuario = u.idusuario
		WHERE p.fecha_pedido >= '$fechaInicio'
		AND p.fecha_pedido <= '$fechaFin'";
		return ejecutarConsulta($sql);
	}

	public function totalComprasHoy() {
		$sql = "SELECT IFNULL(SUM(total),0) as total
		FROM pedidos WHERE fecha_pedido = curdate()";
		return ejecutarConsulta($sql);
	}

	public function comprasUltimos10Dias() {
		$sql = "SELECT CONCAT(DAY(fecha_pedido),'/', MONTH(fecha_pedido)) as fecha, SUM(total) as total
		FROM pedidos GROUP BY fecha_pedido ORDER BY fecha_pedido DESC LIMIT 0,10";
		return ejecutarConsulta($sql);
	}
}
?>