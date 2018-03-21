<?php
require_once('dbconfig.php');
$response=array();
define( 'API_ACCESS_KEY', 'AAAAtcwHo8w:APA91bFD4gR3NEYTeRJmOE0l_uyNAK6uXjZVT-ln-eaFb-BFHW0paX9Eqdp_x-gSzmZE9VbksHRllPgAr2vRcLbDiW6uu6bDVUt2Xop13bwLF-5YJgVSsuWIQWdsIBikFQ2tP9VBdYrr' );
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}
$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);
//var_dump($profile_ids);
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}
$event_id=$decoded['event_id'];
$profile_ids=$decoded['profile_ids'];
$registrationIds=array();

foreach ($profile_ids as $profile) {
	# code...
	$sql_insert="INSERT INTO event_invitees (profile_id, event_id) VALUES ($profile,$event_id)";
	$res_insert=$mysqli->query($sql_insert);
	if (!$res_insert) {
		# code...
		die($mysqli->error);
	}
	$sql_getrecepients="SELECT * FROM users WHERE profile_id=$profile";
		$res_getrecepients=$mysqli->query($sql_getrecepients);
		while ($row_getrecepients=$res_getrecepients->fetch_assoc()) {
			# code...
			array_push($registrationIds, $row_getrecepients['firebase_token']);

			}
}
			$msg = array
			(
				'detail' 	=> 'Hello, You Have New Event Invitation From CoupleMeetup Profile!',
				'title'		=> 'New Event Invitation - CoupleMeetup',
				'invitation_id'=> $event_id
			);
			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $msg
			);
			 
			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch);
			if(curl_error($ch))
{
	$errors=curl_error($ch);
}
			curl_close( $ch );
			//echo $result;
$remlast=substr($result, 0,-1);
$newresult=$remlast.',"type":"event"}';
header('Content-Type: application/json');

echo $newresult;
?>