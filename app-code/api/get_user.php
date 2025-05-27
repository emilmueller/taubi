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
$sql = "SELECT * from users WHERE username = :username AND email = :email;";

$stmt = $conn->prepare($sql);
$username = $_POST['username'];
$email = $_POST['email'];
    

$stmt->execute(['username' => $username, 'email'=> $email]);

// Result array
$user = $stmt->fetch();

if ($user) {
    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($user, JSON_PRETTY_PRINT);
    
}


// Close connection
$conn->close();
?>