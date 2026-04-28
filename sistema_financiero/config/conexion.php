<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sistema_financiero";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}
?>