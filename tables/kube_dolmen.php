<?php

include_once(ROOT."/functions/database_management.php");

function drawKubeDolmenTable() {

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
				<th class="even"><b>Distancia</b>'.getDistanceBox().'</th>
				<th class="odd"><b>Cuadrante</b><p></p>'.getSortArrows("cuad").'</th>
				<th class="even"><b>Notas</b></th>
				<th class="odd"><b>Confianza</b><p></p>'.getSortArrows("trust").'</th>';
		echo '</tr>';

		$i = 0;

		$firestoreClient = initFirestoreClient();
		$dolmens = getGameTable($firestoreClient, "kube", "dolmen");

		if(isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		((isset($_GET['cat']) && ($_GET['cat'] == "date" || $_GET['cat'] == "x" || $_GET['cat'] == "y"
		 || $_GET['cat'] == "cuad"  || $_GET['cat'] == "trust")) || isset($_GET['distance'])) ) {

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
				// $function = "ABS(dolmen_x - ".$oX.") + ABS(dolmen_y - ".$oY.")";

				// while($dolmen = getTableRowSortedByFunction("kube", "dolmen", $function, $order)) {
				// 	if(printKubeDolmenData($dolmen, $i, $oX, $oY)) $i++;
				// }
			} else {

				$column = $_GET['cat'];

                $dolmens = sortTableBy($dolmens, "dolmen_" . $column, $order);
			}
		}
		foreach($dolmens as $dolmen) {
			printKubeDolmenData($dolmen, $i++, $oX, $oY);
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
				<div id="radios">
					<input id="order_input" type="hidden" name="order">
					<input id="distance" class="distance" type="text" name="distance" placeholder="x;y">
					<input type="radio" name="distance" value="0;0">Origen ([0][0])
					<input id="paraiso" type="radio" name="distance" value="16;1">Paraíso Perdido <br />
					<input type="radio" name="distance" value="6;8">Kubeópolis
					<input id="warponia" type="radio" name="distance" value="75;-81">Nueva Warponia
					<input type="radio" name="distance" value="-8;20">Isla misteriosa <br />
					<input id="tierra-firme" type="radio" name="distance" value="21;114">Tierra Firme
					<input id="arkubepielago" type="radio" name="distance" value="101;29">Arkubepiélago <br />
				</div>
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

function printKubeDolmenData($dolmen, $i, $oX, $oY) {

	// $filter = (isset($_GET["filter"]) && ($_GET["filter"] == "on"))?true:false;

	// if(!isset($_SESSION["username"])) {
	// 	$filter = false;
	// } else {
	// 	$user = $_SESSION["username"];
	// }

	// if($filter) {
	// 	if(newHasTag("dolmen", "user", $dolmen["dolmen_id"], $user)) {
	// 		return false;
	// 	}
	// }

	printKubeDolmenSpecialData($dolmen, $i, $oX, $oY);

	return true;
}

function getFilterBox() {
	$value = "on";
	if(isset($_GET['filter']) &&$_GET['filter'] == "on") {
		$value = "off";
	}

	echo '<form method="GET">
			Filtrar tocados: <input type="submit" class="kube-table-btn" name="filter" value="'.$value.'" />
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
			Dolmenes cercanos a: <input class="distance" type="text" name="origin" placeholder="x;y">
			Máx. distancia: <input class="distance" type="text" name="max">
			<input type="submit" class="kube-table-btn" value="Enviar">
	</form><br />';
	echo '<form method="GET" class="kube-table-form">
			<b>Camino "óptimo"</b> (Puede tardar)<br />
			<input type="hidden" name="filter" value="'.$value.'" />
			<input type="hidden" name="special" value="way">
			Comienzo: <input class="distance" type="text" name="origin" placeholder="x;y">
			Máx. dolmenes: <input class="distance" type="text" name="max">
			<input type="submit" class="kube-table-btn" value="Enviar">
	</form>';
}

?>