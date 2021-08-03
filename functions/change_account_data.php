<?php
	include_once(ROOT."/functions/database_management.php");

    $flag = true;
	$success = false;

/*** NEW DATA ANALYSIS ***/
			 
  	/* CONDITIONING */
	
	if( isset($_POST['sendMail']) )
		if( !isset($_POST['mail']) )
			$message = "El mail es un campo obligatorio. <br />"; 
		else if( !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) )
			$message = "El mail introducido es incorrecto.  <br />"; 
		else if( !matchPassword($_SESSION['username'], $_POST['password']) )
			$message = "La contrase&ntilde;a es inv&aacute;lida.";
		else
			if( mailExists($_POST['mail']) )
				$message = "El mail introducido ya existe. <br />";
			else{														
				include_once(ROOT."/functions/random_code.php");		
				include_once(ROOT."/functions/mail.php");		
				$randcode = randomCode(8);								
				$success = sendValidationMail($_POST['mail'], $randcode);	
			}
	
	if ( isset($_POST['sendPass']) )
		if( !isset($_POST['newPassword']) || $_POST['newPassword'] == "" )
			$message = "La contrase&ntilde;a es un campo obligatorio. <br />"; 
		else if( !ctype_alnum($_POST['password']) )
			$message = "La contrase&ntilde;a introducida contiene caracteres inv&aacute;lidos. <br />"; 	
		else if( strlen($_POST['newPassword']) < 8 )
			$message = "La contrase&ntilde;a es demasiado corta. <br />"; 
		else if( !matchPassword($_SESSION['username'], $_POST['password']) )
			$message = "La contrase&ntilde;a es inv&aacute;lida.";
		else if( $_POST['newPassword'] != $_POST['REnewPassword'] )
			$message = "La contrase&ntilde;as no coinciden. <br />"; 
		else
			$success = true;
		

		/**** RESULT *****/

	if ( $success ) {
		$mailMessage = "";
		if( isset($_POST['sendMail']) ){
			$mailMessage = " Pronto llegar&aacute; un mail a tu casilla de correos con el nuevo c&oacute;digo de 
			validaci&oacute;n.";
			editMail( $_SESSION['username'], $_POST['mail'], $randcode );
		}
		else 
			editPassword( $_SESSION['username'], $_POST['newPassword'] );
		echo "<font color='#009900'><b>&iexcl;Los datos de tu cuenta se han modificado con &eacute;xito!".$mailMessage."</b></font><pre>	</pre>";		
	} else
		echo "<font color='#FF0000'><b>".$message."</b></font><pre>	</pre>";

?>