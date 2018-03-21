<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$request_id=intval($_POST['request_id']);
	$action=intval($_POST['action']);
	if ($action==1) {
		# code...
		$sql_update="UPDATE friends2 SET status = 1 WHERE friends_id=$request_id";
		$res_update=$mysqli->query($sql_update);
		if ($res_update) {
			# code...
			$response['status']=1;
			$response['message']='Friend Request Accepted';
		}
	}
	else
	{
		$sql_delete="DELETE FROM friends2 WHERE friends_id=$request_id";
		$res_delete=$mysqli->query($sql_delete);
		if ($res_delete) {
			# code...
			$response['status']=1;
			$response['message']='Friend Request Deleted';
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

