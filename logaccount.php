<div id="log">
<?php   
	if ( (!isset( $_COOKIE['username']) && !isset($_SESSION['username']) ) || $_GET['logout'] == yes ){
		echo '<p>&iexcl;A&uacute;n no has entrado a tu cuenta! <a href="/index.php?id=tu_cuenta"><b>Loguéate</b></a> en tu cuenta, o <a href="/index.php?id=tu_cuenta"><b>regístrate</b></a> si a&uacute;n no tienes.</p>'; 	
	}
	else {
		include_once( ROOT."/functions/database_management.php" );
		
		if( !isset($_SESSION['username']) && matchPassword($_COOKIE['username'], $_COOKIE['password']) )
			$_SESSION['username'] = $_COOKIE['username'];
		if( getSex($_SESSION['username']) == 'M' )
			$sex = 'o';
		else
			$sex = 'a';	
		if( isStaff($_SESSION['username']) ) { 
			$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
			list($gameSection) = sscanf( $url, "http://".$_SERVER['SERVER_NAME']."/%[^/]");
			
			if( $gameSection == "" ) {
				$gameSection = "index.php";
			}
			
			$action = "edit";
			if( $gameSection == "hammerfest" && strpos( $url, '?main') !== false ) {
				$action = "comment";
			}
			
	 		$string = "<td align='left'><form method='post' action='/modify/index.php' \>			
             <input type='hidden' name='section' value='".$gameSection."' />                 
             <input type='hidden' name='action' value='".$action."' />
             <input type='hidden' name='url' value='".$url."' />
             <input type='hidden' name='preview' value='1' /><input type='submit' name='tryToModify' value='Editar' /></form></td>";
		}
		else
			$string = '';
		echo "<table width='740px'><tr>".$string."<td align='right'><p align='right' style='font-size:14;'>&iexcl;Hola de nuevo <b>".$_SESSION['username']."</b>! Bienvenid".$sex." a la GuiaMT. | <a href='/index.php?logout=yes'><b>Logout</b></a></p></td></tr></table>";
	}
?>
</div>
