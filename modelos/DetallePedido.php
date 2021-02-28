<?php
Class DetallePedido {
	
	private $cantidad;
	private $precio_unitario;
	private $impuesto;
	private $idproducto;

	function __construct($cantidad=null, $precio_unitario=null, $impuesto=null, $idproducto=null) {
		$this->cantidad = $cantidad;
		$this->precio_unitario = $precio_unitario;
		$this->impuesto = $impuesto;
		$this->idproducto = $idproducto;
	}

	function getCantidad() {
		return $this->cantidad;
	}
	function setCantidad($cantidad)	{
		$this->cantidad = $cantidad;
	}

	function getPrecioUnitario() {
		return $this->precio_unitario;
	}
	function setPrecioUnitario($precio_unitario) {
		$this->precio_unitario = $precio_unitario;
	}

	function getImpuesto() {
		return $this->impuesto;
	}
	function setImpuesto($impuesto)	{
		$this->impuesto = $impuesto;
	}

	function getIdProducto() {
		return $this->idproducto;
	}
	function setIdProducto($idproducto)	{
		$this->idproducto = $idproducto;
	}
}
?>