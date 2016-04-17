<?php
	include_once(ROOT."/home/recaptchalib.php");
	include_once(ROOT."/functions/mail.php");

	define(MAIL, "no-reply@guiamt.net");
	define(MAIL_NAME, "GuiaMT");

	$flag = true;
	$success = false;
	$message = "";
	
	if ( isset($_POST['enviar']) ) {
		if( isset($_POST['nombre']) && $_POST['nombre'] != "" )
			$nombre = $_POST['nombre'];
		else
			$nombre = "An&oacute;nimo";
			
		if	( isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
			$email = $_POST['email'];
		else {
			$message = $message."El mail es obligatorio.";
			$flag = false;
		}
		
		if	(isset($_POST['asunto']) && $_POST['asunto'] != "" )
			$asunto = $_POST['asunto'];
		else
			$asunto = "Sin Asunto";
			
		if	( isset($_POST['descripcion']) && $_POST['descripcion'] != "" )
			$descripcion = $_POST['descripcion'];
		else {
			$message = $message."El asunto es un obligatorio.";
			$flag = false;
		}
		if( isset($_POST['tipo']) )
			$tipo = $_POST['tipo'];
			
		if (isset($_POST["recaptcha_response_field"])) {
			$resp = recaptcha_check_answer ("6LdJPeESAAAAADavwO8qq8rc9WQAYKy28jh1QLU4",
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);
			if ($resp->is_valid)
				# they got the captcha right
				if( $flag ) {	
					$name = $nombre;
					$mail = $email;
					$subject = $tipo." - ".$asunto;
					$content = $descripcion;
					$success = contactMail($name, $mail, $subject, $content);			
				}
			else 
				$message = $message.$resp->error;
		}
	}	
?>

<p>
<subtitle>
Contacto
</subtitle>
</p>
<pre>

</pre>
<?php
	if ( $success )
		echo "<font color='#009900'><b>&iexcl;Tu mensaje se ha enviado con &eacute;xito!</b></font><pre>	</pre>";
	else if ( !$success && $message != "" )
		echo "<font color='#FF0000'><b>".$message."</b></font><pre>	</pre>";
?>

<p><b>Nota:</b> Antes de hacer una pregunta, verifica que no est&eacute; en las <a href="/?id=faq">Preguntas Frecuentes(FAQ)</a>.</p>
<pre>

</pre>
<p>
<form method="post" action="/?id=contacto">
	<table width="400px" cellspacing="0" cellpadding="5px" align="center">
    	<tr bgcolor="#95CAEA">
        	<td><b>Nombre:</b></td>
            <td><input type="text" name="nombre" value="<?php echo $nombre; ?>" /></td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Mail:</b><br /><i>Es necesario si deseas obtener una respuesta.</i></td>
            <td valign="middle"><input type="text" name="email" value="<?php echo $email; ?>" /></td>
		</tr>
        <tr bgcolor="#95CAEA">
        	<td><b>Asunto:</b><br /></td>
            <td valign="middle"><input type="text" name="asunto" value="<?php echo $asunto; ?>" /></td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Tipo de mensaje:</b></td>
            <td align="center"><select name="tipo" size="1" style="width:150px; ">
                <option value="Sugerencia">Sugerencia</option>
                <option value="Pregunta">Pregunta</option>
                <option value="Comentario">Comentario</option>
                <option value="Reclamo">Reclamo</option>
                <option value="Fan mail">Fan Mail</option>
                <option value="GuiaMT">Otro</option>
                </select>
			</td>
		</tr>
        <tr bgcolor="#95CAEA">
        	<td valign="top" width="400px" colspan="2"><b>Descripci&oacute;n:</b><br /><i>Aqu&iacute; escribe tu mensaje. Intenta de ser lo m&aacute;s claro y espec&iacute;fico posible :)</i>
           <p><textarea name="descripcion" cols="50" rows="5" ><?php echo $descripcion; ?></textarea></p>
			</td>
        </tr>
        <tr>
        	<td align="center" colspan="2">Escribe el c&oacute;digo para verificar que no eres un &#1071;0B0T
            <p>
			<?php
			echo recaptcha_get_html("6LdJPeESAAAAAOAKGddgYPVYU6-YKxRew5ZmlD40", $error);
			?>
            </p>
            </td>
		</tr>  
        <tr>
        	<td colspan="2" align="center" valign="top"><input type="submit" name="enviar" value="Enviar" /></td>
        </tr>              
    </table>
</form>
</p>


