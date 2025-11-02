<?php

include_once('db_connect.php');

$database = new Database();

$db = $database->getConnection();

// $name = "Bhuvnesh";
// $password = "1234";
// $email = "bhuvi@gmail.com";


// // insert user
// $query = "INSERT INTO users SET name=:name, email=:email, password=:password";
// $stmt = $db->prepare($query);
// $stmt->bindParam(":name", $name);
// $stmt->bindParam(":email", $email);
// $password_hash = password_hash($password, PASSWORD_DEFAULT);
// $stmt->bindParam(":password", $password_hash);

// if($stmt->execute())
// {
// 	echo "Insert Success";
// }
// else
// {
// 	echo "Failed to insert";
// }