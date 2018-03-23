<?php
$token=$_GET['prt'];
require 'dbconfig.php';
$sql="UPDATE users SET user_password='{$_POST['password']}', password_reset_token='' WHERE user_email='{$_POST['user_email']}'";
$res=$mysqli->query($sql);
if(mysqli_affected_rows($mysqli)<1){
	die('Password update failed');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Couple Meetup App - Reset Password</title>

<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<style type="text/css">
	*{


    font-family: 'Open Sans', sans-serif;


	}
</style>
</head>
<body>
<center>
	<h3><u>Couple Meetup App - Reset Password</u></h3>
	<img src="logo.png" height="256px" width="256px">
	<h3>Password Updated Successfully!</h3>
</center>
</body>
</html>