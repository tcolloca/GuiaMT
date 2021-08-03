<?php

$allPortals = array( 
array( '.12.1I.', '&side=I&sub0=12&sub1=1&sub2=', 18, 'Cripta frambuesada' ),
array( '.12.1I.18.', '&side=I&sub0=12&sub1=1&sub2=18&sub3=', 5, 'La verdadera tumba de Tub&eacute;rculo'));

for( $h = 0; $h <= 1; $h++ )
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