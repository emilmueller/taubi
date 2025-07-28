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
   


    $roles = ["Admin", "Superuser", "Biblio", "Tag Manager", "Book Manager", "User Manager","Supervisor", "User"];

     if($_GET['type']=="rolelist"){
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'roles' => $roles]);
        exit;
    }
    
    
    $user_roles = $_GET['roles'];
    error_log($user_roles);
    $res = "";
    for($i=0;$i<count($roles);$i++){
        if ($user_roles[$i]!="0"){
            $res.=$roles[$i].", ";
        }
    }
    if(strlen($res)>0){
        $res = substr($res, 0, -2);
        error_log($res);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'roles'=> $res]);

    }else{
        echo json_encode(['error' => false, 'message' => 'Benutzer hat keine Rollen']);
    }
    
?>