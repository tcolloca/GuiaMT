<?php

//Page title is made up with this code.
if( $game == "" ) {
	if( isset( $_GET['id'] ) && $_GET['id'] != 'news' ) {
		$gameTitle = $_GET['id'];
		$gameTitle = str_replace( '_', ' ', $gameTitle );
		$gameTitle = ucfirst( $gameTitle );		
	}
	else
		$gameTitle = 'Noticias';
	$game = "";
} else {
	$gameTitle = ucfirst( $game );
	$gameTitle = str_replace( '_', ' ', $gameTitle );
	if( $gameTitle == "Kadokado" )
			$gameTitle = "KadoKado";
		else if( $gameTitle == "Alphabounce" )
			$gameTitle = "AlphaBounce";
		else if( $gameTitle == "Monstruhotel" )
			$gameTitle = "MonstruHotel";
		else if( $gameTitle == "Monstruhotel2" )
			$gameTitle = "MonstruHotel 2";
		else if( $gameTitle == "Dinorpg" )
			$gameTitle = "DinoRPG";
		else if( $gameTitle == "Elbruto" )
			$gameTitle = "ElBruto";	
		else if( $gameTitle == "Teacher story" )
			$gameTitle = "Teacher Story";
		else if( $gameTitle == "Dead cells" )
			$gameTitle = "Dead Cells";
		else if( $gameTitle == "Street writer" )
			$gameTitle = "Street Writer";	
		else if( $gameTitle == "Rockfaller journey" )
			$gameTitle = "Rockfaller Journey";
}
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Guia de juegos de Motion Twin" />
    <meta name="keywords" content="guia, MT, twinoid, juegos, español, kube, rockfaller_journey, hammerfest, mush, zombinoia, alphabounce,
    kadokado, monstruhotel, monstruhotel2, arkadeo, teacherstory, snake, carapass, kingdom, minitroopers, dinorpg, elbruto, drakarnage, fever" />
    
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/style.php?v=<?=time();?>&game=<?=$game?>">
    
    <title>Gu&iacute;a MT | <?php echo $gameTitle; ?></title>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script> 
</head>