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

	if($_SESSION['username'] == "tomatereloco" || $_SESSION['username'] == "Fraven") {
		echo '
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
		<script type="text/javascript">
			var flashvars = {};
			var params = {};
			var attributes = {};


			params.bgcolor="#FFFFFF";

			attributes.name = "";
			attributes.styleclass = "";
			attributes.align = "";
			swfobject.embedSWF("/flash/events/src/flas/easter2015/rooms/main_room.swf", "flashContent", "700", "450", "9.0.0", false, flashvars, params, attributes);
		</script>
		<div id="flashContent">
			Get <a href="http://www.adobe.com/go/getflash">Adobe Flash Player</a>. Embedded with the help of <a href="http://embed-swf.org">embed-swf.org</a>.
		</div>';
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



