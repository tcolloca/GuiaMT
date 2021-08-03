<?php

	include_once( ROOT.'/functions/database_management.php');

	if($_POST["game"] != "none") {
		
		$game = $_POST["game"];
		
		
		$subdir = $_POST["subdir"];
		$pattern = "/^[a-z]+[a-z_]*$/";
		
		if(preg_match($pattern, $subdir)) {
		
			$url = ROOT."/images/".$game."/".$subdir;
			
			if(!file_exists($url)) {
				
				if (strlen($subdir) <= 128) {
					
					echo $url;
					if(mkdir($url)) {
							
						$msgUrl = "/images/".$game."/".$subdir.$img;
							
						echo "<p class='ok'>¡El subdirectorio se creo con éxito!</p>";
								
						$date = date("Y-m-d H:i:s");
						$user = $_SESSION['username'];
						$new = "Subdirectorio: ".$subdir;
						addModification($date, $user, $new, $url);
					} else {
						echo '<p class="error">Hubo un problema al crear el subdirectorio. Intenta nuevamente, y sino comunícate con Tom.</p>';
					}
				} else {
					echo '<p class="error">El nombre es demasiado largo (más de 128 caracteres)</p>';
				}
			} else {
				echo '<p class="error">¡El subdirectorio ya existe!</p>';	
			}
		} else {
			echo '<p class="error">El subdirectorio no puede tener otros directorios anidados, debe comenzar con una letra, y sólo puede contener letras y guiones bajo(_).</p>';	
		}
	} else {
		echo '<p class="error">Debes seleccionar un juego.</p>';	
	}

	
?>

