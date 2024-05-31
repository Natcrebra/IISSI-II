<?php
  /*
     * #===========================================================#
     * #	Este fichero contiene las funciones de gestión
     * #	de usuarios de la capa de acceso a datos
     * #==========================================================#
     */

 function alta_usuario($conexion,$usuario) {
	$fechaNacimiento = date('d/m/Y', strtotime($usuario["fechaNacimiento"]));

	try {
		$consulta = "CALL INSERTAR_USUARIO(:nif, :nombre, :ape, :dir, :mun, :fec, :email, :pass, :perfil)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':nif',$usuario["nif"]);
		$stmt->bindParam(':nombre',$usuario["nombre"]);
		$stmt->bindParam(':ape',$usuario["apellidos"]);
		$stmt->bindParam(':dir',$usuario["calle"]);
		$stmt->bindParam(':mun',$usuario["municipio"]);
		$stmt->bindParam(':fec',$fechaNacimiento);
		$stmt->bindParam(':email',$usuario["email"]);
		$stmt->bindParam(':pass',$usuario["pass"]);
		$stmt->bindParam(':perfil',$usuario["perfil"]);
		
		$stmt->execute();
		
		return asignar_generos_usuario($conexion, $usuario["nif"], $usuario["generoLiterario"]);
	} catch(PDOException $e) {
		return false;
		// Si queremos visualizar la excepción durante la depuración: $e->getMessage();
    }
}
 
function asignar_generos_usuario($conexion, $nifUsuario, $generos) {
	$consulta = "CALL INSERTAR_GENERO_USUARIO(:genero, :usuario)";
	
	try{
		$stmt=$conexion->prepare($consulta);
		foreach ($generos as $genero){
			$stmt->bindParam(':genero',$genero);
			$stmt->bindParam(':usuario',$nifUsuario);
			$stmt->execute();
		}

		return true;
	}catch(PDOException $e){
		return false;
	}
  }

  
function consultarUsuario($conexion,$email,$pass) {
 	$consulta = "SELECT COUNT(*) AS TOTAL FROM USUARIOS WHERE EMAIL=:email AND PASS=:pass";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':email',$email);
	$stmt->bindParam(':pass',$pass);
	$stmt->execute();
	return $stmt->fetchColumn();
}

