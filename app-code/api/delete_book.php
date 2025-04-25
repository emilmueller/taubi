<?php
include "../config.php";
$delete=$_GET["id"];
$sql="DELETE FROM books WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $delete);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

?>
