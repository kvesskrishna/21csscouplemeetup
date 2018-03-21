<?php
require_once('dbconfig.php');
$response=array();
define( 'API_ACCESS_KEY', 'AAAAtcwHo8w:APA91bFD4gR3NEYTeRJmOE0l_uyNAK6uXjZVT-ln-eaFb-BFHW0paX9Eqdp_x-gSzmZE9VbksHRllPgAr2vRcLbDiW6uu6bDVUt2Xop13bwLF-5YJgVSsuWIQWdsIBikFQ2tP9VBdYrr' );
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$sender_profile=intval($_POST['sender_profile']);
	$recepient_profile=intval($_POST['recepient_profile']);
	$sql_chkrq="SELECT * FROM friends2 WHERE profile1_id=$sender_profile AND profile2_id=$recepient_profile";
	$res_chkrq=$mysqli->query($sql_chkrq);
	if($res_chkrq->num_rows>0) $newresult='{"message":"Friend request already sent"}';

else{
	$sql_insert="INSERT INTO friends2 (profile1_id, profile2_id) VALUES ($sender_profile,$recepient_profile)";
	$res_insert=$mysqli->query($sql_insert);
	$friends_id=mysqli_insert_id($mysqli);
	if ($res_insert) {
		# code...
		
		$registrationIds=array();
		$sql_getrecepients="SELECT * FROM users WHERE profile_id=$recepient_profile";
		$res_getrecepients=$mysqli->query($sql_getrecepients);
		while ($row_getrecepients=$res_getrecepients->fetch_assoc()) {
			# code...
			array_push($registrationIds, $row_getrecepients['firebase_token']);

			}
			// prep the bundle

			$msg = array
			(
				'detail' 	=> 'Hello, You Have New Friend Request From CoupleMeetup Profile!',
				'title'		=> 'New Friend Request - CoupleMeetup',
				'request_id'=> $friends_id
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
			curl_close( $ch );
			//echo $result;
		

	}
	$remlast=substr($result, 0,-1);
$newresult=$remlast.',"type":"friend"}';

}
}
else
{
	http_response_code(405);
}
header('Content-Type: application/json');
echo $newresult;

?>