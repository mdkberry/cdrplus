<?php
	include("db.php");

	global $membership_db_name;
	global $cdr_db_name;
		
	if($_GET['range'] == 'today')
	{
		$CALLS = extractExtData( date("Y-m-d").' '.date("00:00:00"), date("Y-m-d").' '.date("23:59:59") );
		$title = "Today";
	}
	else if($_GET['range'] == 'thisweek' || !isset($_GET['range']))
	{
		$mon = date("N") - 1;
		$sun = 7 - date("N");
		
		$CALLS = extractExtData( date("Y-m-d", strtotime("-$mon days ")).' '.date("00:00:00"), date("Y-m-d", strtotime("+$sun days")).' '.date("23:59:59") );
		$title = "This Week";
	}
	else 
	{
		$CALLS = extractExtData( date("Y-m-01").' '.date("00:00:00"), date("Y-m-t").' '.date("23:59:59") );
		$title = "This Month";
	}


	mysql_select_db($membership_db_name, $locallink);
	$q = "SELECT * FROM user";
	$users = mysql_query($q, $locallink);

	$namemap = array();
	while($row = mysql_fetch_assoc($users))
	{
		if(empty($row['extension'])) continue;

		$namemap[$row['extension']] = $row['user'];
	}
 	
	if(isset($_GET['export']))
	{
		global $locallink;
		
		$export = array();
		$export[] = array("Ext", "User", "Team", "< 1 min", "1 - 5 min", "> 5 mins", "Total (unique)", "Total (hh:mm:ss)");
		
		foreach ($extlist AS $EXT )
		{
			mysql_select_db($membership_db_name, $locallink);
			
			$q = "SELECT * FROM user WHERE extension='$EXT'";
			$users = mysql_query($q, $locallink);
			$user = mysql_fetch_assoc($users);
			$username = $user['user'];
			$groupname = $user['group'];
			
			$export[] = array($EXT, $username, $groupname, 
								isset($CALLS['call1min'][$EXT])? $CALLS['call1min'][$EXT] :'-', 
								isset($CALLS['call5min'][$EXT])? $CALLS['call5min'][$EXT] :'-',
								isset($CALLS['callplus5'][$EXT])? $CALLS['callplus5'][$EXT] :'-',
								isset($CALLS['uniqcalls'][$EXT])? $CALLS['uniqcalls'][$EXT] :'-',
								isset($CALLS['totaltime'][$EXT])? getTimeFormated($CALLS['totaltime'][$EXT]) : "-");
		}
		
		return;
	}
?>

		<div id="table_time"><span style="color:white;">CALLS</span> / <?php echo $title;?></div> 
		<table id="calls_table" width="100%" cellpadding="3" cellspacing="0" >
			<thead>
				<tr>
					<th style="width: 10%;">Ext</th>
					<th style="width: 10%;">User</th>
					<th style="width: 17%;">&lt; 1 min</th>
					<th style="width: 17%;">1 - 5 min</th>
					<th style="width: 17%;">&gt; 5 mins</th>
					<th style="width: 17%;">Total<br /><span style="font-size: 10px;">(unique)</span></th>
					<th class="descending" style="width: 17%;">Total<br /><span style="font-size: 10px;">(hh:mm:ss)</span></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($extlist AS $EXT ) : ?>
				<tr>
					<td><?php echo $EXT; ?></td>
					<td><?php echo $namemap[$EXT]; ?></td>
					<td><?php echo isset($CALLS['call1min'][$EXT])? $CALLS['call1min'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['call5min'][$EXT])? $CALLS['call5min'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['callplus5'][$EXT])? $CALLS['callplus5'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['uniqcalls'][$EXT])? $CALLS['uniqcalls'][$EXT] :'-'; ?></td>
					<td><?php echo isset($CALLS['totaltime'][$EXT])? getTimeFormated($CALLS['totaltime'][$EXT]) : "-"; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>