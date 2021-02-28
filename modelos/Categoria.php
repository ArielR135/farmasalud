<?php 
//Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria {

	private $nombre;
	private $descripcion;

	//Implementamos nuestro constructor
	function __construct($nombre=null, $descripcion=null, $estado=null) {
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->estado = $estado;
	}

	function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	//Implementamos un método para insertar registros
	public function insertar() {
		$sql = "INSERT INTO categorias (nombre, descripcion, estado) VALUES ('$this->nombre', '$this->descripcion', '1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcategoria) {
		$sql = "UPDATE categorias SET nombre='$this->nombre', descripcion='$this->descripcion' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idcategoria) {
		$sql = "UPDATE categorias SET estado='0' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idcategoria) {
		$sql = "UPDATE categorias SET estado='1' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcategoria) {
		$sql = "SELECT * FROM categorias WHERE idcategoria='$idcategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar() {
		$sql = "SELECT * FROM categorias";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select() {
		$sql="SELECT * FROM categorias where estado=1";
		return ejecutarConsulta($sql);		
	}
}

 ?>