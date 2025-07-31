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

include "../config.php";
$tag_id=$_GET["id"];
$sql="DELETE FROM tags WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $tag_id);

if($stmt->execute()){
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => "Tag gelöscht"]);
    exit;
}



$stmt->close();

?>