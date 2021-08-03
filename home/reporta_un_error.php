<?php
	include_once(ROOT."/functions/staff_mails.php");
	include_once(ROOT."/functions/mail.php");
	include_once(ROOT."/home/recaptchalib.php");
	
	define(MAIL, "no-reply@guiamt.net");
	define(MAIL_NAME, "GuiaMT");

	$flag = true;
	$success = false;
	$message = "";
	
	if ( isset($_POST['enviar']) ) {

		if( isset($_POST['nombre']) && $_POST['nombre'] != "" )
			$nombre = $_POST['nombre'];
		else
			$nombre = "";
			
		if( isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
			$email = $_POST['email'];
		else if( isset($_POST['email']) && $_POST['email'] == "" )
			$email = "";
		else if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
			$message = $message."El mail ingresado es inv&aacute;lido.";
			$flag = false;
		}
			
			
		if( isset($_POST['seccion']) && $_POST['seccion'] != "none" )
			$seccion = $_POST['seccion'];
		else {
			$seccion = "none";
			$message = $message."La secci&oacute;n es un campo obligatorio.";
			$flag = false;
		}
		
		if( !isset($_POST['url']) || $_POST['url'] == "" ) {
			$message = $message."La URL donde se encuentra el error es un campo obligatorio.";
			$flag = false;
		}
		else if ( !filter_var( $_POST['url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) ) {
			$message = $message."La URL donde se encuentra el error debe ser v&aacute;lida.";
			$flag = false;
		} else {
			$url = $_POST['url'];
		}
		
		if( isset($_POST['descripcion']) && $_POST['descripcion'] != "" )
			$descripcion = $_POST['descripcion'];
		else {
			$message = $message."La descripci&oacute;n es un campo obligatorio.";
			$flag = false;
		}
		
		if( isset($_POST['tipo']) )
			$tipo = $_POST['tipo'];
			
		if (isset($_POST["recaptcha_response_field"])) {
			$resp = recaptcha_check_answer ("6LdJPeESAAAAADavwO8qq8rc9WQAYKy28jh1QLU4",
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);
			if ($resp->is_valid) {
				# they got the captcha right
				if( $flag ) {
					switch( $seccion ) {
						case "GuiaMT":
							$addressess = $GUIAMT;
							break;
						default:
							$addressess = $GUIAMT;
							break;
					}						
					$name = $nombre;
					$mail = $email;
					$subject = $seccion." - ".$tipo;
					$content = "La p&aacute;gina donde est&aacute; el error es: ".$url." <br />. 
					La descripci&oacute;n del error es: <br />".$descripcion;
					$success = reportErrorMail($name, $mail, $subject, $content, $addressess);
				} else 
					$message = $message.$resp->error;
			}
		}
	}
?>

<p>
<subtitle>
Reporta un Error
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

<p> Si has notado un error, est&aacute;s en el sitio correcto para informarnos. Por favor, s&eacute; cuidadoso al llenar los distintos campos, ya que nos facilitar&aacute; el hallar el problema y solucionarlo.</p>
<pre>

</pre>
<p>
<form method="post" action="/?id=reporta_un_error">
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
        	<td><b>Secci&oacute;n:</b><br /></td>
            <td valign="middle"><select name="seccion" value="<?php echo $seccion; ?>" style="width:150px;">
            	<option value="none">Elige una Secci&oacute;n:</option>
        				<option value="news">Noticia</option>
                        <option value="dead_cells">Dead Cells</option>
                        <option value="kube">Kube</option>
                        <option value="monstruhotel2">Monstruhotel 2</option>
                        <option value="hammerfest">Hammerfest</option>
                        <option value="mush">Mush</option>
                        <option value="zombinoia">Zombinoia</option>
                        <option value="alphabounce">Alphabounce</option>
                        <option value="street_writer">Street Writer</option>
                        <option value="kadokado">KadoKado</option>
                        <option value="monstruhotel">Monstruhotel</option>
                        <option value="arkadeo">Arkadeo</option>
                        <option value="teacher_story">Teacher Story</option>
                        <option value="snake">Snake</option>
                        <option value="carapass">Carapass</option>
                        <option value="kingdom">Kingdom</option>
                        <option value="rockfaller_journey">Rockfaller Journey</option>
                        <option value="minitroopers">Minitroopers</option>
                        <option value="dinorpg">DinoRPG</option>
                        <option value="elbruto">ElBruto</option>
                        <option value="drakarnage">Drakarnage</option>
                        <option value="fever">Fever</option>     
                </select>
            </td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>URL donde est&aacute; el error:</b><br /><i>Deb&eacute;s incluir el <b>http://</b></i></td>
            <td valign="middle"><input type="text" name="url" value="<?php echo $url; ?>" /></td>
		</tr>
        <tr bgcolor="#95CAEA">
        	<td><b>Tipo de error:</b></td>
            <td align="center"><select name="tipo" size="1" style="width:150px;" value="<?php echo $tipo; ?>">
           		<option value="Bug/Glitch">Bug/Glitch</option>
                <option value="Informaci&oacute;n Err&oacute;nea">Informaci&oacute;n Err&oacute;nea</option>
                <option value="Enlace Roto">Enlace roto</option>
                <option value="Informaci&oacute;n Desactualizada">Informaci&oacute;n desactualizada</option>
                <option value="Error Ortogr&aacute;fico">Error ortogr&aacute;fico</option>
                <option value="Falta de Informaci&oacute;n">Falta de informaci&oacute;n</option>
                <option value="Error">Otro</option>
                </select>
			</td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td valign="top" width="400px" colspan="2"><b>Descripci&oacute;n:</b><br /><i>Aqu&iacute; describe el error. Intenta de ser lo m&aacute;s claro y espec&iacute;fico posible :)</i>
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


