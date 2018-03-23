<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$user_email=$_POST['user_email'];
	$old_password=$_POST['old_password'];
	$new_password=$_POST['new_password'];

	$sql_check="SELECT * FROM users WHERE user_email='{$user_email}'";
	$res_check=$mysqli->query($sql_check);
	if($res_check->num_rows>0){
		$row=$res_check->fetch_assoc();
		if($row['user_password']==$old_password){
			$sql="UPDATE users SET user_password='{$_POST['new_password']}' WHERE user_email='{$_POST['user_email']}'";
			$res=$mysqli->query($sql);
			$response['status']=1;
			$response['message']='Password Updated Successfully';
		}
		else
		{
			$response['status']=0;
			$response['message']='Incorrect old password';

		}

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

