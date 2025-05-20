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
  <title>Buchsuche</title>
  <!-- Bootstrap 5.3 CSS -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    body {
      transition: background-color 0.3s, color 0.3s;
    }

    #spinner {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.5); /* halbtransparent schwarz */
      z-index: 1050; /* über Navbar und Modal-Backdrop */
      
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

    .book-form {
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


    
  <!-- Spinner -->
  <div id="spinner" class="d-flex justify-content-center align-items-center">
    <div class="text-center text-white">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Lädt...</span>
      </div>
      <p class="mt-3">Bitte warten…</p>
    </div>
  </div>

    <!-- Books Section -->
  <div id="bookDiv" class="container">
    <h2>Neues Buch</h2>

     
    
    <!-- Book Form  -->
    <form id="bookForm" method="post" action="save_book.php">
      <div class="row">
        <div class="col-lg-4 align-items-center d-flex justify-content-center">
          <div class="row">
            <div class="col-10 col-lg-12">
              <img id="bookImage" src="<?php echo $image_url ?>" alt="Buchbild">
              
              <input id="image_url_input" type="hidden" name="image_url" />
            </div>
            <div class="col-2 col-lg-12">
              <canvas id="canvas" class="d-none mt-2 d-none"></canvas>
              <input type="file" id="cameraInput" style="display:none" accept="image/*" capture="environment">
              <button id="addPicture" type="button" title="Foto aufnehmen" class="btn btn-secondary bi bi-camera"></button>
            
            </div>
          </div>
        </div>
        
        <div class="col-lg-8">
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="titleInput" class="col-form-label">Titel</label>
            </div>  
            <div class="col-10">
                <input type="text" id="titleInput" name="title" class="form-control" placeholder="Titel"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="authorInput" class="col-form-label">Autor:in</label>
            </div>  
            <div class="col-10">
                <input type="text" id="authorInput" name="author" class="form-control" placeholder="Autor:in"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="publisherInput" class="col-form-label">Verlag</label>
            </div>  
            <div class="col-10">
                <input type="text" id="publisherInput" name="publisher" class="form-control" placeholder="Verlag" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="yearInput" class="col-form-label">Erschienen</label>
            </div>  
            <div class="col-10">
                <input type="text" id="yearInput" name="date_published" class="form-control" placeholder="erschienen"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="pagesInput" class="col-form-label">Seiten</label>
            </div>  
            <div class="col-10">
                <input type="text" id="pagesInput" name="pages" class="form-control" placeholder="Anz. Seiten"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="languageInput" class="col-form-label">Sprache</label>
            </div>  
            <div class="col-10">
                <input type="text" id="languageInput" name="language" class="form-control" placeholder="Sprache"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="isbnInput" class="col-form-label">ISBN</label>
            </div>  
            <div class="col-10">
                <input type="text" id="isbnInput" name="isbn" class="form-control" placeholder="ISBN"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="zustandInput" class="col-form-label">Zustand</label>
            </div>  
            <div class="col-10">
                <input type="text" id="zustandInput" name="book_condition" class="form-control" placeholder="Zustand"  />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="preisInput" class="col-form-label">Preis</label>
            </div>  
            <div class="col-10">
                <input type="text" id="preisInput" name="price" class="form-control" placeholder="Preis" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-4">
                <button id="okButton" class="btn btn-secondary" onlick="submit()">Buch speichern</button>
            </div>
          </div>
          
        </div>
      </div>
    
      </form> 
    

  </div>

  <script>
    $(document).ready(function() {
        let action = '<?php echo $_GET['action']; ?>';
        let bookid = '<?php echo $_GET['id']; ?>';

        if (action =="isbn_search"){  //Search Book on ISBN-DB
          $.ajax({
            url:"../api/search_book_on_isbn_db.php",
            method:"GET",
            data:{
              'isbn': bookid
            },
            dataType:"json",
            beforeSend: function(){
              $("#spinner").removeClass('d-none');     // Spinner anzeigen
              $("#bookDiv").addClass('d-none');      // Ergebnisbereich ausblenden
            },
            success: function(response){
              $("#spinner").addClass('d-none');     // Spinner ausblenden 
              
              var res = $.parseJSON(response);
              $('#bookImage').attr('src', res.book.image);
              $('#image_url_input').val(res.book.image);
              $('#titleInput').val(res.book.title);
              $('#publisherInput').val(res.book.publisher);
              $('#languageInput').val(res.book.language);
              $('#yearInput').val(res.book.date_published);
              $('#isbnInput').val(res.book.isbn13);
              $('#pagesInput').val(res.book.pages);
              var author = "";
              $.each(res.book.authors, function(i,item){
                
                author+=item+", ";
              });
              author = author.slice(0,-2);
              $('#authorInput').val(author);
              
              


              $("#bookDiv").removeClass('d-none');      // Ergebnisbereich ausblenden
              
            },
            error: function(){
              $('#spinner').addClass('d-none');

              //Buch nicht gefunden!
              

            }
          });
        } else if (action == "db_search") {  //Search one single book in DB
          $.ajax({
            url:"../api/get_books.php",
            method:"POST",
            data:{
              'bookid': bookid
            },
            dataType:"json",
            beforeSend: function(){
              $("#spinner").removeClass('d-none');     // Spinner anzeigen
              $("#bookDiv").addClass('d-none');      // Ergebnisbereich ausblenden
            },
            success: function(response){
              $("#spinner").addClass('d-none');     // Spinner ausblenden
              console.log(response);
              var res = $.parseJSON(JSON.stringify(response));
              $('#bookImage').attr('src', res.image_url);
              $('#image_url_input').val(res.image_url);
              $('#titleInput').val(res.title);
              $('#publisherInput').val(res.publisher);
              $('#languageInput').val(res.language);
              $('#yearInput').val(res.date_published);
              $('#isbnInput').val(res.isbn13);
              $('#pagesInput').val(res.pages);
              $('#authorInput').val(res.author);
              $('#zustandInput').val(res.book_condition);
              $('#preisInput').val(res.price);
              
              
              
              


              $("#bookDiv").removeClass('d-none');      // Ergebnisbereich ausblenden
              
            },
            error: function(){
              $('#spinner').addClass('d-none');

              //Buch nicht gefunden!
              

            }
          });

        }




        









        $('#addPicture').on('click', function () {
          $('#cameraInput').click();
        });


        $('#cameraInput').on('change', function() {
            
            
            const file = this.files[0];

            if (!file) {
                alert("Bitte zuerst ein Foto aufnehmen.");
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                const img = new Image();
                img.onload = function () {
                    const maxWidth = 400;
                    const scale = maxWidth / img.width;
                    const canvas = document.getElementById('canvas');
                    canvas.width = maxWidth;
                    canvas.height = img.height * scale;
                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    canvas.toBlob(function(blob) {
                        const formData = new FormData();
                        formData.append("photo", blob, "snapshot.jpg");
                        $.ajax({
                          url: '../api/fotoupload.php',
                          type: 'POST',
                          data: formData,
                          processData: false,
                          contentType: false,
                          success: function(response) {
                            //alert(response.filename);
                            $('#bookImage').attr('src', response.filename);
                            $('#image_url_input').attr('value', response.filename);
                          },
                          error: function(request, status, err) {
                            alert('Fehler beim Upload: ' + request.responseText);
                          }
                        });
                        
                    }, "image/jpeg", 0.85);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
           
        });
      });
    </script>
  
 

  
  

     
</body>