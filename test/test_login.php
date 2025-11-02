<?php

include_once('db_connect.php');

$database = new Database();

$db = $database->getConnection();

$name = "praveen";
$password = "1234";
$email = "praveen@gmail.com";

// 1. Get credentials
// $data = json_decode(file_get_contents("php://input"));
// $email = $data->email;
// $password = $data->password;

// 2. Find user
$query = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $email);
$stmt->execute();

// 3. Verify user exists
if ($stmt->rowCount() == 1) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 4. Verify password
    if (password_verify($password, $user['password'])) {
        // 5. Generate token
        $token_data = [
            "user_id" => $user['id'],
            "email" => $user['email'],
            "timestamp" => time()
        ];
        $token = base64_encode(json_encode($token_data));
        
        // 6. Return success
        echo json_encode([
            "message" => "Login successful",
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email']
            ]
        ]);
    }
}