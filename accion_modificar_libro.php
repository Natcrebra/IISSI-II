<?php	
	session_start();	
	
	if (isset($_SESSION["libro"])) {
		$libro = $_SESSION["libro"];
		unset($_SESSION["libro"]);
		
		require_once("gestionBD.php");
		require_once("gestionarLibros.php");
		
		$conexion = crearConexionBD();		
		$excepcion = modificar_titulo($conexion,$libro["OID_LIBRO"],$libro["TITULO"]);
		cerrarConexionBD($conexion);
			
		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_libros.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consulta_libros.php");
	} 
	else Header("Location: consulta_libros.php"); // Se ha tratado de acceder directamente a este PHP
?>
