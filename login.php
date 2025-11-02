<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->email) && !empty($data->password)) {
        $email = $data->email;
        $password = $data->password;
        
        $query = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $row['id'];
            $name = $row['name'];
            $email = $row['email'];
            $hashed_password = $row['password'];
            
            if (password_verify($password, $hashed_password)) {
                // Create token (in real app, use JWT)
                $token = base64_encode(json_encode([
                    "user_id" => $id,
                    "email" => $email,
                    "timestamp" => time()
                ]));
                
                http_response_code(200);
                echo json_encode(array(
                    "message" => "Login successful.",
                    "token" => $token,
                    "user" => [
                        "id" => $id,
                        "name" => $name,
                        "email" => $email
                    ]
                ));
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Login failed. Invalid password."));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "User not found."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to login. Data is incomplete."));
    }
}
?>