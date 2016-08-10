<?php
	include("db.php");
	
	global $membership_db_name;
	global $cdr_db_name;
	
	if($_GET['range'] == 'today')
	{
		$CALLS = extractTeamData( date("Y-m-d").' '.date("00:00:00"), date("Y-m-d").' '.date("23:59:59") );
		$title = "Today";
	}
	else if($_GET['range'] == 'thisweek' || !isset($_GET['range']))
	{
		$mon = date("N") - 1;
		$sun = 7 - date("N");
		
		$CALLS = extractTeamData( date("Y-m-d", strtotime("-$mon days ")).' '.date("00:00:00"), date("Y-m-d", strtotime("+$sun days")).' '.date("23:59:59") );
		$title = "This Week";
	}
	else 
	{
		$CALLS = extractTeamData( date("Y-m-01").' '.date("00:00:00"), date("Y-m-t").' '.date("23:59:59") );
		$title = "This Month";
	}
	
	if(isset($_GET['export']))
	{
		$export = array();
		$export[] = array("Ext", "User", "Team", "Total (unique)", "Total (hh:mm:ss)", "Top Callers");

		foreach ($teamlist AS $teamname => $extlist)
		{
			$q = "SELECT * FROM acl_group WHERE name='$teamname'";
			$teams = mysql_query($q, $locallink);
			$team = mysql_fetch_assoc($teams);
			$teamid = $team['id'];
			
			$q = "SELECT u.name, u.extension FROM acl_user AS u, acl_membership AS m WHERE u.id=m.id_user AND m.id_group=$teamid";
			$users = mysql_query($q, $locallink);
			$user = mysql_fetch_assoc($users);
			$username = $user['name'];
			$userext = $user['extension'];
			
			$export[] = array($userext, $username, $teamname, 
				isset($CALLS['uniqcalls'][$team])? $CALLS['uniqcalls'][$team] :'-',
				isset($CALLS['totaltime'][$team])? getTimeFormated($CALLS['totaltime'][$team]) : '-',
				!empty($CALLS['top3'][$team])? implode(" ", $CALLS['top3'][$team]) : '-');
		}
		
		return;
	}	
?>

	<div id="table_time"><span style="color:white;">TEAMS</span> / <?php echo $title;?></div> 
	<table id="calls_table" width="100%" cellpadding="3" cellspacing="0" >
		<thead>
			<tr>
				<th style="width: 20%;">Team</th>
				<th style="width: 20%;">Total<br /><span style="font-size: 10px;">(unique)</span></th>
				<th class="descending"  style="width: 20%;">Total<br /><span style="font-size: 10px;">(hh:mm:ss)</span></th>
				<th style="width: 35%;">Top Callers</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($teamlist AS $team => $extlist) : ?>	
			<tr>
				<td><?php echo $team; ?></td>
				<td><?php echo isset($CALLS['uniqcalls'][$team])? $CALLS['uniqcalls'][$team] :'-'; ?></td>
				<td><?php echo isset($CALLS['totaltime'][$team])? getTimeFormated($CALLS['totaltime'][$team]) : "-"; ?></td>
				<td><?php echo !empty($CALLS['top3'][$team])? implode(" ", $CALLS['top3'][$team]) : "-"; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
