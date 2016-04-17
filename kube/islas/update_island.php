<?php

	include_once("add_update_island.html");
	include_once(ROOT."/functions/database_management.php");

	if(isset($_POST["send"])) {
		
		if(isset($_SESSION["username"])) {
			$user = $_SESSION["username"];
			$x = $_POST["x"];
			$y = $_POST["y"];
			$id = getIslandIdByPos($x, $y);
			
			if(is_null($id)) {
				echo '<p class="error">La isla aún no fue agregada.</p>';
			} else {
				$notes = $_POST["notes"];
				if($notes == "") {
					echo '<p class="error">No está permitido borrar una nota.</p>';
				} else {
					updateIslandNote($id, $user, $notes);
					echo '<p class="ok">¡La nota se ha actualizado con éxito!</p>';
				}
				
			}
		} else {
			echo '<p class="error">Tienes que iniciar sesión para poder actualizar una nota.</p>';
		}
	}

	echo '<p><b>Importante:</b> Si el problema es que no existe la isla, aparece como filón y no lo era, o al revés, repórtalo desde <a href="?id=reporta_un_error">aquí</a>.</p>';

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