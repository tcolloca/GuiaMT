<?php

include_once(ROOT."/functions/database_management.php");
if( isset($_POST['sendMail']) || isset($_POST['sendPass']) )
	include_once(ROOT."/functions/change_account_data.php");

?>

<p>
<minititle>
Tus Datos
</minititle>
</p>

<p>
<table width="300px" cellspacing="0" cellpadding="5px" align="center">
   <tr bgcolor="#95CAEA">
       <td><b>Usuario:</b></td>
       <td><?php echo $_SESSION['username']; ?></td>
	</tr>
	<tr bgcolor="#75AFF0">
		<td><b>Mail:</b></td>
		<td><?php echo getMail($_SESSION['username']); ?></td>
	</tr>
	<tr bgcolor="#95CAEA">
        <td><b>Sexo:</b></td>
        <td><?php if( getSex($_SESSION['username']) == 'M' ){echo "Masculino";}else{echo "Femenino";} ?></td>
	</tr>
</table>
</p>

<pre>

</pre>
<p>
<minititle>
Cambio de Mail
</minititle>
</p>

<pre>

</pre>
<p>
<form method="post" action="/?id=tu_cuenta&cat=datos">
<table width="400px" cellspacing="0" cellpadding="5px" align="center">
		<tr bgcolor="#95CAEA">
        	<td><b>Mail:</b><br /><i>Debe ser v&aacute;lido, ya que se enviar&aacute; un nuevo c&oacute;digo para validar la cuenta.</i></td>
            <td valign="middle"><input type="text" name="mail" value="<?php echo $mail; ?>" /></td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Contrase&ntilde;a:</b></td>
            <td valign="middle"><input type="password" name="password"/></td>
		</tr>
        <tr>
        	<td colspan="2" align="center" valign="top"><input type="submit" name="sendMail" value="Enviar" /></td>
        </tr>
</table>
</form>
</p>

<pre>

</pre>

<p>
<minititle>
Cambio de Contrase&ntilde;a
</minititle>
</p>

<pre>

</pre>
<p>
<form method="post" action="/?id=tu_cuenta&cat=datos">
<table width="300px" cellspacing="0" cellpadding="5px" align="center">
		<tr bgcolor="#95CAEA">
        	<td><b>Contrase&ntilde;a actual:</b><br /></td>
            <td valign="middle" width="80px"><input width="80px" type="password" name="password" /></td>
		</tr>
        <tr bgcolor="#75AFF0">
        	<td><b>Contrase&ntilde;a nueva:</b><br /><i>Debe tener al menos 8 d&iacute;gitos.</i></td>
            <td valign="middle" width="80px" ><input width="80px" type="password" name="newPassword"/></td>
		</tr>
        <tr bgcolor="#95CAEA">
        	<td><b>Repite tu nueva contrase&ntilde;a:</b><br /><i><?php echo $msgpwd; ?></i></td>
            <td valign="middle" width="80px" ><input width="80px" type="password" name="REnewPassword"/></td>
		</tr>
        <tr>
        	<td colspan="2" align="center" valign="top"><input type="submit" name="sendPass" value="Enviar" /></td>
        </tr>
</table>
</form>
</p>
