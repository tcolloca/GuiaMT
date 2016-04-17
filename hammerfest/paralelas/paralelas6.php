<?php

$allPortals = array( 
array( 51, 11, 'Templo pre-tuberculiano'),
array( 54, 13, 'Los infiernos'),
array( 55, 1, '&iexcl;Trampa de cristal!'),
array( 62, 8, 'Grutas del looping'),
array( 63, 3, 'Base secreta de Tuber'));

for( $h = 0; $h <= 4; $h++ )
{
	$main = $allPortals[$h][0];
	$max= $allPortals[$h][1];
	$title = $allPortals[$h][2];
	if( $main == 54 )
	{
		$string = 'infiernos';
		$div = 'sub0';
	}
	else
	{
		$string = 'paralelas';
		$div = 'portal';
	}
	
	echo '<a name="p'.$main.'"></a>
	<minititle>'.$title.'</minititle>';
	
	echo '<table cellpadding="25px" align="center">';
	
	$portal = 0;
	
	for( $i=1 ; $i<=5 && $portal < $max; $i++ )
	{
		echo "<tr>";	
		for( $j=1 ; $j<=6 && $portal < $max; $j++ )
		{
			echo "<td align='center' valign='middle' width='77px'><a href='/hammerfest/".$string."/?main=".$main."&".$div."=".$portal."'><img src='/images/hammerfest/".$string."/paralela_".$main."/".$main.".".($portal+1).".jpg' width='70px' height='90px' /></a>
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