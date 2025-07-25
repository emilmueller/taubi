<?php
// Initialize the session
session_start();
$username = $password = $confirm_password = "";
$role="user";
$username_err = $password_err = $confirm_password_err = "";
$err="";
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){
	header("location: /app");
    exit;
}
require_once "../config.php";
$link=$conn;
$username = $password = "";
$username_err = $password_err = $login_err = "";
$color="";
$banned=0;
$banned_reason="";
$telegram_id="";
$notification_telegram=0;
$notification_mail=0;
$class_id=0;
//resend account verify mail
if(isset($_GET["resend_acc_verify"])){
			//we need to resend the accont verification lin
			$_SESSION["creation_token"]= urlencode(bin2hex(random_bytes(24/2)));
			$token=$_SESSION["creation_token"];
			if(isset($_SESSION["verify"])){
				$username=$_SESSION["verify"];
				//send the mail:
	    $mail=<<<EOF

curl --request POST \
  --url https://api.sendgrid.com/v3/mail/send \
  --header "Authorization: Bearer $SENDGRID_API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{"personalizations": [{"to": [{"email": "$username"}]}],"from": {"email": "$SENDGRID_EMAIL"},"subject": "Taubi Account Validation","content": [{"type": "text/html", "value": "Hallo $username<br>Hier ist dein Taubi Account verifikations Link. Bitte klicke drauf. Sollte dies nicht funktionieren, kopiere bitte den Link und öffne Ihn in deinem Browser.<br><a href='https://app.ksw3d.ch/login/verify_account.php?token=$token'>https://app.ksw3d.ch/login/verify_account.php?token=$token</a><br>Achtung: der Link funktioniert nur in dem gleichen Browser und Gerät, auf dem du deinen Account erstellt hast.<br><br>Vielen dank für dein Vertrauen in uns!<br><a href=\"https://www.jakach.ch\">Jakach.ch</a><br>"}]}'

EOF;
				exec($mail);
				header("location: /login/?mail_sent1");
			}
			else{
				header("location: /login/?mail_sent3");
			}
		}
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and $_GET["action"]=="login"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, role,banned FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = htmlspecialchars($username);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role,$banned);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
		                if($banned!=1)
		                {
		                    // Password is correct, so start a new session
		                    mysqli_stmt_close($stmt);
		                    session_start();

		                    // Store data in session variables
		                    $_SESSION["logged_in"] = true;
		                    $_SESSION["id"] = $id;
		                    $_SESSION["username"] = $username;
		                    $_SESSION["role"] = $role;
		                    $_SESSION["token"]=bin2hex(random_bytes(32));
				    $_SESSION["creation_token"]= urlencode(bin2hex(random_bytes(24/2)));

		                    // Redirect user to welcome page
		                        header("location:/app/");
		                }
		                else
		                {
		                	$_SESSION["verify"]=$username;
		                	$login_err = "Dein Account wurde noch nicht aktiviert. <a href='?resend_acc_verify'>Neuen aktivierungslink anfordern</a>";
		                }
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
// Processing form data when form is submitted and user wants to create new user
if($_SERVER["REQUEST_METHOD"] == "POST" and $_GET["action"]=="create_user"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_@.\-]+$/', trim($_POST["username"]))){
        $err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $err = "Password must have atleast 6 characters.";
    }
    else if(strlen(trim($_POST["new_password"])) > 64)
        {
            $login_err = "Password cannot have more than 64 characters.";
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
    // Validate kantimail
    //if(1) //put this to disable ksw only mail
    if(strpos($_POST["username"],"@kantiwattwil.ch")===false){
        $err = "Only members of KSW can access this site. (prename.name@kantiwattwil.ch).";     
    } 
    // Check input errors before inserting in database
    if(empty($err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, role,banned) VALUES (?, ?, ?, ?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            $banned=1;
			//$banned=0; //put this to disable email verification enforcment
			$banned_reason="Account muss zuerst verifiziert werden (Link in Mail)";
			$tel=0;
			$mail=1;
            mysqli_stmt_bind_param($stmt, "ssssi", $param_username,$param_username, $param_password, $role,$banned);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $role="00000000000";
            $banned=1;
			$tel=0;
			$mail=1;
			$banned_reason="Account muss zuerst verifiziert werden (Link in Mail)";
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Redirect to login page
				//create session token, which has account creation token inisde it.
				$_SESSION["creation_token"]= urlencode(bin2hex(random_bytes(24/2)));
				$token=$_SESSION["creation_token"];
				$_SESSION["verify"]=$username;
				$_SESSION["email"]=$username;
				//send the mail:
//sendMail($useremail, $subject,$body,$successMessage,$errorMessage,$sendCopyToAdmin=false){

				
				$mailText = "Hallo $username<br>Hier ist dein Taubi Account verifikations Link. Bitte klicke drauf. Sollte dies nicht funktionieren, kopiere bitte den Link und öffne Ihn in deinem Browser.<br><a href='https://taubi.code-camp.ch/login/verify_account.php?token=$token'>https://taubi.code-camp.ch/login/verify_account.php?token=$token</a><br>Achtung: der Link funktioniert nur in dem gleichen Browser und Gerät, auf dem du deinen Account erstellt hast.<br>";

				$res = sendMail($username,"Aktivierung Deines Taubi-Kontos",$mailText,"Mail wurde erfolgreich gesendet","Fehler beim Mailversand.",$sendCopyToAdmin=false);


				if ($res){

					header("location: ?mail_sent1");		
				}else{
					header("location: ?mail_sent3");	

				}

				

                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);

		

        }
    }else{


	}
    
    // Close connection
    mysqli_close($link);
}
if($_SERVER["REQUEST_METHOD"] == "POST" and $_GET["action"]=="reset_pw"){
	$email=htmlspecialchars($_POST["username"]);
	$_SESSION["email"]=$email;
	$_SESSION["pw_reset_token"]= urlencode(bin2hex(random_bytes(24 / 2)));
	$token=$_SESSION["pw_reset_token"];
	$_SESSION["verify"]=$email;
	$mail=<<<EOF
curl --request POST \
  --url https://api.sendgrid.com/v3/mail/send \
  --header "Authorization: Bearer $SENDGRID_API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{"personalizations": [{"to": [{"email": "$email"}]}],"from": {"email": "$SENDGRID_EMAIL"},"subject": "System0 Password reset","content": [{"type": "text/html", "value": "Hallo $email<br>Hier ist dein System0 Passwort Zurücksetzungs Link. Bitte klicke drauf. Sollte dies nicht funktionieren, kopiere bitte den Link und öffne Ihn in deinem Browser.<br><a href='https://app.ksw3d.ch/login/reset_pw.php?token=$token'>https://app.ksw3d.ch/login/reset_pw.php?token=$token</a><br>Achtung: der Link funktioniert nur in dem gleichen Browser und Gerät, auf dem du deinen Account erstellt hast.<br><br>Vielen dank für dein Vertrauen in uns!<br>"}]}'
EOF;

	    exec($mail);
		header("location: ?mail_sent2");
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
						<h3 class="text-center">Login</h3>
						<form action="?action=login" method="post">
							<div class="mb-3">
								<label for="username" class="form-label">Benutzername:</label>
								<input type="text" class="form-control" id="username" name="username" value="<?php echo($username); ?>" required>
							</div>
							<div class="mb-3">
								<label for="pwd" class="form-label">Passwort:</label>
								<input type="password" class="form-control" id="pwd" name="password" required>
							</div>
							<!--<div class="mb-3 form-check">
								<input type="checkbox" class="form-check-input" id="keepmeloggedin" name="keepmeloggedin" value="keepmeloggedin">
								<label class="form-check-label" for="keepmeloggedin">Angemeldet bleiben</label>
							</div>-->
							<div class="d-flex align-items-center">
							    <button type="submit" name="submit" class="btn btn-secondary">Login</button>
							  <!--  <p class="mx-3 mb-0">Oder</p>
							    <a href="https://auth.jakach.ch/?send_to=https://taubi.jakach.ch/login/oauth.php" class="btn btn-secondary">mit Jakach account einloggen</a>
							-->
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
							if(!empty($login_err)){
							echo '<div class="alert alert-danger">' . $login_err . '</div>';
							}   
							if(isset($_GET["mail_sent1"]))
								echo '<div class="alert alert-success">Eine Mail mit einem Aktivierungslink wurde an deine Mailadresse gesendet.</div>';
							if(isset($_GET["mail_sent2"]))
								echo '<div class="alert alert-success">Eine Mail mit einem Passwort zurücksetzungslink wurde an deine Mailadresse gesendet.</div>';
							if(isset($_GET["acc_verify_ok"]))
								echo '<div class="alert alert-success">Email erfolgreich Verifiziert.</div>';
							if(isset($_GET["mail_sent3"]))
								echo '<div class="alert alert-danger">Eine Mail mit einem Passwort zurücksetzungslink konnte nich gesendet werden. Bitte melde dich beim Support <a href="mailto:admin@code-camp.ch">hier.</a></div>';
						?>
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade" id="noaccount" tabindex="1" role="dialog" aria-labelledby="Account" aria-hidden="false">
		      <div class="modal-dialog" role="document">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="exampleModalLabel">Account Erstellen</h5>
		            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		
		          </div>
				<div class="modal-body">
				  
		            <!-- Account things-->
					<form action="?action=create_user" method="post">
						<div class="form-group mb-3">
							<label for="username" class="form-label">Email:</label>
							<input type="text" class="form-control" id="username" name="username" value="<?php echo($username) ?>" required>
					  	</div>
						<div class="form-group mb-3">
							<label for="pwd" class="form-label">Passwort:</label>
							<input type="password" class="form-control" id="pwd" name="password" required>
					  	</div>
						<div class="form-group mb-3">
							<label for="confirmPwd" class="form-label">Passwort bestätigen:</label>
							<input type="password" class="form-control" id="confirmPwd" name="confirm_password" required>
					  	</div>
					
					<?php 
					    if(!empty($err)){
						echo '<div class="alert alert-danger">' . $err . '</div>';
					    }
						  
					?>
				</div>
				<div class="modal-footer">
					<div class="form-check mx-auto">
						<!--<input type="checkbox" class="form-check-input" id="keepmeloggedin" name="keepmeloggedin" value="keepmeloggedin">-->
						<!--<label class="form-check-label" for="keepmeloggedin">Login speichern</label>-->
					</div>
        			<!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
					<button type="submit" name="submit" class="btn btn-secondary">Account erstellen</button>
					<div class="text-center mt-3">
						<p class="mt-3">Durch erstellen des Accounts stimmst du unseren <a href="/app/privacy-policy.php">Datenschutzrichtlinien</a> zu</p>
					</div>
				</div>
				  </div>
				</form>
			</div>
		</div>
	<div class="modal fade" id="reset_pw" tabindex="1" role="dialog" aria-labelledby="Account" aria-hidden="false">
		      <div class="modal-dialog" role="document">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="exampleModalLabel">Passwort Zurücksetzen</h5>
		            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		
		          </div>
				<div class="modal-body">
					<form action="?action=reset_pw" method="post">
						<div class="form-group mb-3">
							<label for="username" class="form-label">Deine Account Email:</label>
							<input type="text" class="form-control" id="username" name="username" value='<?php echo($_SESSION["email"]); ?>' required>
					  	</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="submit" class="btn btn-secondary">Passwort zurücksetzlink senden</button>
				</div>
				  </div>
				</form>
			</div>
		</div>
	<?php
		if(!empty($err)){
			echo("<script>");
				echo('const a=document.getElementById("lnk_1");');
				echo('a.click();');
			echo("</script>");
		}
		if(isset($_GET["resend_pw_reset"])){
			echo("<script>");
				echo('const a=document.getElementById("lnk_2");');
				echo('a.click();');
			echo("</script>");
		}

	?>

		<!-- Bootstrap 5.3 JS and required Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
