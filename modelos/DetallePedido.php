<?php
Class DetallePedido {
	
	private $cantidad;
	private $precio_unitario;
	private $impuesto;
	private $idproducto;

	function __construct($cantidad, $precio_unitario, $impuesto, $idproducto) {
		$this->cantidad = $cantidad;
		$this->precio_unitario = $precio_unitario;
		$this->impuesto = $impuesto;
		$this->idproducto = $idproducto;
	}

	function getCantidad() {
		return $this->cantidad;
	}
	function getPrecioUnitario() {
		return $this->precio_unitario;
	}
	function getImpuesto() {
		return $this->impuesto;
	}
	function getIdProducto() {
		return $this->idproducto;
	}
}
?>