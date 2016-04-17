<center>
<p>
<?php

$url = "/hammerfest/niveles/?main=";
$lvl = $_GET['main'];

if( 0 <= $lvl && $lvl <= 99 )
{
switch ( $lvl )
{
	case 0:
		echo "
		
<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
	<tr>
		<td align='left' width='245px'></td>
		<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
        <td align='right' width='245px'><b><a href='".$url.($lvl+1)."'><font color='#233243' size='+1'>Nivel ".($lvl+1)." >></font></a></b></td>
   	</tr>
</table>";

		break;
	case 99:
		echo "
		
<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
	<tr>
		<td align='left' width='245px'><b><a href='".$url.($lvl-1)."'><font color='#233243' size='+1'><< Nivel ".($lvl-1)."</font></a></b></td>
		<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
		<td align='right' width='245px'></td>
   	</tr>
</table>";

		break;
	default:
		echo "
			
<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
	<tr>
		<td align='left' width='245px'><b><a href='".$url.($lvl-1)."'><font color='#233243' size='+1'><< Nivel ".($lvl-1)."</font></a></b></td>
		<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
		<td align='right' width='245px'><b><a href='".$url.($lvl+1)."'><font color='#233243' size='+1'>Nivel ".($lvl+1)." >></font></a></b></td>
   	</tr>
</table>";
}
?>
</p>

<?php
	$url="/images/hammerfest/niveles/";
	if ( $lvl < 60 )
		$imgurl=$url.$lvl.".jpg";
	else
		$imgurl=$url.$lvl.".JPG";
	if( $lvl == 107 )
		$imgurl=$url.$lvl.".gif";
	echo "<p><img src='".$imgurl."' \></p></center>";
	include ( '../comentarios/'.$lvl.'.html' );
}
else
	echo "<simpletext><font size='+2'><b>¿¡QUÉ HACES AQUÍ!? ¡ESTA PÁGINA NO EXISTE!</b></font><p><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></p></simpletext>";
	
	include_once(ROOT."/functions/database_management.php");
	if(isStaff($_SESSION['username'])) 
	{ 		
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
	 	echo "<form method='post' action='/modify/index.php' \>			
        <input type='hidden' name='section' value='hammerfest' />                 
        <input type='hidden' name='action' value='comment' />
		<input type='hidden' name='url' value='".$url."' />
        <input type='hidden' name='level' value='".$lvl."' />
        <input type='hidden' name='preview' value='1' />
		<input type='submit' name='tryToModify' value='Editar' /></form>";
	}
?>


