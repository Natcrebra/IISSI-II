<?php
require_once("gestionBD.php");

// EJERCICIO 4: Código que se ejecutará en la llamada AJAX a este script

// Si llegamos a este script por haber seleccionado una provincia
if(isset($_GET["provinciaMunicipio"])){
	// Abrimos una conexión con la BD y consultamos la lista de municipios dada una provincia
	$conexion = crearConexionBD();
	$resultado = listarMunicipios($conexion, $_GET["provinciaMunicipio"]);
	
	if(resultado != NULL){
		// Para cada municipio del listado devuelto
		foreach($resultado as $municipio){
			// Creamos options con valores = oid_municipio y label = nombre del municipio
			echo "<option label='" . $municipio["NOMBRE"] . "' value='" . $municipio["OID_MUNICIPIO"] . "'/>";	
		}
	}
	// Cerramos la conexión y borramos de la sesión la variable "provincia"
	cerrarConexionBD($conexion);
	unset($_GET["provinciaMunicipio"]);
}


// Función que devuelve el listado de municipios de una provincia dada
function listarMunicipios($conexion, $provincia){
	try {
		$consulta = "SELECT NOMBRE, OID_MUNICIPIO FROM MUNICIPIOS WHERE OID_PROVINCIA=:prov";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':prov',$provincia);	

		$stmt->execute();	

		return $stmt;
	} catch(PDOException $e) {
		return NULL;
    }
}

// FIN DE EJERCICIO 4 
?>