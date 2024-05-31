<?php
	session_start();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Hay que indicar el fichero externo de estilos -->
    <link rel="stylesheet" type="text/css" href="css/biblio.css" />
	<script type="text/javascript" src="./js/boton.js"></script>
  <title>Gestión de biblioteca: Sobre Nosotros</title>
</head>

<body>
<?php	
	include_once("cabecera.php"); 
	include_once("menu.php");
?>
<main>

<h3>Introducción a la Ingeniería del Software y los Sistemas de Información</h2>

<h4>Objetivos y competencias<sup><a href="#fn1" id="r1">[1]</a></sup></h3>

<ul>
<li>Conocer los conceptos básicos de la Ingeniería del Software.</li>
<li>Conocer los conceptos básicos de los Sistemas de Información.</li>
<li>Conocer los conceptos básicos de Gestión de Proyectos.</li>
<li>Conocer los conceptos básicos de Control de Versiones.</li>
<li>Ser capaz de manejar una herramienta de gestión de proyectos.</li>
<li>Ser capaz de manejar una herramienta de control de versiones.</li>
<li>Ser capaz de estudiar un dominio de problema, unos procesos de negocio y elaborar unos requisitos básicos.</li>
<li>Ser capaz de analizar requisitos mediante el desarrollo de modelos conceptuales.</li>
<li>Conocer el Modelo Relacional de datos.</li>
<li>Ser capaz de transformar modelos conceptuales en modelos relacionales.</li>
<li>Conocer el lenguaje SQL.</li>
<li>Ser capaz de manejar un SGBD relacional avanzado.</li>
<li>Ser capaz de desarrollar un esquema SQL complejo.</li>
<li>Conocer los conceptos básicos de las aplicaciones web.</li>
<li>Conocer tecnologías de cliente web: HTML, CSS y Javascript.</li>
<li>Ser capaz de desarrollar una interfaz de usuario sencilla con HTML, CSS y Javascript.</li>
<li>Conocer tecnologías de procesamiento en servidor web.</li>
<li>Ser capaz de desarrollar una aplicación web con acceso a una base de datos relacional.</li>
</ul>

<p>Para más información, visitar la <a href="http://www.lsi.us.es/docencia/pagina_asignatura.php?id=96" target="_blank">página de la asignatura</a></p>

<section id="footnotes" class="footnotes">
 <p id="fn1"><a href="#r1">[1]</a> Extraído del programa docente de la asignatura.</p>
</section>

</main>

<?php	
	include_once("pie.php");
?>		
</body>
</html>
