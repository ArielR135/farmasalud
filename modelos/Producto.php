<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Producto {
	//Implementamos nuestro constructor
	public function __construct() {

	}

	//Implementamos un método para insertar registros
	public function insertar($codigo_barra,$nombre,$descripcion,$sustancia_activa,$fecha_vencimiento,$stock,$lote,$laboratorio,$presentacion,$imagen,$idcategoria,$idproveedor) {
		$sql="INSERT INTO productos (codigo_barra,nombre,descripcion,sustancia_activa,fecha_vencimiento,stock,lote,laboratorio,presentacion,imagen,estado,idcategoria,idproveedor)
		VALUES ('$codigo_barra','$nombre','$descripcion','$sustancia_activa','$fecha_vencimiento','$stock','$lote','$laboratorio','$presentacion','$imagen','1','$idcategoria','$idproveedor')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idproducto,$codigo_barra, $nombre,$descripcion,$sustancia_activa,$fecha_vencimiento,$stock,$lote,$laboratorio,$presentacion,$imagen,$idcategoria,$idproveedor) {
		$sql="UPDATE productos SET codigo_barra='$codigo_barra',nombre='$nombre',descripcion='$descripcion',sustancia_activa='$sustancia_activa',fecha_vencimiento='$fecha_vencimiento',stock='$stock',lote='$lote',laboratorio='$laboratorio',presentacion='$presentacion',imagen='$imagen',idcategoria='$idcategoria',idproveedor='$idproveedor' WHERE idproducto='$idproducto'";
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