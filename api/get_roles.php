<?php   
    include_once '../config.php';
    error_log(print_r($_GET,true));

     if($_GET['type']=="rolelist"){
        $result = $conn->query('SELECT * from roles;');

        
        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
        // Output JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'roles' => $roles]);
        exit;
    }

    if($_GET['type']=="role" and isset($_GET['id'])){
        $role_id = $_GET['id'];
        $sql = "SELECT * from roles WHERE id = ? LIMIT 1;";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param('i', $role_id);
        $stmt->execute();

        $result = $stmt->get_result();
        
        if($row = $result->fetch_assoc()){
            // Output JSON
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'role' => $row]);
            exit;
        }else{
            // Output JSON
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => "Rolle in der DB nicht gefunden"]);
            exit;

        }
            
            
            
        
       
    }
    
    //Rollen (Namen) für die Übersicht
    if($_GET['type']=="roles" and isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $stmt = $conn->prepare("SELECT value from settings WHERE type='default_role' LIMIT 1;");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        //error_log(print_r($row,true));
        $default_user = $row['0'];
        //error_log("DEFAULT: ".$default_user);
        


        $sql = "SELECT r.name as role from user_roles ur
            JOIN users u ON ur.user_id = u.id
            JOIN roles r ON r.id = ur.role_id
            where u.id = ?
            
            ;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $roles = [];
        $default_set = false;         
        if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                if($row['role']===$default_user){
                    $default_set=true;
                }
                $roles[]=$row['role'];
            }
            if(!$default_set){
                $roles[]=$default_user;
            }


            
            $stmt->close();
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'roles'=> $roles]);
        exit;
    }

    

    
?>