<?php

$allPortals = array( 
array( '.12.1.9I.', '&side=I&sub0=12&sub1=1&sub2=9&sub3=', 4, '&iexcl;La entrada a la tumba!' ),
array( '.12.1.9I.3.', '&side=I&sub0=12&sub1=1&sub2=9&sub3=3&sub4=', 17, 'La tomba de Tub&eacute;rculo'),
array( '.12.1.9I.3.10.', '&side=I&sub0=12&sub1=1&sub2=9&sub3=3&sub4=10&sub5=', 10, 'Escondite de los Consejeros'));

for( $h = 0; $h <= 2; $h++ )
{
	$fixLvl = $allPortals[$h][0];
	$fixUrl = $allPortals[$h][1];
	$max= $allPortals[$h][2];
	$title = $allPortals[$h][3];
	
	echo '<a name="p54'.substr_replace($fixLvl ,"",-1).'"></a>
	<minititle>'.$title.'</minititle>';
	
	echo '<table cellpadding="15px" align="center">';
	
	if( $title == 'Los infiernos / Mezclador de Sacha / Pozo de los Zames' )
	{
		$last = 1;
		$max++;
	}
	else
		$last = 0;
	
	for( $i=1 ; $i<=5 && $last < $max; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 && $last < $max; $j++ )
		{
			echo "<td align='center' valign='middle' width='77px'><a href='/hammerfest/infiernos/?main=54".$fixUrl.$last."'><img src='/images/hammerfest/infiernos/paralela_54/54".$fixLvl.$last.".jpg' width='70px' height='90px' /></a>
			<p><simpletext><font size='-2'><b>Nivel 54".$fixLvl.$last."</b></font></simpletext></p></td>";
			$last++;
		}
		
		while( $j<=6 )
		{
			echo "<td width='70px'></td>";
			$j++;
		}
		
		echo "</tr>";
	}
	echo '</table>';
}

?>