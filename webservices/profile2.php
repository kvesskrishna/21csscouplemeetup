<?php
$mysqli = new mysqli('localhost','a21csabs_couplem','couplemeetup999','a21csabs_couplemeetup');
$myArray = array();
if ($result = $mysqli->query("SELECT * FROM profiles")) {

    while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
    }
    //print_r($myArray);
    header('Content-Type: application/json');

    echo json_encode($myArray,JSON_UNESCAPED_SLASHES);
}

$result->close();
$mysqli->close();
?>