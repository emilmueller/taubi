<?php
session_start();

include "../config.php";


$updateFields = [];
$params = [];
$types = "";

$bindParams = [];

foreach($_POST as $key => $value){
    if($key !== 'id'){
        $updateFields[]= "$key = ?";
        $types .= $db_col_types[$key];        
    }

}
$types.="i";
$bindParams[]=&$types;

foreach($_POST as $key => $value){
    if($key !== 'id'){
        if($db_col_types[$key]=="i"){
            $bindParams[] = (int)$_POST[$key];
        }else{
            $bindParams[] = &$_POST[$key];  
        }
    }
    
}

$bindParams[]=(int)$_POST['id'];
// error_log(print_r($bindParams,true));


// error_log(print_r($params));

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

// Close connection
$conn->close();

//header('Location: ../app/admin.php');


?>