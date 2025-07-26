<?php
session_start();
$_SESSION["logged_in"]=false;
// error_log($_SESSION['pw_reset_token']." ---- ".$_GET['token']);
require_once "../config.php";

$link = $conn;


//Go back to login if tokens dont match
if($_SERVER["REQUEST_METHOD"] == "POST" and $_GET["action"]=="reset_pw" and $_SESSION['pw_reset_token']!=$_GET['token']){
    
    
    header("location: /login/index.php?mail_sent4");
    
}


if($_SERVER["REQUEST_METHOD"] == "POST" and $_GET["action"]=="update_pw"){
     // Validate password
    if(empty(trim($_POST["password"]))){
        $err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $err = "Password must have atleast 6 characters.";
    }
    else if(strlen(trim($_POST["new_password"])) > 64)
        {
            $err = "Password cannot have more than 64 characters.";
        } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($err) && ($password != $confirm_password)){
            $err = "Password did not match.";
        }
    }

    if(empty($err)){

        // Prepare an insert statement
        $sql = "UPDATE users SET password=? WHERE email=?";

        if($stmt = mysqli_prepare($link, $sql)){
             mysqli_stmt_bind_param($stmt, "ss", $param_password, $email);
             $email = $_SESSION["email"];
             $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

             if(mysqli_stmt_execute($stmt)){

                unset($_SESSION['pw_reset_token']);
                header("location: /login/index.php?pw_updated");

             }else{
                header("location: /login/index.php?mail_sent4");

             }

        }


    }
}


?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	<link href="../css/taubi.css" rel="stylesheet">
    <title>Taubi Login</title>
</head>
<body>

	<div class="d-flex align-items-center justify-content-left ribbon" style="height:8vh;">
		<img src="../Taubi_Logo.png" alt="Logo" class="img-fluid p-2" style="height:40px; width:auto;">
	</div>
	<div class="d-flex align-items-center justify-content-center" style="height:92vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h3 class="text-center">Passwort neu setzen</h3>
                    <form action="?action=update_pw" method="post">
                        <div class="mb-3">
							<label for="pwd" class="form-label">Passwort:</label>
							<input type="password" class="form-control" id="pwd" name="password" required>
					  	</div>
						<div class="mb-3">
							<label for="confirmPwd" class="form-label">Passwort bestätigen:</label>
							<input type="password" class="form-control" id="confirmPwd" name="confirm_password" required>
					  	</div>
                        <input type="hidden" name="email" value="<?php $_SESSION['email'] ?>" />
                        <!-- <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="keepmeloggedin" name="keepmeloggedin" value="keepmeloggedin">
                            <label class="form-check-label" for="keepmelogge?din">Angemeldet bleiben</label>
                        </div> -->
                        <div class="d-flex align-items-center">
                            <button type="submit" name="submit" class="btn btn-secondary">Speichern</button>
                            
                        </div>

                    </form>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#noaccount" id="lnk_1">Noch kein Account? Erstelle einen!</button>
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#reset_pw" id="lnk_2">Passwort vergessen?</button>
                    </div>

                    <?php
                    if(!empty($err)){
                        echo '<div class="alert alert-danger">' . $err . '</div>';
                    }

                    ?>
                   
                </div>
            </div>
        </div>
    </div>
</body>