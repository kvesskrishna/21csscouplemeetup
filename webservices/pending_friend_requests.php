<?php
require_once('dbconfig.php');
$response=array();
if ($_SERVER['REQUEST_METHOD']=='GET')
{
	$profile_id=intval($_GET['profile_id']);
	$sql_get="SELECT * FROM friends2 WHERE profile2_id=$profile_id AND status=0";
	$res_get=$mysqli->query($sql_get);
	if ($res_get) {
		# code...
		while ($row_get=$res_get->fetch_assoc()) {
			# code...
			$sql="SELECT * FROM profiles WHERE profile_id=".$row_get['profile1_id'];
			$res=$mysqli->query($sql);
			while($rows=$res->fetch_assoc()){
				$row_get['sender_profile']=$rows;
			}
			$row['requests'][]=$row_get;



			}
			$response[]=$row;
			// prep the bundle
	}

}
else
{
	http_response_code(405);
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_SLASHES);

?>