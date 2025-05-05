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
$sql = "SELECT name FROM tags";
$result = $conn->query($sql);

// Build JSON response
$tags = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['name'];
    }
    $result->free();
}

// Close connection
$conn->close();

// Output JSON
header('Content-Type: application/json');
echo json_encode(['tags' => $tags]);
?>
