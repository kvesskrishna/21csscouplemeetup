<?php
require_once('dbconfig.php');
$response=array();

if ($_SERVER['REQUEST_METHOD']=='POST')
{
	if(!empty($_POST['user_email'])&&!empty($_POST['invite_token']))
	{		
		$user_email=$_POST['user_email'];
		$invite_token=$_POST['invite_token'];
		$invite_url = "http://www.21cssindia.com/invite?token=".$invite_token;
		$sql_email_exists="SELECT * FROM users WHERE user_email='$user_email'";
		$res_email_exists=$mysqli->query($sql_email_exists);
		if ($res_email_exists->num_rows<1) {
			# code...
			http_response_code(204);
			exit();
		}
		$row_email_exists=$res_email_exists->fetch_assoc();

		$sql_profile_name2="SELECT * FROM profiles WHERE profile_id=".$row_email_exists['profile_id'];
		$res_profile_name2=$mysqli->query($sql_profile_name2);
		$row_profile_name2=$res_profile_name2->fetch_assoc();
		$profile_name2=$row_profile_name2['profile_name'];

		$sql_get_profile = "SELECT * FROM profiles WHERE invite_url='$invite_url'";
		$res_get_profile = $mysqli->query($sql_get_profile);
		$row_get_profile = $res_get_profile->fetch_assoc();
		$master_profileid=$row_get_profile['profile_id'];
		$profile_name1=$row_get_profile['profile_name'];

		$sql_profile_count= "SELECT user_id FROM users WHERE profile_id=".$master_profileid;
		$res_profile_count=$mysqli->query($sql_profile_count);
		if ($res_profile_count->num_rows>1) {
			# code...
			http_response_code(400);
			exit();
		}
		$couple_name=$profile_name1." & ".$profile_name2;
		$sql_couplename="UPDATE profiles SET couple_name='{$couple_name}' WHERE profile_id=".$master_profileid;
		$res_couplename=$mysqli->query($sql_couplename);

		$sql_merge ="UPDATE users SET profile_id=$master_profileid WHERE user_email='$user_email'";
		$res_merge=$mysqli->query($sql_merge);
		if ($res_merge) {
		 	# code...
		 	$response['status']=1;
		 	$response['status_message']="Profile Merged Successfully";
		 	$response['merged_profile_id']=$master_profileid;
		 	$response['profile']=$row_get_profile;
		 } 

		else{
		# code...
			$response['status']=0;
			$response['status_message']="Profile Merging Failed";
	}
}
	else
	{
		$response['message']="Invalid Request, user_email/invite_token not defined";
		$response['status']=400;
	}
}

//PUT METHOD START
//PUT METHOD END
//-------------------------------


else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>