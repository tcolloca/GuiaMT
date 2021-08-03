<?php

include_once(ROOT."/functions/database_management.php");

function drawSnakeCardTable() {

	getEditTableButton();
	echo '<br />';

	echo '<table id="snake-table">';
		echo '<tr>';
			echo '<th><b>Img peq.</b></th>
				<th><b>Imagen</b></th>
				<th><b>Nombre</b><p></p>'.getSortArrows("name").'</th>
				<th><b>Rareza</b><p></p>'.getSortArrows("rarity").'</th>
				<th><b>Karma</b><p></p>'.getSortArrows("karma").'</th>
				<th><b>Tiempo de recarga(seg)</b><p></p>'.getSortArrows("time").'</th>
				<th><b>Acción</b></th>
				<th><b>Explicación</b></th>
				<th><b>Máx. precio</b></b><p></p>'.getSortArrows("maxprice").'</th>';
		echo '</tr>';

		$i = 0;

		$firestoreClient = initFirestoreClient();
		$cards = getGameTable($firestoreClient, "snake", "card");

		if(isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "name" || $_GET['cat'] == "rarity" || $_GET['cat'] == "karma" || $_GET['cat'] == "time" ||
		$_GET['cat'] == "maxprice")) {
			$column = $_GET['cat'];
			$order = $_GET['order'];

			$cards = sortTableBy($cards, "card_" . $column, $order);
		}
		foreach($cards as $card) {
			printSnakeCardData($card, $i);
		}

	echo "</table>";
}

function getLink($cat, $order) {
	return '?cat='.$cat.'&order='.$order;
}

function getSortArrows($cat) {
	$upImg = '<img src="/images/snake/table/sortup.png" />';
	$downImg = '<img src="/images/snake/table/sortdown.png" />';

	return '<a href="'.getLink($cat, "desc").'">'.$downImg.'</a>
			<a href="'.getLink($cat, "asc").'">'.$upImg.'</a>';
}

function printSnakeCardData($card, $i) {

	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}

	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$card["card_small_img"].'</td>';
		echo '<td class="even">'.$card["card_img"].'</td>';
		echo '<td class="odd">'.$card["card_name"].'</td>';
		echo '<td class="even">'.$card["card_rarity"].'</td>';
		echo '<td class="odd">'.$card["card_karma"].'</td>';
		echo '<td class="even">'.($card["card_time"]?:"-").'</td>';
		echo '<td class="odd" style="text-align:left;">'.$card["card_action"].'</td>';
		echo '<td class="even" style="text-align:left;">'.$card["card_description"].'</td>';
		echo '<td class="odd">'.($card["card_maxprice"]?:"-").'</td>';
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
			<input type='hidden' name='table' value='card' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}
}

?>