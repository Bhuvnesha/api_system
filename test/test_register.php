<?php

include_once('db_connect.php');

$database = new Database();

$db = $database->getConnection();

$name = "praveen";
$password = "1234";
$email = "praveen@gmail.com";

// 1. Get input data
// $data = json_decode(file_get_contents("php://input"));
// $name = $data->name;
// $email = $data->email;
// $password = $data->password;

// 2. Check if email exists
$check_query = "SELECT id FROM users WHERE email = :email";
$check_stmt = $db->prepare($check_query);
$check_stmt->bindParam(":email", $email);
$check_stmt->execute();

// 3. If email doesn't exist, create user
if ($check_stmt->rowCount() == 0) {
    $insert_query = "INSERT INTO users SET name=:name, email=:email, password=:password";
    $insert_stmt = $db->prepare($insert_query);
    
    $insert_stmt->bindParam(":name", $name);
    $insert_stmt->bindParam(":email", $email);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $insert_stmt->bindParam(":password", $password_hash);
    
    if ($insert_stmt->execute()) {
        echo json_encode(["message" => "User created", "user_id" => $db->lastInsertId()]);
    }
}