<?php
    include($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    $request_path = strtok($_SERVER["REQUEST_URI"], '?');


    if (substr($request_path, -strlen(".php")) != ".php" && substr($request_path, -strlen(".php")) != ".ico") {
      if (substr($request_path, -strlen( "/" )) != "/") {
      	$request_path = $request_path . "/";
      }
      $request_path = $request_path . "index.php";
    }
    if ($request_path != "/index.php") {
      include($_SERVER['DOCUMENT_ROOT']. $request_path);
      return;
    }

	session_start();

	define(ROOT, $_SERVER['DOCUMENT_ROOT']);

	//include_once("functions/event_management.php");

	if( isset($_GET['logout']) && $_GET['logout'] == yes ){
		session_unset();
		setcookie('username', '', time() - 30 );
		setcookie('password', '', time() - 30 );
	}

	if ( isset($_POST['login']) )
		include(constant("ROOT")."/functions/login.php");

	preg_match('/^\/([^\/]*)/', $_SERVER["REQUEST_URI"], $matches);
    $game = substr($matches[0], 1);

	include(constant("ROOT")."/bgcolors.php");
	include(constant("ROOT")."/head.php");
?>

	<body style="margin-top:0; background-color:<?php echo $bgc;?>">
    	<div id="wrapper">

<?php
	include(constant("ROOT")."/header.php");
	include(constant("ROOT")."/gamebar.php");
?>

            <div id="content">
                <div id="main-text">

<?php
	include(constant("ROOT")."/topContent.php");

	//MAIN CONTENT

	$phpArr = array( "news", "contacto", "reporta_un_error", "tu_cuenta", "validar_cuenta", "recuperar_cuenta", "your_events");

	if( !isset($_GET['id']) )
		include( constant("ROOT")."/home/news.php" );
	else {
		$fileName = constant("ROOT")."/home/".$_GET['id'];
		if ( in_array( $_GET['id'], $phpArr ) )
			include($fileName.".php");
		else
			if( file_exists($fileName.".html") )
				include($fileName.".html");
			else
				include( constant("ROOT")."/home/news.php" );
	}

	//END OF MAIN CONTENT
?>

				</div> <!-- main-text -->
			</div> <!-- content -->

<?php
	include(constant("ROOT")."/leftbar.php");
	include(constant("ROOT")."/bottom.php");
?>

		</div> <!-- wrapper -->
	</body>


