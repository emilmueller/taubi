<?php

include "../config.php";

$sql = "INSERT INTO users (username, email) VALUES (?,?);";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $_POST['username'], $_POST['email']);
    
$result = $stmt->execute();

// Close connection
$conn->close();

location($_POST['target']);
?>