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

    require_once "../config.php";

    $sql = "SELECT username, role from users WHERE username = ? AND email = ?;";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param('ss', $username,$email);
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];


        

    $stmt->execute();

    // Result array
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    error_log("CHECK ADMIN: ".$user['username']." ----> ".$user['role'][0]);

    header('Content-Type: application/json');
    if ($user['role'][0]==1) {
       // Output JSON
    
        echo json_encode(['success' => true]);
        
    }else{
        echo json_encode(['error' => false]);
    }


    // Close connection
    $conn->close();
?>