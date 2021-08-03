<?php

define( ROOT, $_SERVER['DOCUMENT_ROOT']);

function nameLevel( &$level )
{
	$flag = true;
	for( $i = 0; $flag; $i++ )
	{
		if( isset( $_GET['sub'.$i] ) )
		{
			if( $i == 2 && isset( $_GET['side'] ) && ( !isset( $_GET['sub3'] ) || $_GET['sub2'] == 18 ) )
				$level = $level."I.".$_GET['sub2'];
			else if( $i == 3 && isset( $_GET['side'] ) && $_GET['sub2'] == 9 )
				$level = $level."I.".$_GET['sub3'];
			else
				$level = $level.".".$_GET['sub'.$i];
		}
		else
			$flag = false;
	}
	return $i;
}

$level = 54;
if( !isset( $_GET['sub1'] ) )	
{
	$_GET['sub0']++;
	nameLevel( $level );
	$_GET['sub0']--;
}
else
	nameLevel( $level );

$imgUrl = "/images/hammerfest/infiernos/paralela_54/".$level.".jpg";
$level = 54;
$lastSub = nameLevel( $level ) - 2;
$alt1Txt = "";

switch( $level )
{
	case '54.12.1.9I.3.0':
	case '54.12.1.9I.3.1':
	case '54.12.1.9I.3.6':
		$imgUrl = "/images/hammerfest/infiernos/paralela_54/".$level.".gif";
}


if( $level == 54.1 || $level == 54.10 )
	str_replace( '.', ',', $level );

if( file_exists( ROOT.$imgUrl ) )
{
	$halfUrl = "/hammerfest/infiernos/?main=54";
	
	switch( $level )
	{
		case '54.0':
			$bUrl = "/hammerfest/niveles/?main=54";
			$nUrl = $halfUrl."&sub0=1";
			$bLvl = '54';
			$nLvl = '54.1';
			break;
		case "54,1":
			$bUrl = $halfUrl."&sub0=0";
			$nUrl = $halfUrl."&sub0=2";
			$bLvl = '54.0';
			$nLvl = '54.2';
			str_replace( ',', '.', $level );
			break;
		case "54,10":
			$bUrl = $halfUrl."&sub0=9";
			$nUrl = $halfUrl."&sub0=11";
			$altUrl = "/hammerfest/niveles/?main=55";
			$bLvl = '54.9';
			$nLvl = '54.11';
			$altLvl = '55';
			$alt1Txt = "(Camino indicado)";
			$alt2Txt = "(Portal)";
			str_replace( ',', '.', $level );
			break;
		case '54.12':
			$bUrl = $halfUrl."&sub0=11";
			$nUrl = $halfUrl."&sub0=12&sub1=0";
			$bLvl = '54.11';
			$nLvl = '54.12.0';
			break;
		case '54.12.0':
			$bUrl = $halfUrl."&sub0=12";
			$nUrl = $halfUrl."&sub0=12&sub1=1";
			$bLvl = '54.12';
			$nLvl = '54.12.1';
			break;
		case '54.12.1':
			$bUrl = $halfUrl."&sub0=12&sub1=0";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=0";
			$altUrl = $halfUrl."&sub0=12&sub1=1&sub2=0&side=I";
			$bLvl = '54.12.0';
			$nLvl = '54.12.1.0';
			$altLvl = '54.12.1I.0';
			$alt1Txt = "(Derecha)";
			$alt2Txt = "(Izquierda)";
			break;
		case '54.12.1.0':
			$bUrl = $halfUrl."&sub0=12&sub1=1";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=1";
			$bLvl = '54.12.1';
			$nLvl = '54.12.1.1';
			break;
		case '54.12.1I.0':
			$bUrl = $halfUrl."&sub0=12&sub1=1";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=1&side=I";
			$bLvl = '54.12.1';
			$nLvl = '54.12.1I.1';
			break;
		case '54.12.1.9':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=8";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=0&side=I";
			$altUrl = "/hammerfest/niveles/?main=55";
			$bLvl = '54.12.0';
			$nLvl = '54.12.1.9I.0';
			$altLvl = '55';
			$alt1Txt = "(Izquierda)";
			$alt2Txt = "(Derecha)";
			break;
		case '54.12.1.9I.0':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=1&side=I";
			$bLvl = '54.12.1.9';
			$nLvl = '54.12.1.9I.1';
			break;
		case '54.12.1.9I.3':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=2&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=0&side=I";
			$bLvl = '54.12.1.9I.2';
			$nLvl = '54.12.1.9I.3.0';
			break;
		case '54.12.1.9I.3.0':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=1&side=I";
			$bLvl = '54.12.1.9I.3';
			$nLvl = '54.12.1.9I.3.1';
			break;
		case '54.12.1.9I.3.10':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=9&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=11&side=I";
			$altUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=10&sub5=0&side=I";
			$bLvl = '54.12.1.9I.3.9';
			$nLvl = '54.12.1.9I.3.11';
			$altLvl = '54.12.1I.10.0';
			$alt1Txt = "(Siguiente nivel)";
			$alt2Txt = "(Ubicar bomba y entrar al Portal)";
			break;
		case '54.12.1.9I.3.10.0':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=10&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=10&sub5=1&side=I";
			$bLvl = '54.12.1.9I.3.10';
			$nLvl = '54.12.1.9I.3.10.1';
			break;
		case '54.12.1.9I.3.10.9':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=10&sub5=8&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=11&side=I";
			$bLvl = '54.12.1.9I.3.10.8';
			$nLvl = '54.12.1.9I.3.11';
			break;
		case '54.12.1.9I.3.16':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=9&sub3=3&sub4=15&side=I";
			$nUrl = "/hammerfest/niveles/?main=55";
			$bLvl = '54.12.1.9I.3.15';
			$nLvl = '55';
			break;
		case '54.12.1I.17':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=16&side=I";
			$nUrl = "/hammerfest/niveles/?main=55";
			$altUrl = $halfUrl."&sub0=12&sub1=1&sub2=18&side=I";
			$bLvl = '54.12.1I.16';
			$nLvl = '55';
			$altLvl = '54.12.1I.18';
			$alt1Txt = "(Portal)";
			$alt2Txt = "(Sólo en Modo Pesadilla explotando la piedra)";
			break;
		case '54.12.1I.18':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=17&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=18&sub3=0&side=I";
			$bLvl = '54.12.1I.17';
			$nLvl = '54.12.1I.18.0';
			break;
		case '54.12.1I.18.0':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=18&side=I";
			$nUrl = $halfUrl."&sub0=12&sub1=1&sub2=18&sub3=1&side=I";
			$bLvl = '54.12.1I.18';
			$nLvl = '54.12.1I.18.1';
			break;
		case '54.12.1I.18.4':
			$bUrl = $halfUrl."&sub0=12&sub1=1&sub2=18&sub3=3&side=I";
			$nUrl = "/hammerfest/niveles/?main=55";
			$bLvl = '54.12.1I.18.3';
			$nLvl = '55';
			break;
		default:
			$bUrl = $halfUrl;
			$bLvl = 54;
			$nLvl = 54;
			
			for( $i = 0; $i < $lastSub; $i++ )
			{
				$bUrl = $bUrl."&sub".$i."=".$_GET['sub'.$i];
			}
			$nUrl = $bUrl."&sub".$lastSub."=".($_GET['sub'.$lastSub] + 1);
			$bUrl = $bUrl."&sub".$lastSub."=".($_GET['sub'.$lastSub] - 1);
			if( isset( $_GET['side'] ) )
			{
				$bUrl = $bUrl."&side=I";	
				$nUrl = $nUrl."&side=I";	
			}
			$_GET['sub'.$lastSub]--;
			nameLevel( $bLvl );
			$_GET['sub'.$lastSub] += 2;
			nameLevel( $nLvl );
	}
	echo "	
		<p><table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
			<tr>
				<td align='left' width='245px'><b><a href='".$bUrl."'><font color='#233243'><< Nivel ".$bLvl."</font></a></b></td>
				<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$level."</font></b></td>
				<td align='right' width='245px'><b><a href='".$nUrl."'><font color='#233243'>Nivel ".$nLvl." >><br></font><font color='#233243' size='-1'>".$alt1Txt."</font></a>				
			</b>";
	if( isset( $altUrl ) )
		echo "<br /><b><a href='".$altUrl."'><font color='#233243'>Nivel ".$altLvl." >><br></font><font color='#233243' size='-1'>".$alt2Txt."</font></a></b>";
	echo "
			</td>	
   			</tr>
		</table></p>";

	echo "<center><p><img src='".$imgUrl."' \></p></center>";
	include ( ROOT.'/hammerfest/comentarios/'.$level.'.html' );
	include_once( ROOT."/functions/database_management.php" );
	
	if($level == '54.12.1.9') {
		if(isset($_SESSION['username']) && isEventOn("easter2014") && hasEvent("easter2014", $_SESSION['username'])) {
			if(hasObject("easter2014", $_SESSION['username'], "mechatom")) {
				echo '<form method="POST" action="/index.php?id=your_events&event=easter2014"><input type="hidden" name="validation" value="0111020101" /><input type="image" src="/images/events/easter2014/binary_egg_small.png" alt="¡Hallar huevo!" /></form>';
			}
		}
	}
	
	
	if(isStaff($_SESSION['username'])) 
	{ 		
	 	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
	 	echo "<form method='post' action='/modify/index.php' \>			
        <input type='hidden' name='section' value='hammerfest' />                 
        <input type='hidden' name='action' value='comment' />
		<input type='hidden' name='url' value='".$url."' />
        <input type='hidden' name='level' value='".$level."' />
        <input type='hidden' name='preview' value='1' />
		<input type='submit' name='tryToModify' value='Editar' /></form>";
	}


}
else
	echo "<center><simpletext><font size='+2'><b>¿¡QUÉ HACES AQUÍ!? ¡ESTA PÁGINA NO EXISTE!</b></font><p><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></simpletext></center>";

?>

