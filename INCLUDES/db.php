<?php
$host = "localhost";
$db = "vzone";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error with db connection". $conn->connect_error);
}
?>