<p>
<subtitle>
Niveles
</subtitle>
</p>
<pre>

</pre>
<center><b><a href="?show=0"><font color="#233243">Niveles 0-29</font></a> | <a href="?show=1"><font color="#233243">Niveles 30-59</font></a>  | <a href="?show=2"><font color="#233243">Niveles 60-99</font></a></b></center>
<pre>

</pre>
<simpletext>
<i><b>Nota:</b> En todos los niveles, la E indica el lugar de aparici&oacute;n m&aacute;s frecuente del objeto de efecto, y la P del de punto. Para conseguir las distintas monedas secretas , Igor deber&aacute; pasar por donde hay alguna "moneda" en la imagen. Por &uacute;ltimo, Salida o Portal indican d&oacute;nde aparecer&aacute; el Portal, y el s&iacute;mbolo de una bomba, donde es necesario ponerla para que este aparezca.</i></simpletext>
<pre>

</pre>
<table cellpadding="25px" align="center">
<?php

switch ( $_GET['show'])
{
	case 0: 
	$k=0;
	for( $i=1 ; $i<=5 ; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 ; $j++ )
		{
			echo "<td align='center' valign='middle'><a href='/hammerfest/niveles/?main=".$k."'><img src='/images/hammerfest/niveles/".$k.".jpg' width='70px' height='90px' /></a>
			<p>Nivel ".$k."</p></td>";
			$k++;
		}
		echo "</tr>";
	}
	break;
	case 1:
	$k=30;
	for( $i=1 ; $i<=5 ; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 ; $j++ )
		{
			echo "<td align='center' valign='middle'><a href='/hammerfest/niveles/?main=".$k."'><img src='/images/hammerfest/niveles/".$k.".jpg' width='70px' height='90px' /></a>
			<p>Nivel ".$k."</p></td>";
			$k++;
		}
		echo "</tr>";
	}
	break;
	case 2: 
	$k=60;
	for( $i=1 ; $i<=6 ; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 ; $j++ )
		{
			echo "<td align='center' valign='middle'><a href='/hammerfest/niveles/?main=".$k."'><img src='/images/hammerfest/niveles/".$k.".JPG' width='70px' height='90px' /></a>
			<p>Nivel ".$k."</p></td>";
			$k++;
		}
		echo "</tr>";
	}
	for( $j=1 ; $j<=4 ; $j++ )
		{
			echo "<td align='center' valign='middle'><a href='/hammerfest/niveles/?main=".$k."'><img src='/images/hammerfest/niveles/".$k.".JPG' width='70px' height='90px' /></a>
			<p>Nivel ".$k."</p></td>";
			$k++;
		}
	break;
}
?>
</table>
			

