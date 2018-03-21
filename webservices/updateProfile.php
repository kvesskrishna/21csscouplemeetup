<?php
require_once('dbconfig.php');
$response=array();

if ($_SERVER['REQUEST_METHOD']=='POST')
{
	$profile_id=intval($_POST["profile_id"]);

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
$profile_thumbnail=$row_get_profile["profile_thumbnail"];
$profile_couplename=$row_get_profile['couple_name'];
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
if(!empty($_POST['profile_name'])) $profile_name=$mysqli->real_escape_string($_POST["profile_name"]);
if(!empty($_POST['profile_description'])) $profile_description=$mysqli->real_escape_string($_POST["profile_description"]);
if(!empty($_POST['profile_location'])) $profile_location=$mysqli->real_escape_string($_POST["profile_location"]);
if(!empty($_POST['profile_latitude'])) $profile_latitude=$mysqli->real_escape_string($_POST["profile_latitude"]);
if(!empty($_POST['profile_longitude'])) $profile_longitude=$mysqli->real_escape_string($_POST["profile_longitude"]);
if(!empty($_POST['couple_name'])) $profile_couplename=$mysqli->real_escape_string($_POST["couple_name"]);
if(!empty($_POST['partner1_name'])) $profile_partner1name=$mysqli->real_escape_string($_POST["partner1_name"]);
if(!empty($_POST['partner2_name'])) $profile_partner2name=$mysqli->real_escape_string($_POST["partner2_name"]);

$sql_update="UPDATE profiles SET profile_name='$profile_name', profile_description='$profile_description', profile_location='$profile_location', profile_latitude='$profile_latitude', profile_longitude='$profile_longitude', profile_thumbnail='$profile_thumbnail', couple_name='$profile_couplename', profile_modified_on=now(),partner1_name='$profile_partner1name', partner2_name='$profile_partner2name' WHERE profile_id=$profile_id";
$res_update=$mysqli->query($sql_update);
if($res_update){
	$response['status']=1;
	$response['id']=$profile_id;
	$response['message']="Profile Updated Successfully";
	$query_profile="SELECT * FROM profiles WHERE profile_id=$profile_id";
	$result_profile=$mysqli->query($query_profile);
	$row_profile = $result_profile->fetch_assoc();
	$response['profile'] = $row_profile;
}
else
	$response['message']="Cannot Update Profile";
$response['error']=$mysqli->error;
}
else
$response['message']="No profile exists";
}


else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>