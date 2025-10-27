<?php

include "../config.php";

$sql = "INSERT INTO users (username, email) VALUES (?,?);";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $_GET['username'], $_GET['email']);
    
$result = $stmt->execute(); 

// Close connection
$conn->close();

header("Location:".$_GET['target']);
?>