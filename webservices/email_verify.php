<?php
$token=$_GET['verify'];
$ch = curl_init();  
					    curl_setopt($ch,CURLOPT_URL,'http://www.21cssindia.com/couplemeetup/webservices/verifyemail?verify_token='.$token);
					    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					    $response=curl_exec($ch);
					    curl_close($ch);
					    $result = json_decode($response);
					    //var_dump($result);
					    $status=$result->status;
					    $statusmsg = $result->status_message;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Couple Meetup App - Email Verification</title>
</head>
<body>
<center>
	<h3><u>Couple Meetup App - Email Verification</u></h3>
	<img src="logo.png" height="256px" width="256px">
	<h4 style="<?php if($status==1) echo 'color:green'; else echo 'color:red';?>"><?php echo $statusmsg;?></h4>
</center>
</body>
</html>