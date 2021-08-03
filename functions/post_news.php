<?php
	include_once(ROOT.'/functions/mail.php');

	if( isset($_POST['modify']) && isset($_POST['validation']) && isset($_POST['content']) && $_POST['content'] != "" &&
		isset($_POST['title']) && $_POST['title'] != "" && isset($_POST['info']) && $_POST['info'] != "" )
	{
		$wholeNews = "
		<p>
		<minititle>".$_POST['title']."</minititle>
		</p>
		<simpletext> 
		<p>
		".$_POST['content']."
		</p>
		<p><b>Posteado por: </b>".ucfirst($_SESSION['username'])."</p>
		<p><b>Fecha: </b>".gmdate("d/m/Y - H:i")."</p>
		</simpletext>
		<pre>

		</pre>";
		
		for( $i = 1; file_exists( ROOT.'/home/news/'.$i.'.html' ); $i++ )
			;
		
		file_put_contents(ROOT.'/home/news/'.$i.'.html', "\xEF\xBB\xBF".$wholeNews);
		
		echo "<center><font color='#009900'><b>&iexcl;La modificaci&oacute;n se realiz&oacute; con &eacute;xito!</b></font><br />
		<strong><a href=\"".$_POST['url']."\">CLIQUEA AQU&Iacute;</a></strong> para volver al sitio.</center>";
	}
	
	else if( !isset($_POST['modify']) ) {
		
		echo '<center><p>Vista Previa</p></center>';
		echo '<minititle><p id="previewTitle"></p></minititle>';
		echo '<p id="preview"></p>';
		
		echo '<center>';
		echo '<p>Nueva Noticia</p>';
		echo '<form method="post" action="/modify/index.php">';
			echo '<input type="hidden" name="validation" value="ok" \>';
			echo '<input type="hidden" name="action" value="'.$_POST['action'].'" \>';
			echo '<input type="hidden" name="section" value="index.php" \>';
			echo '<input type="hidden" name="url" value="'.$_POST['url'].'" \>';
			echo '<p>T&iacute;tulo: <input type="text" id="titleCode" name="title" value="" \></p>';
			echo '<p><textarea id="htmlCode" name="content" cols="80" style="overflow:auto;" rows="30"></textarea></p>';
			echo '<p>Contenido de la noticia: <input type="text" name="info" value="" \></p>';
			echo '<p><input type="submit" name="modify" value="Enviar" /></p>';
		echo '</form>';
		echo '</center>';
			
		echo "<script> 
			$(function(){
				$('#titleCode').keyup(function(){
				  $('#previewTitle').html($(this).val());
				});
			});	
			$(function(){
				$('#htmlCode').keyup(function(){
				  $('#preview').html($(this).val());
				});
			});
			</script>";
	}
	
	else {
		$subject = "Intento de nueva noticia fallido.";
		$content = "";
		
		if( !isset($_POST['content']) || $_POST['content'] == "" ) 
			$content = $content."No quiso poner contenido a la noticia. <br />";
		
		if( !isset($_POST['title']) || $_POST['title'] == "" )
			$content = $content."No quiso poner t&iacute;tulo a la noticia. <br />";
		
		if( !isset($_POST['info']) || $_POST['info'] == "" )	
			$content = $content."No quiso poner de que trata la noticia. <br />";
				
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