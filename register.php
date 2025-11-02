<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $data = json_decode(file_get_contents("php://input"));
    
    // echo "<pre>";
    // print_r($data);
    // echo "<pre>";

    // dd();

    if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
        $name = $data->name;
        $email = $data->email;
        $password = $data->password;
        
        // Check if email already exists
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(array("message" => "Email already exists."));
        } else {
            // Insert new user
            $query = "INSERT INTO users SET name=:name, email=:email, password=:password";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $password_hash);
            
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array(
                    "message" => "User created successfully.",
                    "user_id" => $db->lastInsertId()
                ));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user."));
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
    }
}
?>