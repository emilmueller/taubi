<?php
session_start();

require_once "../config.php";


$updateFields = [];
$params = [];
$types = "";

$bindParams = [];

$db_col_types = ['id'=>'i','username'=>'s', 'email'=>'s', 'banned'=>'i', 'ban_message'=>'s'];

//error_log(print_r($_POST,true));

foreach($_POST as $key => $value){
    if($key !== 'id' and $key!='role'){
        $updateFields[]= "$key = ?";
        $types .= $db_col_types[$key];        
    }

}
$types.="i";
$bindParams[]=&$types;

foreach($_POST as $key => $value){
    if($key !== 'id' and $key!='role'){
        if($db_col_types[$key]=="i"){
            $bindParams[] = (int)$_POST[$key];
        }else{
            $bindParams[] = &$_POST[$key];  
        }
    }
    
}

$bindParams[]=(int)$_POST['id'];
// error_log(print_r($bindParams,true));


//error_log("PARAMS: ".print_r($bindParams,true)); 

$sql = "UPDATE users SET ".implode(", ",$updateFields)." WHERE id = ?;";
// error_log($sql." ---> ".$types);
$stmt = $conn->prepare($sql);
call_user_func_array([$stmt, 'bind_param'], $bindParams);
//$stmt->bind_param($types, $params);

    
if($stmt->execute()){
   

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    // error_log("Affected Rows: ".$stmt->affected_rows);

   

   
}else{
    error_log("User ".$_POST['id']." NOT updated");
    header('Content-Type: application/json');
    echo json_encode(['error' => false, 'message' => 'Daten konnten nicht gespeichert werden. ']);

}


//Handle Roles
$roles = $_POST['role'];
$user_id = $_POST['id'];


error_log("ISSET: ".isset($roles));
if(isset($roles)){
    error_log(print_r($roles,true));
    //Delete all user_roles for user_id
    $sql = "DELETE FROM user_roles WHERE user_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $sql = "INSERT into user_roles (user_id, role_id) VALUES (?,?);";
    $stmt = $conn->prepare($sql);
    foreach($roles as $key => $value){
        if(is_numeric($value)){
            $stmt->execute([$user_id, (int)$value]);
        }
    }
}





// Close connection
$conn->close();

//header('Location: ../app/admin.php');


?>