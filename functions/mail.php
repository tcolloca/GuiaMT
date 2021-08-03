<?php

	define(MAIL_HOST, "snowstorm.dreamhost.com");
	define(MAIL_USER, "tomcol7");
	define(MAIL_PASSWORD, "Guiamt2013");
	define(MAIL, "no-reply@guiamt.net");
	define(MAIL_NAME, "GuiaMT");
	define(MAIL_TOM, "tom@guiamt.net");

	function sendMail($to, $toName, $subject, $content, $from, $fromName, $reply, $replyName){
		
		/*echo "to: ".$to;
		echo "toName: ".$toName;
		echo "Subject: ".$subject;
		echo "content: ".$content;
		echo "from: ".$from;
		echo "fromName: ".$fromName;
		echo "reply: ".$reply;
		echo "replyName: ".$replyName;*/
		
		include_once(ROOT."/functions/php_mailer/class.phpmailer.php");
		$mail = new PHPMailer();
				
		$mail->IsSMTP();           // set mailer to use SMTP
		$mail->Host = MAIL_HOST;  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
			$mail->Username = MAIL_USER;        // Make sure to replace this with your shell enabled user
			$mail->Password = MAIL_PASSWORD;      // Make sure to use the proper password for your user
				
		$mail->From = $from;
		$mail->FromName = $fromName;
		$mail->AddAddress($to, $toName);
		$mail->AddReplyTo($reply, $replyName);
		//$mail->SMTPDebug  = 2; 				//DEBUG-MODE
			
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		$mail->IsHTML(true);                                  // set email format to HTML
		$mail->CharSet = 'UTF-8';
				
		$mail->Subject = $subject;
		$mail->Body    = $content;
						
		if($mail->Send())
			return true;
		else{
			echo "El mensaje no pudo ser enviado. <p>";
			echo "Por favor, env&iacute;a el siguiente error a tomascllc@hotmail.com y en lo posible, describiendo que fue lo previo que hiciste a que ocurra: " . $mail->ErrorInfo;
		}
	}
	
	function sendManyMail($toAddressess, $subject, $content, $from, $fromName, $reply, $replyName){
		$result = false;
		foreach( $toAddressess as $address ) {
			$result = $result || sendMail($address[0], $address[1], $subject, $content, $from, $fromName, $reply, $replyName);
		}
		return $result;
	}
		
		/*require_once "Mail.php";
		$from = "Tom <tom@guiamt.net>";
		$to = $mail;
		$subject = "Validacion de cuenta";
		$body = "Para validar la cuenta, ingresa el siguiente codigo: ".$randcode.
		" en la siguiente pagina: ".$url." . &iexcl;Muchas gracias!";
		 
		$host = "snowstorm.dreamhost.com";
		$username = "tom@guiamt.net";
		$password = "Guiamt2013";
		 
		$headers = array ('From' => $from,
		  'To' => $to,
		  'Subject' => $subject);
		$smtp = Mail::factory('smtp',
		  array ('host' => $host,
			'auth' => true,
			'username' => $username,
			'password' => $password));
		 
		$mail = $smtp->send($to, $headers, $body);
		 
		return !PEAR::isError($mail);
		 
		/*$mailFrom = "From: Tom <tom@guiamt.net>";											
		$success = mail( $mail, "Validacion de cuenta", "Para validar la cuenta, ingresa el siguiente codigo: ".$randcode.
		" en la siguiente pagina: ".$url." . &iexcl;Muchas gracias!", $mailFrom );
		return $success;*/
		
	function sendValidationMail($to, $randcode){
		$toName = "";
		$subject = "Validaci&oacute;n de cuenta";
		$url = "http://".$_SERVER['SERVER_NAME']."/index.php?id=validar_cuenta";
		$content = "&iexcl;Gracias por crearte una cuenta! Para validarla, ingresa el siguiente c&oacute;digo: <b>".$randcode.
		"</b> una vez que hayas iniciado sesi&oacute;n en la siguiente p&aacute;gina: <a href=\"".$url."\">".$url."</a> &iexcl;Muchas gracias!";
		$from = MAIL;
		$fromName = MAIL_NAME;
		$reply = $from;
		$replyName = $fromName;
		return sendMail($to, $toName, $subject, $content, $from, $fromName, $reply, $replyName);
	}
	
	function recoverAccountMail($mail, $username, $randcode) {
		$subject = 'Recuperaci&oacute;n de cuenta';
		$content = "Tu usuario es: <b>".$username."</b> .<br />"."Tu nueva contrase&ntilde;a es: <b>".$randcode."</b> . Recuerda cambiar la contrase&ntilde;a una vez que hayas iniciado sesi&oacute;n :) .";
		$from = MAIL;
		$fromName = MAIL_NAME;
		$reply = MAIL;
		$replyName = MAIL_NAME;
		return sendMail($mail, "", $subject, $content, $from, $fromName, $reply, $replyName);	
	}
	
	function reportErrorMail($name, $mail, $subject, $content, $addressess) {							
		$from = $to = MAIL;
		$fromName = $toName = "GuiaMT";
		$reply = $name;
		$replyName = $mail;
		$content = "<p>De: ".$name." &lt;".$mail."&gt;</p>".$content;
		return sendManyMail($addressess, $subject, $content, $from, $fromName, $reply, $replyName);
	}
	
	function contactMail($name, $mail, $subject, $content) {	
		$from = MAIL;
		$fromName = MAIL_NAME;
		$reply = $name;
		$replyName = $mail;
		$content = "<p>De: ".$name." &lt;".$mail."&gt;</p>".$content;
		return sendMail(MAIL_TOM, "Tom", $subject, $content, $from, $fromName, $reply, $replyName);
	}
?>