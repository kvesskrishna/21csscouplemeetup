<?php
require_once('dbconfig.php');
$response=array();

if ($_SERVER['REQUEST_METHOD']=='POST')
{
	if(!empty($_POST['profile_id']))
	{		
		$profile_id=$_POST['profile_id'];
		$sql_get="SELECT * FROM profiles WHERE profile_id=$profile_id";
		$res_get=$mysqli->query($sql_get);
		if ($res_get->num_rows==0) {
			# code...
			http_response_code(204);
		}

		else{
			$row_get=$res_get->fetch_assoc();

		# code...
		$profile_thumbnail=$row_get['profile_thumbnail'];
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

		$sql="UPDATE profiles SET profile_thumbnail='$profile_thumbnail' WHERE profile_id=$profile_id";
		$res=$mysqli->query($sql);
		if(!$res)
		{
			http_response_code(400);
		}
		$response['status']=200;
		$response['message']="Thumbnail update Successful!";
		$response['profile_thumbnail']=$profile_thumbnail;
	}
}
	else
	{
		$response['message']="Invalid Request, profile_id not defined";
		$response['status']=400;
	}
}


else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>