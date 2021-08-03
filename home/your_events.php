<?php		
	if ( $GLOBALS['logMessage'] != "" )
		echo "<font color='#FF0000'><b>".$GLOBALS['logMessage']."</b></font><pre>	</pre>";		
	
	if ( !isset($_SESSION["username"]) )
		include(ROOT."/home/prelog.php");
	else {
		if(isset($_GET['event'])) {
			$event = $_GET['event'];
			if(isEventOn($event)) {	
				if(!hasEvent($event, $_SESSION["username"])) {
					addToEvent($event, $_SESSION["username"]);
				}
				include(ROOT."/home/events/".$event.".php");
			} else {
				echo "El evento no existe o ya ha terminado.";	
			}
		} else {
			echo "<p><subtitle>Tus eventos</subtitle></p>
				  <pre></pre>";
					
			$string = '<center><a href="?id=tu_cuenta"><font color="#233243"><b>Tu cuenta</b></font></a> | <font color="#233243">Tus eventos</font></center>';
		echo "<p>".$string."</p>";
			
			echo "<minititle>Tus eventos activos</minititle>";
			if(hasNoActiveEvents($_SESSION["username"])) {
				echo "<p>No tienes ningún evento activo por el momento.</p>";
			} else {
				echo "<p><table cellpadding='5px' cellspacing='5px'>";
				while($event = getUserActiveEvent($_SESSION['username'])) {
					echo '<tr>
							<td valign="middle" align="center" bgcolor="#75AFF0"><a style="text-decoration:none;" href="?id=your_events&event='.$event["event_name"].'"><img src="images/events/'.$event["event_name"].'/'.$event["event_logo"].'"/></a></td>
							<td valign="middle" align="center"bgcolor="#95CAEA"><b><a style="text-decoration:none;" href="?id=your_events&event='.$event["event_name"].'"><font color="#233243">'.$event["event_full_name"].'</font></a></b></td>
							<td bgcolor="#75AFF0">'.$event["event_description"].'</td>
						</tr>';
				}
				echo "</table></p>";
			}	
			echo "<pre></pre>";
			echo "<minititle>Otros eventos activos</minititle>";
			if(areNoOtherActiveEvents($_SESSION['username'])) {
				echo "<p>No hay otros eventos activos por el momento.</p>";
			} else {
				echo "<p><table cellpadding='5px' cellspacing='5px'>";
				while($event = getActiveEvent()) {
					if(!hasEvent($event['event_name'], $_SESSION['username'])) {
						echo '<tr>
							<td valign="middle" align="center" bgcolor="#75AFF0"><a style="text-decoration:none;" href="?id=your_events&event='.$event["event_name"].'"><img src="images/events/'.$event["event_name"].'/'.$event["event_logo"].'"/></a></td>
							<td valign="middle" align="center"bgcolor="#95CAEA"><b><a style="text-decoration:none;" href="?id=your_events&event='.$event["event_name"].'"><font color="#233243">'.$event["event_full_name"].'</font></a></b></td>
							<td bgcolor="#75AFF0">'.$event["event_description"].'</td>
							<td valign="middle" align="center" bgcolor="#75AFF0"><a style="text-decoration:none;" href="?id=your_events&event='.$event["event_name"].'">Agregar</a></td>
						</tr>';
					}	
				}
				echo "</table></p>";
			}
		}
	}
?>