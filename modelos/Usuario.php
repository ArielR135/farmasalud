<?php 
// Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario {

	// Implementamos nuestro constructor
	public function __construct() {

	}

	// Implementamos un método para insertar registros
	public function insertar($nombre_usuario, $contraseña, $nombre, $apellido, $dni, $rol, $telefono, $email, $imagen,$permisos) {
		$sql = "INSERT INTO usuarios (nombre_usuario, contraseña, nombre, apellido, dni, rol, telefono, email, imagen, fecha_registro, estado)
		VALUES ('$nombre_usuario', '$contraseña', '$nombre', '$apellido', '$dni', '$rol', '$telefono', '$email', '$imagen', CURRENT_DATE(), '1')";

		// return ejecutarConsulta($sql);
		$idusuarioNew=ejecutarConsulta_retornarID($sql);
		$sw=true;

		for ($i = 0; $i < count($permisos); $i++) {
			$sql_detalle = "INSERT INTO usuarios_permisos(idusuario,idpermiso) VALUES('$idusuarioNew', '$permisos[$i]')";
			ejecutarConsulta($sql_detalle) or $sw=false;
		}
		return $sw;
	}

	// Implementamos un método para editar registros
	public function editar($idusuario, $nombre_usuario, $contraseña, $nombre, $apellido, $dni, $rol, $telefono, $email, $imagen, $permisos) {
		$sql = "UPDATE usuarios SET nombre_usuario='$nombre_usuario', contraseña='$contraseña', nombre='$nombre', apellido='$apellido', dni='$dni', rol='$rol', telefono='$telefono', email='$email', imagen='$imagen'
		WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql);

		// Eliminamos todos los permisos asignados para volverlos a registrar
		$sqlDel="DELETE FROM usuarios_permisos WHERE idusuario='$idusuario'";
		ejecutarConsulta($sqlDel);
		$sw=true;

		for ($i = 0; $i < count($permisos); $i++) {
			$sql_detalle = "INSERT INTO usuarios_permisos(idusuario,idpermiso) VALUES('$idusuario', '$permisos[$i]')";
			ejecutarConsulta($sql_detalle) or $sw=false;
		}		
		return $sw;		
	}

	// Implementamos un método para desactivar registros
	public function desactivar($idusuario) {
		$sql = "UPDATE usuarios SET estado='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	// Implementamos un método para activar registros
	public function activar($idusuario) {
		$sql = "UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	// Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idusuario) {
		$sql = "SELECT * FROM usuarios WHERE idusuario='$idusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	// Implementar un método para listar los registros
	public function listar() {
		$sql = "SELECT * FROM usuarios";
		return ejecutarConsulta($sql);
	}

	// Implementar un método para listar los permisos marcados
	public function listarMarcados($idusuario) {
		$sql="SELECT * FROM usuarios_permisos WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	// Función para verificar el acceso al sistema
	public function verificar($nombre_usuario, $contraseña) {
		$sql="SELECT idusuario,nombre_usuario,nombre,apellido,dni,rol,telefono,email,imagen FROM usuarios WHERE nombre_usuario='$nombre_usuario' AND contraseña='$contraseña' AND estado='1'";
		return ejecutarConsulta($sql);
	}
}

 ?>