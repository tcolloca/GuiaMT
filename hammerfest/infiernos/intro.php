<p>
<subtitle>
Los Infiernos
</subtitle>
</p>
<pre>

</pre>

<?php
$show=$_GET['show'];
if( $show == 0 )
		include( 'intro.html' );
else
		include ( 'infiernos'.$show.'.php' );
?>	