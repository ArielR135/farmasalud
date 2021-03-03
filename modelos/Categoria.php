<?php 
//Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria {

	private $idcategoria;
	private $nombre;
	private $descripcion;

	//Implementamos nuestro constructor
	function __construct($idcategoria=null, $nombre=null, $descripcion=null, $estado=null) {
		$this->idcategoria = $idcategoria;
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->estado = $estado;
	}

	function getIdCategoria() {
		return $this->idcategoria;
	}

	//Implementamos un método para insertar registros
	function insertar() {
		$sql = "INSERT INTO categorias (nombre, descripcion, estado) VALUES ('$this->nombre', '$this->descripcion', '1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	function editar() {
		$sql = "UPDATE categorias SET nombre='$this->nombre', descripcion='$this->descripcion' WHERE idcategoria='$this->idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	function desactivar() {
		$sql = "UPDATE categorias SET estado='0' WHERE idcategoria='$this->idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	function activar() {
		$sql = "UPDATE categorias SET estado='1' WHERE idcategoria='$this->idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	function mostrar() {
		$sql = "SELECT * FROM categorias WHERE idcategoria='$this->idcategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	static function listar() {
		$sql = "SELECT * FROM categorias";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	static function select() {
		$sql="SELECT * FROM categorias where estado=1";
		return ejecutarConsulta($sql);		
	}
}

 ?>