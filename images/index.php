<?php
	session_start();
	
	define(ROOT, $_SERVER['DOCUMENT_ROOT']);
	
	$game = substr(substr($_SERVER["REQUEST_URI"],1), 0, strpos(substr($_SERVER["REQUEST_URI"],1), "/"));	
	
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
	
	include_once( ROOT.'/functions/database_management.php' );
	
	if( !isset($_SESSION['username']) || !isStaff($_SESSION['username']) ) {
		echo "<p class='error'>¿¡QUÉ HACES AQUÍ!? ¡NO TIENES LOS PERMISOS NECESARIOS PARA ACCEDER A ESTA PÁGINA!<br /><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></p>";
	
	} else {	
		if(isset($_POST["upload"])) {
			include_once('upload.php');
		} else if(isset($_POST["create"])) {
			include_once('create_dir.php');
		}
		include('intro.html');
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