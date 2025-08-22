<?php
$servername = "localhost";
$username = "root"; // Change to your phpMyAdmin username
$password = ""; // Change to your phpMyAdmin password
$dbname = "denguecare";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>