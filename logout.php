<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // In a real application, you would:
    // 1. Add token to blacklist
    // 2. Clear session data
    // 3. Update database
    
    http_response_code(200);
    echo json_encode(array("message" => "Logged out successfully."));
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>