<?php
	session_start();
	
	define(ROOT, $_SERVER['DOCUMENT_ROOT']);
	
	include_once(ROOT."/functions/database_management.php");
	
	//TODO: Reemplazar por $_SESSION['username']
	switch($_POST["functionCalled"]) {
		case "hasCompleted":
			echo 'ret='.var_export(hasCompleted($_POST["eventName"], $_POST["username"], $_POST["puzzle"]), true);
		 	break;
		case "complete":
			echo complete($_POST["eventName"], $_POST["username"], $_POST["puzzle"]);
			break;
		case "isAvailable":
			echo 'ret='.var_export(isAvailable($_POST["eventName"], $_POST["username"], $_POST["object"]), true);
			break;
		case "hasObject":
			echo 'ret='.var_export(hasObject($_POST["eventName"], $_POST["username"], $_POST["object"]), true);
			break;
		case "usedObject":
			echo 'ret='.var_export(usedObject($_POST["eventName"], $_POST["username"], $_POST["object"]), true);
			break;
		case "releaseObject":
			releaseObject($_POST["eventName"], $_POST["username"], $_POST["object"]);
			break;
		case "getObject":
			getObject($_POST["eventName"], $_POST["username"], $_POST["object"]);
			break;	
		case "useObject":
			useObject($_POST["eventName"], $_POST["username"], $_POST["object"]);
			break;	
	}
?>	