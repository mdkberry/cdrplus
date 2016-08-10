<?php
	
	$membership_db_name = "membership";
	$cdr_db_name = "asteriskcdrdb";
	
	$locallink = mysql_connect("localhost", "root", "") or die("Unable to connect to local MySQL server");
	mysql_select_db($membership_db_name, $locallink) or die("Unable to select database at local DB");
	
	$remotelink = mysql_connect("10.1.100.4", "root", "") or die("Unable to connect to remote MySQL server");
	//$remotelink = $locallink; // mysql_connect("localhost", "root", "") or die("Unable to connect to remote MySQL server");
	mysql_select_db($cdr_db_name, $remotelink) or die("Unable to select database at remote DB");
	
	$ignoreList = array('000', 's', '*97', 'asterisk', 'STARTMEETME');
	
	$FIRSTEXT = 100000;
	$LASTEXT =  0;
	
	error_reporting(0);
	
	function getExtList()
	{
		global $membership_db_name;
		global $cdr_db_name;
	
		global $locallink;
		global $FIRSTEXT;
		global $LASTEXT;
		
		if(!mysql_select_db($membership_db_name, $locallink)) die("Can't select db - membership");
		
		$extlist = array();
		
		$query = "SELECT extension FROM user ORDER BY extension ASC";
		if($exts = mysql_query($query, $locallink)) {} else die("Can't execute mysql at line 24");
		
		while($row = mysql_fetch_assoc($exts))
		{
			if(empty($row['extension']) || $row['extension'] == null || $row['extension'] == 'null') continue;
			
			$extlist[] = intval($row['extension']);
			
			if($FIRSTEXT > intval($row['extension'])) $FIRSTEXT = intval($row['extension']);
			if($LASTEXT  < intval($row['extension'])) $LASTEXT = intval($row['extension']);
			    
		}
				
		return $extlist;
	}
	
	function getTeamList()
	{
		global $membership_db_name;
		global $cdr_db_name;

		global $locallink;
		mysql_select_db($membership_db_name, $locallink);
		
		$teamlist = array();

		$query = "SELECT * FROM user ORDER BY `group` ASC";
		
		$teams = mysql_query($query, $locallink);
		
		while($row = mysql_fetch_assoc($teams))
		{
			if(empty($row['group']) || empty($row['extension'])) continue;
			
			if( !isset($teamlist[$row['group']]) ) $teamlist[$row['group']] = array();
			$teamlist[$row['group']][] = $row['extension'];
		}
				
		return $teamlist;
	}
	
	$extlist = getExtList();
	$teamlist = getTeamList();

	function getCheckValid($cdr)
	{
		global $membership_db_name;
		global $cdr_db_name;

		global $FIRSTEXT;
		global $LASTEXT;
		global $ignoreList;
		
		$srcInRange = ($cdr['src'] < $FIRSTEXT || $cdr['src'] > $LASTEXT) ? 0 : 1; 
		$dstInRange = ($cdr['dst'] < $FIRSTEXT || $cdr['dst'] > $LASTEXT) ? 0 : 1;
		
		if( ($srcInRange + $dstInRange) != 1 || in_array($cdr['src'], $ignoreList) || in_array($cdr['dst'], $ignoreList) ) 
				return array("isValid" => false, "thisExt" => "", "outExt" => "" );
		
		if( $srcInRange == 1)
			return array("isValid" => true, "thisExt" => $cdr['src'], "outExt" => $cdr['dst'] );
		else 
			return array("isValid" => true, "thisExt" => $cdr['dst'], "outExt" => $cdr['src'] );
	}

	function extractExtData($start, $end)
	{
//		echo $start." - ".$end;
		global $membership_db_name;
		global $cdr_db_name;

		global $remotelink;
		mysql_select_db($cdr_db_name, $remotelink);
		
		$CALL1MIN = array();
		$CALL5MIN = array();
		$CALLPLUS5 = array();
		$CALLSEC = array();
		$CALLCOUNTX = array();
		$DIALEDNUM = array();
		$UNIQ = array();
		
		$q = "SELECT * FROM cdr WHERE calldate BETWEEN '$start' AND '$end'";
		$cdrs = mysql_query($q, $remotelink);
		while ( $cdr = mysql_fetch_assoc($cdrs) ) {
			$check = getCheckValid($cdr);
			if($cdr['billsec'] <= 0) continue; 	
			
			if( !$check["isValid"] ) continue;
			
			$THISEXT = $check['thisExt'];
			$OUTEXT = $check['outExt'];
			
			if(!isset($CALL1MIN[$THISEXT]))  $CALL1MIN[$THISEXT] = 0;
			if(!isset($CALL5MIN[$THISEXT]))  $CALL5MIN[$THISEXT] = 0;
			if(!isset($CALLPLUS5[$THISEXT])) $CALLPLUS5[$THISEXT] = 0;
			if(!isset($CALLSEC[$THISEXT]))   $CALLSEC[$THISEXT] = 0;
			if(!isset($UNIQ[$THISEXT]))      $UNIQ[$THISEXT] = 0;

			$len = $cdr['billsec'];
			 
			if( $len < 60)		{	$CALL1MIN[$THISEXT]++;	}
			else if($len < 300)	{	$CALL5MIN[$THISEXT]++;	}
			else				{	$CALLPLUS5[$THISEXT]++;	}
			
			$CALLSEC[$THISEXT] += $len;
			
//			$CALLCOUNTX[$THISEXT]++;
			
			//if(!isset($DIALEDNUM[$OUTEXT])) { $DIALEDNUM[$OUTEXT] = "dialed"; $UNIQ[$THISEXT]++; }

			if ( !isset($DIALEDNUM[$OUTEXT]) ) {
				$DIALEDNUM[$OUTEXT] = array();
			}

			if ( in_array($THISEXT, $DIALEDNUM[$OUTEXT]) ) {
				
			} else {				
				$UNIQ[$THISEXT]++;				
				array_push($DIALEDNUM[$OUTEXT], $THISEXT);
			}
		}
		
		return array("call1min" => $CALL1MIN, "call5min" => $CALL5MIN, "callplus5" => $CALLPLUS5,
					 "uniqcalls" => $UNIQ, "totaltime" => $CALLSEC	);
	}
	
	function extractCallcountReport($extlist, $fromdate, $todate)
	{
		$calls = extractExtData($fromdate, $todate);

		$CALL1MIN = array();
		$CALL5MIN = array();
		$CALLPLUS5 = array();
		$CALLSEC = array();
		$UNIQ = array();
		
		for($i=0; $i < sizeof($extlist); $i++)
		{
			$ext = $extlist[$i];
			
			$CALL1MIN[$ext] = $calls['call1min'][$ext]; 
			$CALL5MIN[$ext] = $calls['call5min'][$ext];
			$CALLPLUS5[$ext] = $calls['callplus5'][$ext];
			$CALLSEC[$ext] = $calls['totaltime'][$ext];
			$UNIQ[$ext] = $calls['uniqcalls'][$ext];
		}
		
		return array("call1min" => $CALL1MIN, "call5min" => $CALL5MIN, "callplus5" => $CALLPLUS5,
					 "uniqcalls" => $UNIQ, "totaltime" => $CALLSEC	);
	}
	
	function extractDetailedReport($extlist, $fromdate, $todate)
	{
		global $membership_db_name;
		global $cdr_db_name;

		global $remotelink;
		mysql_select_db($cdr_db_name, $remotelink);
		
		$q = "SELECT * FROM cdr WHERE calldate BETWEEN '$fromdate' AND '$todate' ORDER BY calldate DESC";
		$cdrs = mysql_query($q, $remotelink);
		
		$ret = array();
		while($cdr = mysql_fetch_assoc($cdrs))
		{
			$check = getCheckValid($cdr);
			if($cdr['billsec'] <= 0) continue; 	
			
			if(!$check["isValid"] ) continue;
			
			if(!in_array($check['thisExt'], $extlist)) continue;
			
			$cdr['srcExt'] = $check['thisExt'];
			$cdr['tgtExt'] = $check['outExt'];
			
			$ret[] = $cdr;
		}
			
		return $ret;		
		
	}
	
	function getTop3Ext($calls)
	{
		$tops = array();
		
		foreach ($calls as $ext => $call)
		{
			if(!isset($calls[$ext])) continue;
			
			$inserted = false;
			
			for($i = 0; $i < count($tops) && $i < 3 ; $i++)
			{
				if( $calls[$tops[$i]] >= $call ) continue;

				$j = (count($tops) > 2) ? 1 : count($tops) - 1; 
				for(; $j >= $i; $j--) {$tops[$j + 1] = $tops[$j]; }
				$tops[$i] = $ext;
				
				$inserted = true; break;
			}
			
			if(!$inserted && count($tops) < 3) $tops[] = $ext; 
		}
		
		return $tops;
	}
	
	function extractTeamData($start, $end)
	{
		$extdata = extractExtData($start, $end);
		
		$teamlist = getTeamList();
		
		$extUNIQ = $extdata['uniqcalls'];
		$extCALL = $extdata['totaltime'];
		
		$teamUNIQ = array();
		$teamCALL = array();
		$teamTOP = array();
		
		foreach ($teamlist as $team => $extList) {
			if(!isset($teamUNIQ[$team])) $teamUNIQ[$team] = 0;
			if(!isset($teamCALL[$team])) $teamCALL[$team] = 0;
			if(!isset($teamTOP[$team]))  $teamTOP[$team] = array();

			$teamCalls = array();
			
			foreach ($extList as $ext)
			{
				$teamUNIQ[$team] += isset($extUNIQ[$ext]) ? $extUNIQ[$ext] : 0;
				$teamCALL[$team] += isset($extCALL[$ext]) ? $extCALL[$ext] : 0;
				if(isset($extCALL[$ext])) $teamCalls[$ext] = $extCALL[$ext];
			}

			$teamTOP[$team] = getTop3Ext($teamCalls);	
		}
		
		return array("uniqcalls" => $teamUNIQ, "totaltime" => $teamCALL, "top3" => $teamTOP);
	}
	
	function paddingZero($n) {	if($n < 10) return "0".$n; return $n; }	

	function getTimeFormated($sec)
	{
		$hour = floor($sec / 3600);
		$mins = floor(($sec % 3600 ) / 60);
		$secs = $sec % 60;
		 
		return paddingZero($hour).":".paddingZero($mins).":".paddingZero($secs);
	}
	
	function multi_attach_mail($to, $data, $sendermail)
	{
		$origdata = $data;
	    // email fields: to, from, subject, and so on
	    $from = "CDR Plus <cdr@mbitservices.com.au>";
	    $subject = "CDR Plus Generated Report";
	    $message = "Attached is a schedule generated call report from CDR Plus.";
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
	    $ok = @mail($to, $subject, $message, $headers);//, $returnpath);
		
	    return $ok;
	}
?>