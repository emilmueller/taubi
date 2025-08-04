<?php
    require_once '../config.php';

    //For Use with PHP
    $roles =[];
    $result = $conn->query('SELECT * from roles;');
    while($role = $result->fetch_assoc()){
        
        $stmt = $conn->prepare("SELECT permission_id FROM role_permissions WHERE role_id=?");
        $stmt->bind_param('i', $role['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $perm = array_column($res->fetch_all(MYSQLI_ASSOC), 'permission_id');
        $roles[]=['id'=> $role['id'], 'name'=> $role['name'], 'description' => $role['description'], 'permissions'=> $perm];
    }
    //error_log(print_r($roles, true));


?>