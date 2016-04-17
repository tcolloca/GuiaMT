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
	
	echo "<subtitle>Guarida del Venerable</subtitle><br /><br />";
	
	if(isset($_SESSION['username']) && isEventOn("easter2014") && hasEvent("easter2014", $_SESSION['username'])) {
		if(hasObject("easter2014", $_SESSION['username'], "dragon")) {
			echo '<form method="POST" action="/index.php?id=your_events&event=easter2014"><input type="hidden" name="validation" value="dragonsLair142" /><input type="image" src="/images/events/easter2014/dragon_egg.png" alt="¡Hallar huevo!" /></form>';
		}
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


