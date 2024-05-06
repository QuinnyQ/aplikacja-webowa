<?php
$servername = "mysql.cba.pl";
$username = "kingapabinczyk";
$password = "pracaGranum345!1";
$dbname = "kingapabinczyk";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
mysqli_query($conn, "SET CHARSET utf8");
mysqli_query($conn, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
