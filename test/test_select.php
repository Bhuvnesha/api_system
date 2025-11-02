<?php

include_once('db_connect.php');

$database = new Database();

$db = $database->getConnection();

$name = "Bhuvnesh";
$password = "1234";
$email = "bhuvi@gmail.com";


// select user
$query = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $email);
$result = $stmt->execute();

echo "<pre>";
print_r($result);
echo "<pre>";