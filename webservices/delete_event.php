<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$event_id=$_POST['event_id'];
		$sql_delete="DELETE FROM events WHERE event_id=".$event_id;
		$res_delete=$mysqli->query($sql_delete);
		if ($res_delete) {
			# code...
			$response['status']=1;
			$response['message']='Event Deleted';
		}

	}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>

