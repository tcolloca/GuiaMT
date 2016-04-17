<?php

include_once(ROOT."/functions/database_management.php");

function drawDistinctionTable() {
	
	getEditTableButton();
	echo '<br />';
	
	echo '<table id="main-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Juego</b><p></p>'.getSortArrows("game").'</th> 
				<th class="even"><b>Tipo</b><p></p>'.getSortArrows("type").'</th>
				<th class="odd"><b>Distinción</b></th>
				<th class="even"><b>Nombre</b><p></p>'.getSortArrows("name").'</th>
				<th class="odd"><b>Puntos</b><p></p>'.getSortArrows("points").'</th>
				<th class="even"><b>Porcentaje</b><p></p>'.getSortArrows("percentage").'</th>
				<th class="odd"><b>Cómo conseguirla</b><p></p></th>';
		echo '</tr>';
		
		$i = 0;
		$filter = (isset($_GET["filter"]) && $_GET["filter"] != "")?"distinction_game = '".$_GET['filter']."'":"";
		
		if(isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "points" || $_GET['cat'] == "percentage" || $_GET['cat'] == "type" ||
		 $_GET['cat'] == "game" || $_GET['cat'] == "name")) {
			$column = $_GET['cat'];
			if($column == "percentage") {
				$column = "points";
			}
			$order = $_GET['order'];
			
			while($dist = getTableRowSortedWithFilter("twinoid", "distinction", $filter, $column, $order)) {
				printDistinctionData($dist, $i++);
			}
		} else {
			while($dist = getTableRowSortedWithFilter("twinoid", "distinction", $filter, "id", "asc")) {
				printDistinctionData($dist, $i++);
			}	
		}

	echo "</table>";
}

function getLink($cat, $order) {
	return '?cat='.$cat.'&order='.$order.'&filter='.$_GET['filter'];
}

function getSortArrows($cat) {
	$upImg = '<img src="/images/main/table/sortup.png" />';
	$downImg = '<img src="/images/main/table/sortdown.png" />';
	
	return '<a href="'.getLink($cat, "desc").'">'.$downImg.'</a>
			<a href="'.getLink($cat, "asc").'">'.$upImg.'</a>';
}
			
function printDistinctionData($dist, $i) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	$percent = $dist["distinction_points"]/10;
	
	include_once(ROOT."/functions/database_management.php");
	
	if(isset($_SESSION['username']) && isEventOn("easter2014") && hasEvent("easter2014", $_SESSION['username'])) {
		if($dist["distinction_name"] == "N utilizadas x100") {
			$dist["distinction_distinction"] = '<a href="http://guiamt.net/letraespacion">'.$dist["distinction_distinction"].'</a>';
		}
	}
	
	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$dist["distinction_game"].'</td>';
		echo '<td class="even">'.$dist["distinction_type"].'</td>';
		echo '<td class="odd">'.$dist["distinction_distinction"].'</td>';	
		echo '<td class="even">'.$dist["distinction_name"].'</td>';
		echo '<td class="odd">'.$dist["distinction_points"].'</td>';
		echo '<td class="even">'.$percent.'%</td>';
		echo '<td class="odd" style="text-align:left;">'.$dist["distinction_description"].'</td>';
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
            <input type='hidden' name='section' value='twinoid' />                 
            <input type='hidden' name='action' value='table' />
			<input type='hidden' name='table' value='distinction' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}	
}

?>