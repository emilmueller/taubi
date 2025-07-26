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

$sql = "SELECT * from users WHERE username = ? AND email = ?;";

$stmt = $conn->prepare($sql);

$stmt->bind_param('ss', $username,$email);
$username = $_POST['username'];
$email = $_POST['email'];

 error_log($username." -----------".$email);
    

$stmt->execute();

// Result array
$result = $stmt->get_result();
$user = $result->fetch_assoc();

header('Content-Type: application/json');
if ($user) {
    // Output JSON
    
    echo json_encode(['success' => true, 'user'=> $user]);
    
}else{
    echo json_encode(['error' => false, 'message' => 'Benutzer nicht gefunden']);
}


// Close connection
$conn->close();
?>