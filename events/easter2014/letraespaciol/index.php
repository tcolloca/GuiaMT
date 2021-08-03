<?php
	session_start();

	define(ROOT, $_SERVER['DOCUMENT_ROOT']);

	preg_match('/^\/([^\/]*)/', $_SERVER["REQUEST_URI"], $matches);
	$game = substr($matches[0], 1);

	include(ROOT."/bgcolors.php");
	include(ROOT."/head.php");
?>

	<body style="margin-top:0; background-color:<?php echo $bgc;?>;">
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

	echo "<subtitle>Letra L</subtitle><br /><br />";
	echo  "<center><img src='/images/events/easter2014/alphabounce.png' alt='La imagen no se pudo cargar. Intente luego.'/>";
	echo "<p><em>Ahora sí, ¿Podremos identificar al espacio?</em></p></center>";

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


