<?php

$allPortals = array( 
array( 42, 5, 'Ayamedium'),
array( 43, 9, 'BubbleFest'),
array( 46, 8, 'La troglogruta'));

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
			echo "<td align='center' valign='middle' width='77px'><a href='/hammerfest/paralelas/?main=".$main."&portal=".$portal."'><img src='/images/hammerfest/paralelas/paralela_".$main."/".$main.".".($portal+1).".jpg' width='70px' height='90px' /></a>
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

?>