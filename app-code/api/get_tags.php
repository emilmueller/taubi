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
