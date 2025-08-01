<?php
    session_start();
    include_once '../config.php';

    function isBanned(){
        global $conn;
        $sql ="SELECT 1 FROM users WHERE id = ? AND banned = 1 LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_SESSION['id']);

        $stmt->execute();
        $result = $stmt->get_result();
        $banned = $result->num_rows >0;
        
        return $banned;
    }
    

?>