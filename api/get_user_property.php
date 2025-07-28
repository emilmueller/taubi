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

$sql = "SELECT * from users WHERE id = ?;";

$stmt = $conn->prepare($sql);

$stmt->bind_param('ss', $id);
$id = $_GET['id'];


    

$stmt->execute();

$property = $_GET['property'];

// Result array
$result = $stmt->get_result();
$user = $result->fetch_assoc();


header('Content-Type: application/json');
if (isset($user[$property]) {
    // Output JSON
    
    echo json_encode(['success' => true, 'property'=> $user[$property]]);
    
}else{
    echo json_encode(['error' => false, 'message' => 'Property nicht gefunden']);
}


// Close connection
$conn->close();
?>
