<?php
	session_start();

	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionar_direcciones.php");
	require_once("gestionar_generos_literarios.php");

	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		// Recogemos los datos del formulario
		$nuevoUsuario["nif"] = $_REQUEST["nif"];
		$nuevoUsuario["nombre"] = $_REQUEST["nombre"];
		$nuevoUsuario["apellidos"] = $_REQUEST["apellidos"];
		$nuevoUsuario["email"] = $_REQUEST["email"];
		$nuevoUsuario["perfil"] = $_REQUEST["perfil"];
		$nuevoUsuario["fechaNacimiento"] = $_REQUEST["fechaNacimiento"];
		$nuevoUsuario["pass"] = $_REQUEST["pass"];
		$nuevoUsuario["confirmpass"] = $_REQUEST["confirmpass"];
		
		$nuevoUsuario["municipio"] = $_REQUEST["municipio"];
		$nuevoUsuario["provincia"] = $_REQUEST["provincia"];
		$nuevoUsuario["calle"] = $_REQUEST["calle"];
	
		if(isset($_REQUEST["generoLiterario"])){
			$nuevoUsuario["generoLiterario"] = $_REQUEST["generoLiterario"];
		}else{
			$nuevoUsuario["generoLiterario"] = array();
		}
		
		// Guardar la variable local con los datos del formulario en la sesión.
		$_SESSION["formulario"] = $nuevoUsuario;		
	}
	else // En caso contrario, vamos al formulario
		Header("Location: form_alta_usuario.php");

	// Validamos el formulario en servidor
	$conexion = crearConexionBD(); 
	$errores = validarDatosUsuario($conexion, $nuevoUsuario);
	cerrarConexionBD($conexion);
	
	// Si se han detectado errores
	if (count($errores)>0) {
		// Guardo en la sesión los mensajes de error y volvemos al formulario
		$_SESSION["errores"] = $errores;
		Header('Location: form_alta_usuario.php');
	} else
		// Si todo va bien, vamos a la página de acción (inserción del usuario en la base de datos)
		Header('Location: accion_alta_usuario.php');

///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosUsuario($conexion, $nuevoUsuario){
	$errores=array();
	// Validación del NIF
	if($nuevoUsuario["nif"]=="") 
		$errores[] = "<p>El NIF no puede estar vacío</p>";
	else if(!preg_match("/^[0-9]{8}[A-Z]$/", $nuevoUsuario["nif"])){
		$errores[] = "<p>El NIF debe contener 8 números y una letra mayúscula: " . $nuevoUsuario["nif"]. "</p>";
	}

	// Validación del Nombre			
	if($nuevoUsuario["nombre"]=="") 
		$errores[] = "<p>El nombre no puede estar vacío</p>";
	
	// Validación del email
	if($nuevoUsuario["email"]==""){ 
		$errores[] = "<p>El email no puede estar vacío</p>";
	}else if(!filter_var($nuevoUsuario["email"], FILTER_VALIDATE_EMAIL)){
		$errores[] = "<p>El email es incorrecto: " . $nuevoUsuario["email"]. "</p>";
	}
		
	// Validación del perfil
	if($nuevoUsuario["perfil"] != "ALUMNO" &&
		$nuevoUsuario["perfil"] != "PAS" && 
		$nuevoUsuario["perfil"] != "PDI") {
		$errores[] = "<p>El perfil debe ser ALUMNO, PAS o PDI</p>";
	}
		
	// Validación de la contraseña
	if(!isset($nuevoUsuario["pass"]) || strlen($nuevoUsuario["pass"])<8){
		$errores [] = "<p>Contraseña no válida: debe tener al menos 8 caracteres</p>";
	}else if(!preg_match("/[a-z]+/", $nuevoUsuario["pass"]) || 
		!preg_match("/[A-Z]+/", $nuevoUsuario["pass"]) || !preg_match("/[0-9]+/", $nuevoUsuario["pass"])){
		$errores[] = "<p>Contraseña no válida: debe contener letras mayúsculas y minúsculas y dígitos</p>";
	}else if($nuevoUsuario["pass"] != $nuevoUsuario["confirmpass"]){
		$errores[] = "<p>La confirmación de contraseña no coincide con la contraseña</p>";
	}
	
	// Validación de la dirección
	if($nuevoUsuario["calle"]==""){
		$errores[] = "<p>La dirección no puede estar vacía</p>";	
	}
	
	// Validar municipio y provincia
	$error = validarProvinciaMunicipio($conexion, $nuevoUsuario["provincia"], $nuevoUsuario["municipio"]);
	if($error!="")
		$errores[] = $error;
	
	// Validar géneros literarios
	$error = validarGenerosLiterarios($conexion, $nuevoUsuario["generoLiterario"]);
	if($error!="")
		$errores[] = $error;
	
	return $errores;
}

// Comprueba si los géneros literarios pasados por el usuario están en la BD
function validarGenerosLiterarios($conexion, $generos){
	$error="";
	$generos_db = array(); 
	$db = listarGeneros($conexion);
	foreach ($db as $gen_db){
		$generos_db[] = $gen_db["OID_GENERO"];
	}
	
	if(count(array_intersect($generos_db, $generos)) < count($generos)){
		$error = $error ."<p>Los géneros no son válidos</p>";
	}
	
	return $error;
}

// Comprueba que la pareja municipio-provincia están en la BD
function validarProvinciaMunicipio($conexion, $provincia, $municipio){
	$error="";
	$mun = buscarMunicipioProvincia($conexion, $provincia, $municipio);
	$cont = 0;
	foreach($mun as $m){
		$cont = $cont + 1;
	}
	
	if($cont != 1){
		$error =  "<p>El municipo y la provincia no son válidos</p>";
	}
	return $error;
}

?>

