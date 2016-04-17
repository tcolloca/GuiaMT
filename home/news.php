<p>
<subtitle>
Noticias
</subtitle>
</p>
<pre>

</pre>
<?php
	define( 'MAX_NEWS', 5);
	
	for( $i = 1; file_exists(ROOT."/home/news/".$i.".html" ); $i++ )
		;
		
	for( $j = $i - 1; $j > $i - MAX_NEWS ; $j-- )
		if(  file_exists( ROOT."/home/news/".$j.".html" ) )
			include( ROOT."/home/news/".$j.".html" );
?>
	