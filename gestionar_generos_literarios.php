<?php
require_once("gestionBD.php");

// Listado de los géneros literarios de la BD
function listarGeneros($conexion){
	try {
		$consulta = "SELECT * FROM GENEROS";
		$stmt = $conexion->query($consulta);

		return $stmt;
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function getNombreGeneroLiterario($conexion, $genero){
	try {
		$consulta = "SELECT NOMBRE FROM GENEROS WHERE OID_GENERO=:gen";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':gen',$genero);
		$stmt->execute();
		$result = $stmt->fetch();
		
		return $result["NOMBRE"];
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
?>