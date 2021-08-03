<?php
	session_start();

	define(ROOT, $_SERVER['DOCUMENT_ROOT']);

	preg_match('/^\/([^\/]*)/', $_SERVER["REQUEST_URI"], $matches);
$game = substr($matches[0], 1);

	include(ROOT."/bgcolors.php");
	include(ROOT."/head.php");
?>

	<body style="margin-top:0; background-color:<?php echo $bgc;?>">
    	<div id="wrapper">

<?php
	include(ROOT."/header.php");
	include(ROOT."/gamebar.php");
?>

            <div id="content">
                <div id="main-text">

<?php
	include(ROOT."/topContent.php");

	//MAIN CONTENT

	include_once( ROOT.'/functions/database_management.php' );

	if( !isset($_SESSION['username']) || !isStaff($_SESSION['username']) )
		echo "<center><font size='+2'><b>¿¡QUÉ HACES AQUÍ!? ¡NO TIENES LOS PERMISOS NECESARIOS PARA ACCEDER A ESTA PÁGINA!</b><	</font><p><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></p></center>";

	else {

		if( isset($_POST['modify']) || isset($_POST['tryToModify']) ){
			$others = array("distinciones", "events", "modify", "images", "twinoid");
		 	if( strpos( $_POST['section'], 'index.php') !== false || in_array($_POST['section'], $others)){

				if( !hasPriviledge($_SESSION['username'], "main") )
					echo "<center><font size='+2'><b>¿¡QUÉ INTENTAS HACER!? ¡NO TIENES LOS PERMISOS NECESARIOS PARA MODIFICAR LA SECCIÓN ELEGIDA!</b></font><p><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></p></center>";
				else {

					if(in_array($_POST['section'], $others)) {

						switch( $_POST['action'] )
						{
							case 'edit':
								include( ROOT.'/functions/edit_page.php' );
								break;
							case 'table':
								include( ROOT.'/functions/edit_table.php' );
						}

						if( isset($_POST['validation']) )
							include( ROOT.'/functions/modify_log.php' );

					} else {
						list( $indexId ) = sscanf($_POST['section'], "index.php?id=%s");
							if( $indexId == "" ) {
								include(ROOT.'/functions/post_news.php');
								if( isset($_POST['validation']) )
									include( ROOT.'/functions/modify_log.php' );
							} else {
								include(ROOT.'/functions/edit_main.php');
								if( isset($_POST['validation']) )
									include( ROOT.'/functions/modify_log.php' );
							}

						}
				}
			}
			else if( !hasPriviledge($_SESSION['username'], $_POST['section']) && $_SESSION['username'] != "tomatereloco" )
				echo "<center><font size='+2'><b>¿¡QUÉ INTENTAS HACER!? ¡NO TIENES LOS PERMISOS NECESARIOS PARA MODIFICAR LA SECCIÓN ELEGIDA!</b></font><p><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></p></center>";

			else {

				switch( $_POST['action'] )
				{
					case 'comment':
						include( ROOT.'/functions/comment.php' );
						break;
					case 'edit':
						include( ROOT.'/functions/edit_page.php' );
						break;
					case 'table':
						include( ROOT.'/functions/edit_table.php' );
				}

				if( isset($_POST['validation']) )
					include( ROOT.'/functions/modify_log.php' );
			}
		}
	}

	include("intro.html");

	//END OF MAIN CONTENT
?>

				</div> <!-- main-text -->
			</div> <!-- content -->

<?php
	include(ROOT."/leftbar.php");
	include(ROOT."/bottom.php");
?>

		</div> <!-- wrapper -->
	</body>


