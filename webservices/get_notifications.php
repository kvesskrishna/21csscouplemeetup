<?php
require_once('dbconfig.php');
$verb = $_SERVER['REQUEST_METHOD'];
//HANDLE POST REQUEST START
//-----------------------
//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------
function get_notifications($profile_id=0){
	global $mysqli;

		$query="SELECT * FROM event_invitees WHERE profile_id=".$profile_id;
		$notifications=array();
		$result=$mysqli->query($query);
		while($row=$result->fetch_assoc())
		{
			
			$response['notification_type']='event';
			$response['notification_status']=$row['accepted'];
			$response['notification_id']=$row['invitation_id'];
			$response['notification_datetime']=$row['created'];

			$sql_notifier="SELECT * FROM events WHERE event_id=".$row['event_id'];
			$res_notifier=$mysqli->query($sql_notifier);
			$row_notifier=$res_notifier->fetch_assoc();
			$response['notification_by']=$row_notifier['event_createdby'];
			$sql_getprofile="SELECT * FROM profiles WHERE profile_id=".$response['notification_by'];
			$res_getprofile=$mysqli->query($sql_getprofile);
			$row_getprofile=$res_getprofile->fetch_assoc();
			$response['notifier_profile_name']=$row_getprofile['profile_name'];
			$response['notifier_thumbnail']=$row_getprofile['profile_thumbnail'];
			$response['notification_title']=$row_notifier['event_title'];
			$response['event_details']=$row_notifier;
			array_push($notifications, $response);
			//echo $row['event_id'];
		}
		$query2="SELECT * FROM friends2 WHERE profile2_id=".$profile_id;
		$result2=$mysqli->query($query2);
		while($row2=$result2->fetch_assoc())
		{
			$response2['notification_type']='friend';
			$response2['notification_status']=$row2['status'];
			$response2['notification_by']=$row2['profile1_id'];
			$sql_getprofile2="SELECT * FROM profiles WHERE profile_id=".$response2['notification_by'];
			$res_getprofile2=$mysqli->query($sql_getprofile2);
			$row_getprofile2=$res_getprofile2->fetch_assoc();
			$response2['notifier_profile_name']=$row_getprofile2['profile_name'];
			$response2['notifier_thumbnail']=$row_getprofile2['profile_thumbnail'];
			$response2['notification_title']='New Friend Request';
			array_push($notifications, $response2);
			//echo $row['event_id'];
		}

		header('Content-Type: application/json');
		echo json_encode($notifications, JSON_UNESCAPED_SLASHES);
}
//-----------------------
//HANDLE GET REQUEST END





switch ($verb) {
	case 'GET':
		if(!empty($_GET["profile_id"]))
			{
				$profile_id=intval($_GET["profile_id"]);
				get_notifications($profile_id);
			}
			else
			{
				get_notifications();
			}
		break;
	
	default:
		http_response_code(405);
		break;
}
?>