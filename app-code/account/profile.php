<?php
session_start();

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
  <title>Taubi - My Profile</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    body {
      transition: background-color 0.3s, color 0.3s;
    }

    .ribbon {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
    }

    .ribbon a {
      color: white;
      margin-right: 20px;
      text-decoration: none;
    }

    .card-deck .card {
      margin-bottom: 20px;
    }

    .search-form {
      margin: 20px 0;
    }

    #notification {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 9999;
      background-color: #333;
      color: #fff;
      padding: 12px 20px;
      border-radius: 6px;
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }

    #notification.show {
      opacity: 1;
    }

    #notification.success {
      background-color: #28a745;
    }

    #notification.error {
      background-color: #dc3545;
    }
  </style>
</head>
<body id="body">

  <!-- Ribbon at the top -->
  <div class="ribbon d-flex justify-content-between align-items-center">
    <div>
      <a href="/" class="btn btn-link">Bibliothek</a>
      <a href="/account?my_books" class="btn btn-link">Meine Bücher</a>
    </div>
    <div class="d-flex align-items-center">
      <a href="/account" class="btn btn-link">
        <i class="bi bi-person-circle"></i> Konto
      </a>
    </div>
  </div>

  <form id="bookForm" method="post" action="save_user.php">
      
        
        <div class="col-lg-8">
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="nameInput" class="col-form-label">Name:</label>
            </div>  
            <div class="col-10">
                <input type="text" id="nameInput" name="name" class="form-control" placeholder="Titel"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="mailInput" class="col-form-label">Kontakt-Mail</label>
            </div>  
            <div class="col-10">
                <input type="text" id="mailInput" name="mail" class="form-control" placeholder="Kontakt-Mail"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="passwordInput" class="col-form-label">Passwort</label>
            </div>  
            <div class="col-10">
                <input type="text" id="passwordInput" name="password" class="form-control" placeholder="Verlag" />
            </div>
          </div>
          
          <div class="row align-items-center">
            <div class="col-12">
                
                <button id="backButton" type="button"  class="btn btn-secondary  float-end" onclick="window.open('/account', '_self');">Abbrechen</button>
                <button id="okButton" type="button" class="btn btn-secondary  float-end me-1" onclick="submit()">Änderungen speichern</button>
                <input type="hidden" name="action" value="save" />
                <input type ="hidden" name="id"  />
            </div>
          </div>
          
        </div>
      </div>
    
      </form> 

<script>
    //load user data
    document.addEventListener('DOMContentLoaded', () => {
      
	});

	


   


    $(document).ready(function(){

        // Fetch user data from the backend API
      fetch('/api/get_user_data.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to fetch user data');
          }
          return response.json();
        })
        .then(user => {
          console.log("HOOOOO");
            $.ajax({
                url: '/api/get_user.php',
                method: 'POST',
                data: {
                    username: user.username, 
                    email: user.email
                },
                dataType: 'json',
                success: function(response){
                  
                  console.log(response);
                  // $('nameInput').val(res.user.)
              

                },
                error: function(response){
                  console.log("EEEERROR");


                }
            });
        }) 
        .catch(error => {
          console.error('Error:', error);
		//TODO: show error
        });

      // $('#addBookButton').on('click', function(){
      //   window.location = "/app/scan_barcode.php";

      // });

    });

</script>
</body>
</html>