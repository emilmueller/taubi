<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("LOCATION:/login");
    exit();
}

require_once '../config.php';



?>

<!DOCTYPE html>
<html data-bs-theme="dark" lang="de">
    <head>
    <meta charset="UTF-8">
    <title>Fehler</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    </head>
    <body>
        <div class="container mt-3">

            <div class="alert alert-danger">
                <strong>Fehler </strong><?php echo $_GET['message'] ?>  
            </div>
            <div>
                <div class="mt-4">
                <a href="<?php echo $_GET['redirect'] ?>" class="btn btn-outline-light">Zurück</a>
                </div>
            </div>
        </div>
        





    </body>
</html>