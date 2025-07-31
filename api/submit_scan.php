<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("LOCATION:/app");
    exit();
}
require_once '../config.php';


$isbn = $_POST['isbn_scan'];
$token = $_POST['token'];

// error_log("TTTTTTT: ".$isbn);

$sql = "UPDATE temp_tokens SET isbn_scan = ? WHERE token = '".$token."';";
error_log($sql. "   -->    ".$token. " / ".$isbn);

$stmt = $conn->prepare($sql);

$stmt->bind_param('s', $isbn);

if($stmt->execute()){
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;

}else{
    header('Content-Type: application/json');
    echo json_encode(['success' => false]);
    exit;

}




?>