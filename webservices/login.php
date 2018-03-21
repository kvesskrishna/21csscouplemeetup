<?php
require_once('dbconfig.php');
$response=array();

if ($_SERVER['REQUEST_METHOD']=='POST') {
	# code...
if(isset($_POST['user_email'])&&!isset($_POST['user_password'])){
	$user_email=$_POST['user_email'];
	$sql_email_exists="SELECT * FROM users WHERE user_email='$user_email'";
	$res_email_exists=$mysqli->query($sql_email_exists);
	if($res_email_exists->num_rows>0){
		$row_email_exists=$res_email_exists->fetch_assoc();
		$profile_id=$row_email_exists['profile_id'];
		$user_id=$row_email_exists['user_id'];
		if(!empty($profile_id)){
			$sql_emailverify="UPDATE profiles SET profile_email_verified=1 WHERE profile_id=$profile_id";
			$res_emailverify=$mysqli->query($sql_emailverify);
			$sql_get_profile="SELECT * FROM profiles WHERE profile_id=$profile_id";
			$res_get_profile=$mysqli->query($sql_get_profile);
			$response['message']="Login Success";
			$response['user_id']=$user_id;
			$response['user_email']=$user_email;
			$response['profile']=$res_get_profile->fetch_assoc();
		}		
	}
else{
			
$invite_token = sha1($user_email.time().rand(999,99999));
$invite_url = "http://www.21cssindia.com/invite?token=".$invite_token;
$sql="INSERT INTO profiles (profile_name,profile_description,profile_location,profile_latitude,profile_longitude,profile_thumbnail,invite_url, profile_email_verified) VALUES ('null','null','null',null,null,'null','{$invite_url}',1)";
		$res=$mysqli->query($sql);
		if(!$res)
		{
			http_response_code(400);
		}
		$profile_id=mysqli_insert_id($mysqli);
		
		$user_password=null;
		
		$sql_update_user="INSERT INTO users (user_email,user_password,profile_id,invite_token) VALUES ('$user_email','$user_password',$profile_id,'$invite_token')";
		$res_update_user=$mysqli->query($sql_update_user);
		if(!$res_update_user)
		{
			http_response_code(400);
		}
			
		$response['message']="User Login Successful!";
		$response['user_email']=$user_email;
		$sql_get_profile="SELECT * FROM profiles WHERE profile_id=$profile_id";
		$res_get_profile=$mysqli->query($sql_get_profile);
		$response['profile']=$res_get_profile->fetch_assoc();

		}
}
elseif(isset($_POST['user_email'])&&isset($_POST['user_password'])){
	$user_email=$_POST['user_email'];
	$user_password=$_POST['user_password'];
	$sql_email_exists="SELECT * FROM users WHERE user_email='$user_email'";
	$res_email_exists=$mysqli->query($sql_email_exists);
	if($res_email_exists->num_rows==0){
		http_response_code(204);
	}
	else{
	$sql_email_valid="SELECT * FROM users WHERE user_email='$user_email' AND user_password='$user_password'";
	$res_email_valid=$mysqli->query($sql_email_valid);
	if($res_email_valid->num_rows>0){
		$row_email_valid=$res_email_valid->fetch_assoc();
		$profile_id=$row_email_valid['profile_id'];
		$user_id=$row_email_valid['user_id'];
		if(!empty($profile_id)){
			$sql_get_profile="SELECT * FROM profiles WHERE profile_id=$profile_id";
			$res_get_profile=$mysqli->query($sql_get_profile);
			$response['message']="Login Success";
			$response['user_id']=$user_id;
			$response['user_email']=$user_email;
			$response['profile']=$res_get_profile->fetch_assoc();
		}		
	}
else{
			$response['message']="Invalid Credentials";
				http_response_code(401);

		}
	}
}
}

elseif ($_SERVER['REQUEST_METHOD']=='GET') {
	# code...
	
	
}
else
{
	http_response_code(405);
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);
?>