<?php 

require_once '../config.php';


     if($_GET['type']=="permissionlist"){
        $sql = "SELECT name from permissions;";

        $result = $conn->query($sql);
        $permissions = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Decode tags JSON array to actual array (optional)
                //$row['name'] = json_decode($row['name']);
                $permissions[] = $row['name'];
            }
            $result->free();
        }

         // Close connection
        $conn->close();
        //error_log(print_r($roles));
        // Output JSON
        header('Content-Type: application/json');
        echo json_encode($permissions);
        exit;
    }
    
    if($_GET['type']=="permissions" and isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $sql = "SELECT p.name as permission from user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            where ur.user_id = ?
            
            ;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $permissions = [];         
        if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $permissions[]=$row['permission'];
            }
            $stmt->close();
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'permissions'=> $permissions]);
        exit;
    }

    //Permission Types für die Übersicht
    if($_GET['type']=="permission_types" and isset($_GET['user_id'])){
        
        $user_id = $_GET['user_id'];
        $sql = "SELECT p.type as permission from user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            where ur.user_id = ?
            
            ;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $permissions = [];         
        if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $permissions[]=$row['permission'];
            }
            $stmt->close();
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'permissions'=> $permissions]);
        exit;
    }


    if($_GET['type']=="has_permission" and isset($_GET['user_id']) and isset($_GET['permission_type'])){
        $user_id = $_GET['user_id'];
        $permission_type= $_GET['permission_type'];
        $sql = "SELECT 1 from user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            where ur.user_id = ? and p.type = ?
            LIMIT 1
            
            ;";

        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param('is', $user_id,$permission_type);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $found = $result->num_rows >0;
        header('Content-Type: application/json');
        echo json_encode($found);
    }


    if($_GET['type']=="has_only_user_permission" and isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
        $sql = "SELECT 1 from user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            where ur.user_id = ? and p.type != 'user'
            LIMIT 1
            
            ;";

        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        
        //error_log("USER-PERMISSIONS: ".$user_id);
        $result = $stmt->get_result();
        //error_log("----------".$result->num_rows);
        $found = $result->num_rows == 0;
        header('Content-Type: application/json');
        echo json_encode($found);
    }
    
?>