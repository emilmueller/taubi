<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("LOCATION:/app");
        exit();
    }
    require_once '../config.php';


    $token = $_POST['token'];

    // error_log("TTTTTTT: ".$isbn);

    $sql = "SELECT isbn_scan FROM temp_tokens WHERE token = ? and isbn_scan IS NOT NULL LIMIT 1 ;";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param('s', $token);

    $stmt->execute();

    $result = $stmt->get_result();
    //error_log("token: ".$token. "  -------- ".print_r($result->fetch_assoc(), true));

    if($result and $result->num_rows >0){

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'isbn' => $result->fetch_assoc()['isbn_scan']]);
        exit;

    }else{
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;

    }




?>