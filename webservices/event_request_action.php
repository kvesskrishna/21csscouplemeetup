<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$request_id=intval($_POST['notification_id']);
	$action=intval($_POST['action']);
	if ($action==3) {
		# code...
				$sql_delete="DELETE FROM event_invitees WHERE invitation_id=$request_id";
		$res_delete=$mysqli->query($sql_delete);
		if ($res_delete) {
			# code...
			$response['status']=1;
			$response['message']='Event Request Deleted';
		}

	}
	else
	{
				$sql_update="UPDATE event_invitees SET accepted = ".$action." WHERE invitation_id=$request_id";
		$res_update=$mysqli->query($sql_update);
		if ($res_update) {
			# code...
			$response['status']=1;
			if($action=1)
			{
				$status='Accepted';
			}
			else if($action=2){
				$status="Tentative";
			}
			$response['message']='Event Request is '.$status;
		}

	}
	

}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>

