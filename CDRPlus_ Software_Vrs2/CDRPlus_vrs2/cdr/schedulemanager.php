<?php
	include("db.php");
	global $locallink;
	global $membership_db_name;
	global $cdr_db_name;
	
	$action = $_POST['action'];
	
	mysql_select_db($membership_db_name, $locallink);
	
	if($action == "save" || $action == "display" || $action == "sendmail")
	{
		$mail = $_POST['mail'];
		
		$q1 = "INSERT INTO schedule (mail";
		$q2 = "VALUES ('$mail'";
		
		$extlist = $_POST['extlist'];
		if(isset($_POST['extlist'])) { $q1 .= ', extlist'; $q2 .= ", '$extlist'"; }

		$fromdate = $_POST['fromdate'];
		$todate = $_POST['todate'];
		$period = $_POST['period'];
		if(isset($_POST['fromdate'])) 
		{
			$fromdate = DateTime::createFromFormat('d-m-Y', $fromdate);
			$todate   = DateTime::createFromFormat('d-m-Y', $todate);
			
			$fromdate = $fromdate->format('Y-m-d');
			$todate   = $todate->format('Y-m-d');
			
			$q1 .= ', fromdate, todate'; $q2 .= ", '$fromdate', '$todate'"; 
		}
		else { $q1 .= ', period'; $q2 .= ", '$period'"; }

		if($period == 'daily')
		{
			$report_run = date("Y-m-d").' '.date("23:59:59");
		}
		elseif($period == 'weekly')
		{
			$mon = date("N") - 1;
			$sun = 7 - date("N");
	
			$report_run = date("Y-m-d", strtotime("+$sun days")).' '.date("23:59:59");
		}
		elseif($period == 'monthly')
		{
			$report_run = date("Y-m-t").' '.date("23:59:59");
		}
		else 
		{
			$report_run = $todate.' '.date("23:59:59");
		}				
		
		$q1 .= ', report_run'; $q2 .= ", '$report_run'";
	
		$type = $_POST['type'];
		if($action == 'save') 	{$q1 .= ', type, fordisplay) '; $q2 .= ", '$type', 'no')"; }
		else 					{$q1 .= ', type, fordisplay) '; $q2 .= ", '$type', 'yes')"; }

		$save_result = mysql_query($q1.$q2, $locallink);

		if(!$save_result) {echo 'fail'; return; }
		
		if($action == "sendmail")
		{
			$newid = mysql_insert_id($locallink);
			
			if (function_exists('curl_init')) 
			{
		        $url = $_SERVER['HTTP_REFERER'];
		        $url = str_replace('reports.php', '', $url).'displayschedule.php?id='.$newid;
		        
				$sess = curl_init($url);
		        
				curl_setopt($sess, CURLOPT_CONNECTTIMEOUT, '20');
				curl_setopt($sess, CURLOPT_TIMEOUT, '20');
		
		        curl_setopt($sess, CURLOPT_RETURNTRANSFER, 1);
		        $data = curl_exec($sess);
		        $response_code = curl_getinfo($sess, CURLINFO_HTTP_CODE);
		        $curlerr = curl_error($sess);
		        curl_close($sess);
		
				$ok = multi_attach_mail($mail, $data, "sdf@hotmail.com");
						
				echo $ok ? "ok":"fail";
			}
			else 
			{
				echo "fail";
			}
		}
		else
		{
			echo mysql_insert_id($locallink);
		}
	}
	else if($action == "update")
	{
		$scheduleid = $_POST['id'];
		
		$mail = $_POST['mail'];
		$extlist = $_POST['extlist']; if(!isset($_POST['extlist'])) { $extlist = ""; }

		$fromdate = $_POST['fromdate'];
		$todate = $_POST['todate'];
		$period = $_POST['period'];
		if(isset($_POST['fromdate'])) 
		{
			$fromdate = DateTime::createFromFormat('d-m-Y', $fromdate);
			$todate   = DateTime::createFromFormat('d-m-Y', $todate);
			
			$fromdate = $fromdate->format('Y-m-d');
			$todate   = $todate->format('Y-m-d');
			
			$period = "";
		}
		else 
		{   
			$fromdate = ""; $todate = ""; 
		}

		$type = $_POST['type'];
		
		$query = "UPDATE schedule SET `mail`='$mail', `extlist`='$extlist', `fromdate`='$fromdate', `todate`='$todate', 
									  `period`='$period', `type`='$type' WHERE `id`=$scheduleid";
		
		if(mysql_query($query, $locallink)) echo 'ok'; 
		else echo 'fail';		
	}
	else if($action == "delete")
	{
		$schArr = explode(',', $_POST['ids']);
		for($i=0; $i < sizeof($schArr); $i++)
		{
			$id    = $schArr[$i];
			$query = "DELETE FROM `schedule` WHERE `id`=$id";
			if(!mysql_query($query, $locallink)) { echo 'fail'; return; }
		}
		
		echo 'ok';
	}
	else if($action == "load")
	{
		$scheduleArr = array();
		$q = "SELECT * FROM schedule";
		$schedules = mysql_query($q, $locallink);
		
		while($schedule = mysql_fetch_assoc($schedules))
		{
			$scheduleArr[] = array(	'id' => $schedule['id'], 'mail' => $schedule['mail'], 'extlist' => $schedule['extlist'], 
									'fromdate' => $schedule['fromdate'], 'todate' => $schedule['todate'], 
									'period' => $schedule['period'], 'type' => $schedule['type']);
		}
		
		echo json_encode($scheduleArr);
	}
?>
