<?php 
//Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proveedor {

	private $nombre;
	private $cuit_cuil;
	private $email;
	private $sitio_web;
	private $telefono;
	private $direccion;
	private $ciudad;
	private $provincia;
	private $codigo_postal;
	private $pais;

	//Implementamos nuestro constructor
	function __construct($nombre=null, $cuit_cuil=null, $email=null, $sitio_web=null, $telefono=null, $direccion=null, $ciudad=null, $provincia=null, $codigo_postal=null, $pais=null) {
		$this->nombre = $nombre;
		$this->cuit_cuil = $cuit_cuil;
		$this->email = $email;
		$this->sitio_web = $sitio_web;
		$this->telefono = $telefono;
		$this->direccion = $direccion;
		$this->ciudad = $ciudad;
		$this->provincia = $provincia;
		$this->codigo_postal = $codigo_postal;
		$this->pais = $pais;
	}

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	public function setCuitCuil($cuit_cuil) {
		$this->cuit_cuil = $cuit_cuil;
	}
	public function setEmail($email) {
		$this->email = $email;
	}
	public function setSitioWeb($sitio_web) {
		$this->sitio_web = $sitio_web;
	}
	public function setTelefono($telefono) {
		$this->telefono = $telefono;
	}
	public function setDireccion($direccion) {
		$this->direccion = $direccion;
	}
	public function setCiudad($ciudad) {
		$this->ciudad = $ciudad;
	}
	public function setProvincia($provincia) {
		$this->provincia = $provincia;
	}
	public function setCodigoPostal($codigo_postal) {
		$this->codigo_postal = $codigo_postal;
	}
	public function setPais($pais) {
		$this->pais = $pais;
	}

	//Implementamos un método para insertar registros
	public function insertar() {
		$sql = "INSERT INTO proveedores (nombre, cuit_cuil, email, sitio_web, telefono, direccion, ciudad, provincia, codigo_postal, pais) VALUES ('$this->nombre', '$this->cuit_cuil', '$this->email', '$this->sitio_web', '$this->telefono', '$this->direccion', '$this->ciudad', '$this->provincia', '$this->codigo_postal', '$this->pais')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idproveedor) {
		$sql = "UPDATE proveedores SET nombre='$this->nombre', cuit_cuil='$this->cuit_cuil', email='$this->email', sitio_web='$this->sitio_web', telefono='$this->telefono', direccion='$this->direccion', ciudad='$this->ciudad', provincia='$this->provincia', codigo_postal='$this->codigo_postal', pais='$this->pais' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

//Implementamos un método para eliminar proveedores
	public function eliminar($idproveedor) {
		$sql = "DELETE FROM proveedores WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproveedor) {
		$sql = "SELECT * FROM proveedores WHERE idproveedor='$idproveedor'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	static function listar() {
		$sql = "SELECT * FROM proveedores";
		return ejecutarConsulta($sql);
	}

}

 ?>