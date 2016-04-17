<?php

	if( isset($_POST['modify']) && isset($_POST['validation']) && isset($_POST['newfile']) && $_POST['newfile'] != "" ){
			
			for( $i = 1; file_exists(  ROOT.'/home/'.$indexId.$i.'.html' ); $i++ )
				;
				
			copy( ROOT.'/home/'.$indexId.'.html',  ROOT.'/home/'.$indexId.$i.'.html' );
		
			file_put_contents (ROOT.'/home/'.$indexId.'.html', $_POST['newfile']);
			
			echo "<center><font color='#009900'><b>&iexcl;La modificaci&oacute;n se realiz&oacute; con &eacute;xito!</b></font><br />
		<strong><a href=\"".$_POST['url']."\">CLIQUEA AQU&Iacute;</a></strong> para volver al sitio.</center>";
	}			
	else if( !isset($_POST['modify']) ){
		$fileHTML = false;
		
		if( file_exists(ROOT.'/home/'.$indexId.'.html' ) )			
			$fileHTML = file(ROOT.'/home/'.$indexId.'.html');

		if( $fileHTML === false )
			echo "<center><p><font color='#FF0000'><b>La p&aacute;gina seleccionada no se puede editar desde aqu&iacute;. Por favor, reporta cambio que se desea hacer a trav&eacute;s de Contacto.</b></font></p></center>";
		
		else {	
			echo '<center><p>Vista Previa</p></center>';
			echo '<p id="preview"></p>';
			
			echo '<center><p>C&oacute;digo HTML</p>';
			echo '<form method="post" action="/modify/index.php">';
			echo '<input type="hidden" name="validation" value="ok" \>';
			echo '<input type="hidden" name="action" value="'.$_POST['action'].'" \>';
			echo '<input type="hidden" name="section" value="'.$_POST['section'].'" \>';
			echo '<input type="hidden" name="url" value="'.$_POST['url'].'" \>';
			echo '<p><textarea id="htmlCode" name="newfile" cols="80" style="overflow:auto;" rows="30">';
			foreach( $fileHTML as $line )
					echo $line;
			echo '</textarea></p>';
			echo '<p>Modificaci&oacute;n: <input type="text" name="modifications" value="" \></p>';
			echo '<p><input type="submit" name="modify" value="Enviar" /></p></center>';
			
			echo "<script> 		
			$(function(){
				$('#htmlCode').keyup(function(){
				  $('#preview').html($(this).val());
					});
				});
			</script>";
		}
	} else {
		$subject = "Intento de modificaci&oacute;n de p&aacute;gina principal fallido.";
		$content = "";
		
		if( !isset($_POST['newfile']) || $_POST['newfile'] == "" ) 
			$content = $content."No quiso poner contenido a la p&aacute;gina. <br />";
		
		if( !isset($_POST['modifications']) || $_POST['modifications'] == "" )	
			$content = $content."No quiso poner que cambios hizo. <br />";
				
		$content = $content."Usuario: ".$_SESSION['username'];	
		$to = MAIL_MAIL;
		$toName = 'Tom';
		$from = $to;
		$fromName = $toName;
		$reply = $to;
		$replyName = $toName;
		sendMail($to, $toName, $subject, $content, $from, $fromName, $reply, $replyName);
	}
	
?>
 