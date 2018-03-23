<?php
$token=$_GET['prt'];
require 'dbconfig.php';
$sql="SELECT * FROM users WHERE password_reset_token = '{$token}'";
$res=$mysqli->query($sql);
if($res->num_rows==0){
	die('Invalid Token / Token Expired!');
}
else
{
	$row=$res->fetch_assoc();
	$user_email=$row['user_email'];
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
	<form method="post" action="resetPassword" name="resetform" onsubmit="return comparepwd()">
		<table>
			<tr>
				<td>New Password</td>
				<td><input type="password" name="password" id="password"></td>
			</tr>
			<tr>
				<td>Confirm Password</td>
				<td><input type="password" name="cpassword" id="cpassword"></td>
			</tr>
			<input type="hidden" name="user_email" value="<?php echo $user_email?>">
			<tr>
				<td colspan="2" style="text-align: center"><input type="submit" name="submit" value="Reset Password"></td>
			</tr>
		</table>
	</form>
</center>
<script type="text/javascript">
	function comparepwd(){
		var pwd=document.getElementById('password').value;
		var cpwd=document.getElementById('cpassword').value;
		if(pwd!=cpwd){
			alert('Password and Confirm password are not same'+pwd+cpwd);
			return false;
		}
	}
</script>
</body>
</html>