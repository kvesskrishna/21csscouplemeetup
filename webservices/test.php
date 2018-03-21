<?php
require_once('dbconfig.php');
$response=array();
define( 'API_ACCESS_KEY', 'AAAAtcwHo8w:APA91bFD4gR3NEYTeRJmOE0l_uyNAK6uXjZVT-ln-eaFb-BFHW0paX9Eqdp_x-gSzmZE9VbksHRllPgAr2vRcLbDiW6uu6bDVUt2Xop13bwLF-5YJgVSsuWIQWdsIBikFQ2tP9VBdYrr' );
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$sender_profile=intval($_POST["sender_profile"]);
	$recepient_profile=intval($_POST["recepient_profile"]);
	$sql_insert="INSERT INTO friends (profile1_id, profile2_id) VALUES ($sender_profile,$recepient_profile)";
	$res_insert=$mysqli->query($sql_insert);
	if ($res_insert) {
			# code...
		$response['status']=1;
		}	
		else
			$response['status']=0;
}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>