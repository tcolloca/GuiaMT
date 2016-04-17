<?php

include_once(ROOT."/functions/database_management.php");

function drawKubeDolmenSpecialTable($function) {
	
	$max = intval($_GET["max"]);
		
	$origin = explode(";", $_GET["origin"]);
					
	$oX = 0;
	$oY = 0;
			
	if(count($origin) == 2) {
		$oX = intval($origin[0]);
		$oY = intval($origin[1]);
	}	
	
	$filter = (isset($_GET["filter"]) && ($_GET["filter"] == "on"))?true:false;
		
	if(!isset($_SESSION["username"])) {
		$filter = false;
	} else {
		$user = $_SESSION["username"];	
	}
	
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
		
		if($function == "way") {
			$dolmens = array();
			
			while($dolmen = getTableRow("kube", "dolmen")) {
				if(!$filter || !newHasTag("dolmen", "user", $dolmen["dolmen_id"], $user)) {
					$dolmens[] = $dolmen; 
				}
			}
			
			while(!empty($dolmens) && $i < $max) {
				
				$min = PHP_INT_MAX;
				$minDolmen;
				$minKey;
				
				foreach($dolmens as $key => $dolmen) {
					
					$x = $dolmen["dolmen_x"];
					$y = $dolmen["dolmen_y"];
					$distance = abs($x - $oX) + abs($y - $oY);
							
					if($distance < $min) {
						$min = $distance;
						$minDolmen = $dolmen;
						$minKey = $key;
					}
				}
				
				printKubeDolmenSpecialData($minDolmen, $i++, $oX, $oY);
				$oX = $minDolmen["dolmen_x"];
				$oY = $minDolmen["dolmen_y"];
				unset($dolmens[$minKey]);
			}
		} else {
			
			$function = "ABS(dolmen_x - ".$oX.") + ABS(dolmen_y - ".$oY.")";  
			$where = "ABS(dolmen_x - ".$oX.") + ABS(dolmen_y - ".$oY.") <= ".$max;
				
			while($dolmen = getTableRowSortedByFunctionWithFilter("kube", "dolmen", $where, $function, $order)) {
				if(printKubeDolmenData($dolmen, $i, $oX, $oY)) $i++;
			}	
		}

	echo "</table>";
}
			
function printKubeDolmenSpecialData($dolmen, $i, $oX, $oY) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	$x = $dolmen["dolmen_x"];
	$y = $dolmen["dolmen_y"];
	$distance = abs($oX - $x) + abs($oY - $y);
	
	$link = "";
	if($dolmen["dolmen_note"] == "") {
		$href = 'http://es.dolmenizer.wikia.com/wiki/'.$x.'_'.$y;
		$file_headers = @get_headers($href);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$link = "";
		}
		else {
			$link = '<p><a href="'.$href.'">Link a Dolmenizer</a></p>';
		};
	}
	
	echo '<tr class="'.$class.'">';
	echo '<td class="odd">'.$dolmen["dolmen_date"].'</td>';
		echo '<td class="even">'.$x.'</td>';
		echo '<td class="odd">'.$y.'</td>';
		echo '<td class="even">'.$distance.'</td>';
		echo '<td class="odd">'.$dolmen["dolmen_cuad"].'</td>';
		echo '<td class="even">'.$dolmen["dolmen_note"].$link.'</td>';
		echo '<td class="odd">'.$dolmen["dolmen_trust"].'</td>';
	echo '</tr>';
}

?>