<?php
    session_start();

    require_once "../config.php";


    $dataFields = [];
    $params = [];
    $types = "";

    $bindParams = [];

    $db_col_types = getBindParamTypes('roles');

    if(isset($_GET['new'])){ //New Role

        foreach($_POST as $key => $value){
            if($key !== 'id'){
                $dataFields[]= "$key";
                $types .= $db_col_types[$key];        
            }

        }
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

        $sql = "INSERT into roles (".implode(", ",$dataFields).") VALUES (".rtrim(str_repeat('?, ', count($dataFields)), ', ').");";



    }else{

        foreach($_POST as $key => $value){
            if($key !== 'id'){
                $dataFields[]= "$key = ?";
                $types .= $db_col_types[$key];        
            }

        }
        $types.=$db_col_types['id'];
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
        
        $sql = "UPDATE roles SET ".implode(", ",$dataFields)." WHERE id = ?;";



    }


    

    
    
    $stmt = $conn->prepare($sql);
    call_user_func_array([$stmt, 'bind_param'], $bindParams);

        
    if($stmt->execute()){
    

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        
    }else{
        error_log("Role ".$_POST['id']." NOT updated");
        header('Content-Type: application/json');
        echo json_encode(['error' => false, 'message' => 'Daten konnten nicht gespeichert werden. ']);

    }

?>