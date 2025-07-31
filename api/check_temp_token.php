<?php

session_start();

require_once '../config.php';




function check_temp_token(){
    global $conn;
    
    $sql = "SELECT user_id, u.username, u.email FROM temp_tokens t
        JOIN users u ON u.id = user_id
        WHERE t.token = ? AND expires_at > NOW() 
        LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token);
    $token = $_GET['token']; 
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $user_id = $result['user_id'];
    


    // //Delete temp_token

    // $sql = "DELETE from temp_tokens WHERE user_id = ".$user_id.";";
    // error_log($sql);
    // $stmt = $conn->prepare($sql);
    // $stmt->execute();

    if(isset($user_id)){
        
        
        $_SESSION['id']=$user_id;
        $_SESSION['logged_in']=true;
        $_SESSION['username']=$result['username'];
        $_SESSION['email']=$result['email'];

        return true;

    }else{
        return false;
    }
}







?>