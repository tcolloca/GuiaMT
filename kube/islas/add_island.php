<?php

	include_once("add_update_island.html");
	include_once(ROOT."/functions/database_management.php");

	if(isset($_POST["send"])) {
		
		if(isset($_SESSION["username"])) {
			$user = $_SESSION["username"];
			if(isStaff($user)) {
				$trust = 3;
			} else {
				$trust = 1;	
			}
			$x = $_POST["x"];
			$y = $_POST["y"];
			$id = getIslandIdByPos($x, $y);
			
			if(is_null($id)) {
				
				$type = $_POST["type"];
				if($type == "none") {
					echo '<p class="error">Debes seleccionar un tipo.</p>';
				} else {
					$date = date("Y-m-d H:i:s");
					$notes = $_POST["notes"];
					$filon = isset($_POST["filon"])?1:0;
					
					addIsland($user, $date, $x, $y, $type, $filon, $notes, $trust);
					echo '<p class="ok">¡La isla se ha agregado con éxito!</p>';
				}
			} else {
				if(newHasTag("island", "user", $id, $user)) {
					echo '<p class="error">¡Ya has agregado a esta isla! No puedes volver a hacerlo.</p>';
				} else {
					updateIslandTrust($id, $user, $trust);
					echo '<p class="ok">¡La isla se ha agregado con éxito!</p>';
				}
			}
		} else {
			echo '<p class="error">Tienes que iniciar sesión para poder agregar una isla.</p>';
		}
	}

	echo '
	<form class="kube-table-form" method="POST">
		<table id="kube-table" class="entry">
			<tr>
				<th class="odd"><b>X</b></th>
				<th class="even"><b>Y</b></th>
				<th class="odd"><b>Tipo</b></th>
				<th class="even"><b>Filón</b></th>
				<th class="odd"><b>Nota</b></th>
				<th class="even" />
			</tr>
			<tr class="odd">
				<td class="odd"><input type="number" name="x" required="required"></td>
				<td class="even"><input type="number" name="y" required="required"></td>
				<td class="odd">
					<select name="type">	
						<option value="none">Tipo...</option>	 
						<option value="Abedul">Abedul</option>
						<option value="Antena">Antena</option>
						<option value="Azul">Azul</option>
						<option value="Desierto">Desierto</option>
						<option value="Gigante">Gigante</option>
						<option value="Gruta">Gruta</option>
						<option value="Jungla">Jungla</option>
						<option value="Meka">Meka</option>
						<option value="Milagro">Milagro</option>
						<option value="Nevada">Nevada</option>
						<option value="Oscura">Oscura</option>
						<option value="Otoño">Otoño</option> 
						<option value="Pantano">Pantano</option>
						<option value="Pastizal">Pastizal</option>
						<option value="Pradera">Pradera</option>
						<option value="Sabana">Sabana</option>
						<option value="Shinsekai">Shinsekai/Púrpura</option>
						<option value="Volcán">Volcán</option>
					</select>
				</td>
				<td class="even"><input type="checkbox" name="filon"></td>
				<td class="odd"><textarea class="kube-textarea" name="notes"></textarea></td>
				<td class="even"><input type="submit" class="kube-table-btn" name="send" value="Enviar"></td>
			</tr>	
		</table>
	</form>';	 

?>