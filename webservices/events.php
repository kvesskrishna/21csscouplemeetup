<?php
require_once('dbconfig.php');
$verb = $_SERVER['REQUEST_METHOD'];
//HANDLE POST REQUEST START
//-----------------------
function insert_event()
	{
		global $mysqli;
		$event_title=mysqli_real_escape_string($mysqli,$_POST['event_title']);
		$event_description=mysqli_real_escape_string($mysqli,$_POST['event_description']);
		$event_datetime=mysqli_real_escape_string($mysqli,$_POST['event_datetime']);
		$event_location=mysqli_real_escape_string($mysqli,$_POST['event_location']);
		$event_find=mysqli_real_escape_string($mysqli,$_POST['event_find']);
			$event_createdby=intval($_POST['event_createdby']);
		$sql="INSERT INTO events (event_title, event_description, event_datetime, event_location, event_find, event_createdby) VALUES ('{$event_title}','{$event_description}','{$event_datetime}','{$event_location}','{$event_find}', $event_createdby)";
		$res=$mysqli->query($sql);
		if(!$res)
		{
			$response['error']=$mysqli->error;
			http_response_code(400);
		}
		$event_id=mysqli_insert_id($mysqli);
		$response['message']="Event Creation Successful!";
		$response['event_id']=$event_id;
		header('Content-Type: application/json');
		echo json_encode($response, JSON_UNESCAPED_SLASHES);
	}
//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------
function get_events($profile_id=0){
	global $mysqli;

		$query="SELECT * FROM events";
		if($profile_id != 0)
		{
			$query.=" WHERE event_createdby=".$profile_id;
		}
		$result=$mysqli->query($query);
		while($row=$result->fetch_assoc())
		{
			$response[]=$row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
//-----------------------
//HANDLE GET REQUEST END





switch ($verb) {
	case 'GET':
		if(!empty($_GET["profile_id"]))
			{
				$profile_id=intval($_GET["profile_id"]);
				get_events($profile_id);
			}
			else
			{
				get_events();
			}
		break;
	case 'POST':
		insert_event();
		break;
	
	default:
		http_response_code(405);
		break;
}
?>