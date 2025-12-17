<?php

$host = "localhost";      
$user = "root";           
$pass = "";               
$db   = "online_store";    


$conn = mysqli_connect($host, $user, $pass, $db);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


mysqli_set_charset($conn, "utf8");


?>
