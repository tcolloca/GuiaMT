<p>
<subtitle>
Las dimensiones paralelas
</subtitle>
</p>
<pre>

</pre>

<?php
	$show = $_GET['show'];
	if( $show == 0 )
			include( 'intro.html' );
	else
			include ( 'paralelas'.$show.'.php' );
?>	