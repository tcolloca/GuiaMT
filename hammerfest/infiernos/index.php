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
	
	$hellsInfo = array( 
	array( 'Los infiernos / Mezclador de Sacha / Pozo de los Zames', 13, 54 ),
	array( 'La taberna de las frambuesas', 2, '54.12' ),
	array( 'Metal que grita', 10, '54.12.1' ),
	array( '&iexcl;La entrada a la tumba!', 4, '54.12.1.9I' ),
	array( 'La tomba de Tub&eacute;rculo', 17, '54.12.1.9I.3' ),
	array( 'Escondite de los Consejeros', 10, '54.12.1.9I.3.10' ),
	array( 'Cripta frambuesada', 18, '54.12.1I' ),
	array( 'La verdadera tumba de Tub&eacute;rculo', 5, '54.12.1I.18' ));
	
	
	
	if( !isset($_GET['main']) )
		include('intro.php');
	else 
		include('nivel.php');

	
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
    



