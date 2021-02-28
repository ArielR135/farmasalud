<?php
session_start();
require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre_usuario=isset($_POST["nombre_usuario"])? limpiarCadena($_POST["nombre_usuario"]):"";
$contraseña=isset($_POST["contraseña"])? limpiarCadena($_POST["contraseña"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$apellido=isset($_POST["apellido"])? limpiarCadena($_POST["apellido"]):"";
$dni=isset($_POST["dni"])? limpiarCadena($_POST["dni"]):"";
$rol=isset($_POST["rol"])? limpiarCadena($_POST["rol"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$imagen=isset($_FILES['imagen'])? $_FILES['imagen']:Array();
$permisos=isset($_POST['permisos'])? $_POST['permisos']:Array();

switch ($_GET["op"]) {
	case 'guardaryeditar':
		// var_dump($imagen);
		$usuario->setNombreUsuario($nombre_usuario);
		$usuario->setContraseña($contraseña);
		$usuario->setNombre($nombre);
		$usuario->setApellido($apellido);
		$usuario->setDni($dni);
		$usuario->setRol($rol);
		$usuario->setTelefono($telefono);
		$usuario->setEmail($email);
		$usuario->setImagen($imagen);
		$usuario->setPermisos($permisos);
		if (!file_exists($imagen['tmp_name']) || !is_uploaded_file($imagen['tmp_name'])) {
			$usuario->setImagen($_POST["imagenactual"]);
			//$imagen="";
		}
		else {
			$ext = explode(".", $usuario->getImagen()["name"]);
			if ($imagen['type'] == "image/jpg" || $imagen['type'] == "image/jpeg" || $imagen['type'] == "image/png") {
				$usuario->setImagen(round(microtime(true)) . '.' . end($ext));
				move_uploaded_file(
					$imagen["tmp_name"], 
					"../files/usuarios/" . $usuario->getImagen()
				);
			}
		}

		// Hash SH256 en la contraseña
		$usuario->setContraseña(hash("SHA256",$contraseña));

		if (empty($idusuario)) {
			$rspta=$usuario->insertar();
			echo $rspta ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
		}
		else {
			$rspta=$usuario->editar($idusuario);
			echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$usuario->desactivar($idusuario);
 		echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
	break;

	case 'activar':
		$rspta=$usuario->activar($idusuario);
 		echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
	break;

	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$usuario->listar();
		
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')" title="Ver y editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')" title="Desactivar"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')" title="Ver y editar"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')" title="Activar"><i class="fa fa-check"></i></button>',
				"1"=>$reg->nombre_usuario,
 				"2"=>$reg->nombre,
 				"3"=>$reg->apellido,
 				"4"=>$reg->dni,
 				"5"=>$reg->rol,
 				"6"=>$reg->telefono,
 				"7"=>$reg->email,
 				"8"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px' >",
 				"9"=>$reg->fecha_registro,
				"10"=>$reg->ultima_sesion,
 				"11"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
				'<span class="label bg-red">Desactivado</span>'
 			);
		}
		 
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
			"aaData"=>$data
		);
 		echo json_encode($results);
	break;
	
	case 'permisos':
		// Obtenemos todos los permisos de la tabla permisos
		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();

		// Obtener los permisos asignados al usuario
		$id=$_GET['id'];
		$marcados = $usuario->listarMarcados($id);
		// Declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		// Almacenar los permisos asignados al usuario en el array
		while ($per = $marcados->fetch_object()) {
			array_push($valores, $per->idpermiso);
		}

		// Mostramos la lista de permisos en la vista y si están o no marcados
		while ($reg = $rspta->fetch_object()) {
			$sw=in_array($reg->idpermiso,$valores)?'checked':'';
			echo '<li><input type="checkbox" '.$sw.' name="permisos[]" value="'.$reg->idpermiso.'"> '.$reg->nombre.'</li>';
		}
	break;

	case 'verificar':
		// $nombre_usuario=$_POST['nombre_usuario'];
		// $contraseña=$_POST['contraseña'];

		// Hash SHA256 en la contraseña
		$claveHash=hash("SHA256",$contraseña);

		$rspta=$usuario->verificar($nombre_usuario, $claveHash);
		$fetch=$rspta->fetch_object();

		if (isset($fetch)) {
			// Declaramos las variables de sesión
			$_SESSION['idusuario']=$fetch->idusuario;
			$_SESSION['nombre']=$fetch->nombre;
			$_SESSION['apellido']=$fetch->apellido;
			$_SESSION['imagen']=$fetch->imagen;
			$_SESSION['nombre_usuario']=$fetch->nombre_usuario;

			// Obtenemos los permisos del usuario
			$marcados = $usuario->listarmarcados($fetch->idusuario);

			// Declaramos el array para almacenar todos los permisos marcados
			$valores=array();

			// Almacenamos los permisos marcados en el array
			while ($per = $marcados->fetch_object()) {
				array_push($valores, $per->idpermiso);
			}

			// Determinamos los accesos del usuario
			in_array(1, $valores) ? $_SESSION['escritorio']=1 : $_SESSION['escritorio']=0;
			in_array(2, $valores) ? $_SESSION['almacen']=1 : $_SESSION['almacen']=0;
			in_array(3, $valores) ? $_SESSION['compras']=1 : $_SESSION['compras']=0;
			in_array(4, $valores) ? $_SESSION['acceso']=1 : $_SESSION['acceso']=0;
			in_array(5, $valores) ? $_SESSION['consultas']=1 : $_SESSION['consultas']=0;
		}
		echo json_encode($fetch);
	break;

	case 'salir':
		// Limpiamos las variables de sesión
		session_unset();
		// Destruimos la sesión
		session_destroy();
		// Redireccionamos al login
		header("Location: ../index.php");
	break;
}
?>