<?php

	include_once("table.html");

	include_once(ROOT."/tables/kube_dolmen.php");
	include_once(ROOT."/tables/kube_dolmen_special.php");
	if(isset($_GET["special"]) && isset($_GET["max"]) && isset($_GET["origin"])) {
		$function = $_GET["special"];		
		if($function == "way" || $function == "radius") {
			drawKubeDolmenSpecialTable($function);
		} else {
			drawKubeDolmenTable();	
		}
	} else {
		drawKubeDolmenTable();
	}
	echo "<p></p>";

?>
