<?php

include_once(ROOT."/functions/database_management.php");

function drawKubeIslandTable() {

	echo '<br />';

	echo getFilterBox();

	echo '<br />';

	echo getSpecialSearch();

	echo '<br />';

	echo '<table id="kube-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Fecha</b><p></p>'.getSortArrows("date").'</th>
				<th class="even"><b>x</b><p></p>'.getSortArrows("x").'</th>
				<th class="odd"><b>y</b><p></p>'.getSortArrows("y").'</th>
				<th class="even"><b>Tipo</b><p></p>'.getSortArrows("type").'</th>
				<th class="odd"><b>Filón</b><p></p>'.getSortArrows("filon").'</th>
				<th class="even"><b>Distancia</b>'.getDistanceBox().'</th>
				<th class="odd"><b>Cuad.</b><p></p>'.getSortArrows("cuad").'</th>
				<th class="even"><b>Notas</b></th>
				<th class="odd"><b>Confianza</b><p></p>'.getSortArrows("trust").'</th>';
		echo '</tr>';

		$i = 0;

	    $firestoreClient = initFirestoreClient();
		$islands = getGameTable($firestoreClient, "kube", "island");

		if(isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		((isset($_GET['cat']) && ($_GET['cat'] == "date" || $_GET['cat'] == "x" || $_GET['cat'] == "y"
		 || $_GET['cat'] == "cuad"  || $_GET['cat'] == "type" || $_GET['cat'] == "filon" || $_GET['cat'] == "trust"))
		  || isset($_GET['distance'])) ) {

			$order = $_GET['order'];

			if(isset($_GET['distance'])) {
				$origin = explode(";", $_GET['distance']);

				$oX = 0;
				$oY = 0;
				if(count($origin) == 2) {
					$oX = intval($origin[0]);
					$oY = intval($origin[1]);
				}

                // TODO(migration): This is not implemented :(
				// $function = "ABS(island_x - ".$oX.") + ABS(island_y - ".$oY.")";

				// while($island = getTableRowSortedByFunction("kube", "island", $function, $order)) {
				// 	if(printKubeIslandData($island, $i, $oX, $oY)) $i++;
				// }
			} else {

				$column = $_GET['cat'];

				$islands = sortTableBy($islands, "island_" . $column, $order);
			}
		}
		foreach($islands as $island) {
			printKubeIslandData($island, $i++, $oX, $oY);
		}

	echo "</table>";
}

function getLink($cat, $order) {
	return '?cat='.$cat.'&order='.$order.'&filter='.$_GET['filter'];
}

function getSortArrows($cat) {
	$upImg = '<img src="/images/kube/table/sortup.png" />';
	$downImg = '<img src="/images/kube/table/sortdown.png" />';

	return '<a href="'.getLink($cat, "desc").'">'.$downImg.'</a>
			<a href="'.getLink($cat, "asc").'">'.$upImg.'</a>';
}

function getDistanceBox() {
	$upImg = '<img src="/images/kube/table/sortup.png" />';
	$downImg = '<img src="/images/kube/table/sortdown.png" />';

	return '<form class="kube-table-form" method="GET">
				<input type="hidden" name="filter" value="'.$_GET['filter'].'"/>
				<input id="order_input" type="hidden" name="order">
				<input class="distance" type="text" name="distance" placeholder="x;y"><br />
				<input type="image"name="distDown" src="/images/kube/table/sortdown.png" alt="DESC" onclick="return setHidden(\'desc\');">
				<input type="image"name="distUp" src="/images/kube/table/sortup.png" alt="ASC" onclick="return setHidden(\'asc\');">
			</form>
			<script language="javascript">
				function setHidden(value) {
					var element = document.getElementById("order_input");
					element.value = value;
					return true;
				}
			</script>
			';
}

function printKubeIslandData($island, $i, $oX, $oY) {

	// $filter = (isset($_GET["filter"]) && ($_GET["filter"] == "on"))?true:false;

	// if(!isset($_SESSION["username"])) {
	// 	$filter = false;
	// } else {
	// 	$user = $_SESSION["username"];
	// }

	// if($filter) {
	// 	if(newHasTag("island", "user", $island["island_id"], $user)) {
	// 		return false;
	// 	}
	// }

	printKubeIslandSpecialData($island, $i, $oX, $oY);

	return true;
}

function getFilterBox() {
	$value = "on";
	if(isset($_GET['filter']) &&$_GET['filter'] == "on") {
		$value = "off";
	}

	echo '<form method="GET">
			Filtrar tocadas: <input type="submit" class="kube-table-btn" name="filter" value="'.$value.'" />
	</form>';
}

function getSpecialSearch() {
	$value = "off";
	if(isset($_GET['filter']) && $_GET['filter'] == "on") {
		$value = "on";
	}

	echo '<form method="GET" class="kube-table-form">
			<input type="hidden" name="filter" value="'.$value.'" />
			<input type="hidden" name="special" value="radius">
			Islas cercanas a: <input class="distance" type="text" name="origin" placeholder="x;y">
			Máx. distancia: <input class="distance" type="text" name="max">
			<input type="submit" class="kube-table-btn" value="Enviar">
	</form><br />';
	echo '<form method="GET" class="kube-table-form">
			<b>Camino "óptimo"</b> (Puede tardar)<br />
			<input type="hidden" name="filter" value="'.$value.'" />
			<input type="hidden" name="special" value="way">
			Comienzo: <input class="distance" type="text" name="origin" placeholder="x;y">
			Máx. islas: <input class="distance" type="text" name="max">
			<input type="submit" class="kube-table-btn" value="Enviar">
	</form>';
}

?>