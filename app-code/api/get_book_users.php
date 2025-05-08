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
$sql = "
SELECT * from book_users;";

$result = $conn->query($sql);

// Result array
$book_users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Decode tags JSON array to actual array (optional)
       
        $book_users[] = $row;
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($book_users, JSON_PRETTY_PRINT);

// Close connection
$conn->close();
?>