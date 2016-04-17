<?php

include_once(ROOT."/functions/database_management.php");

function drawKubeIslandSpecialTable($function) {
	
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
				<th class="even"><b>Tipo</b><p></p>'.getSortArrows("type").'</th>
				<th class="odd"><b>Filón</b><p></p>'.getSortArrows("filon").'</th>
				<th class="even"><b>Distancia</b>'.getDistanceBox().'</th>
				<th class="odd"><b>Cuad.</b><p></p>'.getSortArrows("cuad").'</th>
				<th class="even"><b>Notas</b></th>
				<th class="odd"><b>Confianza</b><p></p>'.getSortArrows("trust").'</th>';
		echo '</tr>';
		
		$i = 0;
		
		if($function == "way") {
			$islands = array();
			
			while($island = getTableRow("kube", "island")) {
				if(!$filter || !newHasTag("island", "user", $island["island_id"], $user)) {
					$islands[] = $island; 
				}
			}
			
			while(!empty($islands) && $i < $max) {
				
				$min = PHP_INT_MAX;
				$minIsland;
				$minKey;
				
				foreach($islands as $key => $island) {
					
					$x = $island["island_x"];
					$y = $island["island_y"];
					$distance = abs($x - $oX) + abs($y - $oY);
							
					if($distance < $min) {
						$min = $distance;
						$minIsland = $island;
						$minKey = $key;
					}
				}
				
				printKubeIslandSpecialData($minIsland, $i++, $oX, $oY);
				$oX = $minIsland["island_x"];
				$oY = $minIsland["island_y"];
				unset($islands[$minKey]);
			}
		} else {
			
			$function = "ABS(island_x - ".$oX.") + ABS(island_y - ".$oY.")";  
			$where = "ABS(island_x - ".$oX.") + ABS(island_y - ".$oY.") <= ".$max;
				
			while($island = getTableRowSortedByFunctionWithFilter("kube", "island", $where, $function, $order)) {
				if(printKubeIslandData($island, $i, $oX, $oY)) $i++;
			}	
		}

	echo "</table>";
}
			
function printKubeIslandSpecialData($island, $i, $oX, $oY) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	$x = $island["island_x"];
	$y = $island["island_y"];
	$distance = abs($oX - $x) + abs($oY - $y);
	$filon = $island["island_filon"] == 1?"Sí":"No";
	
	//$href = 'http://es.islandizer.wikia.com/wiki/'.$x.'_'.$y;
	//$file_headers = @get_headers($href);
	/*if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
		$link = "";
	}
	else {
		$link = '<p><a href="'.$href.'">Link a Islandizer</a></p>';
	};*/
	
	echo '<tr class="'.$class.'">';
	echo '<td class="odd">'.$island["island_date"].'</td>';
		echo '<td class="even">'.$x.'</td>';
		echo '<td class="odd">'.$y.'</td>';
		echo '<td class="even">'.$island["island_type"].'</td>';
		echo '<td class="odd">'.$filon.'</td>';
		echo '<td class="even">'.$distance.'</td>';
		echo '<td class="odd">'.$island["island_cuad"].'</td>';
		echo '<td class="even">'.$island["island_note"].'</td>';
		echo '<td class="odd">'.$island["island_trust"].'</td>';
	echo '</tr>';
}

?>