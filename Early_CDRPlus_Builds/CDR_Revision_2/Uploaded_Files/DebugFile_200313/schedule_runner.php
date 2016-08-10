<?php
	function multi_attach_mail($to, $data, $sendermail)
	{
		$origdata = $data;
	    // email fields: to, from, subject, and so on
	    $from = "Files attach <anonymous@hotmail.com>";
	    $subject = "Generated Report";
	    $message = "Following is generated report.";
	    $headers = "From: $from";
 
	    // boundary
	    $semi_rand = md5(time());
	    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
 
	    // headers for attachment
	    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
 
	    // multipart boundary
	    $message = "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
	    			"Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
 
	    // preparing attachments
		$message .= "--{$mime_boundary}\n";
		$data = chunk_split(base64_encode($data));

		$message .= "Content-Type: application/octet-stream; name=\"Generated Report\"\n" .
            "Content-Description: Generated Report\n" .
            "Content-Disposition: attachment;\n" . " filename=\"report.html\"; size=".strlen($origdata).";\n" .
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";

		$message .= "--{$mime_boundary}--";
	    //$returnpath = "-f" . $sendermail;
	    
		echo "before sending mail";
	    $ok = @mail($to, $subject, $message, $headers);//, $returnpath);
		echo "after sending mail : result = ".$ok;
		
	    return $ok;
	}
	
	$membership_db_name = "membership";
	$cdr_db_name = "asteriskcdrdb";
	$locallink = mysql_connect("localhost", "root", "") or die("Unable to connect to local MySQL server");	
	if (!mysql_select_db($membership_db_name, $locallink)) die("Unable to select db - ".$membership_db_name);
	
	$scheduleArr = array();
	$q = "SELECT * FROM schedule";
	$schedules = mysql_query($q, $locallink);
	
	while($schedule = mysql_fetch_assoc($schedules))
	{
//		print_r($schedule);
		
		$fromdate = $schedule['fromdate'];
		$todate = $schedule['todate'];
		$period = $schedule['period'];
		$report_run = $schedule['report_run'];
		$report_run_exec = $schedule['report_run_exec'];
	
		if(date("Y-m-d H:i:s") > $report_run && $report_run_exec == 'no')
		{
			if (function_exists('curl_init')) 
			{
//				echo "---------";
//				print_r($_SERVER);
				
		        $url = "http://localhost/cdr/displayschedule.php?id=".$schedule['id'];
				$sess = curl_init($url);
		        if(!$sess) die("Unable to connect curl init - ".$url);
				curl_setopt($sess, CURLOPT_CONNECTTIMEOUT, '20');
				curl_setopt($sess, CURLOPT_TIMEOUT, '20');
		
		        curl_setopt($sess, CURLOPT_RETURNTRANSFER, 1);
		        $data = curl_exec($sess);
		        
		        $response_code = curl_getinfo($sess, CURLINFO_HTTP_CODE);
		        $curlerr = curl_error($sess);
		        curl_close($sess);
		
				$ok = multi_attach_mail($schedule['mail'], $data, "sdf@hotmail.com");

				//if(!$ok) continue;
				
				if($period == '' || $period == null)
				{
//					echo "deleting ...";
					$query = "UPDATE schedule SET `report_run_exec`='yes' WHERE id=".$schedule['id'];
					
					$ok = mysql_query($query, $locallink);
					
//					echo "schedule running.";
				} 		
			}
		} 
		
	}
	
	
?>
