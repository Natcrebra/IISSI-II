<?php
  /*
     * #===========================================================#
     * #	Este fichero contiene las funciones de gestión     			 
     * #	de libros de la capa de acceso a datos 		
     * #==========================================================#
     */
function consultarTodosLibros($conexion) {
	$consulta = "SELECT * FROM AUTORES, LIBROS, AUTORIAS"
		. " WHERE (AUTORES.OID_AUTOR = AUTORIAS.OID_AUTOR"
		. "   AND LIBROS.OID_LIBRO = AUTORIAS.OID_LIBRO)"
		. " ORDER BY APELLIDOS, NOMBRE";
    return $conexion->query($consulta);
}

function quitar_libro($conexion,$OidLibro) {
	try {
		$stmt=$conexion->prepare('CALL QUITAR_LIBRO(:OidLibro)');
		$stmt->bindParam(':OidLibro',$OidLibro);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function modificar_titulo($conexion,$OidLibro,$TituloLibro) {
	try {
		$stmt=$conexion->prepare('CALL MODIFICAR_TITULO(:OidLibro,:TituloLibro)');
		$stmt->bindParam(':OidLibro',$OidLibro);
		$stmt->bindParam(':TituloLibro',$TituloLibro);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
    
?>