<?php

include_once(ROOT."/functions/database_management.php");

function drawTeacherStoryObjectTable() {
	
	getEditTableButton();
	echo '<br />';
	
	echo '<table id="teacher-story-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Imagen</b></th> 
				<th class="even"><b>Nombre</b><p></p>'.getSortArrows("name").'</th>
				<th class="odd"><b>Descripci&oacuten</b></th>
				<th class="even"><b>Rareza</b><p></p>'.getSortArrows("rarity").'</th>
				<th class="odd"><b>Puntos Twinoid</b></th>
				<th class="even"><b>Familia</b></th>';
		echo '</tr>';
		
		$i = 0;
		if(isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "name" || $_GET['cat'] == "rarity")) {
			$column = $_GET['cat'];
			$order = $_GET['order'];
			
			while($object = getTableRowSorted("teacher_story", "object", $column, $order)) {
				printTSObjectData($object, $i++);
			}
		} else {
			while($object = getTableRow("teacher_story", "object")) {
				printTSObjectData($object, $i++);
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
			
function printTSObjectData($object, $i) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$object["object_img"].'</td>';
		echo '<td class="even">'.$object["object_name"].'</td>';
		echo '<td class="odd">'.$object["object_description"].'</td>';
		echo '<td class="even">'.$object["object_rarity"].'</td>';	
		echo '<td class="odd">'.$object["object_points"].'</td>';
		echo '<td class="even">'.$object["object_family"].'</td>';
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
			<input type='hidden' name='table' value='object' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}	
}

?>