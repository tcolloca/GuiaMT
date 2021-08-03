<?php

include_once(ROOT."/functions/database_management.php");

function drawMushProjectTable() {

	getEditTableButton();
	echo '<br />';

	echo '<table id="mush-table">';
		echo '<tr>';
			echo '<th class="odd"><b>Imagen</b></th>
				<th class="even"><b>Nombre</b><br />'.getSortArrows("name").'</th>
				<th class="odd"><b>Habilidades</b><br />'.getSortArrows("abilities").'</th>
				<th class="even"><b>Eficiencia</b><br />'.getSortArrows("minefficiency").'</th>
				<th class="odd"><b>Efecto</b></th>';
		echo '</tr>';

        $firestoreClient = initFirestoreClient();
		$projects = getGameTable($firestoreClient, "mush", "project");

		if(isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "name" || $_GET['cat'] == "abilities" || $_GET['cat'] == "minefficiency")) {
			$column = $_GET['cat'];
			$order = $_GET['order'];

            $projects = sortTableBy($projects, "project_" . $column, $order);
		}
		$i = 0;
		foreach($projects as $project) {
			printMushProjectData($project, $i++);
		}
	echo "</table>";
}

function getLink($cat, $order) {
	return '?cat='.$cat.'&order='.$order;
}

function getSortArrows($cat) {
	$upImg = '<img src="/images/mush/table/sortup.png" />';
	$downImg = '<img src="/images/mush/table/sortdown.png" />';

	return '<a href="'.getLink($cat, "desc").'">'.$downImg.'</a>
			<a href="'.getLink($cat, "asc").'">'.$upImg.'</a>';
}

function printMushProjectData($project, $i) {

	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}

	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$project["project_img"].'</td>';
		echo '<td class="even">'.$project["project_name"].'</td>';
		echo '<td class="odd">'.$project["project_abilities"].'</td>';
		echo '<td class="even">'.$project["project_efficiency"].'</td>';
		echo '<td class="odd">'.$project["project_effect"].'</td>';
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
			<input type='hidden' name='table' value='project' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}
}

?>