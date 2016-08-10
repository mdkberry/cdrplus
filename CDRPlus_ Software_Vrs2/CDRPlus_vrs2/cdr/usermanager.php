<?php
	include("db.php");
	
	global $membership_db_name;
	global $cdr_db_name;
	global $locallink;
	
	$action = $_POST['action'];
	mysql_select_db($membership_db_name, $locallink);
	
	if($action == "read")
	{
		$usersArr = array();
		$q = "SELECT * FROM user";
		$users = mysql_query($q, $locallink);
		
		while($user = mysql_fetch_assoc($users))
		{
			$usersArr[] = array('id' => $user['id'], 'name' => $user['user'], 'ext' => $user['extension'], 'group' => $user['group'] );
		}
		
		echo json_encode($usersArr);
	}
	else if($action == 'update')
	{
		$usersArr = explode(',', $_POST['users']);
		for($i=0; $i < sizeof($usersArr); $i += 4)
		{
			$id    = $usersArr[$i];
			$ext   = $usersArr[$i + 1];
			$name  = $usersArr[$i + 2];
			$group = $usersArr[$i + 3];
			
			$query = "UPDATE user SET `user`='$name', `group`='$group', `extension`='$ext' WHERE `id`=$id";
			if(!mysql_query($query, $locallink)) { echo 'fail'; return; }
		}
		
		echo 'ok';
	}
	else if($action == 'delete')
	{
		$usersArr = explode(',', $_POST['users']);
		for($i=0; $i < sizeof($usersArr); $i++)
		{
			$id    = $usersArr[$i];
			$query = "DELETE FROM `user` WHERE `id`=$id";
			if(!mysql_query($query, $locallink)) { echo 'fail'; return; }
		}
		
		echo 'ok';
	}
?>
