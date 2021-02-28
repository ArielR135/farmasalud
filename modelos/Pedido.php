<?php 
// Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
// require_once '../config/util.php';

Class Pedido {

	// Atributos de clase
	private $referencia_pedido;
	private $fecha_pedido;
	private $direccion_destino;
	private $documento_origen;
	private $estado_pedido;
	private $total_impuesto;
	private $total;
	private $idproveedor;
	private $idusuario;
	private $detallePedido;

	// Implementamos nuestro constructor
	public function __construct($referencia_pedido=null, $fecha_pedido=null, $direccion_destino=null, $documento_origen=null, $estado_pedido=null, $total_impuesto=null, $total=null, $idproveedor=null, $idusuario=null, $detallePedido=null) {
		$this->referencia_pedido = $referencia_pedido;
		$this->fecha_pedido = $fecha_pedido;
		$this->direccion_destino = $direccion_destino;
		$this->documento_origen = $documento_origen;
		$this->estado_pedido = $estado_pedido;
		$this->total_impuesto = $total_impuesto;
		$this->total = $total;
		$this->idproveedor = $idproveedor;
		$this->idusuario = $idusuario;
		$this->detallePedido = $detallePedido;
	}

	function setReferenciaPedido($referencia_pedido) {
		$this->referencia_pedido = $referencia_pedido;
	}
	function setFechaPedido($fecha_pedido)	{
		$this->fecha_pedido = $fecha_pedido;
	}
	function setDireccionDestino($direccion_destino)	{
		$this->direccion_destino = $direccion_destino;
	}
	function setEstadoPedido($estado_pedido)	{
		$this->estado_pedido = $estado_pedido;
	}
	function setTotalImpuesto($total_impuesto)	{
		$this->total_impuesto = $total_impuesto;
	}
	function setTotal($total)	{
		$this->total = $total;
	}
	function setIdProveedor($idproveedor)	{
		$this->idproveedor = $idproveedor;
	}
	function setIdUsuario($idusuario)	{
		$this->idusuario = $idusuario;
	}
	function setDetallePedido($detallePedido)	{
		$this->detallePedido = $detallePedido;
	}

	// Implementamos un método para insertar registros
	public function insertar() {
		$sql = "INSERT INTO pedidos (referencia_pedido, fecha_pedido, direccion_destino, documento_origen, estado_pedido, total_impuesto, total, idproveedor, idusuario)
		VALUES ('$this->referencia_pedido', '$this->fecha_pedido', '$this->direccion_destino', '$this->documento_origen', '$this->estado_pedido', '$this->total_impuesto', '$this->total', '$this->idproveedor', '$this->idusuario')";

		$idpedidoUltimo = ejecutarConsulta_retornarID($sql);
		$sw = true;
		$dp = $this->detallePedido;
		$cantidad = $dp->getCantidad();
		$precioUnitario = $dp->getPrecioUnitario();
		$impuesto = $dp->getImpuesto();
		$idproducto = $dp->getIdProducto();

		for ($i = 0; $i < count($idproducto); $i++) {
			$sql_detalle = "INSERT INTO detalles_pedidos (cantidad,precio_unitario,impuesto,idproducto,idpedido) VALUES ('$cantidad[$i]', '$precioUnitario[$i]', '$impuesto[$i]', '$idproducto[$i]', '$idpedidoUltimo')";
			ejecutarConsulta($sql_detalle) or $sw = false;
		}
		return $sw;
	}

	// Implementamos un método para editar registros
	public function editar($idpedido) {
        $sql = "UPDATE pedidos SET referencia_pedido='$this->referencia_pedido', fecha_pedido='$this->fecha_pedido', direccion_destino='$this->direccion_destino', documento_origen='$this->documento_origen', estado_pedido='$this->estado_pedido', total_impuesto='$this->total_impuesto', total='$this->total', idproveedor='$this->idproveedor', idusuario='$this->idusuario'
					WHERE idpedido='$idpedido'";
					return ejecutarConsulta($sql);
        // ejecutarConsulta($sql);

		// Eliminamos todos los detalles asignados para volverlos a registrar
		/*$sqlDel="DELETE FROM detalles_pedidos WHERE idpedido='$idpedido'";
		ejecutarConsulta($sqlDel);
		$sw=true;

		for ($i = 0; $i < count($idproducto); $i++) {
			$sql_detalle = "INSERT INTO detalles_pedidos(cantidad,precio_unitario,impuesto,idproducto,idpedido) VALUES('$cantidad[$i]', '$precio_unitario[$i]', '$impuesto[$i]', '$idproducto[$i]', '$idpedido')";
			ejecutarConsulta($sql_detalle) or $sw=false;
		}	
		return $sw;*/
	}

	// Implementamos un método para desactivar registros
	public function anular($idpedido) {
		$sql = "UPDATE pedidos SET estado_pedido='Anulado' WHERE idpedido='$idpedido'";
		return ejecutarConsulta($sql);
	}

	// Implementamos un método para activar registros
	public function activar($idpedido) {
		$sql = "UPDATE pedidos SET estado_pedido='Pendiente' WHERE idpedido='$idpedido'";
		return ejecutarConsulta($sql);
	}

	// Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpedido) {
		$sql = "SELECT p.idpedido,p.referencia_pedido,p.fecha_pedido,p.direccion_destino,p.documento_origen,p.estado_pedido,p.total_impuesto,p.total,p.idproveedor,pv.nombre as proveedor,u.idusuario,u.nombre as usuario FROM pedidos p INNER JOIN proveedores pv ON p.idproveedor=pv.idproveedor INNER JOIN usuarios u ON p.idusuario=u.idusuario WHERE idpedido='$idpedido'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalles($idpedido) {
		$sql="SELECT di.idpedido,di.idproducto,p.nombre,di.cantidad,di.precio_unitario,di.impuesto FROM detalles_pedidos di inner join productos p on di.idproducto=p.idproducto where di.idpedido='$idpedido'";
		return ejecutarConsulta($sql);
	}

	// Implementar un método para listar los registros
	public function listar() {
		$sql = "SELECT p.idpedido,p.referencia_pedido,p.fecha_pedido,p.direccion_destino,p.documento_origen,p.estado_pedido,p.total_impuesto,p.total,p.idproveedor,pv.nombre as proveedor,u.idusuario,u.nombre as usuario FROM pedidos p INNER JOIN proveedores pv ON p.idproveedor=pv.idproveedor INNER JOIN usuarios u ON p.idusuario=u.idusuario ORDER BY p.idpedido DESC";
		return ejecutarConsulta($sql);
	}

	public function ultimaRef() {
		$sql = "SELECT referencia_pedido FROM pedidos
			ORDER BY referencia_pedido DESC
			LIMIT 1";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function cabeceraPedido($idpedido) {
		$sql = "SELECT p.idpedido, p.idproveedor, pv.nombre as proveedor, pv.direccion, pv.cuit_cuil, pv.email, pv.telefono, p.idusuario, u.nombre, u.apellido, p.referencia_pedido, p.fecha_pedido, p.total_impuesto, p.total, p.direccion_destino
		FROM pedidos p INNER JOIN proveedores pv ON p.idproveedor = pv.idproveedor INNER JOIN  usuarios u ON p.idusuario = u.idusuario
		WHERE p.idpedido = '$idpedido'";
		return ejecutarConsulta($sql);
	}

	public function detallePedido($idpedido) {
		$sql = "SELECT pd.nombre as producto, pd.codigo_barra, dp.cantidad, dp.precio_unitario, dp.impuesto, (dp.cantidad * dp.precio_unitario) as subtotal
		FROM detalles_pedidos dp INNER JOIN productos pd ON dp.idproducto = pd.idproducto
		WHERE dp.idpedido = '$idpedido'";
		return ejecutarConsulta($sql);
	}

}

?>