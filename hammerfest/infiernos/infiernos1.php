<?php

$allPortals = array( 
array( '.', '&sub0=', 13, 'Los infiernos / Mezclador de Sacha / Pozo de los Zames'),
array( '.12.', '&sub0=12&sub1=', 2, 'La taberna de las frambuesas'),
array( '.12.1.', '&sub0=12&sub1=1&sub2=', 10, 'Metal que grita'));

for( $h = 0; $h <= 2; $h++ )
{
	$fixLvl = $allPortals[$h][0];
	$fixUrl = $allPortals[$h][1];
	$max = $allPortals[$h][2];
	$title = $allPortals[$h][3];
	
	echo '<a name="p54'.substr_replace($fixLvl ,"",-1).'"></a>
	<minititle>'.$title.'</minititle>';
	
	echo '<table cellpadding="25px" align="center">';
	
	if( $title == 'Los infiernos / Mezclador de Sacha / Pozo de los Zames' )
	{
		$last = 1;
		$realLast = 0;
		$max++;
	}
	else{
		$last = 0;
		$realLast = 0;
	}
	
	for( $i=1 ; $i<=5 && $last < $max; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 && $last < $max; $j++ )
		{
			echo "<td align='center' valign='middle' width='77px'><a href='/hammerfest/infiernos/?main=54".$fixUrl.$realLast."'><img src='/images/hammerfest/infiernos/paralela_54/54".$fixLvl.$last.".jpg' width='70px' height='90px' /></a>
			<p><simpletext>Nivel 54".$fixLvl.$realLast."</simpletext></p></td>";
			$last++;
			$realLast++;
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