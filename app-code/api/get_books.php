<?php

include "../config.php";
$sql = "
SELECT
    b.*,
    u.username AS seller_name,
    JSON_ARRAYAGG(t.name) AS tags
FROM books b
LEFT JOIN users u ON b.sold_by = u.id
LEFT JOIN book_tags bt ON b.id = bt.book_id
LEFT JOIN tags t ON bt.tag_id = t.id
GROUP BY b.id;
";

$result = $conn->query($sql);

// Result array
$books = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Decode tags JSON array to actual array (optional)
        $row['tags'] = json_decode($row['tags']);
        $books[] = $row;
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($books, JSON_PRETTY_PRINT);

// Close connection
$conn->close();
?>
