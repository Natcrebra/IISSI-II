<nav>
	<ul class="topnav" id="myTopnav">
		<li><a href="consulta_libros.php">Libros</a></li>
	  	<li><a href="about.php">Sobre nosotros</a></li>
		<li><a href="https://scholar.google.com">Busca en google</a></li>
 		  	
		<li><?php if (isset($_SESSION['login'])) {	?>
				<a href="logout.php">Desconectar</a>
			<?php } ?>
		</li>
		
		<li class="icon">
			<a href="javascript:void(0);" onclick="myToggleMenu()">&#9776;</a>
		</li>	
	</ul>
</nav>
