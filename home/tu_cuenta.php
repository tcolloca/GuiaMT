<p>
<subtitle>
Tu Cuenta
</subtitle>
<pre>

</pre>

<?php		
	if ( $GLOBALS['logMessage'] != "" )
		echo "<font color='#FF0000'><b>".$GLOBALS['logMessage']."</b></font><pre>	</pre>";		
	
	if ( !isset($_SESSION["username"]) )
		include(ROOT."/home/prelog.php");
	else {
		$string = '<center><font color="#233243">Tu cuenta</font> | <a href="?id=your_events"><font color="#233243"><b>Tus eventos</b></font></a></center>';
		
		echo "<p>".$string."</p>";
		
		include(ROOT."/home/datos.php");		
	}
?>
