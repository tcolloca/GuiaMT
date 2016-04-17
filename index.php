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
	
	$game = substr(substr($_SERVER["REQUEST_URI"],1), 0, strpos(substr($_SERVER["REQUEST_URI"],1), "/"));
	
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

	$phpArr = array( "news", "contacto", "reporta_un_error", "tu_cuenta", "validar_cuenta", "recuperar_cuenta", "your_events");
	
	if( !isset($_GET['id']) )
		include( ROOT."/home/news.php" );
	else {
		$fileName = ROOT."/home/".$_GET['id'];
		if ( in_array( $_GET['id'], $phpArr ) )
			include($fileName.".php");
		else
			if( file_exists($fileName.".html") )
				include($fileName.".html");
			else
				include( ROOT."/home/news.php" );
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
    

