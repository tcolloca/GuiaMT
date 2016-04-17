<?php

include_once(ROOT."/functions/database_management.php");

function drawKubeIslandTypeTable() {
	
	getEditTableButton();
	echo '<br />';
	
	echo '<table id="kube-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Imagen</b></th>
				<th class="even"><b>Nombre</b></th>
				<th class="odd"><b>Cubos</b></th>
				<th class="even"><b>Notas</b></th>';
		echo '</tr>';
		
		$i = 0;
		
		while($island = getTableRow("kube", "island_type")) {
			drawKubeIslandType($island, $i++);
		}
	echo "</table>";
}
			
function drawKubeIslandType($island, $i) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$island["island_type_img"].'</td>';
		echo '<td class="even" width="100px">'.$island["island_type_name"].'</td>';
		echo '<td class="odd" width="200px">'.$island["island_type_cubes"].'</td>';
		echo '<td class="even" style="text-align:left;padding:10px">'.$island["island_type_notes"].'</td>';
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
			<input type='hidden' name='table' value='island_type' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}	
}

?>