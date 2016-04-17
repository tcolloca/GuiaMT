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
	
	include("intro.html");
	
	if(isset($_GET["action"])) {
		if($_GET["action"] == "list") {
			include("islands_table.php");	
		} else if($_GET["action"] == "add") {
			include("add_island.php");	
		} else if($_GET["action"] == "update") {
			include("update_island.php");	
		}
	}
	if(!isset($_GET["action"])) {
		include("islands_types.php");
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




