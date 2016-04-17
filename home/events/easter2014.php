<?php
	$username = $_SESSION['username'];
	
	if(isset($_POST["validation"])) {
		switch($_POST["validation"]) {
			case "KEN49Sa3jsM":
				useObject("easter2014", $username, "tom");
				break;
			case "Ammdkei8j3aaKmne":
				useObject("easter2014", $username, "vexhatesyou");
				break;
			case "10sm6zp2mrytuisnv84ll":
				useObject("easter2014", $username, "tom_jefe");
				break;
			case "zainsEsSuperCool19202":
				useObject("easter2014", $username, "zains");
				break;
			case "dragonsLair142":
				useObject("easter2014", $username, "dragon");
				break;
			case "mksajd92":
				useObject("easter2014", $username, "somnium");
				break;
			case "holdBackTheRiver2015":
				useObject("easter2014", $username, "castellanos4");
				break;
			case "0111020101":
				useObject("easter2014", $username, "mechatom");
				break;
			case "theLastEgg":
				useObject("easter2014", $username, "otipkrogi");
				break;
		}
	}
	
	if(isset($_POST["activate"])) {
		while($column = getTableColumn("easter2014", "objects")) {
			$column = $column['COLUMN_NAME'];
			if($column != "user_id") {
				$staffer = str_replace("object_", "", $column);
				if(!usedObject("easter2014", $username, $staffer)) {
					releaseObject("easter2014", $username, $staffer);
				}	
			}
		}
		getObject("easter2014", $username, $_POST["clue"]);
	}
?>


<p><subtitle>Búsqueda de Huevos 2015</subtitle></p>

<center><p>
<strong><a href="/events/easter2014"><font color="#233243">Página principal</font></a></strong> |
Tus avances</strong>
</p></center>

<p><em>Atención: Recuerda leer las reglas, o podrías quedar descalificado por hacer algo que no está permitido sin saberlo.</em></p>

<p><minititle>Los acertijos</minititle></p>

<?php

	echo "<p><table cellpadding='10px'>";
	echo "<tr>
			<th bgcolor='#3276AD'><strong>Acertijo</strong></th>
			<th bgcolor='#3276AD'><strong>Estado</strong></th>
			<th bgcolor='#3276AD'><strong>Huevo</strong></th>
		  </tr>";
	while($column = getTableColumn("easter2014", "objects")) {
		$column = $column['COLUMN_NAME'];
		if($column != "user_id" && $column != "object_tom_jefe") {
			$staffer = str_replace("object_", "", $column);
			if($staffer == strtolower($username)) {
				$staffer = "tom_jefe";
			}
			if(isAvailable("easter2014", $username, $staffer)) {
				$status = '<font color="#DC2910">Desactivado</font>';
				$action = '<center><form method="POST" action="?id=your_events&event='.$_GET['event'].'">
								<input type="hidden" name="clue" value="'.$staffer.'" />
								<input type="submit" name="activate" value="Activar" />
						   </form></center>';
			} else if(hasObject("easter2014", $username, $staffer)) {
				$status = '<font color="#ffdd22">Activado</font>';
				$action = 'No hay ninguna acción disponible.';
			} else {
				$egg = getEgg("easter2014", $staffer);
				$status = '<font color="#00AA00">¡Finalizado!</font>';
				$action = '<img src="/images/events/easter2014/'.$egg["egg_image"].'" style="float:left;margin-right:30px"/>
				<strong>'.$egg["egg_name"].'</strong>
				 <p><em>'.$egg["egg_description"].'</em></p>';
			}
			$clue = '<img src="/images/events/easter2014/'.$staffer.'_clue.png" />';
			
			echo '<tr>
				<td valign="middle" align="center" bgcolor="#75AFF0">'.$clue.'</td>
				<td valign="middle" align="center"bgcolor="#95CAEA">'.$status.'</td>
				<td valign="middle" bgcolor="#75AFF0">'.$action.'</td>
			</tr>';
		}
	}
	echo "</table></p>";
	
?>
