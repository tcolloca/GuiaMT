<?php

	include_once( ROOT.'/functions/database_management.php');

	if($_POST["game"] != "none") {
		
		$game = $_POST["game"];
		
		if(!isset($_POST["subdir"]) || $_POST["subdir"] == "" || file_exists(ROOT."/images/".$game."/".$_POST["subdir"])) {
		
			if(isset($_POST["subdir"]) && $_POST["subdir"] != "") {
				$subdir = $_POST["subdir"]."/";
			} else {
				$subdir = "";
			}
			
			
			$img = $_FILES["img"]["name"];
			$pattern = "/^[a-z]+[a-z0-9_]*.(png|gif)$/";
		
			if(preg_match($pattern, $img)) {
				
				$url = ROOT."/images/".$game."/".$subdir.$img;
			
				if(!file_exists($url)) {
					
					if ($_FILES["img"]["size"] <= 1024*1024) {
						if(move_uploaded_file($_FILES['img']['tmp_name'], $url)) {
							
							$msgUrl = "/images/".$game."/".$subdir.$img;
						
							echo "<p class='ok'>¡La imagen se subió con éxito! La url de la misma es: ".$msgUrl."</p>";
							
							$date = date("Y-m-d H:i:s");
							$user = $_SESSION['username'];
							$new = "Imagen: ".$img;
							addModification($date, $user, $new, $url);
						} else {
							echo '<p class="error">Hubo un problema al cargar el archivo. Intenta nuevamente, y sino comunícate con Tom.</p>';
						}
					} else {
						echo '<p class="error">La imagen es demasiado grande (Mayor a 1MB)</p>';	
					}
				} else {
					echo '<p class="error">¡Ya existe una imagen con el mismo nombre!</p>';	
				}
			} else {
				echo '<p class="error">El nombre de la imagen es inválido. Verifica que empiece con una letra, que contenga sólo letras en minúscula, números y guiones bajo(_), y termine en .png o .gif</p>';	
			}
		} else {
			echo '<p class="error">El subdirectorio '.$_POST["subdir"].' es inválido.</p>';	
		}
	} else {
		echo '<p class="error">Debes seleccionar un juego.</p>';	
	}

	
?>

