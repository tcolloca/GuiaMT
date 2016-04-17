<?php

	include_once("table.html");
	include_once(ROOT."/tables/kube_island.php");
	include_once(ROOT."/tables/kube_island_special.php");
	if(isset($_GET["special"]) && isset($_GET["max"]) && isset($_GET["origin"])) {
		$function = $_GET["special"];		
		if($function == "way" || $function == "radius") {
			drawKubeIslandSpecialTable($function);
		} else {
			drawKubeIslandTable();	
		}
	} else {
		drawKubeIslandTable();
	}
	echo "<p></p>";

?>
