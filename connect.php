<?php
$host = 'localhost';
$db = 'yr11_test_db';
$user = 'root';
$pass = '';
$conn=new mysqli($host, $user, $pass, $db);
date_default_timezone_set('Australia/Melbourne');
if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error); // die() stops the script and outputs the msg
    }
    $conn->close();
    ?>