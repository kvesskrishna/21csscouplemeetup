<?php
require_once('dbconfig.php');

$verb = $_SERVER['REQUEST_METHOD'];

//HANDLE POST REQUEST START
//-----------------------
function send_verify()
	{
		global $mysqli;
		if(!empty($_POST['verify_email']))
		{
			$token = sha1($_POST['verify_email'].time());
			$query_exists="SELECT * FROM verify_email WHERE verify_email='{$_POST['verify_email']}' LIMIT 1";
			$result_exists=$mysqli->query($query_exists);
			if($result_exists->num_rows>0)
			{
				$query = "UPDATE verify_email SET verify_token = '{$token}' WHERE verify_email = '{$_POST['verify_email']}'";
				
				}
			else
			{
				$query = "INSERT INTO verify_email (verify_email, verify_token) VALUES
				('{$_POST['verify_email']}', '{$token}')";
			}
			$result=$mysqli->query($query);
			//$response['status']=$query;
			if ($result) {
				$to = $_POST['verify_email'];
				$subject = 'Couple Meetup Email Verfication';
				$from = 'test@21cssindia.com';
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
				$message = '<html><body>';
				$message .= '<h1 style="color:#f40;">Hi!</h1>';
				$message .= '<p style="color:#080;font-size:14px;">Please click on the following link to verify your email with Couple Meetup.</p><br>
				<a href="http://www.21cssindia.com/couplemeetup/webservices/email_verify?verify='.$token.'"><h4>Verify Link</h4></a>';
				$message .= '</body></html>';
				if(mail($to, $subject, $message, $headers)){
				    $response['status_message'] = 'Verification mail has been sent successfully.';
									
				} 
				else{
				    $response['status_message'] = 'Unable to send email. Please try again.';
				}
				$response['status']=1;
			}				
				else
					$response=array(
					'status' => 0,
					'status_message' =>'Process Failed.'
				);

		}
		else
			$response=array(
					'status' => 0,
					'status_message' =>'Email/profile_id Empty.'
				);			
			header('Content-Type: application/json');
			echo json_encode($response, JSON_UNESCAPED_SLASHES);
	}
//-----------------------
//HANDLE POST REQUEST END


//HANDLE GET REQUEST START
//-----------------------

function check_verify()
{
	global $mysqli;
		if(!empty($_GET['verify_token']))
		{
			$query="SELECT * FROM verify_email WHERE verify_token='{$_GET['verify_token']}'";
			$result=$mysqli->query($query);
			if ($result->num_rows>0) {
				# code...
				$row = $result->fetch_assoc();
				$verify_email = $row['verify_email'];
				$query_profile = "SELECT profile_id FROM users WHERE user_email = '{$verify_email}'";
				$result_profile = $mysqli->query($query_profile);
				$row_profile = $result_profile->fetch_assoc();
				$profile_id = $row_profile['profile_id'];

				$query_update = "UPDATE profiles SET profile_email_verified=1 WHERE profile_id=$profile_id";
				$result_update=$mysqli->query($query_update);
				if($result_update)
				{
				$response=array(
					'status' => 1,
					'status_message' =>'Email Verification Success.'
				);
				}
			}
			else
				$response=array(
					'status' => 0,
					'status_message' =>'Email Verification Failed.'
				);
		}
		else
			$response=array(
					'status' => 0,
					'status_message' =>'Token Empty.'
				);			
			header('Content-Type: application/json');
			echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
//-----------------------
//HANDLE GET REQUEST END

switch ($verb) {
	case 'POST':
		send_verify();
		break;
	case 'GET':
		check_verify();
		break;
	default:
		http_response_code(405);
		break;
}


?>