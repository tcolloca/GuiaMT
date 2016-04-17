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
	
	include_once('intro.html');
	include_once('investigations_table.php');
	
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

