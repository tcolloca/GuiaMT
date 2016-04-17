<?php
	include_once(ROOT."/functions/database_management.php");
	include_once(ROOT."/home/recaptchalib.php");

    $flag = true;
	$message = "";
	$success = false;
		
/***** REGISTER ANALYSIS ****/

	/* ALREADY EXISTING DATA */
		
		/* AGE CALCULATOR */
		
	if( is_numeric(trim($_POST['month'])) && is_numeric(trim($_POST['day'])) && is_numeric(trim($_POST['year'])) &&
	checkdate($_POST['month'], $_POST['day'], $_POST['year']) ) {
		$birthDate = array( $_POST['month'], $_POST['day'], $_POST['year']);
		$age = ( date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y")-$birthDate[2])-1):(date("Y")-$birthDate[2]));
	} else {
		$message = $message."La fecha de nacimiento seleccionada es inv&aacute;lida. <br />"; 
		$flag = false;
	}
  
  		/* CONDITIONING */
		
	if( !isset($_POST['username']) || $_POST['username'] == "" ) {
		$message = $message."El nombre de usuario es un campo obligatorio. <br />"; 
		$flag = false;
	} else if( !ctype_alnum($_POST['username']) ) {
		$message = $message."El nombre de usuario contiene caracteres inv&aacute;lidos. <br />"; 
		$flag = false;	
	} else {
		if(  !userExists( $_POST['username'] ) )
			$username = $_POST['username'];
		else {
			$message = $message."El nombre de usuario escogido ya existe. <br />";
			$flag = false;
		}
	}
			
	if( !isset($_POST['mail']) ) {
		$message = $message."El mail es un campo obligatorio. <br />"; 
		$flag = false;
	} else if( !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) ) {
		$message = $message."El mail introducido es incorrecto. <br />"; 
		$flag = false;
	} else {
		if( !mailExists($_POST['mail']) )
			$mail = $_POST['mail'];
		else {
			$message = $message."El mail introducido ya existe. <br />";
			$flag = false;
		}
	}
		
	if( !isset($_POST['password']) || $_POST['password'] == "" ) {
		$message = $message."La contrase&ntilde;a es un campo obligatorio. <br />"; 
		$flag = false;
	} else if( !ctype_alnum($_POST['password']) ) {
		$message = $message."La contrase&ntilde;a introducida contiene caracteres inv&aacute;lidos. <br />"; 
		$flag = false;	
	} else if( strlen($_POST['password']) < 8 ) {
		$message = $message."La contrase&ntilde;a es demasiado corta. <br />"; 
		$flag = false;	
	} else {
		if( $_POST['password'] == $_POST['REpassword'] ) {
			$password = $_POST['password'];
			$REpassword = $_POST['password'];
		} else {
				$password = "";
				$REpassword = "";
				$message = $message."La contrase&ntilde;as no coinciden. <br />"; 
				$flag = false;
		}
	}
		
	if( $age < 13 ) {
		$message = $message."Debes ser mayor de 13 a&ntilde;os para crear una cuenta, o mandar por Contacto una autorizaci&oacute;n de tus padres. <br />";
		$flag = false;
	}

	if( !isset($_POST['sex']) ) {
		$message = $message."El sexo es un campo obligatorio. <br />";
		$flag = false;
	}
	
	if( $_POST['privacy'] != 1 ) {
		$flag = false;
		$message = $message."Debes aceptar los T&eacute;rminos y Condiciones de Privacidad y uso de la P&aacute;gina. <br />";
	} 

	if ( isset($_POST["recaptcha_response_field"]) ) {
        $resp = recaptcha_check_answer ("6LdJPeESAAAAADavwO8qq8rc9WQAYKy28jh1QLU4",
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);
		
		if ( $resp->is_valid ) {
			# They got the captcha right.
			
			if( $flag ){
				include_once(ROOT."/functions/random_code.php");		
				include_once(ROOT."/functions/mail.php");		
				$randcode = randomCode(8);								
				$success = sendValidationMail($mail, $randcode);
			}
		} else		
			$message = $message.$resp->error;
	}

	/**** TEXTO EXITO DE CUENTA *****/

	if ( $success ) {
		echo "<font color='#009900'><b>&iexcl;Tu cuenta se ha registrado con &eacute;xito! <!--Pronto llegar&aacute; un mail a tu casilla de correos con el c&oacute;digo de validaci&oacute;n.--></b></font><pre>	</pre>";
		addUser( $username, $password, $mail, $randcode, $_POST['sex'] );
	} else
		echo "<font color='#FF0000'><b>".$message."</b></font><pre>	</pre>";
?>