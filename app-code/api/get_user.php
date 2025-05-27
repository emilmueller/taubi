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
SELECT * from users where username=?, email=?;";

$stmt = $conn->prepare($sql);
$stmt.bind_param('ss', $_POST['username'],$_POST['email']);




    

$result = $stmt->execute();

// Result array
$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
               
        $users[] = $row;
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($users, JSON_PRETTY_PRINT);

// Close connection
$conn->close();
?>