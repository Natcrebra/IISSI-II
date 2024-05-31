<?php
	session_start();
	
	// Importar librerías necesarias para gestionar direcciones y géneros literarios
	require_once("gestionBD.php");
	require_once("gestionar_direcciones.php");
	require_once("gestionar_generos_literarios.php");
	
	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION["formulario"])) {
		$formulario['nif'] = "";
		$formulario['nombre'] = "";
		$formulario['apellidos'] = "";
		$formulario['perfil'] = "ALUMNO";
		$formulario['fechaNacimiento'] = "";
		$formulario['email'] = "";
		$formulario['pass'] = "";
		$formulario['calle'] = "";
		$formulario['provincia'] = "";
		$formulario['municipio'] = "";
		$formulario['generoLiterario'] = array();
	
		$_SESSION["formulario"] = $formulario;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formulario = $_SESSION["formulario"];
			
	// Si hay errores de validación, hay que mostrarlos y marcar los campos (El estilo viene dado y ya se explicará)
	if (isset($_SESSION["errores"])){
		$errores = $_SESSION["errores"];
		unset($_SESSION["errores"]);
	}
		
	// Creamos una conexión con la BD
	$conexion = crearConexionBD();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/biblio.css" />
  <script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
  <!--<script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>-->
  <script src="js/validacion_cliente_alta_usuario.js" type="text/javascript"></script>
  <title>Gestión de Biblioteca: Alta de Usuarios</title>
</head>

<body>
	<script>
		// Inicialización de elementos y eventos cuando el documento se carga completamente
		$(document).ready(function() {
			$("#altaUsuario").on("submit", function() {
				return validateForm();
			});
			
			// EJERCICIO 2: Manejador de evento para copiar automáticamente el email como nick del usuario
			$("#email").on("input", function(){
				$("#nick").val($(this).val());
			});

			// EJERCICIO 3: Manejador de evento del color de la contraseña
			$("#pass").on("keyup", function() {
				// Calculo el color
				passwordColor();
			});

			// EJERCICIO 4: Uso de AJAX con JQuery para cargar de manera asíncrona los municipios según la provincia seleccionada
			// Manejador de evento sobre el campo de provincias
			$("#provincia").on("input", function () {
				// Llamada AJAX con JQuery, pasándole el valor de la provincia como parámetro
        		$.get("cambio_provincia.php", { provinciaMunicipio: $("#provincia").val()}, function (data) {
        			// Borro los municipios que hubiera antes en el datalist
        			$("#opcionesMunicipios").empty();
        			// Adjunto al datalist la lista de municipios devuelta por la consulta AJAX
        			$("#opcionesMunicipios").append(data);
				});
    		});
		});
	</script>
	
	<?php
		include_once("cabecera.php");
	?>
	
	<?php 
		// Mostrar los erroes de validación (Si los hay)
		if (isset($errores) && count($errores)>0) { 
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores en el formulario:</h4>";
    		foreach($errores as $error){
    			echo $error;
			} 
    		echo "</div>";
  		}
	?>
	
	<form id="altaUsuario" method="get" action="validacion_alta_usuario.php"
		>
		<!--novalidate-> 
		<!--onsubmit="return validateForm()"-->   
		<p><i>Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Datos personales</legend>
			<div></div><label for="nif">NIF<em>*</em></label>
			<input id="nif" name="nif" type="text" placeholder="12345678X" pattern="^[0-9]{8}[A-Z]" title="Ocho dígitos seguidos de una letra mayúscula" value="<?php echo $formulario['nif'];?>" required>
			</div>

			<div><label for="nombre">Nombre:<em>*</em></label>
			<input id="nombre" name="nombre" type="text" size="40" value="<?php echo $formulario['nombre'];?>" required/>
			</div>

			<div><label for="apellidos">Apellidos:</label>
			<input id="apellidos" name="apellidos" type="text" size="80" value="<?php echo $formulario['apellidos'];?>"/>
			</div>

			<div><label>Perfil:</label>
			<label>
				<input name="perfil" type="radio" value="ALUMNO" <?php if($formulario['perfil']=='ALUMNO') echo ' checked ';?>/>
				Alumno</label>
			<label>
				<input name="perfil" type="radio" value="PDI" <?php if($formulario['perfil']=='PDI') echo ' checked ';?>/>
				PDI</label>
			<label>
				<input name="perfil" type="radio" value="PAS" <?php if($formulario['perfil']=='PAS') echo ' checked ';?>/>
				PAS</label>
			</div>

			<div<<label for="fechaNacimiento">Fecha de nacimiento:</label>
			<input type="date" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $formulario['fechaNacimiento'];?>"/>
			</div>

			<div><label for="email">Email:<em>*</em></label>
			<input id="email" name="email"  type="email" placeholder="usuario@dominio.extension" value="<?php echo $formulario['email'];?>" required/><br>
			</div>
		</fieldset>

		<fieldset><legend>Datos de cuenta</legend>
			
			<div><label for="nick">Nickname:</label>
				<input id="nick" name="nick" type="text" size="40" value="<?php echo $formulario['email'];?>" />
			</div>
			<div><label for="pass">Password:<em>*</em></label>
                <input type="password" name="pass" id="pass" placeholder="Mínimo 8 caracteres entre letras y dígitos" required oninput="passwordValidation(); "/>
			</div>
			<div><label for="confirmpass">Confirmar Password: </label>
				<input type="password" name="confirmpass" id="confirmpass" placeholder="Confirmación de contraseña"  oninput="passwordConfirmation();" required"/>
			</div>
			
			<div><label for="generoLiterario">¿Cuáles son tus géneros literarios favoritos?<em>*</em></label>
				<select multiple name="generoLiterario[]" id="generoLiterario" required>
					<?php
						$generos = listarGeneros($conexion);
						
				  		foreach($generos as $genero) {
				  			// Chequea si este género literario fue seleccionado previamente, para marcarlo en el HTML con el atributo "selected"
				  			if(in_array($genero["OID_GENERO"], $formulario['generoLiterario'])){ 
								echo "<option value='".$genero["OID_GENERO"]."' label='".$genero["NOMBRE"]."' selected/>";
							}else{
								echo "<option value='".$genero["OID_GENERO"]."' label='".$genero["NOMBRE"]."'/>";
							}
						}
					?>
				</select>
			</div>
		</fieldset>

		<fieldset>
			<legend>
				Dirección
			</legend>

			<div><label for="calle">Calle/Avda.:<em>*</em></label>
			<input id="calle" name="calle" type="text" size="80" value="<?php echo $formulario['calle'];?>" required/>
			</div>

			<div><label for="provincia">Provincia:<em>*</em></label>
			<input list="opcionesProvincias" name="provincia" id="provincia" required value="<?php echo $formulario['provincia'];?>"/>
			<datalist id="opcionesProvincias">
			  	<?php
			  		$provincias = listarProvincias($conexion);

			  		foreach($provincias as $provincia) {
			  			echo "<option label='".$provincia["NOMBRE"]."' value='".$provincia["OID_PROVINCIA"]."'>";
					}
				?>
			</datalist>
			</div>

			<div><label for="municipio">Municipio:<em>*</em></label>
			<input id="municipio" name="municipio" type="text" list="opcionesMunicipios" required value="<?php echo $formulario['municipio'];?>">
			<datalist id="opcionesMunicipios">
			<!--
				AQUÍ se añadirán con AJAX las opciones de municipios según la provincia escogida
			-->
			</datalist>
			</div>
		</fieldset>

		<div><input type="submit" value="Enviar" /></div>
	</form>
	
	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>
	
	</body>
</html>
