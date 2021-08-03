<?php
	session_start();

	define(ROOT, $_SERVER['DOCUMENT_ROOT']);

	preg_match('/^\/([^\/]*)/', $_SERVER["REQUEST_URI"], $matches);
	$game = substr($matches[0], 1);

	include(ROOT."/bgcolors.php");
	include(ROOT."/head.php");

	include_once(ROOT."/tables/user_data.php");
	include_once(ROOT."/functions/database_management.php");

	echo $_SESSION['username'];
	if( $_SESSION['username'] == 'tomatereloco' || $_SESSION['username'] == 'somnium') {

		$games = array( 'main', 'dead_cells', 'kube', 'monstruhotel2', 'hammerfest', 'mush', 'zombinoia', 'alphabounce',
		'street_writer', 'kadokado', 'monstruhotel', 'arkadeo', 'teacher_story', 'snake', 'carapass',
		'kingdom', 'rockfaller_journey', 'minitroopers', 'dinorpg', 'elbruto', 'drakarnage', 'fever');

		echo "Agrega ?DBuser=tomatereloco&DBpassword=***** . Luego actualiza, y realiza la operación.";

		if( isset( $_POST['send'] ) ) {
			editStatus($_POST['user'], $_POST['status'], $_POST['DBuser'], $_POST['DBpassword']);

			if( $_POST['staff'] == 1 && !isStaff($_POST['user']) ||
			 $_POST['staff'] == 0 && isStaff($_POST['user']) ) {

				editStaff($_POST['user'], $_POST['staff'], $_POST['DBuser'], $_POST['DBpassword']);

				if( $_POST['staff'] == 1 )
					initPriviledges( $_POST['user']);

				if( $_POST['staff'] == 0 )
					removeAllPriviledges( $_POST['user'], $_POST['DBuser'], $_POST['DBpassword']);
			} else
				if( $_POST['staff'] == 1 )
					foreach( $games as $game )
						editPriviledge($_POST['user'], $game, $_POST[$game], $_POST['DBuser'], $_POST['DBpassword']);
		}

		drawUserDataTable();
	}

?>





