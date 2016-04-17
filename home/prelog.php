<?php
	include_once(ROOT."/home/recaptchalib.php");
	if ( isset($_POST['send']) )
		include_once(ROOT."/functions/create_account.php");
?>

<p>&iexcl;A&uacute;n no has entrado a tu cuenta! Para ingresar a ella:</p>

<p>
<form method="post" action="/?id=tu_cuenta">
	<table cellspacing="0" cellpadding="5px" align="center">
		<tr>  	
			<td valign="bottom" width="100" align="center"><input type="text" name="logUser" style="width:90px;"/></td>
			<td valign="bottom" width="100" align="center"><input type="password" name="logPassword" style="width:90px;"/></td>
			<td valign="bottom" style="font-size:12;" width="135px"><input type="checkbox" name="remember" value="1"><font size="-1">Recordar sesi&oacute;n.</font></input></td>
			<td align="center" valign="middle"><input type="submit" name="login" value="Login" /></td> 
		</tr>
		<tr>
			<td align="center" style="font-size:12;" valign="top"><b>Usuario</b></td>
			<td align="center" style="font-size:12;" valign="top"><b>Contrase&ntilde;a</b></td>
			<td align="center" style="font-size:8;" valign="top" colspan="2"><b><a href="/?id=recuperar_cuenta"><font color="#233243" size="-3">&iquest;Olvidaste tu contrase&ntilde;a o usuario?</font></a></b></td>   
		</tr>
	</table>
</form>
</p>

<pre>

</pre>
		
		
<!-- REGISTRATION -->
		
<p>Si a&uacute;n no tienes una cuenta, a continuaci&oacute;n podr&aacute;s crearla. Al crearte una, podr&aacute;s comentar las noticias, unirte al Personal de la Gu&iacute;a, participar en eventos, y m&aacute;s cosas que vendr&aacute;n con el tiempo. Para conseguirla, s&oacute;lo debes completar el siguiente formulario: </p>		
<pre>

</pre>

<p>
<form method="post" action="/?id=tu_cuenta">
	<table width="400px" cellspacing="0" cellpadding="5px" align="center">
    	<tr bgcolor="#95CAEA">
        	<td><b>Nombre de usuario:</b></td>
            <td colspan="3"><input type="text" name="username" value="<?php echo $username; ?>" /></td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Mail:</b><br /><i>Debe ser v&aacute;lido, ya que se enviar&aacute; un c&oacute;digo para validar la cuenta.</i></td>
            <td valign="middle" colspan="3"><input type="text" name="mail" value="<?php echo $mail; ?>" /></td>
		</tr>
        <tr bgcolor="#95CAEA">
        	<td><b>Contrase&ntilde;a:</b><br /><i>Debe tener al menos 8 d&iacute;gitos.</i></td>
            <td valign="middle" colspan="3"><input type="password" name="password" value="<?php echo $password; ?>" /></td>
		</tr>
        </tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Repite tu contrase&ntilde;a:</b><br /><i><?php echo $msgpwd; ?></i></td>
            <td valign="middle" colspan="3"><input type="password" name="REpassword" value="<?php echo $REpassword; ?>" /></td>
		</tr>
		<tr bgcolor="#95CAEA">
        	<td><b>Sexo:</b><br /></td>
            <td valign="middle"><input type="radio" name="sex" value="M" /> M</td>
			<td valign="middle" colspan="2" align="left" ><input type="radio" name="sex" value="F" /> F</td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Fecha de Nacimiento:</b><br /></td>
            <td valign="middle"><input maxlength="2" type="text" name="day" value="DD" style="width:30px;"></td>
            <td valign="middle"><input maxlength="2" type="text" name="month" value="MM" style="width:30px;"></td>
            <td valign="middle"><input maxlength="4" type="text" name="year" value="YYYY" style="width:60px;"></td>
		</tr>
        <tr>
        	<td align="center" colspan="5">Escribe el c&oacute;digo para verificar que no eres un &#1071;0B0T
                <p>
                    <?php echo recaptcha_get_html("6LdJPeESAAAAAOAKGddgYPVYU6-YKxRew5ZmlD40", $error)?>
                </p>
            </td>
		</tr>  
        <tr>
        	<td colspan="5" align="left" valign="top"><input type="checkbox" name="privacy" value="1"><font size="-2">Acepto los <b><a href="/?id=privacy_policy">T&eacute;rminos y Condiciones de Privacidad y uso de la P&aacute;gina</a></b>.</font></input></td>
        </tr>     
        <tr>
       		<td colspan="5" align="center" valign="top"><input type="submit" name="send" value="Enviar" /></td>    
        </tr>     
    </table>
</form>
