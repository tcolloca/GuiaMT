<?php

include_once(ROOT."/functions/database_management.php");

function drawKubeKubeTable() {

	getEditTableButton();
	echo '<br />';

	echo '<table id="kube-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Id</b></th>
				<th class="even"><b>Imagen</b></th>
				<th class="odd"><b>Nombre</b></th>
				<th class="even"><b>Isla</b></th>
				<th class="odd"><b>Efecto</b></th>';
		echo '</tr>';

		$i = 0;

		$firestoreClient = initFirestoreClient();
		$kubes = getGameTable($firestoreClient, "kube", "kube");

        foreach($kubes as $kube) {
			printKubeKubeData($kube, $i++);
		}
	echo "</table>";
}

function printKubeKubeData($kube, $i) {

	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}

	if(isset($_SESSION['username']) && isEventOn("easter2014") && hasEvent("easter2014", $_SESSION['username'])) {
		if(hasObject("easter2014", $_SESSION['username'], "zains")) {
			if($kube["kube_id"] == 75) {
				$kube["kube_img"] = '<form method="POST" action="/index.php?id=your_events&event=easter2014"><input type="hidden" name="validation" value="zainsEsSuperCool19202" /><input type="image" src="/images/events/easter2014/mt_egg_small.png" alt="¡Hallar huevo!" /></form>';
			}
		}
	}

	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$kube["kube_id"].'</td>';
		echo '<td class="even">'.$kube["kube_img"].'</td>';
		echo '<td class="odd">'.$kube["kube_name"].'</td>';
		echo '<td class="even">'.$kube["kube_island"].'</td>';
		echo '<td class="odd">'.$kube["kube_effect"].'</td>';
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
			<input type='hidden' name='table' value='kube' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}
}

?>