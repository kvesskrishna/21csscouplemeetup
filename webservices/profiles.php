<?php
require_once('dbconfig.php');

$verb = $_SERVER['REQUEST_METHOD'];

//HANDLE POST REQUEST START
//-----------------------

//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------
function get_profiles($profile_id=0,$user_profile)
	{
		global $mysqli;
		if ($profile_id==0) {
			# code...
			$query="SELECT * FROM profiles WHERE profile_id<>$user_profile";
			$result=$mysqli->query($query);
			while($row=$result->fetch_assoc())
		{
			$sql_friendpairs="SELECT * FROM friends2 WHERE (profile1_id=".$row['profile_id']." AND profile2_id=$user_profile) OR (profile2_id=".$row['profile_id']." AND profile1_id=$user_profile)";
			$res_friendpairs=$mysqli->query($sql_friendpairs);
			if ($res_friendpairs->num_rows>0) {
				# code...
				$row_friendpairs=$res_friendpairs->fetch_assoc();
				if ($row_friendpairs['status']==1) {
					# code...
				$row['friendship']="Friends";
				$row['friends_id']=$row_friendpairs['friends_id'];

				}
				else{
					if($row_friendpairs['profile1_id']==$user_profile){
				$row['friendship']="Friend Request Sent";
								$row['friends_id']=$row_friendpairs['friends_id'];

			}
			else{ 
				$row['friendship']="Accept Request";
								$row['friends_id']=$row_friendpairs['friends_id'];

			}
		}
			}
			else{
				$row['friendship']="Send Friend Request";
								$row['friends_id']=null;

			}
//echo $row['friendship']."<br>";
			//$response['profile']=$row;
			$sql_users="SELECT * FROM users WHERE profile_id=".$row['profile_id'];
			$res_users=$mysqli->query($sql_users);
			while ($row_users=$res_users->fetch_assoc()) {
				# code...

				$row['users'][]=$row_users;
			}
//			array_push($response, $row);
//			var_dump($response);
			$response[]=$row;
			//var_dump($response);
		}

		}
		
		if($profile_id != 0)
		{
			$query="SELECT * FROM profiles WHERE profile_id=".$profile_id;
			$result=$mysqli->query($query);
		$row=$result->fetch_assoc();
		//var_dump($row);
		$sql_friendpairs="SELECT * FROM friends2 WHERE (profile1_id=".$row['profile_id']." AND profile2_id=$user_profile) OR (profile2_id=".$row['profile_id']." AND profile1_id=$user_profile)";
			$res_friendpairs=$mysqli->query($sql_friendpairs);
			if ($res_friendpairs->num_rows>0) {
				$row_friendpairs=$res_friendpairs->fetch_assoc();
				if ($row_friendpairs['status']==1) {
					# code...
				$row['friendship']="Friends";
				$row['friends_id']=$row_friendpairs['friends_id'];
				}
				else{
					if($row_friendpairs['profile1_id']==$user_profile){
				$row['friendship']="Friend Request Sent";
				$row['friends_id']=$row_friendpairs['friends_id'];
					}
					else{ 
						$row['friendship']="Accept Request";
						$row['friends_id']=$row_friendpairs['friends_id'];
					}
				}
			}
			else{
				$row['friendship']="Send Friend Request";
				$row['friends_id']=null;
			}
//echo $row['friendship'];
			$sql_users="SELECT * FROM users WHERE profile_id=".$row['profile_id'];
			$res_users=$mysqli->query($sql_users);
			while ($row_users=$res_users->fetch_assoc()) {
				# code...
				unset($row_users['user_password']);
				$row['users'][]=$row_users;
			}
//			var_dump($row);
			$response[]=$row;
		}		
	header('Content-Type: application/json');
	echo json_encode($response, JSON_UNESCAPED_SLASHES);
	}
//-----------------------
//HANDLE GET REQUEST END





//HANDLE PUT REQUEST START
//-----------------------
//-----------------------
//HANDLE PUT REQUEST END


//HANDLE DELETE REQUEST START
//-----------------------

//-----------------------
//HANDLE DELETE REQUEST END

switch ($verb) {
	case 'GET':
		if(!empty($_GET["profile_id"]))
			{
				$profile_id=intval($_GET["profile_id"]);
				$user_profile=intval($_GET["user_profile"]);
				get_profiles($profile_id,$user_profile);
			}
			else
			{
				$user_profile=intval($_GET["user_profile"]);
				get_profiles(0,$user_profile);
			}
		break;
	
	default:
		http_response_code(405);
		break;
}
?>