<?php
require_once('dbconfig.php');
$verb = $_SERVER['REQUEST_METHOD'];
//HANDLE POST REQUEST START
//-----------------------
//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------
function get_attendees($event_id=0){
	global $mysqli;

		$query="SELECT * FROM events";
		if($event_id != 0)
		{
			$query.=" WHERE event_id=".$event_id;
		}
		$result=$mysqli->query($query);
		while($row=$result->fetch_assoc())
		{
			$sql="SELECT * FROM event_invitees WHERE accepted=1 AND event_id=".$row['event_id'];
			$res=$mysqli->query($sql);
			while($rows=$res->fetch_assoc()){
				$sqlp="SELECT * FROM profiles WHERE profile_id=".$rows['profile_id'];
				$resp=$mysqli->query($sqlp);
				$row['profiles'][]=$resp->fetch_assoc();
			}


			$response[]=$row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
//-----------------------
//HANDLE GET REQUEST END





switch ($verb) {
	case 'GET':
		if(!empty($_GET["event_id"]))
			{
				$event_id=intval($_GET["event_id"]);
				get_attendees($event_id);
			}
			else
			{
				get_attendees();
			}
		break;
	
	default:
		http_response_code(405);
		break;
}
?>