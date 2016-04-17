<?php

include_once(ROOT."/functions/database_management.php");



function drawMushResearchTable() {
	
	$tags = array(
			array("root", "raiz", "<b>Muestra de raíz Mush</b>"),
			array("disk", "diskette", "<b>Diskette del Genoma Mush</b>"),
			array("mush", "mush", "mush muerto"),
			array("chun", "chun", "presencia de <b>Chun</b>"),
			array("chun", "gato", "<b>Schrödinger</b>"),
			array("food", "comestible", "comestible"),
			array("medikit", "medikit", "<b>Medikit</b>"),
			array("soap", "jabon", "<b>Jabón</b>"),
			array("blaster", "blaster", "<b>Blaster</b>"),
			array("map", "mapa", "<b>Trozo de mapa estelar</b>"),
			array("rod", "barra", "<b>Barrilla acuosa</b>")
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
	
	echo '<table id="mush-table">';
		echo '<tr>';
			echo '<th><b>Imagen</b></th> 
				<th><b>Nombre</b><p></p>'.getSortArrows("name").'</th>
				<th><b>Eficiencia</b><p></p>'.getSortArrows("minefficiency").'</th>
				<th><b>Gloria</b><p></p>'.getSortArrows("glory").'</th>
				<th><b>Efecto</b><p></p></th>
				<th><b>Requisitos</b>'.getTagBox().'</th>';
		echo '</tr>';
		
		$i = 0;
		
		if((isset($_GET['cat']) && isset($_GET['order']) && ($_GET['order'] == "asc" || $_GET['order'] == "desc") &&
		($_GET['cat'] == "name" || $_GET['cat'] == "minefficiency" || $_GET['cat'] == "glory")) || isset($_GET['tags'])) {
			
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
				
				while($research = getTableRowByTags("mush", "research", $pcTags, $_GET["condition"])) {
					printMushResearchData($research, $i++, $dbTags, $fullTags);
				}	
			} else {
			
				$column = $_GET['cat'];
				$order = $_GET['order'];
				
				while($research = getTableRowSorted("mush", "research", $column, $order)) {
					printMushResearchData($research, $i++, $dbTags, $fullTags);
				}
			}
		} else {
			while($research = getTableRow("mush", "research")) {
				printMushResearchData($research, $i++, $dbTags, $fullTags);
			}	
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

function getTagBox() {
	return '<form class="mush-table-form" method="GET">
				<input type="text" name="tags"/><br />
				<button class="mush-table-btn" name="condition" value="iall">Disponibles</button>
				<button class="mush-table-btn" name="condition" value="all">Requieran</button>
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
			
function printMushResearchData($research, $i, $dbTags, $fullTags) {
	
	if($i % 2 == 0) {
		$class = "even";
	} else {
		$class = "odd";
	}
	
	echo '<tr class="'.$class.'">';
		echo '<td class="odd">'.$research["research_img"].'</td>';
		echo '<td class="even">'.$research["research_name"].'</td>';
		echo '<td class="odd" width="150px">'.$research["research_efficiency"].'</td>';
		echo '<td class="even">'.$research["research_glory"].'</td>';
		echo '<td class="odd" style="text-align:left">'.$research["research_effect"].'</td>';
		echo '<td class="even" width="150px">'.getFullTags($research["research_id"], $dbTags, $fullTags).'</td>';
	echo '</tr>';
}

function getFullTags($id, $dbTags, $fullTags) {
	$resTagList = getTags("research", $id);
	$fullTagList = array();	
				
	foreach($resTagList as $tag_name => $value) {
		
		if(strpos($tag, "_id") !== false) {
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
			<input type='hidden' name='table' value='research' />
            <input type='hidden' name='url' value='".$url."' />
            <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar tabla' /></form>";
	}	
}

?>