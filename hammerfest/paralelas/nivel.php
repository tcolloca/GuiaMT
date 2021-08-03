<center>
<p>
<?php
$main=$_GET['main'];
$portal=$_GET['portal'];
$sub=$_GET['sub'];
$http="/";
define(ROOT, $_SERVER['DOCUMENT_ROOT']);

if( ( !isset($_GET['sub']) && file_exists( ROOT."/images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".jpg" ) ) ||
( isset($_GET['sub']) && $main != 33 && 
	file_exists( ROOT."/images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".".($sub+1).".jpg" ) ) ||
( isset($_GET['sub']) && $main == 33 && 
file_exists( ROOT."/images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal).".".($sub).".jpg" ) ) )
{
if ( !isset($_GET['sub']) && $main != 33 )
{
$bportal=$portal-1;
$nportal=$portal+1;
$lvl=$main.".".$portal;
$blvl=$main.".".$bportal;
$nlvl=$main.".".$nportal;
$nnlvl=$main.".".($nportal+1);

$ext1 = $ext2 = $ext3 = "jpg";

switch( $blvl )
{
	case 55.0:
	case 92.0:
		$ext1 = "gif";
}

switch( $lvl )
{
	case 55.0:
	case 92.0:
		$ext2 = "gif";
}

switch( $blvl )
{
	case 55.0:
	case 92.0:
		$ext3 = "gif";
}


$imgurl="images/hammerfest/paralelas/paralela_".$main."/".$nlvl.".".$ext1."";
$bimgurl="images/hammerfest/paralelas/paralela_".$main."/".$lvl.".".$ext2."";
$nimgurl="images/hammerfest/paralelas/paralela_".$main."/".$nnlvl.".".$ext3."";

switch ( $main )
{
	case 0:
		$returnlvl=0;
		break;
	case 2:
		$returnlvl=11;
		break;
	case 3:
		$returnlvl=5;
		break;
	case 6:
		$returnlvl=7;
		break;
	case 11:
		$returnlvl=14;
		break;
	case 13:
		$returnlvl=16;
		break;
	case 15:
		$returnlvl=20;
		break;
	case 16:
		$returnlvl=17;
		break;
	case 23:
		$returnlvl=24;
		break;
	case 25:
		$returnlvl=27;
		break;
	case 26:
		$returnlvl=29;
		break;
	case 42:
		$returnlvl=45;
		break;
	case 43:
		$returnlvl=44;
		break;
	case 46:
		$returnlvl=50;
		break;
	case 51:
		$returnlvl=52;
		break;
	case 55:
		$returnlvl=56;
		break;
	case 62:
		$returnlvl=69;
		break;
	case 63:
		$returnlvl=65;
		break;
	case 64:
		$returnlvl=65;
		break;
	case 72:
		$returnlvl=75;
		break;
	case 74:
		$returnlvl=76;
		break;
	case 77:
		$returnlvl=80;
		break;
	case 82:
		$returnlvl=83;
		break;
	case 92:
		$returnlvl=93;
		break;
	case 97:
		$returnlvl=99;
		break;
}


if ( file_exists( ROOT."/".$bimgurl ) )
	$burl="/hammerfest/paralelas/?main=".$main."&portal=".$bportal;
else
{
	$burl="/hammerfest/niveles/?main=".$main;
	$blvl=$main;
}
	
if ( file_exists( ROOT."/".$nimgurl ) )
	$nurl="/hammerfest/paralelas/?main=".$main."&portal=".$nportal;
else
{
	$nurl="/hammerfest/niveles/?main=".$returnlvl;
	$nlvl=$returnlvl;
}

echo "	
<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
	<tr>
		<td align='left' width='245px'><b><a href='".$burl."'><font color='#233243' size='+1'><< Nivel ".$blvl."</font></a></b></td>
		<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
		<td align='right' width='245px'><b><a href='".$nurl."'><font color='#233243' size='+1'>Nivel ".$nlvl." >></font></a></b></td>
   	</tr>
</table></p>";

/* PRINTEA IMAGENES */


	echo "<p><img src='".$http.$imgurl."' \>";
	echo "</p></center>";
	include ( ROOT.'/hammerfest/comentarios/'.$lvl.'.html' );
	include_once(ROOT."/functions/database_management.php" );
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

/* SUBPARALELAS */
}
else if ( $main != 33 )
{

$bsub=$sub-1;
$nsub=$sub+1;
$nnsub=$sub+2;
$lvl=$main.".".$portal.".".$sub;
$blvl=$main.".".$portal.".".$bsub;
$nlvl=$main.".".$portal.".".$nsub;
$imgurl="images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".".$nsub.".jpg";
$bimgurl="images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".".$sub.".jpg";
$nimgurl="images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".".$nnsub.".jpg";

switch ( $main )
{
	case 15: /*15.11*/
		$returnportal=12; /*15.12*/
		break;
	case 72: /*72.1*/
		$returnportal=2 /*72.2*/;
		break;
}

if ( file_exists( ROOT."/".$bimgurl ) )
	$burl="/hammerfest/paralelas/?main=".$main."&portal=".$portal."&sub=".$bsub;
else
{
	$burl="/hammerfest/paralelas/?main=".$main."&portal=".$portal;
	$blvl=$main.".".$portal;
}
	
if ( file_exists( ROOT."/".$nimgurl ) )
	$nurl="/hammerfest/paralelas/?main=".$main."&portal=".$portal."&sub=".$nsub;
else
{
	$nurl="/hammerfest/paralelas/?main=".$main."&portal=".$returnportal;
	$nlvl=$main.".".$returnportal;
}

echo "	
<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
	<tr>
		<td align='left' width='245px'><b><a href='".$burl."'><font color='#233243' size='+1'><< Nivel ".$blvl."</font></a></b></td>
		<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
		<td align='right' width='245px'><b><a href='".$nurl."'><font color='#233243' size='+1'>Nivel ".$nlvl." >></font></a></b></td>
   	</tr>
</table></p>";

	echo "<p><img src='".$http.$imgurl."' \></p></center>";
	include ( ROOT.'/hammerfest/comentarios/'.$lvl.'.html' );
	include_once(ROOT."/functions/database_management.php" );
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

} 

/* PARALELA 33 */

else
{

$lvl=$main.".".$portal.".".$sub;
$imgurl="/images/hammerfest/paralelas/paralela_33/".$lvl.".jpg";

switch ( $portal )
{
	case 0:
		switch ( $sub )
		{
			case 0:
				$burl="/hammerfest/niveles/?main=33";
				$nurl="/hammerfest/paralelas/?main=33&portal=1&sub=0";
				$blvl="33";
				$nlvl="33.1.0";
				break;
			case 1:
				$burl="/hammerfest/paralelas/?main=33&portal=1&sub=1";
				$nurl="/hammerfest/paralelas/?main=33&portal=1&sub=1";
				$blvl="33.1.1";
				$nlvl="33.1.1";
				break;
			case 2:
				$burl="/hammerfest/paralelas/?main=33&portal=1&sub=2";
				$nurl1="/hammerfest/paralelas/?main=33&portal=0&sub=3";
				$nurl2="/hammerfest/paralelas/?main=33&portal=2&sub=0";
				$blvl="33.1.2";
				$nlvl1="33.0.3";
				$nlvl2="33.2.0";
				echo "	
				<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
				<tr>
				<td align='left' width='245px'><b><a href='".$burl."'><font color='#233243' size='+1'><< Nivel ".$blvl."</font></a></b></td>
				<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
				<td align='right' width='245px'><b><a href='".$nurl1."'><font color='#233243' size='+1'>Nivel ".$nlvl1." >>(Abajo)</font></a>	
				</b>
				<br /><b><a href='".$nurl2."'><font color='#233243' size='+1'>Nivel ".$nlvl2." >>(Izquierda)</font></a></b></td>
   				</tr>
				</table></p>";

				echo "<p><img src='".$imgurl."' \></p></center>";
				include ( ROOT.'/hammerfest/comentarios/'.$lvl.'.html' );
				include_once(ROOT."/functions/database_management.php" );
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
				break;
			case 3:
				$burl="/hammerfest/paralelas/?main=33&portal=0&sub=2";
				$nurl="/hammerfest/paralelas/?main=33&portal=0&sub=2";
				$blvl="33.0.2";
				$nlvl="33.0.2";
				break;
		}
		break;
	case 1:
		switch ( $sub )
		{
			case 0:
				$burl="/hammerfest/paralelas/?main=33&portal=0&sub=0";
				$nurl="/hammerfest/paralelas/?main=33&portal=1&sub=1";
				$blvl="33.0.0";
				$nlvl="33.1.1";
				break;
			case 1:
				$burl="/hammerfest/paralelas/?main=33&portal=1&sub=0";
				$nurl1="/hammerfest/paralelas/?main=33&portal=0&sub=1";
				$nurl2="/hammerfest/paralelas/?main=33&portal=1&sub=2";
				$blvl="33.1.0";
				$nlvl1="33.0.1";
				$nlvl2="33.1.2";
				echo "	
				<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
				<tr>
				<td align='left' width='245px'><b><a href='".$burl."'><font color='#233243' size='+1'><< Nivel ".$blvl."</font></a></b></td>
				<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
				<td align='right' width='245px'><b><a href='".$nurl1."'><font color='#233243' size='+1'>Nivel ".$nlvl1." >>(Izquierda)</font>
				</a></b>
				<br /><b><a href='".$nurl2."'><font color='#233243' size='+1'>Nivel ".$nlvl2." >>(Abajo)</font></a></b></td>
   				</tr>
				</table></p>";

				echo "<p><img src='".$imgurl."' \></p></center>";
				include ( ROOT.'/hammerfest/comentarios/'.$lvl.'.html' );
				include_once(ROOT."/functions/database_management.php" );
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
				break;
			case 2:
				$burl="/hammerfest/paralelas/?main=33&portal=1&sub=1";
				$nurl="/hammerfest/paralelas/?main=33&portal=0&sub=2";
				$blvl="33.1.1";
				$nlvl="33.0.2";
				break;
		}
		break;
	case 2:
		$burl="/hammerfest/paralelas/?main=33&portal=0&sub=2";
		$nurl="/hammerfest/paralelas/?main=33&portal=0&sub=0";
		$blvl="33.0.2";
		$nlvl="33.0.0";
		break;
}

	if ( !( $portal == 0 && $sub == 2 ) && !( $portal == 1 && $sub == 1 ) )
	{
		echo "	
<table cellpadding='10px' width='735px' style='font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:14px'>
	<tr>
		<td align='left' width='245px'><b><a href='".$burl."'><font color='#233243' size='+1'><< Nivel ".$blvl."</font></a></b></td>
		<td align='center' width='245px'><b><font color='#233243' size='+2'>Nivel ".$lvl."</font></b></td>
		<td align='right' width='245px'><b><a href='".$nurl."'><font color='#233243' size='+1'>Nivel ".$nlvl." >></font></a></b></td>
   	</tr>
</table></p>";

		echo "<p><img src='".$imgurl."' \></p></center>";
		include ( ROOT.'/hammerfest/comentarios/'.$lvl.'.html' );
		include_once(ROOT."/functions/database_management.php" );
		
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
	}
}
} else
	echo "<simpletext><font size='+2'><b>¿¡QUÉ HACES AQUÍ!? ¡ESTA PÁGINA NO EXISTE!</b></font><p><i>Huyes velozmente antes de que aparezca quien ya tú sabes...</i></p></simpletext>";
?>