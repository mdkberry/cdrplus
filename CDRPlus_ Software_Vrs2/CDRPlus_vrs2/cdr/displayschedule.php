<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Schedule Test Page</title>

<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>

<style type="text/css">
#table_wrapper {
    background: none repeat scroll 0 0 #d7d7d7;
    color: black;
    font-family: Tahoma;
    font-weight: bold;
    letter-spacing: 1px;
    margin: auto;
    padding: 10px;
    width: 800px;
}
#calls_table tr th {
  border-bottom: 3px solid #AEACAD;
  color: black;
  line-height: 11px;
}
#calls_table tr td {
  border-bottom: 2px solid #AEACAD;
  color: black;
  text-align: center;
}
.totalline td {
  border-top: 1px solid #787878;
  border-bottom: 3px solid #787878 !important;
}
</style>
</head>
<body>
<div id="table_wrapper">
<?php


	include_once 'db.php';
	global $locallink;
	global $remotelink;
	global $membership_db_name;
	global $cdr_db_name;
	
	if(isset($_GET['id'])) { $id = $_GET['id']; }
	else { 
		$q = "SELECT * FROM schedule WHERE fordisplay='yes' ORDER BY created DESC"; 
		$schedule = mysql_fetch_assoc(mysql_query($q, $locallink));
		
		$id = $schedule['id'];
	}

	$q = "SELECT * FROM schedule WHERE id=".$id;
	 
	$schedule = mysql_fetch_assoc(mysql_query($q, $locallink));
	if($schedule)
	{
		$mail = $schedule['mail'];
		$extlist = isset($schedule['extlist']) ? explode(",", $schedule['extlist']) : getExtList();
		$fromdate = $schedule['fromdate'];
		$todate = $schedule['todate'];
		$period = $schedule['period'];
		$type = $schedule['type'];
		$fordisplay = $schedule['fordisplay'];

		if($fordisplay == "yes")
		{
			$q = "DELETE FROM schedule WHERE id=".$id;
			$rr = mysql_query($q, $locallink);
		}

		if($period == 'daily')
		{
			$fromdate = date("Y-m-d").' '."00:00:00";
			$todate = date("Y-m-d").' '."23:59:59";
		}
		elseif($period == 'weekly')
		{
			$mon = date("N") - 1;
			$sun = 7 - date("N");
	
			$fromdate = date("Y-m-d", strtotime("-$mon days ")).' '."00:00:00";
			$todate = date("Y-m-d", strtotime("+$sun days")).' '."23:59:59";
		}
		elseif($period == 'monthly')
		{
			$fromdate = date("Y-m-01").' '."00:00:00";
			$todate = date("Y-m-t").' '."23:59:59";
		}
		else 
		{
			$fromdate = $fromdate.' '."00:00:00";
			$todate = $todate.' '."23:59:59";
		}
	
		
		if($type == "detailed")
		{ 	$CALLS = extractDetailedReport($extlist, $fromdate, $todate); }
		else { 
			$CALLS = extractCallcountReport($extlist, $fromdate, $todate); arsort($CALLS['totaltime']);
		}
	}
	else {
		echo "sorry, this page can't be refreshed.";
	}
?>

<?php if($type == "callcount") : ?>
		<div id="table_time"><span style="color:white;">CALL Counts</span> / <?php echo $title;?></div> 
		<table id="calls_table" width="100%" cellpadding="3" cellspacing="0" style="table-layout:fixed;">
			<thead>
				<tr>
					<th style="width: 12%;">Ext</th>
					<th style="width: 16%;">User Name</th>
					<th style="width: 12%;">Total #</th>
					<th style="width: 17%;">Uniq #<br /><span style="font-size: 10px;">(uniq)</span></th>
					<th style="width: 12%;">&lt; 1 min</th>
					<th style="width: 12%;">1 - 5 min</th>
					<th style="width: 12%;">&gt; 5 mins</th>
					<th style="width: 12%;">Total<br /><span style="font-size: 10px;">(hh:mm:ss)</span></th>
				</tr>
			</thead>
			<tbody>
			<?php 	mysql_select_db($membership_db_name, $locallink); 
					$t3 = 0; $t4 = 0; $t5 = 0; $t6 = 0; $t7 = 0; $t8 = 0;
			?>		
			<?php foreach ($CALLS['totaltime'] AS $EXT => $val ) : ?>
			<?php 
				if($EXT == 'null') continue;
				$q = "SELECT * FROM user WHERE extension='$EXT'";
				$user = mysql_fetch_assoc(mysql_query($q, $locallink));
				
				$t3 += $CALLS['call1min'][$EXT] + $CALLS['call5min'][$EXT] + $CALLS['callplus5'][$EXT];
				$t4 += $CALLS['uniqcalls'][$EXT];
				$t5 += $CALLS['call1min'][$EXT];
				$t6 += $CALLS['call5min'][$EXT];
				$t7 += $CALLS['callplus5'][$EXT];
				$t8 += $CALLS['totaltime'][$EXT];
			?>	
				<tr>
					<td><?php echo $EXT; ?></td>
					<td><?php echo $user['user']; ?></td>
					<td><?php echo $CALLS['call1min'][$EXT] + $CALLS['call5min'][$EXT] + $CALLS['callplus5'][$EXT]; ?></td>
					<td><?php echo isset($CALLS['uniqcalls'][$EXT])? $CALLS['uniqcalls'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['call1min'][$EXT])? $CALLS['call1min'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['call5min'][$EXT])? $CALLS['call5min'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['callplus5'][$EXT])? $CALLS['callplus5'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['totaltime'][$EXT])? getTimeFormated($CALLS['totaltime'][$EXT]) : "-"; ?></td>
				</tr>
			<?php endforeach; ?>
				<tr class="totalline">
					<td colspan='2'>[ Total ]</td>
					<td><?php echo $t3; ?></td>
					<td><?php echo $t4; ?></td>
					<td><?php echo $t5; ?></td>
					<td><?php echo $t6; ?></td>
					<td><?php echo $t7; ?></td>
					<td><?php echo getTimeFormated($t8); ?></td>
				</tr>			
			</tbody>
		</table>
<?php elseif($type == "detailed"): ?>
		<div id="table_time"><span style="color:white;">Detailed CALLS</span> / <?php echo $title;?></div> 
		<table id="calls_table" width="100%" cellpadding="3" cellspacing="0" style="table-layout:fixed;">
			<thead>
				<tr>
					<th style="width: 27%;">Date & Time</th>
					<th style="width: 19%;">User Name</th>
					<th style="width: 9%;">Ext.</th>
					<th style="width: 31%;">Destination Number</th>
					<th style="width: 14%;">Call Duration</th>
				</tr>
			</thead>
			<tbody>
			<?php mysql_select_db($membership_db_name, $locallink); $t = 0;?>
			<?php foreach ($CALLS AS $call) : ?>
			<?php 
				$t += $call['billsec'];
				$q = "SELECT * FROM user WHERE extension='".$call['srcExt']."'";
				$user = mysql_fetch_assoc(mysql_query($q, $locallink));
			?>	
				<tr>
					<td><?php echo $call['calldate']; ?></td>
					<td><?php echo $user['user']; ?></td>
					<td><?php echo $call['srcExt']; ?></td>
					<td><?php echo $call['tgtExt']; ?></td>
					<td><?php echo getTimeFormated($call['billsec']); ?></td>
				</tr>
			<?php endforeach; ?>
				<tr class="totalline">
					<td colspan='4'>[ Total ]</td>
					<td><?php echo getTimeFormated($t); ?></td>
				</tr>				
			</tbody>
		</table>
<?php endif; ?>
</div>
</body>
</html>