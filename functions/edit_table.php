<?php

include_once( ROOT."/functions/database_management.php" );

	list($page) = sscanf( $_POST['url'], "http://".$_SERVER['SERVER_NAME']."/%s");
	$page = str_replace("index.php", "", $page);
	$game = $_POST['section'];
	$table = $_POST['table'];

	if(isset( $_POST['modify'] ) && isset( $_POST['validation'] )) {
			
		$colsType = array();

		while($col = getTableColumn($game, $table)) {
			if(strpos($col['COLUMN_NAME'], "_id") === false) {
				$colsType[$col['COLUMN_NAME']] = ($col['DATA_TYPE'] == "int")?"i":"s";
			}
		}	
			
		if($_POST['modify'] == "TODOS") {
			
			$idVars = array_filter(array_keys($_POST), "isId");
			
			foreach($idVars as $idVar) {
				$aux = explode("-", $idVar);
				$id = $aux[0];
				$_POST['modify'] = $id; //for array filter
				$fields = array_filter(array_keys($_POST), "isValidTextBox");
				
				foreach($fields as $field) {
					$column = str_replace($id."-", "", $field);
					$newVal = $_POST[$field];
					
					if(strpos($column, "_id") === false) {	
						updateTableField($game, $table, $id, $column, $newVal, $colsType[$column]);
					}
				}
			}
		} else if($_POST['modify'] == "new") {
			$id = $_POST['modify'];
			$fields = array_filter(array_keys($_POST), "isNew");
			
			$row = array();
			
			foreach($fields as $field) {
				$column = str_replace($id."-", "", $field);
				
				if(strpos($column, "_id") === false) {
					$value = $_POST[$field];
					$row[$column] = $value; 
				}
			}
			
			addTableRow($game, $table, $row, implode("", $colsType));
		} else {	
			$id = $_POST['modify'];
			$fields = array_filter(array_keys($_POST), "isValidTextBox");
			
			foreach($fields as $field) {
				$column = str_replace($id."-", "", $field);
				$newVal = $_POST[$field];
				
				if(strpos($column, "_id") === false) {
					updateTableField($game, $table, $id, $column, $newVal, $colsType[$column]);
				}
				
			}
		}
		
		echo "<center><font color='#009900'><b>&iexcl;La modificaci&oacute;n se realiz&oacute; con &eacute;xito!</b></font><br />
		<strong><a href=\"".$_POST['url']."\">CLIQUEA AQU&Iacute;</a></strong> para volver al sitio.</center>";
	
	} else if( !isset($_POST['modify']) )	{
		
		$row = getTableRow($game, $table);
			
			echo '<form method="post" action="/modify/index.php">';
			echo '<input type="hidden" name="validation" value="ok" \>';
			echo '<input type="hidden" name="action" value="'.$_POST['action'].'" \>';
			echo '<input type="hidden" name="table" value="'.$_POST['table'].'" \>';
			echo '<input type="hidden" name="section" value="'.$_POST['section'].'" \>';
			echo '<input type="hidden" name="url" value="'.$_POST['url'].'" \>';
			echo '<p><input type="submit" name="modify" value="TODOS" /></p>';
			echo '<table id="'.$game.'-table">';
		
			$cols = array();
			$maxCols = array();
			$maxRows = array();
		
			while($col = getTableColumn($game, $table)) {
				$cols[] = $col['COLUMN_NAME'];
				$maxCols[$col['COLUMN_NAME']] = 3;
				$maxRows[$col['COLUMN_NAME']] = 1;
			}
			
			echo '<tr>';
					
				foreach($cols as $colName) {
					echo '<th><b>'.str_replace($_POST["table"]."_", "", $colName).'</b></th>';
				}
					echo '<th><b>B</b></th>';
				echo '</tr>';
		
			if($row) {
		
			do {		
				echo '<tr>';
				
				foreach($row as $key => $value) {
					
					$str = "".$value;
					
					if(strlen($str) < 10) {
						$width = strlen($str) + 1;
						$height = 1;
					} else if(strlen($str) < 50) {
						$width = 10;
						$height = 4;
					} else {
						$width = 25;
						$height = (int)strlen($str)/25;
					}
					
					$maxCols[$key] = max($maxCols[$key], $width);
					$maxRows[$key] = max($maxRows[$key], $width);
					
					echo '<td>';
						echo '<textarea name="'.$row[$table."_id"]."-".$key.'" onkeyup="textAreaAdjust(this)" 
							   style="overflow:hidden" cols="'.$maxCols[$key].'" rows="'.$height.'">';
							echo $value;
						echo '</textarea>';
					echo '</td>';
					
				}
					echo '<td>';
						echo '<p><input type="submit" name="modify" value="'.$row[$table.'_id'].'" /></p>';
					echo '</td>';
				
				echo '</tr>';
			} while($row = getTableRow($game, $table));	
			}
				echo '<tr>';			
				foreach($cols as $colName) {
					echo '<td>';
						echo '<textarea name="new-'.$colName.'" onkeyup="textAreaAdjust(this)" style="overflow:hidden"
								cols="'.$maxCols[$colName].'" rows="'.$maxRows[$colName].'"></textarea>';
					echo '</td>';
				}
					echo '<td>';
						echo '<p><input type="submit" name="modify" value="new" /></p>';
					echo '</td>';
				echo '</tr>';
			
			echo '</table>';
			echo '</form>';
			
		echo '<p>Modificaci&oacute;n: <input type="text" name="modifications" value="" \></p>';
		
		
		echo '<script>
		function textAreaAdjust(o) {
			o.style.height = "1px";
			o.style.height = (25+o.scrollHeight)+"px";
		}
		</script>';
	} else {
		$subject = "Intento de modificaci&oacute;n de p&aacute;gina fallido.";
		$content = "";
		
		if( !isset($_POST['newfile']) || $_POST['newfile'] == "" ) 
			$content = $content."No quiso poner contenido a la p&aacute;gina. <br />";
		
		if( !isset($_POST['modifications']) || $_POST['modifications'] == "" )	
			$content = $content."No quiso poner que cambios hizo. <br />";
				
		$content = $content."Usuario: ".$_SESSION['username'];	
		$to = MAIL_MAIL;
		$toName = 'Tom';
		$from = $to;
		$fromName = $toName;
		$reply = $to;
		$replyName = $toName;
		sendMail($to, $toName, $subject, $content, $from, $fromName, $reply, $replyName);
	}

function isValidTextBox($o) {
	$needle = $_POST['modify']."-".$_POST['table']."_";
	return $needle === "" || strrpos($o, $needle, -strlen($o)) !== false;
}

function isId($o) {
	$needle = "-".$_POST['table']."_id";
	return strrpos($o, $needle) !== false && !isNew($o);
}

function isNew($o) {
	$needle = "new-".$_POST['table']."_";
	return $needle === "" || strrpos($o, $needle, -strlen($o)) !== false;
}

?>
 