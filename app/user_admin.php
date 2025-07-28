<?php
    session_start();
    require_once "../config.php";

    header("Content-Type: text/html; charset=UTF-8");

    // Check if the user is logged in
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("LOCATION:/login/login.php");
        exit();
    }

    

?>
<!DOCTYPE html>
<html data-bs-theme="dark" lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Benutzerverwaltung</title>
        <!-- Bootstrap 5.3 CSS -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
        <link href="../css/taubi.css" rel="stylesheet">
    
        <script>
            // Blockiert Seitenaufbau, bis Berechtigung geprüft ist
            document.addEventListener("DOMContentLoaded", function () {
            fetch("../api/is_admin.php")
                .then(response => response.json())
                .then(data => {
                if (data.success === true) {
                    console.log("✅ Zugriff erlaubt.");
                    // Optional: zeige Seite oder führe Setup aus
                } else {
                    console.warn("⛔ Kein Zugriff. Weiterleitung...");
                    document.body.innerHTML = "<h1>Zugriff verweigert</h1>";
                    // Optional: Weiterleitung oder Nachricht
                    alert("Du hast keinen Zugriff auf diese Seite!");
                    window.location.href = 'index.php';
                }
                })
                .catch(error => {
                console.error("Fehler beim Abrufen von is_admin.php:", error);
                document.body.innerHTML = "<h1>Fehler beim Berechtigungscheck</h1>";
                });
            });



        </script>

    
    
    
    </head>
    <body id="body">

        <!-- Navigation -->  
        <?php include '../app/nav.php'; ?>

        <?php
            ob_start();
            include '../api/get_users.php';
            $res = ob_get_clean();

            echo $res;


        ?>

        






    </body>
</html>