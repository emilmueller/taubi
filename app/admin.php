<?php
    session_start();
    require_once "../config.php";

   include '../api/login_check.php';
?>

<!DOCTYPE html>
<html data-bs-theme="dark" lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verwaltung</title>
        <!-- Bootstrap 5.3 CSS -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        
        
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

        <!-- Choices.js laden -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <link href="../css/taubi.css" rel="stylesheet">
        <!-- <script src="../js/taubi.js"></script> -->
        

        <script>
       // Check Admin
        fetch("../api/get_permissions.php?type=has_only_user_permission&user_id=<?php echo $_SESSION['id'] ?> ")
            .then(response => response.json())
            .then(data => {
                
                if (data == false) {
                    console.log("✅ Zugriff erlaubt für "+ "<?php echo $_SESSION['username'] ?>");
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
                window.location.href = '/app';
            });

        
        
        
        


        </script>
    
        

    
    
    
    </head>
    <body id="body">

        <!-- Navigation -->  
        <?php include '../app/nav.php'; ?>

        

        
        <div class="container mt-5">
            <h2>Admin</h2>
            <ul class="nav nav-tabs" id="adminTabs" role="tablist">
                <li data-permission="edit_users delete_users ban_users" class="nav-item" role="presentation">
                <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">Users</button>
                </li>
                <li data-permission="edit_books delete_books" class="nav-item" role="presentation">
                <button class="nav-link" id="books-tab" data-bs-toggle="tab" data-bs-target="#books" type="button" role="tab">Bücher</button>
                </li>
                <li data-permission="admin" class="nav-item" role="presentation">
                <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Rollen</button>
                </li>
                <li data-permission="edit_tags" class="nav-item" role="presentation">
                <button class="nav-link" id="tags-tab" data-bs-toggle="tab" data-bs-target="#tags" type="button" role="tab">Tags</button>
                </li>
                <li data-permission="admin" class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">Settings</button>
                </li>
            </ul>
            <div class="tab-content mt-3" id="adminTabsContent">
                <div class="tab-pane fade" id="users" role="tabpanel">
                    Wird geladen...
                </div>
                <div class="tab-pane fade" id="books" role="tabpanel">
                    Wird geladen...
                </div>
                <div class="tab-pane fade" id="roles" role="tabpanel">
                    Wird geladen...
                </div>
                <div class="tab-pane fade" id="tags" role="tabpanel">
                    Wird geladen...
                </div>
                <div class="tab-pane fade" id="settings" role="tabpanel">
                    Wird geladen...
                </div>
            </div>
        </div>  



    
    <script type='module' src="../js/admin.js"></script>

    </body>
</html>