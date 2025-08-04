<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
	$response = [
	    "error" => "not authorized"
	];
	echo json_encode($response);
    exit();
}

require_once "../config.php";
$delete_id=$_GET["id"];
$sql="UPDATE books SET sold = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $delete_id);
if($stmt->execute()){
	header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => "Buch gelöscht"]);
    exit;
}


?>
