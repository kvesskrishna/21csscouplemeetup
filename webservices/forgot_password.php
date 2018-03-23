<?php
require_once('dbconfig.php');
require_once 'PHPMailer/SMTPMailer.php';
$response=array();
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$user_email=$_POST['user_email'];
	$sql_check="SELECT * FROM users WHERE user_email='{$user_email}'";
	$res_check=$mysqli->query($sql_check);
	if($res_check->num_rows>0){
		$token=sha1(time().$user_email.rand(1,999));
		$sql_gen="UPDATE users SET password_reset_token='$token' WHERE user_email='$user_email'";
		$res_gen=$mysqli->query($sql_gen);
		if($res_gen){
			$msg="
			Dear User,<br>
			Please click on the link below to reset your Couple meetup account password. 
			<br>
			Follow this link: <a href='http://www.21cssindia.com/couplemeetup/webservices/createPassword?prt=$token'>
			CLICK HERE</a><br>
			Regards,<br>
			Couple Meetup Team.
			";
        SMTPMailer($user_email, 'Forgot Password request for Couple Meetup account', $msg);

		}
		$response['status']=1;
		$response['message']='Reset Mail triggered';
	}	
	else{
		$response['status']=0;
		$response['message']='No such user';
	}

}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>

