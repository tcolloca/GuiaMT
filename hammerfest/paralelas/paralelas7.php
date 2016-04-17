<?php

$allPortals = array( 
array( 64, 5, 'Escarcha inferior...'),
array( 72, 5, 'Los Gatotoro'));

for( $h = 0; $h <= 1; $h++ )
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

	$specialPortal = array( 72, 5, 'Los Gatotoro (Sub-Dimensional)' );

	$main = $specialPortal[0];
	$max= $specialPortal[1];
	$title = $specialPortal[2];
	$portal = 1;
	
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

$allPortals = array( 
array( 74, 1, 'Prototipo Secreto Tuberqui'),
array( 77, 5, 'Antro de los Geluloz'),
array( 82, 5, 'Diffiland'));

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