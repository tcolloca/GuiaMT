<?php

include_once(ROOT."/functions/database_management.php");

function drawHammerfestObjectTable() {

	getEditTableButton();
	echo '<br />';

	echo '<table id="hammerfest-table">';
		echo '<tr>';
			echo '<th><b>Img.</b></th>
				<th><b>Nombre</b><p></p>'.getSortArrows("name").'</th>
				<th><b>Coef.</b><p></p>'.getSortArrows("coef").'</th>
				<th><b>Puntos</b><p></p>'.getSortArrows("points").'</th>
				<th><b>Efecto</b><p></p>'.getSortArrows("effect").'</th>
				<th><b>Familia</b><p></p>'.getSortArrows("family").'</th>
				<th><b>Exploraciones</b></th>';
		echo '</tr>';

		$firestoreClient = initFirestoreClient();
		$objects = getGameTable($firestoreClient, "hammerfest", "object");

		if(isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "name" || $_GET['cat'] == "coef" || $_GET['cat'] == "points" || $_GET['cat'] == "effect" ||
		$_GET['cat'] == "family")) {
			$column = $_GET['cat'];
			$order = $_GET['order'];

            $objects = sortTableBy($objects, "object_" . $column, $order);
		}
		foreach($objects as $object) {
			printHammerfestObjectData($object);
		}

	echo "</table>";
}

function getLink($cat, $order) {
	return '?cat='.$cat.'&order='.$order;
}

function getSortArrows($cat) {
	$upImg = '<img src="/images/hammerfest/table/sortup.png" />';
	$downImg = '<img src="/images/hammerfest/table/sortdown.png" />';

	return '<a href="'.getLink($cat, "desc").'">'.$downImg.'</a>
			<a href="'.getLink($cat, "asc").'">'.$upImg.'</a>';
}

function printHammerfestObjectData($object) {

	switch($object["object_id"]) {
		case 59:
			$object["object_points"] = '0 ó 5.000';
			break;
		case 118:
			$object["object_points"] = 'Azules: 500 Verdes: 2.000     Rojos: 4.500 Amarillos: 8.000 Morados: 12.500 Anaranjados: 18.000          Negros: 24.500   Los cristales negros pueden dar más de 24.500, ya sea por el efeto de "Sombrero de Mago Gris" o por jugar en modo ninjutsu, pueden valer 32.000, 40.500 y 50.000 puntos.';
			break;
		case 122:
			$object["object_points"] = 'Azules: 75 Naranjas: 1.200    Verdes: 6.075 Moradas: 10.000';
			break;
	}

	if(isset($_SESSION['username']) && isEventOn("easter2014") && hasEvent("easter2014", $_SESSION['username'])) {
		if(hasObject("easter2014", $_SESSION['username'], "vexhatesyou")) {
			if($object["object_name"] == "Gafas volteadoras azules") {
				$object["object_img"] = '<form method="POST" action="/index.php?id=your_events&event=easter2014"><input type="hidden" name="validation" value="Ammdkei8j3aaKmne" /><input type="image" src="/images/events/easter2014/inverted_egg_small.png" alt="¡Hallar huevo!" /></form>';
			}
		}
	}

	echo '<tr>';
		echo '<td>'.$object["object_img"].'</td>';
		echo '<td>'.$object["object_name"].'</td>';
		echo '<td>'.$object["object_coef"].'</td>';
		echo '<td>'.$object["object_points"].'</td>';
		echo '<td>'.$object["object_effect"].'</td>';
		echo '<td>'.$object["object_family"].'</td>';
		echo '<td>'.$object["object_quests"].'</td>';
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