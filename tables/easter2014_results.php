<?php

include_once(ROOT."/functions/database_management.php");

function drawEaster2014ResultsTable() {

	echo '<br />';

	echo '<table id="main-table" style="max-width:400px">';
		echo '<tr>';
			echo '<th class="odd"><b>Posición</b></th>
				<th class="even"><b>Usuario</b></th>
				<th class="odd"><b>Puntos</b></th>
				<th class="even"><b>Huevos hallados</b></th>';
		echo '</tr>';

		$i = 0;

        $firestoreClient = initFirestoreClient();
		$results = getGameTable($firestoreClient, "easter2014", "result");

		foreach($results as $result) {
			printUserData($result, $i++);
		}


	echo "</table>";
}

function printUserData($user, $i) {

	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}

	$position = ($i + 1)."º";

	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$position.'</td>';
		echo '<td class="even">'.$user["result_user_name"].'</td>';
		echo '<td class="odd">'.$user["result_points"].'</td>';
		echo '<td class="even">'.$user["result_eggs"].'</td>';
	echo '</tr>';
}

?>