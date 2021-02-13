<?php 
//Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proveedor {

	//Implementamos nuestro constructor
	public function __construct() {

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$cuit_cuil,$email,$sitio_web,$telefono,$calle,$ciudad,$provincia,$codigo_postal,$pais) {
		$sql = "INSERT INTO proveedores (nombre,cuit_cuil,email,sitio_web,telefono,calle,ciudad,provincia,codigo_postal,pais, estado) VALUES ('$nombre','$cuit_cuil','$email','$sitio_web','$telefono','$calle','$ciudad','$provincia','$codigo_postal','$pais', '1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idproveedor,$nombre,$cuit_cuil,$email,$sitio_web,$telefono,$calle,$ciudad,$provincia,$codigo_postal,$pais) {
		$sql = "UPDATE proveedores SET nombre='$nombre',cuit_cuil='$cuit_cuil',email='$email',sitio_web='$sitio_web',telefono='$telefono',calle='$calle',ciudad='$ciudad',provincia='$provincia',codigo_postal='$codigo_postal',pais='$pais' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idproveedor) {
		$sql = "UPDATE proveedores SET estado='0' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idproveedor) {
		$sql = "UPDATE proveedores SET estado='1' WHERE idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproveedor) {
		$sql = "SELECT * FROM proveedores WHERE idproveedor='$idproveedor'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar() {
		$sql = "SELECT * FROM proveedores";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select() {
		$sql="SELECT * FROM proveedores where estado=1";
		return ejecutarConsulta($sql);		
	}
}

 ?>