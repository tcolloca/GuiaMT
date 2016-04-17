<?php
	define(DB_HOST, "mysql.guiamt.net");
	define(DB_NAME, "guiamt");
	
	define(DB_ACCOUNTS_USER, "accountsmanager");
	define(DB_ACCOUNTS_PASSWORD, "accountsMT2014");
	
	define(DB_EVENTS_USER, "events_manager");
	define(DB_EVENTS_PASSWORD, "eventsMT2014");
	
	define(DB_TABLES_USER, "tables_manager");
	define(DB_TABLES_PASSWORD, "tablesMT2014");
	
	define(DB_TABLE_EDITOR_USER, "table_editor");
	define(DB_TABLE_EDITOR_PASSWORD, "tableEditorMT2014");
	
	define(DB_LOG_USER, "log_manager");
	define(DB_LOG_PASSWORD, "logMT2014");

	function connectDB($action){
		$DBuser = "";
		$DBPassword = "";
		
		switch($action) {
			case "register":
			case "login":
			case "editAccount":
			case "getter":
				$DBuser = DB_ACCOUNTS_USER;
				$DBPassword = DB_ACCOUNTS_PASSWORD;
				break;
			case "events":
				$DBuser = DB_EVENTS_USER;
				$DBPassword = DB_EVENTS_PASSWORD;
				break;
			case "tables":
				$DBuser = DB_TABLES_USER;
				$DBPassword = DB_TABLES_PASSWORD;
				break;	
			case "editTable":
				$DBuser = DB_TABLE_EDITOR_USER;
				$DBPassword = DB_TABLE_EDITOR_PASSWORD;
				break;	
			case "log":
				$DBuser = DB_LOG_USER;
				$DBPassword = DB_LOG_PASSWORD;
				break;	
		}
		
		$mysqli = new mysqli(DB_HOST, $DBuser, $DBPassword, DB_NAME);
		if ( $mysqli->connect_errno )
			echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		$mysqli->set_charset("utf8");
		return $mysqli;
	}
	
	function specialConnectDB($DBuser, $DBPassword){
		
		$mysqli = new mysqli(DB_HOST, $DBuser, $DBPassword, DB_NAME);
		if ( $mysqli->connect_errno )
			echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		return $mysqli;
	}
	
	function closeDB(){
		$mysqli->close();
	}
	
	/*** USER'S ACCOUNTS ***/
	
	function addUser($username, $password, $mail, $status, $sex) {
		$mysqli = connectDB("register");
		$stmt = $mysqli->prepare("INSERT INTO users (user_name, user_pwd, user_mail, user_status, user_sex) VALUES (?, ?, ?, ?, ?)");
		$hashPwd = hash(whirlpool, $password);
		$stmt->bind_param('sssss', $username, $hashPwd, $mail, $status, $sex);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function editMail($username, $mail, $status){
		$mysqli = connectDB("editAccount");
		$stmt = $mysqli->prepare("UPDATE users SET user_mail = ?, user_status = ? WHERE user_name = ?");
		$stmt->bind_param('sss', $mail, $status, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function editPassword($username, $newPassword){
		$mysqli = connectDB("editAccount");
		$stmt = $mysqli->prepare("UPDATE users SET user_pwd = ? WHERE user_name = ?");
		$hashPwd = hash(whirlpool, $newPassword);
		$stmt->bind_param('ss', $hashPwd, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function editStatus($username, $status){
		$mysqli = connectDB("editAccount");
		$stmt = $mysqli->prepare("UPDATE users SET user_status = ? WHERE user_name = ?");
		$stmt->bind_param('ss', $status, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function specialEditStatus($username, $status, $DBuser, $DBpassword){
		$mysqli = specialConnectDB($DBuser, $DBpassword);
		$stmt = $mysqli->prepare("UPDATE users SET user_status = ? WHERE user_name = ?");
		$stmt->bind_param('ss', $status, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function editStaff($username, $value, $DBuser, $DBpassword){
		$mysqli = specialConnectDB($DBuser, $DBpassword);
		$stmt = $mysqli->prepare("UPDATE users SET user_staff = ? WHERE user_name = ?");
		$stmt->bind_param('is', $value, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function activate($username){
		editStatus($username, 'active');
	}
	
	function freeze($username){
		editSatus($username, 'frozen');
	}
	
	function recoverAccount($mail, $newPassword){
		$userData = getDataByMail($mail);
		if( $userData == null )
			return null;
		editPassword( $userData["user_name"], $newPassword );
		return $userData["user_name"];
	}
		
	
	function getDataByUsername($username){
		$mysqli = connectDB("getter");
		$stmt = $mysqli->prepare("SELECT * FROM users WHERE user_name = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$userRawRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$userData = $userRawRow->fetch_assoc();
		return $userData;
	}
	
	function getDataByMail($mail){
		$mysqli = connectDB("getter");
		$stmt = $mysqli->prepare("SELECT * FROM users WHERE user_mail = ?");
		$stmt->bind_param('s', $mail);
		$stmt->execute();
		$userRawRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$userData = $userRawRow->fetch_assoc();
		return $userData;
	}
	
	function userExists($username){
		$userData = getDataByUsername($username);
		return $userData != null;
	}
	
	function mailExists($mail){
		$userData = getDataByMail($mail);
		return $userData != null;
	}
	
	function matchPassword( $username, $password ){
		$userData = getDataByUsername($username);
		return strcmp($userData["user_pwd"], hash(whirlpool, $password)) == 0;
	}
	
	function getAllUsers() {
		$mysqli = connectDB("getter");
		$stmt = $mysqli->prepare("SELECT * FROM users");
		$stmt->execute();
		$users = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $users;
	}
	
	function getUser() {
		static $users;	
		if( !isset($users) )
			$users = getAllUsers();
		$user = $users->fetch_assoc();
		if( $user == false ) {
			$users = getAllUsers();
			return false;
		}
		return $user["user_name"];		
	}
	
	function getMail( $username ){
		$userData = getDataByUsername($username);
		return $userData["user_mail"];
	}
	
	function getStatus( $username ){
		$userData = getDataByUsername($username);
		return $userData["user_status"];
	}
	
	function isStaff( $username ){
		$userData = getDataByUsername($username);
		return $userData["user_staff"] == 1;
	}
	
	function getSex( $username ){
		$userData = getDataByUsername($username);
		return $userData["user_sex"];
	}
	
	function getUserId($username) {
		$userData = getDataByUsername($username);
		return $userData["user_id"];
	}
	
	/*** USER'S PRIVILEDGES ***/
	
	function initPriviledges( $username ){
		$mysqli = connectDB("register");
		$stmt = $mysqli->prepare("INSERT INTO priviledges (user_id) VALUES ((SELECT user_id FROM users WHERE user_name = ?))");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function removeAllPriviledges( $username, $DBuser, $DBpassword ){
		$mysqli = specialConnectDB($DBuser, $DBpassword);
		$stmt = $mysqli->prepare("DELETE FROM priviledges WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function addPriviledge( $username, $game){
		editPriviledge( $username, $game, 1);
	}
	
	function removePriviledge( $username, $game){
		editPriviledge( $username, $game, 0);
	}
	
	function editPriviledge( $username, $game, $value, $DBuser, $DBpassword ){
		$mysqli = specialConnectDB($DBuser, $DBpassword);
		$stmt = $mysqli->prepare("UPDATE priviledges SET priviledge_".$game." = ?
								 WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('is', $value, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function hasPriviledge($username, $game){
		$mysqli = connectDB("getter");
		$stmt = $mysqli->prepare("SELECT priviledge_".$game." FROM priviledges
								 WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$priviledge = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$priviledge = $priviledge->fetch_assoc();
		return $priviledge['priviledge_'.$game] == 1;
	}
	
	/*** EVENTS ***/
	
	function complete($event, $username, $puzzle) {
		
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("UPDATE ".$event."_puzzles SET puzzle_".$puzzle." = 1 
								WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function hasCompleted($event, $username, $puzzle) {
		
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT puzzle_".$puzzle." FROM ".$event."_puzzles
								 WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$ret = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$ret = $ret->fetch_assoc();
		return $ret['puzzle_'.$puzzle] == 1;
	}
	
	function changeObjectStatus($event, $username, $object, $value) {
		
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("UPDATE ".$event."_objects SET object_".$object." = ? 
								WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('is', $value, $username);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function getObjectStatus($event, $username, $object, $status) {
		
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT object_".$object." FROM ".$event."_objects
								 WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$ret = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$ret = $ret->fetch_assoc();
		return $ret['object_'.$object] == $status;
	}
	
	function isAvailable($event, $username, $object) {
		
		return getObjectStatus($event, $username, $object, 0);
	}
	
	function hasObject($event, $username, $object) {
		
		return getObjectStatus($event, $username, $object, 1);
	}
	
	function usedObject($event, $username, $object) {
		
		return getObjectStatus($event, $username, $object, 2);
	}
	
	function releaseObject($event, $username, $object) {
		
		changeObjectStatus($event, $username, $object, 0);
	}
	
	function getObject($event, $username, $object) {
		
		changeObjectStatus($event, $username, $object, 1);
	}
	
	function useObject($event, $username, $object) {
		
		changeObjectStatus($event, $username, $object, 2);
		addObjectDate($event, $username, $object);
	}
	
	function addObjectDate($event, $username, $object) {
		$date = date("Y-m-d H:i:s");
		$userId = getUserId($username);
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("INSERT INTO ".$event."_object_dates (user_id, date_object, date_date) VALUES (?,?,?)");
		$stmt->bind_param('iss', $userId, $object, $date);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function addToEvent($event, $username) {
		$userId = getUserId($username);
		switch($event) {
			case 'easter2014':
				addObjects($event, $username);
				break;
		}
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("INSERT INTO event_users (user_id, event_name) VALUES (?, ?)");
		$stmt->bind_param('is', $userId, $event);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function addObjects($event, $username) {
		$userId = getUserId($username);
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("INSERT INTO ".$event."_objects (user_id) VALUES (?)");
		$stmt->bind_param('i', $userId);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function hasEvent($event, $username) {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM event_users WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?) AND event_name = ?");
		$stmt->bind_param('ss', $username, $event);
		$stmt->execute();
		$userRawRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$userData = $userRawRow->fetch_assoc();
		return $userData != null;
	}
	
	function isEventOn($event) {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM events WHERE event_name = ?");
		$stmt->bind_param('s', $event);
		$stmt->execute();
		$eventRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$eventData = $eventRow->fetch_assoc();
		return $eventData != null && $eventData["event_status"] == 1;
	}
	
	function getEventByName($event) {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM events WHERE event_name = ?");
		$stmt->bind_param('s', $event);
		$stmt->execute();
		$eventRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$eventData = $eventRow->fetch_assoc();
		return $eventData;
	}
	
	function getAllUserEvents($username) {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM event_users WHERE user_id = (SELECT user_id FROM users WHERE user_name = ?)");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$events = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $events;
	}
	
	function getUserActiveEvent($username) {
		static $events;	
		if(!isset($events))
			$events = getAllUserEvents($username);
		do {
			$event = $events->fetch_assoc();
			if($event != false) {
				$eventData = getEventByName($event["event_name"]);
			}
		} while($event != false && $eventData["event_status"] != 1);
		if($event == false) {
			$events = getAllUserEvents($username);
			return false;
		}
		return $eventData;
	}
	
	function hasNoActiveEvents($username) {
		$events = getAllUserEvents($username);
		do {
			$event = $events->fetch_assoc();
			if($event != false) {
				$eventData = getEventByName($event["event_name"]);
			}
		} while($event != false && $eventData["event_status"] != 1);
		return $event == false;
	}
	
	function getAllEvents() {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM events");
		$stmt->execute();
		$events = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $events;
	}
	
	function getActiveEvent() {
		static $events;	
		if(!isset($events))
			$events = getAllEvents();
		do {
			$event = $events->fetch_assoc();
		} while($event != false && $event["event_status"] != 1);
		if($event == false) {
			$events = getAllEvents();
			return false;
		}
		return $event;
	}
	
	function areNoActiveEvents() {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM events WHERE event_status = 1");
		$stmt->execute();
		$events = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $events->fetch_assoc() == null;
	}
	
	function areNoOtherActiveEvents($username) {
		while($event = getActiveEvent()) {
			if(!hasEvent($event["event_name"], $username)) {
				while($event = getActiveEvent());
				return false;
			}
		}	
		return true;
	}
	
	function getEgg($event, $owner) {
		$mysqli = connectDB("events");
		$stmt = $mysqli->prepare("SELECT * FROM ".$event."_eggs WHERE egg_owner = ?");
		$stmt->bind_param('s', $owner);
		$stmt->execute();
		$eggRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $eggRow->fetch_assoc();
	}
	
	/*** TABLES ***/
	
	function getRow($game, $table, $where, $orderByCols, $order) {
		static $rows = array();	
		if(!isset($rows[$game])) {
			$rows[$game] = array();
			$rows[$game][$table] = getFromTable($game."_".$table, $where, $orderByCols, $order);
		} else {
			if(!isset($rows[$game][$table])) {
				$rows[$game][$table] = getFromTable($game."_".$table, $where, $orderByCols, $order);
			}
		}
		$row = $rows[$game][$table]->fetch_assoc();
		if($row == false) {
			unset($rows[$game][$table]);
		}
		return $row;	
	}
	
	function getFromTable($table, $where, $orderByCols, $order) {
		$mysqli = connectDB("tables");
		//echo getSelectQuery($table, $where, $orderByCols, $order);
		$stmt = $mysqli->prepare(getSelectQuery($table, $where, $orderByCols, $order));
		$stmt->execute();
		$tableRows = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $tableRows;
	}
	
	function getSelectQuery($table, $where = null, $orderByCols = null, $order = "ASC") {
		$query = "SELECT * FROM ".$table;
		if($where != null) {
			$query .= " WHERE ".$where;
		}
		if($orderByCols != null) {
			$query .= " ORDER BY ".$orderByCols." ".$order;
		}
		
		return $query;
	}
	
	function getTableRow($game, $table) {	
		return getRow($game, $table, "", "", "");		
	}
	
	function getAllTableColumns($game, $table) {
		return getFromTable("`INFORMATION_SCHEMA`.`COLUMNS`", "`TABLE_NAME`='".$game."_".$table."'", "", "");
	}
	
	function getTableColumn($game, $table) {	
		static $cols = array();	
		if(!isset($cols[$game])) {
			$cols[$game] = array();
			$cols[$game][$table] = getAllTableColumns($game, $table);
		} else {
			if(!isset($cols[$game][$table])) {
				$cols[$game][$table] = getAllTableColumns($game, $table);
			}
		}
		$col = $cols[$game][$table]->fetch_assoc();
		if($col == false) {
			unset($cols[$game][$table]);
		}
		return $col;		
	}
	
	function getTableRowSortedByFunction($game, $table, $function, $order){
		return getRow($game, $table, "", $function, $order);
	}
	
	function getTableRowSorted($game, $table, $column, $order) {	
		return getRow($game, $table, "", $table."_".$column, $order);
	}
	
	function getTableRowWithFilter($game, $table, $filter) {	
		return getRow($game, $table, $filter, "", "");		
	}
	
	function getTableRowSortedWithFilter($game, $table, $filter, $column, $order) {	
		return getRow($game, $table, $filter, $table."_".$column, $order);		
	}
	
	function getTableRowSortedByFunctionWithFilter($game, $table,  $filter, $function, $order) {	
		return getRow($game, $table, $filter, $function, $order);		
	}
	
	function getTags($table, $id) {
		$mysqli = connectDB("tables");
		$stmt = $mysqli->prepare("SELECT * FROM ".$table."_tag WHERE ".$table."_id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$tagsRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$tags = $tagsRow->fetch_assoc();
		return $tags;
	}
	
	function hasTag($table, $id, $tag) {
		$tags = getTags($table, $id);	
		return $tags["tag_".$tag] == 1;
	}
	
	
	/*
	**	inone = none = tags ∩ $tags_array = empty_set;
	**  some = isome = tags ∩ $tags_array != empty_set;
	**	exact = iexact = tags = tags_array;
	**	iall = tags included in $tags_array;
	**  all = $tags_array included in $tags;
	*/
	function getTableRowByTags($game, $table, $tags_array, $condition) {
		$valid_row = false;
		
		while(!$valid_row && ($row = getTableRow($game, $table))) {
			
			$id = $row[$table."_id"];
			
			if($condition == "none" || $condition == "inone") {
				$valid_row = true;
				
				foreach($tags_array as $tag) {	
					if(hasTag($table, $id, $tag)) {
						$valid_row = false;
						break;
					}	
				}
			} else if($condition == "exact" || $condition == "iexact") {
				$valid_row = true;
				$fullTagList = getTags($table, $id);
				
				foreach($fullTagList as $tag_name => $value) {		
					$tag = str_replace("tag_", "", $tag_name);
					if(($value == 1 && !in_array($tag, $tags_array)) || ($value == 0 && in_array($tag, $tags_array))) {
						$valid_row = false;
						break;
					}	
				}
			} else if($condition == "some" || $condition == "isome") {	
				$valid_row = false;
				
				foreach($tags_array as $tag) {	
					if(hasTag($table, $id, $tag)) {
						$valid_row = true;
						break;
					}	
				}
			} else if($condition == "iall") {
				$valid_row = true;
				$fullTagList = getTags($table, $id);
				
				foreach($fullTagList as $tag_name => $value) {		
					$tag = str_replace("tag_", "", $tag_name);
					if($value == 1 && !in_array($tag, $tags_array)) {
						$valid_row = false;
						break;
					}	
				}
			} else {	//all (default)
				$valid_row = true;
				
				foreach($tags_array as $tag) {	
					if(!hasTag($table, $id, $tag)) {
						$valid_row = false;
						break;
					}	
				}
			}			
		}
		
		return $row;
	}
	
	function newGetAllTags($table, $tagsName, $id) {
		$mysqli = connectDB("tables");
		$stmt = $mysqli->prepare("SELECT * FROM ".$table."_".$tagsName." WHERE ".$table."_id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$tags = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $tags;
	}
	
	function newGetTag($table, $tagsName, $id) {
		static $tags;	
		if(!isset($tags))
			$tags = newGetAllTags($table, $tagsName, $id);
		$tagRow = $tags->fetch_row();
		if($tagRow == false) {
			$tags = newGetAllTags($table, $tagsName, $id);
			return false;
		}
		return $tagRow[1];		
	}
	
	function newHasTag($table, $tagsName, $id, $tag) {
		while($itTag = newGetTag($table, $tagsName, $id)) {
			if($itTag == $tag) {
				return true;
			}
		}
		return false;
	}
	
	function updateTableField($game, $table, $id, $column, $newVal, $type) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("UPDATE ".$game."_".$table." SET ".$column." = ? WHERE ".$table."_id = ?;");
		$stmt->bind_param($type.$type, $newVal, $id);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function addTableRow($game, $table, $row, $types) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare(getInsertTableQuery($game, $table, $row));
		$tmp = array();
        foreach($row as $key => $value) $tmp[$key] = &$row[$key];
		call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), $tmp));
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function getInsertTableQuery($game, $table, $row) {
		$cols = "(";
		$values = "(";
		foreach($row as $key => $value) {
			$cols = $cols.$key.", ";
			$values = $values."?, ";
		}
		$cols = substr($cols, 0, -2);
		$values = substr($values, 0, -2);
		$cols = $cols.")";
		$values = $values.")";
		
		return "INSERT INTO ".$game."_".$table." ".$cols." VALUES ".$values;
	}
	
	
	/*** LOG ***/
	
	function getAllModifications(){
		$mysqli = connectDB("log");
		$stmt = $mysqli->prepare("SELECT * FROM modifications_log");
		$stmt->execute();
		$logRows = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		return $logRows;
	}
	
	function getModification() {
		static $modifications;	
		if( !isset($modifications) )
			$modifications = getAllModifications();
		$modification = $modifications->fetch_assoc();
		if( $modification == false ) {
			$modifications = getAllModifications();
			return false;
		}
		return $modification;		
	}
	
	function addModification($date, $user, $new, $url) {
		$mysqli = connectDB("log");
		$stmt = $mysqli->prepare("INSERT INTO modifications_log VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss', $date, $user, $new, $url);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	/*** KUBE DOLMEN'S AND ISLAND'S ***/
	
	function getDolmenIdByPos($x, $y) {
		$mysqli = connectDB("tables");
		$stmt = $mysqli->prepare("SELECT dolmen_id FROM kube_dolmen WHERE dolmen_x = ? && dolmen_y = ?");
		$stmt->bind_param('ii', $x, $y);
		$stmt->execute();
		$dolmenRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$dolmen = $dolmenRow->fetch_assoc();
		return $dolmen["dolmen_id"];
	}
	
	function getIslandIdByPos($x, $y) {
		$mysqli = connectDB("tables");
		$stmt = $mysqli->prepare("SELECT island_id FROM kube_island WHERE island_x = ? && island_y = ?");
		$stmt->bind_param('ii', $x, $y);
		$stmt->execute();
		$islandRow = $stmt->get_result();
		$stmt->close();
		$mysqli->close();
		$island = $islandRow->fetch_assoc();
		return $island["island_id"];
	}
	
	function addDolmen($user, $date, $x, $y, $note, $trust) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("INSERT INTO kube_dolmen (dolmen_date, dolmen_x, dolmen_y, dolmen_note, dolmen_trust)VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param('siisi', $date, $x, $y, $note, $trust);
		$stmt->execute();
		$stmt->close();
		$id = $mysqli->insert_id;
		$mysqli->close();
		addDolmenUser($id, $user);
	}
	
	function addDolmenUser($dolmenId, $user) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("INSERT INTO dolmen_user VALUES (?, ?)");
		$stmt->bind_param('is', $dolmenId, $user);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function addIsland($user, $date, $x, $y, $type, $filon, $note, $trust) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("INSERT INTO kube_island (island_date, island_x, island_y, island_type, island_filon,
		island_note, island_trust) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('siisisi', $date, $x, $y, $type, $filon, $note, $trust);
		$stmt->execute();
		$stmt->close();
		$id = $mysqli->insert_id;
		$mysqli->close();
		addIslandUser($id, $user);
	}
	
	function addIslandUser($islandId, $user) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("INSERT INTO island_user VALUES (?, ?)");
		$stmt->bind_param('is', $islandId, $user);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function updateDolmenNote($id, $user, $note) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("UPDATE kube_dolmen SET dolmen_note = ? WHERE dolmen_id = ?;");
		$stmt->bind_param('si', $note, $id);
		$stmt->execute();
		$stmt->close();
	}
	
	function updateIslandNote($id, $user, $note) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("UPDATE kube_island SET island_note = ? WHERE island_id = ?;");
		$stmt->bind_param('si', $note, $id);
		$stmt->execute();
		$stmt->close();
	}
	
	function updateDolmenTrust($id, $user, $trust) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("UPDATE kube_dolmen SET dolmen_trust = dolmen_trust + ? WHERE dolmen_id = ?;");
		$stmt->bind_param('ii', $trust, $id);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
		addDolmenUser($id, $user);
	}
	
	function updateIslandTrust($id, $user, $trust) {
		$mysqli = connectDB("editTable");
		$stmt = $mysqli->prepare("UPDATE kube_island SET island_trust = island_trust + ? WHERE island_id = ?;");
		$stmt->bind_param('ii', $trust, $id);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
		addIslandUser($id, $user);
	}
?>