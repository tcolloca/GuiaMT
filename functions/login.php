<?php	
		include_once(ROOT."/functions/database_management.php");
		
		if( !userExists($_POST['logUser']) ) {
			$GLOBALS['logMessage'] = "El usuario es incorrecto.";
		} else {			
			if( matchPassword($_POST['logUser'], $_POST['logPassword']) ) {
				$status = getStatus($_POST['logUser']);
				if( $status == 'frozen' )
					$GLOBALS['logMessage'] = "La cuenta ha sido congelada.";
				else {
					if( $_POST['remember'] == 1 ) {
						$var = setcookie( 'username', $_POST['logUser'], time() + 60*60*24*60 );
						setcookie( 'password', $_POST['logPassword'], time() + 60*60*24*60 );
					}
					$_SESSION['username'] = $_POST['logUser'];	
					if( $status == 'active' )	
						$GLOBALS['logMessage'] = "";
					else
						$GLOBALS['logMessage'] = "<!--La cuenta a&uacute;n no ha sido registrada. 
						Por favor, reg&iacute;strala cuanto antes, o podr&aacute; ser borrada.-->";			
				}
			}
			else
				$GLOBALS['logMessage'] = "La contrase&ntilde;a es incorrecta.";
		}
?>