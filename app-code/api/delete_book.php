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
$sql="DELETE FROM books WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $delete);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

?>
