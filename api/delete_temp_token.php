<?php

    session_start();

    require_once '../config.php';

    $token = $_GET['token'];
    //error_log("SSS".$_SESSION['id']);
    $sql = "DELETE FROM temp_tokens WHERE user_id = (
        SELECT user_id FROM (
            SELECT user_id FROM temp_tokens WHERE token = ? LIMIT 1
        ) AS sub
    );";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);


   

    if($stmt->execute()){

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
        

    }






?>