<?php

			/* displayArray */
/*
** Parameters: $array to be displayed.
** Description: Shows the array as a table, with all of its internal arrays.
** Return value: Void. 
*/

function displayArray( $matrix )
{
	if ( gettype( $matrix == 'array' ) )
		if ( !count($matrix) )
			echo "<font color='red'>Matriz Vacia.</font><br />";
		else
			echo "<table border='1' width='100%' cellpadding='5'>\n";
		echo "<tr bgcolor='orange'>\n";
		echo "<th>Indice</th>\n";
		echo "<th>Valor</th>\n";
		echo "</tr>\n";
		foreach ( $matrix as $index => $value )
		{
			echo "<tr>\n";
			echo "<td align='left' valign='middle'>", $index, "</td>\n";
			echo "<td align='left' valign='middle'>";
			if ( gettype( $value ) != 'array' )
				echo $value;
			else
			 displayArray( $value );
			echo "</td>\n";
			echo "</tr>\n";
		}
	echo "</table>\n";
}
?>
		