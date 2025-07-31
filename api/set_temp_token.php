<?php

session_start();

require_once '../config.php';


//error_log("SSS".$_SESSION['id']);
$sql = "INSERT into temp_tokens (token, user_id, expires_at) VALUES (?,?, NOW() + INTERVAL 10 MINUTE)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $token, $user_id);


$user_id = $_SESSION['id'];
$token = bin2hex(random_bytes(32));
unset($_SESSION['isbn_scan']);

if($stmt->execute()){

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'token'=> $token]);
    exit;
    

}






?>