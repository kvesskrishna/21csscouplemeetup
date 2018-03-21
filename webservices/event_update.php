<?php
require_once('dbconfig.php');
$verb = $_SERVER['REQUEST_METHOD'];
//HANDLE POST REQUEST START
//-----------------------
function update_event()
	{
		global $mysqli;
		$event_title=mysqli_real_escape_string($mysqli,$_POST['event_title']);
		$event_description=mysqli_real_escape_string($mysqli,$_POST['event_description']);
		$event_datetime=mysqli_real_escape_string($mysqli,$_POST['event_datetime']);
		$event_location=mysqli_real_escape_string($mysqli,$_POST['event_location']);
		$event_find=mysqli_real_escape_string($mysqli,$_POST['event_find']);
			$event_id=intval($_POST['event_id']);
		$sql="UPDATE events SET event_title='{$event_title}', event_description='{$event_description}', event_datetime='{$event_datetime}', event_location='{$event_location}', event_find='{$event_find}' WHERE event_id={$event_id}";
		$res=$mysqli->query($sql);
		if(!$res)
		{
			$response['error']=$mysqli->error;
			http_response_code(400);
		}
		$event_id=mysqli_insert_id($mysqli);
		$response['message']="Event Update Successful!";
		$response['event_id']=$event_id;
		header('Content-Type: application/json');
		echo json_encode($response, JSON_UNESCAPED_SLASHES);
	}
//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------
//-----------------------
//HANDLE GET REQUEST END





switch ($verb) {
	case 'POST':
		update_event();
		break;
	
	default:
		http_response_code(405);
		break;
}
?>