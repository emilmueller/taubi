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
   
    include '../config.php';

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
        $sql = "SELECT r.name as role from user_roles ur
            JOIN users u ON ur.user_id = u.id
            JOIN roles r ON r.id = ur.role_id
            where u.id = ?
            
            ;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $roles = [];         
        if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $roles[]=$row['role'];
            }
            $stmt->close();
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'roles'=> $roles]);
        exit;
    }
    
?>