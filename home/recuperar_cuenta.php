<p>
<subtitle>
Recupera tu Cuenta
</subtitle>
<pre>

</pre>

<?php

if( isset($_POST['enviar']) ){
	include_once(ROOT."/functions/database_management.php");
	include_once(ROOT."/functions/mail.php");
	include_once(ROOT."/functions/random_code.php");
	
	$randcode = randomCode(8);
	$mail = $_POST['mail'];
	$username = recoverAccount($mail, $randcode);
	
	if( $username == null )
		echo "<center><font color='#FF0000'><b>El email ingresado es inv&aacute;lido.</b></font></center><pre>	</pre>";
	else{
			$success = recoverAccountMail($mail, $username, $randcode);			
			if( $success )
				echo "<center><font color='#009900'><b>Se ha enviado a tu casilla de correos un email con tu usuario y contrase&ntilde;a.</b></font></center><pre>	</pre>";
			else
				echo "<center><font color='#FF0000'><b>No se pudo enviar el email. Por favor, prueba m&aacute;s tarde.</b></font></center><pre>	</pre>";
	}
}

?>

<form method="post" action="/?id=recuperar_cuenta">
<table cellspacing="0" cellpadding="5px" align="center">
    	<tr>  	
            <td valign="bottom" width="100" align="center"><input type="text" name="mail" style="width:200px;"/></td>
			<td align="center" valign="middle"><input type="submit" name="enviar" value="Enviar" /></td> 
		</tr>
        <tr>
			<td align="center" style="font-size:12;" valign="top"><b>Email</b></td>
  		</tr>
		
</table>
</form>
