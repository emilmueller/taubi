<?php

include "../config.php";

$sql = "UPDATE users Set username = ?, email=?) Where id = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $_GET['username'], $_GET['email'], $_GET['id']);
    
$result = $stmt->execute();

// Close connection
$conn->close();

header("Location:".$_GET['target']);
?>