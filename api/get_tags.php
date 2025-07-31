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

require_once "../config.php";

if(isset($_POST['id'])){
    $id = $_POST['id'];
    //error_log("FETCH TAG: ".$id);
    $sql = "SELECT * FROM tags WHERE id= ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    $tag = $row;
    //error_log("----- ".$tag);
    //$result->free();
    

    // Close connection
    $conn->close();

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['success'=> true, 'tag' => $tag]);
    exit;


}


$sql = "SELECT * FROM tags";
$result = $conn->query($sql);

// Build JSON response
$tags = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row;
    }
    //$result->free();
}

// Close connection
$conn->close();

// Output JSON
header('Content-Type: application/json');
echo json_encode(['tags' => $tags]);
?>
