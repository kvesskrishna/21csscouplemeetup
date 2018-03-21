<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='GET')
{
	$profile_id=intval($_GET['profile_id']);
	$sql_get="SELECT profile1_id FROM friends2 WHERE profile2_id=$profile_id AND status=1";
	$res_get=$mysqli->query($sql_get);
	$friends=array();
	if ($res_get) {
		# code...
		while ($row_get=$res_get->fetch_assoc()) {
			# code...
			$sql_friends="SELECT * FROM profiles WHERE profile_id = ".$row_get['profile1_id'];
			$res_friends=$mysqli->query($sql_friends);
			while ($row_friends=$res_friends->fetch_assoc()) {
				# code...
				$sql_users="SELECT * FROM users WHERE profile_id=".$row_friends['profile_id'];
			$res_users=$mysqli->query($sql_users);
			while ($row_users=$res_users->fetch_assoc()) {
				# code...
				unset($row_users['user_password']);
				$row_friends['users'][]=$row_users;


			}
				array_push($friends, $row_friends);
			}
			
			}
	}
	$sql_get="SELECT profile2_id FROM friends2 WHERE profile1_id=$profile_id AND status=1";
	$res_get=$mysqli->query($sql_get);
	if ($res_get) {
		# code...
		while ($row_get=$res_get->fetch_assoc()) {
			# code...
			$sql_friends="SELECT * FROM profiles WHERE profile_id = ".$row_get['profile2_id'];
			$res_friends=$mysqli->query($sql_friends);
			while ($row_friends=$res_friends->fetch_assoc()) {
				# code...
				$sql_users="SELECT * FROM users WHERE profile_id=".$row_friends['profile_id'];
			$res_users=$mysqli->query($sql_users);
			while ($row_users=$res_users->fetch_assoc()) {
				# code...
				unset($row_users['user_password']);
				$row_friends['users'][]=$row_users;


			}
				array_push($friends, $row_friends);
			}
			}
	}

$response=$friends;
}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>