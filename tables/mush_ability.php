<?php

include_once(ROOT."/functions/database_management.php");

function drawMushAbilityTable() {
	
	getEditTableButton();
	echo '<br />';
	
	echo '<table id="mush-table">';
		echo '<tr>';
			echo '<th><b>Imagen</b></th>
				<th><b>Nombre</b></th>
				<th><b>Acción</b></th>
				<th><b>Explicación</b></th>
				<th><b>Libro</b></th>
				<th><b>Personajes</b></th>';
		echo '</tr>';
		
		$i = 0;
		while($ability = getTableRow("mush", "ability")) {
			printMushAbilityData($ability, $i++);
		}
	echo "</table>";
}
			
function printMushAbilityData($ability, $i) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$ability["ability_img"].'</td>';
		echo '<td class="even">'.$ability["ability_name"].'</td>';
		echo '<td class="odd">'.$ability["ability_action"].'</td>';
		echo '<td class="even">'.$ability["ability_description"].'</td>';
		echo '<td class="odd">'.$ability["ability_book"].'</td>';
		echo '<td class="even">'.$ability["ability_characters"].'</td>';
	echo '</tr>';
}

function getEditTableButton() {
	
	if(isStaff($_SESSION['username'])) { 
			$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
			list($gameSection) = sscanf( $url, "http://".$_SERVER['SERVER_NAME']."/%[^/]");
			
			if( $gameSection == "" ) {
				$gameSection = "index.php";
			}
			
	 		echo "<form method='post' action='/modify/index.php' \>			
            <input type='hidden' name='section' value='".$gameSection."' />                 
            <input type='hidden' name='action' value='table' />
			<input type='hidden' name='table' value='ability' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}	
}

?>