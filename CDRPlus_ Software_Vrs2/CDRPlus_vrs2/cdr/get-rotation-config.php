<?php
	include 'db.php';
	
	global $locallink;
	global $membership_db_name;
	global $cdr_db_name;
	
	mysql_select_db($membership_db_name, $locallink);
	
	$q = "SELECT * FROM rotation_config";
	$cfg = mysql_query($q, $locallink);
	$cfg = mysql_fetch_assoc($cfg);
	
	echo json_encode($cfg);
?>