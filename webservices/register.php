<?php
require_once('dbconfig.php');
require_once 'PHPMailer/SMTPMailer.php';

$response=array();

if ($_SERVER['REQUEST_METHOD']=='POST')
{
	if(!empty($_POST['user_email']))
	{		
		$user_email=$_POST['user_email'];
		$sql_email_exists="SELECT * FROM users WHERE user_email='$user_email'";
		$res_email_exists=$mysqli->query($sql_email_exists);
		if ($res_email_exists->num_rows>0) {
			# code...
			http_response_code(409);
		}

		else{
		# code...
		$invite_token = sha1($user_email.time().rand(999,99999));
		$invite_url = "http://www.21cssindia.com/invite?token=".$invite_token;
		$profile_thumbnail="http://www.21cssindia.com/couplemeetup/profiles/thumbnails/default.png";
		if (is_uploaded_file($_FILES['profile_thumbnail']['tmp_name']))
		{
			# code...
			$tmp_file=$_FILES['profile_thumbnail']['tmp_name'];
			$file_name=time().$_FILES['profile_thumbnail']['name'];
			$upload_dir="../profiles/thumbnails/".$file_name;
			if (move_uploaded_file($tmp_file, $upload_dir)) 
			{
				# code...
				$profile_thumbnail="http://www.21cssindia.com/couplemeetup/profiles/thumbnails/".$file_name;
			}
		}

		$sql="INSERT INTO profiles (profile_name,profile_description,profile_location,profile_latitude,profile_longitude,profile_thumbnail,invite_url,partner1_name,partner2_name) VALUES ('{$mysqli->real_escape_string($_POST['profile_name'])}','{$mysqli->real_escape_string($_POST['profile_description'])}','{$mysqli->real_escape_string($_POST['profile_location'])}','{$_POST['profile_latitude']}','{$_POST['profile_longitude']}','$profile_thumbnail','$invite_url','{$mysqli->real_escape_string($_POST['partner1_name'])}','{$mysqli->real_escape_string($_POST['partner2_name'])}')";
		$res=$mysqli->query($sql);
		if(!$res)
		{
			http_response_code(400);
		}
		$profile_id=mysqli_insert_id($mysqli);
		$user_email=$_POST['user_email'];
		$user_password=null;
		if(isset($_POST['user_password'])){
			$user_password=$_POST['user_password'];
		}
		$sql_update_user="INSERT INTO users (user_email,user_password,profile_id,invite_token) VALUES ('$user_email','$user_password',$profile_id,'$invite_token')";
		$res_update_user=$mysqli->query($sql_update_user);
		if(!$res_update_user)
		{
			http_response_code(400);
		}
		
		$token = sha1($user_email.time());
		$sql_verify_email = "INSERT INTO verify_email (verify_email, verify_token) VALUES ('{$user_email}', '{$token}')";
		$res_verify_email = $mysqli->query($sql_verify_email);
		if ($res_verify_email) {
				$to = $user_email;
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

        


				if(SMTPMailer($to, $subject, $message)){
				    $response['status_message'] = 'Verification mail has been sent successfully.';
				    $response['profile_email_verified'] = 0;
									
				} 
				else{
				    $response['status_message'] = 'Unable to send email. Please try again.';
				}
				$response['status']=1;
			}		
		$response['message']="User Registration Successful!";
		$response['user_email']=$user_email;
		$sql_get_profile="SELECT * FROM profiles WHERE profile_id=$profile_id";
		$res_get_profile=$mysqli->query($sql_get_profile);
		$response['profile']=$res_get_profile->fetch_assoc();
	}
}
	else
	{
		$response['message']="Invalid Request, user_email not defined";
		$response['status']=400;
	}
}

elseif ($_SERVER['REQUEST_METHOD']=='GET') 
{
	# code...
	if(isset($_GET['profile_id']))
	{
		$profile_id=$_GET['profile_id'];
		$sql="SELECT * FROM profiles WHERE profile_id=$profile_id";
		$res=$mysqli->query($sql);
		if($res->num_rows==0)
			{ 
				http_response_code(204); 
			}
		$response['profile']=$res->fetch_assoc();
	}
	else
	{
			$sql="SELECT * FROM profiles";
		$res=$mysqli->query($sql);
		if($res->num_rows==0)
			{ 
				http_response_code(204); 
			}
			while ($row=$res->fetch_assoc()) {
				# code...
				array_push($response, $row);
			}

	}
	
}

//PUT METHOD START
elseif ($_SERVER['REQUEST_METHOD']=='PUT') {
	# code...
$profile_id=intval($_GET["profile_id"]);

$sql_get_profile="SELECT * FROM profiles WHERE profile_id=$profile_id";
$res_get_profile=$mysqli->query($sql_get_profile);

if ($res_get_profile->num_rows>0) {
	# code...
$row_get_profile=$res_get_profile->fetch_assoc();
$profile_name=$row_get_profile["profile_name"];
$profile_description=$row_get_profile["profile_description"];
$profile_location=$row_get_profile["profile_location"];
$profile_latitude=$row_get_profile["profile_latitude"];
$profile_longitude=$row_get_profile["profile_longitude"];

parse_str(file_get_contents("php://input"),$post_vars);
if(!empty($post_vars['profile_name'])) $profile_name=$post_vars["profile_name"];
if(!empty($post_vars['profile_description'])) $profile_description=$post_vars["profile_description"];
if(!empty($post_vars['profile_location'])) $profile_location=$post_vars["profile_location"];
if(!empty($post_vars['profile_latitude'])) $profile_latitude=$post_vars["profile_latitude"];
if(!empty($post_vars['profile_longitude'])) $profile_longitude=$post_vars["profile_longitude"];
$sql_update="UPDATE profiles SET profile_name='$profile_name', profile_description='$profile_description', profile_location='$profile_location', profile_latitude='$profile_latitude', profile_longitude='$profile_longitude' WHERE profile_id=$profile_id";
$res_update=$mysqli->query($sql_update);
if($res_update){
	$response['id']=$profile_id;
	$response['message']="Profile Updated Successfully";
}
else
	$response['message']="Cannot Update Profile";
}
else
$response['message']="No profile exists";
}
//PUT METHOD END
//-------------------------------


else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>