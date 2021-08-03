<?php
	session_start();

	define(ROOT, $_SERVER['DOCUMENT_ROOT']);

	//include_once("functions/event_management.php");

	if( isset($_GET['logout']) && $_GET['logout'] == yes ){
		session_unset();
		setcookie('username', '', time() - 30 );
		setcookie('password', '', time() - 30 );
	}

	if ( isset($_POST['login']) )
		include(ROOT."/functions/login.php");

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

	echo "<p><subtitle>Búsqueda de Huevos 2015</subtitle></p>";
	if(isset($_GET["id"])) {
		if($_GET["id"] == "clasification") {
			include(ROOT."/events/easter2014/intro.php");
		}
		include(ROOT."/events/easter2014/intro.php");
	} else {
		include(ROOT."/events/easter2014/intro.php");
	}

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




