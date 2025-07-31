<?php
/*
..............Ban Usesrs..Edit Tags..Edit Books..Delete Books..Edit Users..Delete Users..Set Admin
Admin             *          *           *            *             *           *           *
Superuser         *          *           *                          *
Biblio            *                      *            *             
Tag Manager                  *
Book Manager                             *            *
User Manager      *                                                 *           *
Supervisor        *
User




*/
   
    require_once '../config.php';

    $roles = ["Admin", "Superuser", "Biblio", "Tag Manager", "Book Manager", "User Manager","Supervisor", "User"];

     if($_GET['type']=="rolelist"){
        $sql = "SELECT name,id from roles;";

        $result = $conn->query($sql);
        $roles = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Decode tags JSON array to actual array (optional)
                //$row['name'] = json_decode($row['name']);
                $roles[] = $row;
            }
            $result->free();
        }

         // Close connection
        $conn->close();
        //error_log(print_r($roles));
        // Output JSON
        header('Content-Type: application/json');
        echo json_encode($roles);
        exit;
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