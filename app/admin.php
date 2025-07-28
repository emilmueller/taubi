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
            // Check Admin
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
                window.location.href = 'index.php';
                });
            });



        </script>

    
    
    
    </head>
    <body id="body">

        <!-- Navigation -->  
        <?php include '../app/nav.php'; ?>

        

        
        <div class="container mt-5">
            <h2>Admin</h2>
            <ul class="nav nav-tabs" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">Users</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Rollen</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="tags-tab" data-bs-toggle="tab" data-bs-target="#tags" type="button" role="tab">Tags</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">Settings</button>
                </li>
            </ul>
            <div class="tab-content mt-3" id="adminTabsContent">
                <div class="tab-pane fade show active" id="users" role="tabpanel">
                    <?php include '../api/admin_users.php'; ?>
                </div>
                <div class="tab-pane fade" id="roles" role="tabpanel">
                    <?php include '../api/admin_roles.php'; ?>
                </div>
                <div class="tab-pane fade" id="tags" role="tabpanel">
                    <?php include '../api/admin_tags.php'; ?>
                </div>
                <div class="tab-pane fade" id="settings" role="tabpanel">
                    <?php include '../api/admin_settings.php'; ?>
                </div>
            </div>
        </div>  





    </body>
</html>