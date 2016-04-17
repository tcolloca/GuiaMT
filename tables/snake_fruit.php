<?php

include_once(ROOT."/functions/database_management.php");



function drawSnakeFruitTable() {
	
	$tags = array(
			array("alien", "alien", "alien"),
			array("leaf", "hoja", "hoja"),
			array("sweet", "dulce", "dulce"),
			array("mini", "mini", "mini"),
			array("large", "larga", "larga"),
			array("pumpkin", "calabaza", "calabaza"),
			array("citric", "citrico", "cítrico"),
			array("berry", "baya", "baya"),
			array("apple", "manzana", "manzana"),
			array("pear", "pera", "pera"),
			array("flower", "flor", "flor"),
			array("vine", "liana", "liana"),
			array("nut", "nuez", "nuez"),
			array("dung", "caca", "caca"),
			array("pink", "rosa", "rosa"),
			array("red", "roja", "roja"),
			array("orange", "naranja", "naranja"),
			array("blue", "azul", "azul"),
			array("green", "verde", "verde"),
			array("yellow", "amarillo", "amarillo")
	);
	
	$allTags = array();
	$dbTags = array();
	$fullTags = array();
					
	foreach($tags as $row) {		
		$dbTags[] = $row[0];
		$allTags[] = $row[1];
		$fullTags[] = $row[2];
	}
	
	getEditTableButton();
	echo '<br />';
	
	echo '<table id="snake-table">';
		echo '<tr>';
			echo '<th><b>Número</b><p></p>'.getSortArrows("id").'</th> 
				<th><b>Imagen</b></th> 
				<th><b>Nombre</b><p></p>'.getSortArrows("name").'</th>
				<th><b>Puntos</b><p></p>'.getSortArrows("points").'</th>
				<th><b>Vitaminas(mg)</b><p></p>'.getSortArrows("vitamins").'</th>
				<th><b>Nutrición(cal.)</b><p></p>'.getSortArrows("calories").'</th>
				<th><b>Conservación(seg.)</b><p></p>'.getSortArrows("time").'</th>
				<th width="200px"><b>Características</b>'.getTagBox().'</th>';
		echo '</tr>';
		
		$i = 0;
		
		if((isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "id" || $_GET['cat'] == "name" || $_GET['cat'] == "points" || $_GET['cat'] == "vitamins"  ||
		 $_GET['cat'] == "calories" || $_GET['cat'] == "time")) || isset($_GET['tags'])) {
			
			if(isset($_GET['tags'])) {
				$usersTags = deTag($_GET['tags']);
				$pcTags = array();
				
				foreach($usersTags as $i => $tag) {
					if(!in_array($tag, $allTags)) {
						unset($usersTags[$i]);
					} else {
						$pcTags[] = $dbTags[array_search($tag, $allTags)]; 	
					}
				}
				
				while($fruit = getTableRowByTags("snake", "fruit", $pcTags, $_GET["condition"])) {
					printSnakeFruitData($fruit, $i++, $dbTags, $fullTags);
				}	
			} else {
			
				$column = $_GET['cat'];
				$order = $_GET['order'];
				
				while($fruit = getTableRowSorted("snake", "fruit", $column, $order)) {
					printSnakeFruitData($fruit, $i++, $dbTags, $fullTags);
				}
			}
		} else {
			while($fruit = getTableRow("snake", "fruit")) {
				printSnakeFruitData($fruit, $i++, $dbTags, $fullTags);
			}	
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

function getTagBox() {
	return '<form class="snake-table-form" method="GET">
				<input type="text" name="tags"/><br />
				<button class="snake-table-btn" name="condition" value="some">Alguno</button>
				<button class="snake-table-btn" name="condition" value="all">Contienen</button>
				<button class="snake-table-btn" name="condition" value="exact">Exact.</button>
				</form>';
}

function deTag($stringTags){

	return multiexplode(array(","," ",";"), $stringTags);
}

function multiexplode ($delimiters, $string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  array_filter($launch, 'strlen');
}
			
function printSnakeFruitData($fruit, $i, $dbTags, $fullTags) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	if(isset($_SESSION['username']) && isEventOn("easter2014") && hasEvent("easter2014", $_SESSION['username'])) {
		if(hasObject("easter2014", $_SESSION['username'], "castellanos4")) {
			if($fruit["fruit_name"] == "Ravinia") {
				$fruit["fruit_img"] = '<form method="POST" action="/index.php?id=your_events&event=easter2014"><input type="hidden" name="validation" value="holdBackTheRiver2015" /><input type="image" src="/images/events/easter2014/negg_egg_small.png" alt="¡Hallar huevo!" /></form>';
				$fruit["fruit_name"] = 'Negg';
				$fruit["fruit_points"] = 100;
				$fruit["fruit_vitamins"] = 10;
				$fruit["fruit_calories"] = 10;
				$fruit["fruit_time"] = 5;
			}
		}
	}
	
	echo '<tr class="'.$class.'">';
	echo '<td class="odd">'.$fruit["fruit_id"].'</td>';
		echo '<td class="even">'.$fruit["fruit_img"].'</td>';
		echo '<td class="odd">'.$fruit["fruit_name"].'</td>';
		echo '<td class="even">'.$fruit["fruit_points"].'</td>';
		echo '<td class="odd">'.$fruit["fruit_vitamins"].'</td>';
		echo '<td class="even">'.$fruit["fruit_calories"].'</td>';
		echo '<td class="odd">'.$fruit["fruit_time"].'</td>';
		echo '<td class="even" width="150px">'.getFullTags($fruit["fruit_id"], $dbTags, $fullTags).'</td>';
	echo '</tr>';
}

function getFullTags($id, $dbTags, $fullTags) {
	$resTagList = getTags("fruit", $id);
	$fullTagList = array();	
				
	foreach($resTagList as $tag_name => $value) {
		
		if(strpos($tag_name, "_id") !== false) {
			continue;
		}
					
		if($value == 1) {
			$tag = str_replace("tag_", "", $tag_name);
			$fullTagList[] = $fullTags[array_search($tag, $dbTags)];
		}	
	}	
	
	
	
	$str = '';
	
	foreach($fullTagList as $tag) {
		$str = $str.$tag.", ";	
	}
	
	return substr($str, 0, -2);
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
			<input type='hidden' name='table' value='fruit' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}	
}

?>