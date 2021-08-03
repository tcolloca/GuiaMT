<?php

$allPortals = array( 
array( 11, 5, 'Mechayame'),
array( 13, 5, 'Irish Coffee'),
array( 15, 13, 'Madera-Bonita de Giraflor'));

for( $h = 0; $h <= 2; $h++ )
{
	$main = $allPortals[$h][0];
	$max= $allPortals[$h][1];
	$title = $allPortals[$h][2];
	
	echo '<a name="p'.$main.'"></a>
	<minititle>'.$title.'</minititle>';
	
	echo '<table cellpadding="25px" align="center">';
	
	$portal = 0;
	
	for( $i=1 ; $i<=5 && $portal < $max; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 && $portal < $max; $j++ )
		{
			echo "<td align='center' valign='middle' width='77px' ><a href='/hammerfest/paralelas/?main=".$main."&portal=".$portal."'><img src='/images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".jpg' width='70px' height='90px' /></a>
			<p><simpletext>Nivel ".$main.".".$portal."</simpletext></p></td>";
			$portal++;
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

	$specialPortal = array( 15, 10, 'Madera-Bonita de Giraflor (Sub-Dimensional)' );

	$main = $specialPortal[0];
	$max= $specialPortal[1];
	$title = $specialPortal[2];
	$portal = 11;
	
	echo '<a name="p'.$main.'.'.$portal.'"></a>
	<minititle>'.$title.'</minititle>';
	
	echo '<table cellpadding="25px" align="center">';
	
	$sub = 0;
	
	for( $i=1 ; $i<=5 && $sub < $max; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 && $sub < $max; $j++ )
		{
			echo "<td align='center' valign='middle' width='77px'><a href='/hammerfest/paralelas/?main=".$main."&portal=".$portal."&sub=".$sub."'><img src='/images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".".($sub+1).".jpg' width='70px' height='90px' /></a>
			<p><simpletext>Nivel ".$main.".".$portal.".".$sub."</simpletext></p></td>";
			$sub++;
		}
		
		while( $j<=6 )
		{
			echo "<td width='70px'></td>";
			$j++;
		}
		
		echo "</tr>";
	}
	echo '</table>';

?>