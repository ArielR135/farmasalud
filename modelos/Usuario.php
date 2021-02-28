<?php 
// Incluimos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario {

	private $nombre_usuario;
	private $contraseña;
	private $nombre;
	private $apellido;
	private $dni;
	private $rol;
	private $telefono;
	private $email;
	private $imagen;
	private $permisos;

	// Implementamos nuestro constructor
	function __construct($nombre_usuario=null, $contraseña=null, $nombre=null, $apellido=null, $dni=null, $rol=null, $telefono=null, $email=null, $imagen=null, $permisos=Array()) {
		$this->nombre_usuario = $nombre_usuario;
		$this->contraseña = $contraseña;
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->dni = $dni;
		$this->rol = $rol;
		$this->telefono = $telefono;
		$this->email = $email;
		$this->imagen = $imagen;
		$this->permisos = $permisos;
	}

	function setNombreUsuario($nombre_usuario) {
		$this->nombre_usuario = $nombre_usuario;
	}
	function setContraseña($contraseña) {
		$this->contraseña = $contraseña;
	}
	function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	function setApellido($apellido) {
		$this->apellido = $apellido;
	}
	function setDni($dni) {
		$this->dni = $dni;
	}
	function setRol($rol) {
		$this->rol = $rol;
	}
	function setTelefono($telefono) {
		$this->telefono = $telefono;
	}
	function setEmail($email) {
		$this->email = $email;
	}
	function setImagen($imagen) {
		$this->imagen = $imagen;
	}
	function getImagen()	{
		return $this->imagen;
	}
	function setPermisos($permisos) {
		$this->permisos = $permisos;
	}

	// Implementamos un método para insertar registros
	public function insertar() {
		$sql = "INSERT INTO usuarios (nombre_usuario, contraseña, nombre, apellido, dni, rol, telefono, email, imagen, fecha_registro, estado)
		VALUES ('$this->nombre_usuario', '$this->contraseña', '$this->nombre', '$this->apellido', '$this->dni', '$this->rol', '$this->telefono', '$this->email', '$this->imagen', CURRENT_DATE(), '1')";

		// return ejecutarConsulta($sql);
		$idusuarioNew=ejecutarConsulta_retornarID($sql);
		$sw=true;

		for ($i = 0; $i < count($this->permisos); $i++) {
			$sql_detalle = "INSERT INTO usuarios_permisos(idusuario,idpermiso) VALUES('$idusuarioNew', '$this->permisos[$i]')";
			ejecutarConsulta($sql_detalle) or $sw=false;
		}
		return $sw;
	}

	// Implementamos un método para editar registros
	public function editar($idusuario) {
		// var_dump($this->permisos);
		// var_dump($this->permisos[0]);
		// var_dump($this->permisos[1]);
		$sql = "UPDATE usuarios SET nombre_usuario='$this->nombre_usuario', contraseña='$this->contraseña', nombre='$this->nombre', apellido='$this->apellido', dni='$this->dni', rol='$this->rol', telefono='$this->telefono', email='$this->email', imagen='$this->imagen'
		WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql);

		// Eliminamos todos los permisos asignados para volverlos a registrar
		$sqlDel="DELETE FROM usuarios_permisos WHERE idusuario='$idusuario'";
		ejecutarConsulta($sqlDel);
		$sw=true;

		for ($i = 0; $i < count($this->permisos); $i++) {
			$sql_detalle = "INSERT INTO usuarios_permisos (idusuario, idpermiso) VALUES ('$idusuario', '{$this->permisos[$i]}')";
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