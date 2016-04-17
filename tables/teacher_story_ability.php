<?php

include_once(ROOT."/functions/database_management.php");

function drawTSAbilityTable() {
	
	getEditTableButton();
	echo '<br />';
	
	echo '<table id="teacher-story-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Imagen</b></th>
				<th class="even"><b>Nombre</b><br />'.getSortArrows("name").'</th>
				<th class="odd"><b>Tipo</b><br />'.getSortArrows("type").'</th>
				<th class="even"><b>Tiempo</b><br />'.getSortArrows("time").'</th>
				<th class="odd"><b>Efecto</b></th>';
		echo '</tr>';
		
		$i = 0;
		
		if(isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "name" || $_GET['cat'] == "type" || $_GET['cat'] == "time")) {
			$column = $_GET['cat'];
			$order = $_GET['order'];
			
			while($ability = getTableRowSorted("teacher_story", "ability", $column, $order)) {
				printTSAbilityData($ability, $i++);
			}
		} else {
			while($ability = getTableRow("teacher_story", "ability")) {
				printTSAbilityData($ability, $i++);
			}	
		}
	echo "</table>";
}
	
function getLink($cat, $order) {
	return '?cat='.$cat.'&order='.$order;
}

function getSortArrows($cat) {
	$upImg = '<img src="/images/teacher_story/table/sortup.png" />';
	$downImg = '<img src="/images/teacher_story/table/sortdown.png" />';
	
	return '<a href="'.getLink($cat, "desc").'">'.$downImg.'</a>
			<a href="'.getLink($cat, "asc").'">'.$upImg.'</a>';
}	
			
function printTSAbilityData($ability, $i) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	$time = $ability["ability_time"] == 0?"-":$ability["ability_time"];
	
	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$ability["ability_img"].'</td>';
		echo '<td class="even">'.$ability["ability_name"].'</td>';
		echo '<td class="odd">'.$ability["ability_type"].'</td>';
		echo '<td class="even">'.$time.'</td>';
		echo '<td class="odd" style="text-align:left; padding:15px">'.$ability["ability_effect"].'</td>';
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