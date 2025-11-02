<?php
include_once 'config.php';

$database = new Database();
$db = $database->getConnection();

$token = getBearerToken();

if (!$token) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Token required."));
    exit();
}

$token_data = json_decode(base64_decode($token), true);
$user_id = $token_data['user_id'];

if (!verifyToken($user_id, $db)) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Invalid token."));
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get profile
        $query = "SELECT id, name, email, created_at FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode(array(
                "message" => "Profile retrieved successfully.",
                "profile" => $user
            ));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "User not found."));
        }
        break;
        
    case 'PUT':
        // Update profile
        $data = json_decode(file_get_contents("php://input"));
        
        $name = isset($data->name) ? $data->name : null;
        $email = isset($data->email) ? $data->email : null;
        $password = isset($data->password) ? $data->password : null;
        
        $query = "UPDATE users SET";
        $params = array();
        
        if ($name) {
            $query .= " name = :name,";
            $params[':name'] = $name;
        }
        
        if ($email) {
            // Check if email is already taken by another user
            $check_query = "SELECT id FROM users WHERE email = :email AND id != :id";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bindParam(":email", $email);
            $check_stmt->bindParam(":id", $user_id);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                http_response_code(400);
                echo json_encode(array("message" => "Email already taken."));
                exit();
            }
            
            $query .= " email = :email,";
            $params[':email'] = $email;
        }
        
        if ($password) {
            $query .= " password = :password,";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $query = rtrim($query, ',');
        $query .= " WHERE id = :id";
        $params[':id'] = $user_id;
        
        $stmt = $db->prepare($query);
        
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("message" => "Profile updated successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update profile."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}
?>