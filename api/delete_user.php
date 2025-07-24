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
?>
<?php
include "../config.php";
$delete=$_GET["id"];
$sql="DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $delete);
$stmt->execute();
$conn->close();

?>