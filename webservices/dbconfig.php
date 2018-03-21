<?php
//Open a new connection to the MySQL server
$mysqli = new mysqli('localhost','a21csabs_couplem','couplemeetup999','a21csabs_couplemeetup');

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

?>