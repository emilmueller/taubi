<?php
session_start();
$_SESSION["logged_in"]=false;

require_once "../config.php";

$link = $conn;


// $token = $_GET['token'];

$sql = "SELECT token,id,username,role FROM users WHERE id=?;";

//error_log($_GET['id']." - ".$_GET['token']);

// Prepare an insert statement



if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $id);
    $id = trim($_GET['id']);
    
    

    if(mysqli_stmt_execute($stmt)){
        // error_log(".......... ".mysqli_stmt_num_rows($stmt));

        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){
            // Bind result variables
            mysqli_stmt_bind_result($stmt,$token,$id,$username,$role);
            if(mysqli_stmt_fetch($stmt)){
                if(is_null($token)){
                    error_log("Account ".$id." already activated.");
                    header("location:/login/index.php?acc_verify_already_ok");
                    exit;

                }elseif($token === trim($_GET['token'])){
                
                    // Password is correct, so start a new session
                    mysqli_stmt_close($stmt);
                    session_start();

                    
                    $sql = "UPDATE users SET banned=0, token=NULL WHERE id= ?;";
                    $stmt = mysqli_prepare($link, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    error_log("Account ".$id." activated");
                    if(mysqli_stmt_execute($stmt)){
                        // Store data in session variables
                        $_SESSION["logged_in"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;
                        $_SESSION["token"]=bin2hex(random_bytes(32));
                        

                        // Redirect user to welcome page
                        header("location:/app/");
                        exit;
                    
                    }else{
                        error_log("-->2");
                       
                        header("location:/login/index.php?activation_failed");
                        exit;
                        

                    }


                    

                    
                }else  {      
                    error_log("-->3");           
                   
                    header("location:/login/index.php?activation_failed");
                    exit;
                }
            } 

    
            header("location: /app/");

        }else{
            
            error_log("-->4");
            header("location:/login/index.php?activation_failed");

        }

    }
}



?>