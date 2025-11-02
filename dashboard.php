<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $database = new Database();
    $db = $database->getConnection();
    
    $token = getBearerToken();
    
    if ($token) {
        $token_data = json_decode(base64_decode($token), true);
        $user_id = $token_data['user_id'];
        
        if (verifyToken($user_id, $db)) {
            // Get user stats (example data)
            $query = "SELECT name, email, created_at FROM users WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":id", $user_id);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            http_response_code(200);
            echo json_encode(array(
                "message" => "Welcome to Dashboard!",
                "user" => $user,
                "stats" => [
                    "total_visits" => 150,
                    "last_login" => date('Y-m-d H:i:s'),
                    "account_status" => "Active"
                ]
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied. Invalid token."));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Access denied. Token required."));
    }
}
?>