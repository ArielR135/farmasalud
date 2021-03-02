<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Producto {

	private $condigo_barra;
	private $nombre;
	private $descripcion;
	private $sustancia_activa;
	private $fecha_vencimiento;
	private $stock;
	private $lote;
	private $laboratorio;
	private $presentacion;
	private $imagen;
	private $idcategoria;
	private $idproveedor;

	//Implementamos nuestro constructor
	function __construct($codigo_barra=null, $nombre=null, $descripcion=null, $sustancia_activa=null, $fecha_vencimiento=null, $stock=null, $lote=null, $laboratorio=null, $presentacion=null, $imagen=null, $idcategoria=null, $idproveedor=null) {
		$this->codigo_barra = $codigo_barra;
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->sustancia_activa = $sustancia_activa;
		$this->fecha_vencimiento = $fecha_vencimiento;
		$this->stock = $stock;
		$this->lote = $lote;
		$this->laboratorio = $laboratorio;
		$this->presentacion = $presentacion;
		$this->imagen = $imagen;
		$this->idcategoria = $idcategoria;
		$this->idproveedor = $idproveedor;
	}

	public function setCodigoBarra($codigo_barra) {
		$this->codigo_barra = $codigo_barra;
	}
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	public function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}
	public function setSustanciaActiva($sustancia_activa) {
		$this->sustancia_activa = $sustancia_activa;
	}
	public function setFechaVencimiento($fecha_vencimiento) {
		$this->fecha_vencimiento = $fecha_vencimiento;
	}
	public function setStock($stock) {
		$this->stock = $stock;
	}
	public function setLote($lote) {
		$this->lote = $lote;
	}
	public function setLaboratorio($laboratorio) {
		$this->laboratorio = $laboratorio;
	}
	public function setPresentacion($presentacion) {
		$this->presentacion = $presentacion;
	}
	public function setImagen($imagen) {
		$this->imagen = $imagen;
	}
	function getImagen() {
		return $this->imagen;
	}
	public function setIdCategoria($idcategoria) {
		$this->idcategoria = $idcategoria;
	}
	public function setIdProveedor($idproveedor) {
		$this->idproveedor = $idproveedor;
	}

	//Implementamos un método para insertar registros
	public function insertar() {
		$sql="INSERT INTO productos (codigo_barra,nombre,descripcion,sustancia_activa,fecha_vencimiento,stock,lote,laboratorio,presentacion,imagen,estado,idcategoria,idproveedor)
		VALUES ('$this->codigo_barra','$this->nombre','$this->descripcion','$this->sustancia_activa','$this->fecha_vencimiento','$this->stock','$this->lote','$this->laboratorio','$this->presentacion','$this->imagen','1','$this->idcategoria','$this->idproveedor')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idproducto) {
		$sql="UPDATE productos SET codigo_barra='$this->codigo_barra',nombre='$this->nombre',descripcion='$this->descripcion',sustancia_activa='$this->sustancia_activa',fecha_vencimiento='$this->fecha_vencimiento',stock='$this->stock',lote='$this->lote',laboratorio='$this->laboratorio',presentacion='$this->presentacion',imagen='$this->imagen',idcategoria='$this->idcategoria',idproveedor='$this->idproveedor' WHERE idproducto='$idproducto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idproducto)	{
		$sql="UPDATE productos SET estado='0' WHERE idproducto='$idproducto'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idproducto) {
		$sql="UPDATE productos SET estado='1' WHERE idproducto='$idproducto'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproducto) {
		$sql="SELECT * FROM productos WHERE idproducto='$idproducto'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar() {
		$sql="SELECT pd.idproducto,pd.codigo_barra,pd.nombre,pd.descripcion,pd.sustancia_activa,pd.fecha_vencimiento,pd.stock,pd.lote,pd.laboratorio,pd.presentacion,pd.imagen,pd.estado,pd.idcategoria,c.nombre as categoria,pd.idproveedor,pv.nombre as proveedor FROM productos pd INNER JOIN categorias c ON pd.idcategoria=c.idcategoria INNER JOIN proveedores pv ON pd.idproveedor=pv.idproveedor";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos() {
		$sql="SELECT pd.idproducto,pd.codigo_barra,pd.nombre,pd.descripcion,pd.sustancia_activa,pd.fecha_vencimiento,pd.stock,pd.lote,pd.laboratorio,pd.presentacion,pd.imagen,pd.estado,pd.idcategoria,c.nombre as categoria,pd.idproveedor,pv.nombre as proveedor FROM productos pd INNER JOIN categorias c ON pd.idcategoria=c.idcategoria INNER JOIN proveedores pv ON pd.idproveedor=pv.idproveedor WHERE pd.estado='1'";
		return ejecutarConsulta($sql);		
	}

}

?>