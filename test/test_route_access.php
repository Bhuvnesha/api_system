<?php

include_once('db_connect.php');

$database = new Database();

$db = $database->getConnection();


function getAuthorizationHeader() {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

function getBearerToken() {
    $headers = getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function verifyToken($userId, $db) {
    $query = "SELECT id FROM users WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $userId);
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

// 1. Get token from header
// $token = getBearerToken();
$token = "eyJ1c2VyX2lkIjozLCJlbWFpbCI6InByYXZlZW5AZ21haWwuY29tIiwidGltZXN0YW1wIjoxNzYxOTc4NjM2fQ==1";

if ($token) {
    // 2. Decode token
    $token_data = json_decode(base64_decode($token), true);
    $user_id = $token_data['user_id'];
    
    // 3. Verify user exists
    if (verifyToken($user_id, $db)) {
        // 4. Get user data
        $query = "SELECT name, email, created_at FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 5. Return protected data
        echo json_encode([
            "message" => "Welcome to Dashboard!",
            "user" => $user
        ]);
    }
}