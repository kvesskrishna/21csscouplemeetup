<?php
require_once('dbconfig.php');
$verb = $_SERVER['REQUEST_METHOD'];
//HANDLE POST REQUEST START
//-----------------------
//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------
function get_events($event_id=0){
	global $mysqli;

		$query="SELECT * FROM events";
		if($event_id != 0)
		{
			$query.=" WHERE event_id=".$event_id;
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
		if(!empty($_GET["event_id"]))
			{
				$event_id=intval($_GET["event_id"]);
				get_events($event_id);
			}
			else
			{
				get_events();
			}
		break;
	
	default:
		http_response_code(405);
		break;
}
?>