<?php
function listarProvincias($conexion){
	try{
		$consulta = "SELECT * FROM PROVINCIAS ORDER BY NOMBRE";
    	$stmt = $conexion->query($consulta);
		return $stmt;
	}catch(PDOException $e) {
		return $e->getMessage();
    }
}

function buscarProvincia($conexion, $nombre){
	try{
		$consulta = "SELECT OID_PROVINCIA FROM PROVINCIAS WHERE NOMBRE LIKE :nombre";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':nombre',$nombre);	
		$stmt->execute();	
		
		return $stmt;
	}catch(PDOException $e) {
		return $e->getMessage();
    }
}

function buscarMunicipioProvincia($conexion, $oid_provincia, $oid_municipio){
	try{
		$consulta = "SELECT * FROM MUNICIPIOS WHERE OID_PROVINCIA = :prov AND OID_MUNICIPIO = :mun";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':mun',$oid_municipio);
		$stmt->bindParam(':prov',$oid_provincia);	
		$stmt->execute();	
		
		return $stmt;
	}catch(PDOException $e) {
		return $e->getMessage();
    }
}

?>