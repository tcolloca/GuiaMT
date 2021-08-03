<?php

if( isset($_POST['modifications']) )
	$new = 'modificacion: '.$_POST['modifications'];
else
	$new = 'contenido: '.$_POST['info'];

$date = date("Y-m-d H:i:s");
$user = $_SESSION['username'];
$url = $_POST['url'];

addModification($date, $user, $new, $url);

?>
