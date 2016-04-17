<?php

include_once(ROOT."/functions/database_management.php");

			/* drawUserDataTable */
/*
** Description: Shows the table with the users and the option to change staff, status, and permissions.
** Return value: Void.
*/

function drawUserDataTable() {
	
	$games = array( 'main', 'dead_cells', 'kube', 'monstruhotel2', 'hammerfest', 'mush', 'zombinoia', 'alphabounce', 
		'street_writer', 'kadokado', 'monstruhotel', 'arkadeo', 'teacher_story', 'snake', 'carapass', 
		'kingdom', 'rockfaller_journey', 'minitroopers', 'dinorpg', 'elbruto', 'drakarnage', 'fever');
	
	echo '<table id="userTable">';
		echo '<tr>';
			echo '<th><b>User</b></th> 
				<th><b>Status</b></th>
				<th><b>Staff</b></th>';
		
		foreach( $games as $game )
			echo '<th><b>'.ucfirst($game[0]).'</b></th>';
			
			echo'<th><b>Submit</b></th>
			</tr>';
	
	while( $username = getUser() )
		printUserData($username, $games);
	echo "</table>";
}

			/* printUserData */
/*
** Parameters: $user.
** Description: Prints the user with all the form to edit.
** Return value: Void.
*/

function printUserData($username, $games) {
	
	$status = getStatus($username);
	$isStaff = isStaff($username);
	
	if( $status != 'active' && $status != 'frozen' ) {
		$unregisValue = $status;
		$active = '';
		$frozen = '';
		$unregistered = 'selected';
	} else {
		$unregisValue = 'unregist';
		$active = ($status == 'active') ? 'selected' : '';
		$frozen = ($status == 'frozen') ? 'selected' : '';
		$unregistered = '';
	}
	
	if( $isStaff ) {
		$Ystaff = 'selected';
		$Nstaff = '';
	} else {
		$Ystaff = '';
		$Nstaff = 'selected';
	}
	
	if( $isStaff ) {	
		foreach( $games as $game )
		{
			if( hasPriviledge($username, $game) )
			{
				${"Y".$game} = 'selected';
				${"N".$game} = '';
			}
			else
			{
				${"Y".$game} = '';
				${"N".$game} = 'selected';
			}
		}
	}
	
	echo '<form  method="post" action="/permissions/?DBuser='.$_GET["DBuser"].'&DBpassword='.$_GET["DBpassword"].'" \>';
	echo '<input type="hidden" name="user" value="'.$username.'">';
	echo '<input type="hidden" name="DBuser" value="'.$_GET['DBuser'].'">';
	echo '<input type="hidden" name="DBpassword" value="'.$_GET['DBpassword'].'">';
	echo '<tr>';
	echo '<td>'.$username.'</td>';
	echo '<td><select name="status">';
		echo'<option value="active" '.$active.'>active</option>';
		echo'<option value="frozen" '.$frozen.'>frozen</option>';
		echo'<option value="'.$unregisValue.'" '.$unregistered.'>unregistered</option>';
	echo '</select></td>';
	echo '<td><select name="staff">';
		echo'<option value="1" '.$Ystaff.'>Y</option>';
		echo'<option value="0" '.$Nstaff.'>N</option>';
	echo '</select></td>';
	if( $isStaff )
	{
		foreach( $games as $game )
		{
			echo '<td><select name="'.$game.'">';
				echo'<option value="1" '.${"Y".$game}.'>Y</option>';
				echo'<option value="0" '.${"N".$game}.'>N</option>';
			echo '</select></td>';
		}
	}
	else
		echo '<td colspan="'.count( $games ).'"><center>-</center></td>';
	echo '<td><input type="submit" name="send" value="Edit" /></td>';
	echo '</tr></form>';
}

?>