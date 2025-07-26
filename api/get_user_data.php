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


// Check if the session variables 'email' and 'username' are set
if (isset($_SESSION['email']) && isset($_SESSION['username'])) {
    // Prepare the data to be returned as JSON
    $response = [
        'status' => 'success',
        'email' => $_SESSION['email'],
        'username' => $_SESSION['username']
    ];
} else {
    // If session variables are not set, return an error
    $response = [
        'status' => 'error',
        'message' => 'Session data not found.'
    ];
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
?>
