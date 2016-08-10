<?php
	include 'db.php';
	
	global $locallink;
	global $membership_db_name;
	global $cdr_db_name;
	
	mysql_select_db($membership_db_name, $locallink);
	
	$call_today = $_POST['call_today'];
	$call_thisweek = $_POST['call_thisweek'];
	$call_thismonth = $_POST['call_thismonth'];
	$team_today = $_POST['team_today'];
	$team_thisweek = $_POST['team_thisweek'];
	$team_thismonth = $_POST['team_thismonth'];
	
	$q = "UPDATE rotation_config SET call_today=$call_today, call_this_week=$call_thisweek, call_this_month=$call_thismonth,
									 team_today=$team_today, team_this_week=$team_thisweek, team_this_month=$team_thismonth";

	$result = mysql_query($q, $locallink);
	if($result) echo "success";
	else echo "fail";
?>