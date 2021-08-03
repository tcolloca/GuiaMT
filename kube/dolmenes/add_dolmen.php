<?php

	include_once("add_update_dolmen.html");
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
			$id = getDolmenIdByPos($x, $y);
			
			if(is_null($id)) {
				$date = date("Y-m-d H:i:s");
				$notes = $_POST["notes"];
				
				addDolmen($user, $date, $x, $y, $notes, $trust);
				echo '<p class="ok">¡El dolmen se ha agregado con éxito!</p>';
			} else {
				if(newHasTag("dolmen", "user", $id, $user)) {
					echo '<p class="error">¡Ya has agregado a este dolmen! No puedes volver a hacerlo.</p>';
				} else {
					updateDolmenTrust($id, $user, $trust);
					echo '<p class="ok">¡El dolmen se ha agregado con éxito!</p>';
				}
			}
		} else {
				echo '<p class="error">Tienes que iniciar sesión para poder agregar un dolmen.</p>';
		}
	}

	echo '
	<form class="kube-table-form" method="POST">
		<table id="kube-table" class="entry">
			<tr>
				<th class="odd"><b>X</b></th>
				<th class="even"><b>Y</b></th>
				<th class="odd"><b>Nota</b></th>
				<th class="even" />
			</tr>
			<tr class="odd">
				<td class="odd"><input type="number" name="x" required="required"></td>
				<td class="even"><input type="number" name="y" required="required"></td>
				<td class="odd"><textarea class="kube-textarea" name="notes"></textarea></td>
				<td class="even"><input type="submit" class="kube-table-btn" name="send" value="Enviar"></td>
			</tr>	
		</table>
	</form>';	 

?>