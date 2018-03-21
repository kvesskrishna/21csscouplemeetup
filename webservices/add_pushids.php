<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$user=intval($_POST["user_id"]);
	$pushid=$_POST['firebase_token'];
	$sql_insert="UPDATE users SET firebase_token='$pushid' WHERE user_id=$user";
	$res_insert=$mysqli->query($sql_insert);
	if ($res_insert) {
		# code...
		$response['status']=1;
		$response['message']="Firebase token updated successfully";

	}

}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>