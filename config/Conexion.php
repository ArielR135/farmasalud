<?php 
/*Conexión a la base de datos utilizando el controlador "mysqli" y 
las constantes globales incluidas en el archivo "global.php"*/
require_once "global.php";

$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Consulta a la BD indicando el conjunto de caracteres según el valor de la constante global "DB_ENCODE"
mysqli_query($conexion, 'SET NAMES "' . DB_ENCODE . '"');

//Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno()) {
	printf("Falló la conexión a la base de datos: %s\n", mysqli_connect_error());
	exit();
}

//Funciones para hacer peticiones a la BD evaluando primero si no existe ya alguna petición
if (!function_exists('ejecutarConsulta')) {
	
	function ejecutarConsulta($sql) {
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaSimpleFila($sql) {
		global $conexion;
		$query = $conexion->query($sql);
		//Obtiene una fila como resultado en un array y lo guarda en la variable "$row"
		$row = $query->fetch_assoc();
		return $row;
	}

	function ejecutarConsulta_retornarID($sql) {
		global $conexion;
		$query = $conexion->query($sql);
		return $conexion->insert_id;
	}

	function limpiarCadena($str) {
		global $conexion;
		//Escapa los carácteres especiales de una cadena para usarlo en una sentencia SQL
		$str = mysqli_real_escape_string($conexion, trim($str));
		return htmlspecialchars($str);
	}
}

 ?>
