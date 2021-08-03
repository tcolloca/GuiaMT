<?php
	if( !isset($_SESSION['username']) )
		include(ROOT."/home/tu_cuenta.php");
	else{
		echo "
		<p>
		<subtitle>
		Validar la cuenta
		</subtitle>
		<pre>
		
		</pre>
		Simplemente ingresa tu nombre de usuario y el c&oacute;digo que te ha llegado por mail.";

		$message = "";		
		$status = getStatus($_SESSION['username']);		
				
		if( $status == 'active' )
			$message = "<font color='#FF0000'>Tu cuenta ya ha sido validada previamente.</font>";
		else if( $status == 'frozen' )
			$message = "<font color='#FF0000'>Tu cuenta ha sido congelada.</font>";
		else{
			include_once( ROOT."/functions/database_management.php" );	
			
			if( isset($_POST['send']) )
				if( !isset($_POST['code']) || $status != $_POST['code'] )
					$message = "<font color='#FF0000'>El c&oacute;digo ingresado es inv&aacute;lido.</font>";
				else{
					activate($_SESSION['username']);
					$message = "<font color='#009900'>&iexcl;La cuenta ha sido validada con &eacute;xito!</font>";
				}
			
			if( isset($_POST['resend']) ){	
				include_once(ROOT."/functions/random_code.php");		
				include_once(ROOT."/functions/mail.php");		
				
				$randcode = randomCode(8);			
				$mail = getMail($_SESSION['username']);					
				$success = sendValidationMail($mail, $randcode);	
				if( $success ){
					$message = "<font color='#009900'>Pronto llegar&aacute; un nuevo mail a tu casilla de correos con el c&oacute;digo de validaci&oacute;n.</font>";
					editStatus($_SESSION['username'], $randcode);
				}
				else
					$message = "<font color='#FF0000'>No se pudo enviar un nuevo c&oacute;digo de validaci&oacute;n. Intenta nuevamente m&aacute;s tarde, y/o reporta el error.</font>";
			}
		}
	
		if( $message != "" )
			echo '<p></p><p align="center"><b>'.$message.'</b></p>';
		echo '
		<p>
		<form method="post" action="/?id=validar_cuenta">
			<table width="300px" cellspacing="0" cellpadding="5px" align="center">
				<tr bgcolor="#95CAEA">
					<td><b>C&oacute;digo:</b><br /></td>
					<td valign="middle"><input type="text" name="code" /></td>
				</tr>
				<tr bgcolor="#75AFF0">
					<td align="center"><input type="submit" name="resend" value="Reenviar c&oacute;digo" /></td>
					<td align="center"><input type="submit" name="send" value="Enviar" /></td>        
				</tr>
			</table>
		</form>
		</p>';
	}
?>
