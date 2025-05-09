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


    <?php 
    include "../config.php";
    

    $isbn = $_GET['isbn'];
    $url = 'https://api2.isbndb.com/book/'.$isbn;  
    $restKey = $isbnapikey; 

    
    $headers = array(  
      "Content-Type: application/json",  
      "Authorization: " . $restKey  
    );  
    
    $rest = curl_init();  
    curl_setopt($rest,CURLOPT_URL,$url);  
    curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);  
    curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);  
    
    $response = curl_exec($rest);  
    $book = json_decode($response,true);
    $book = $book['book'];

    // echo "<pre>";
    // echo json_encode($book, JSON_PRETTY_PRINT);
    // echo "</pre>";


    
    
    

    




    
      
    $title = $book['title'];
    
    $pages = $book['pages'];
    $author = "";
    foreach($book['authors'] as $key => $value){
      $author .= $value." / ";
    }
    $author =substr($author, 0, -3);
    
    $publisher= $book['publisher'];

    $language = $book['language'];
    $image_url = $book['image'];
    $date_published =$book['date_published'];
    $isbn = $book['isbn13'];

    




    curl_close($rest);

    

    ?> 



    <!-- Books Section -->
  <div class="container">
    <h2>Mein Buch</h2>

 
    
    <!-- Book Form  -->
    
      <div class="row">
        <div class="col-lg-4 align-items-center d-flex justify-content-center">
          <div class="row">
            <div class="col-10 col-lg-12">
              <img src="<?php echo $image_url ?>" alt="Buchbild">
              
              <input type="hidden" name="image_url" value="<?php echo $image_url ?>"/>
            </div>
            <div class="col-2 col-lg-12">
              
              <button id="addPicture" type="button" title="Foto aufnehmen" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#photoModal">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
              </svg>
              </button>
            
            </div>
          </div>
        </div>
        
        <div class="col-lg-8">
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="titleInput" class="col-form-label">Titel</label>
            </div>  
            <div class="col-10">
                <input type="text" id="titleInput" name="title" class="form-control" placeholder="Titel" value="<?php echo $title ?>" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="authorInput" class="col-form-label">Autor:in</label>
            </div>  
            <div class="col-10">
                <input type="text" id="authorInput" name="author" class="form-control" placeholder="Autor:in" value="<?php echo $author ?>" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="publisherInput" class="col-form-label">Verlag</label>
            </div>  
            <div class="col-10">
                <input type="text" id="publisherInput" name="publisher" class="form-control" placeholder="Verlag" value="<?php echo $publisher ?>" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="yearInput" class="col-form-label">Erschienen</label>
            </div>  
            <div class="col-10">
                <input type="text" id="yearInput" name="date_published" class="form-control" placeholder="erschienen" value="<?php echo $date_published ?>" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="pagesInput" class="col-form-label">Seiten</label>
            </div>  
            <div class="col-10">
                <input type="text" id="pagesInput" name="pages" class="form-control" placeholder="Anz. Seiten" value="<?php echo $pages ?>" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="languageInput" class="col-form-label">Sprache</label>
            </div>  
            <div class="col-10">
                <input type="text" id="languageInput" name="language" class="form-control" placeholder="Sprache" value="<?php echo $language ?>" />
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-2 mb-2">
                <label for="isbnInput" class="col-form-label">ISBN</label>
            </div>  
            <div class="col-10">
                <input type="text" id="isbnInput" name="isbn" class="form-control" placeholder="ISBN" value="<?php echo $isbn ?>" />
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
                <button id="okButton" class="btn btn-secondary">Buch speichern</button>
            </div>
          </div>
          
        </div>
      </div>
    
      
    <!-- Modal-Overlay -->
  <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content p-3">
        <div class="modal-header">
          <h5 class="modal-title" id="photoModalLabel">Foto aufnehmen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
        </div>
        <div class="modal-body text-center">
          <video id="video" autoplay></video>
          <canvas id="canvas" class="d-none mt-2"></canvas>
          <div class="mt-3">
            <button class="btn btn-success" id="capture">Foto aufnehmen</button>
            <button class="btn btn-secondary d-none" id="uploadBtn">Foto hochladen</button>
          </div>
        </div>
      </div>
    </div>
  </div>  
    

  </div>

  <script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture');

    // 1. Zugriff auf die Kamera anfragen
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        console.error('Kein Kamerazugriff:', err);
        alert('Kann nicht auf die Kamera zugreifen.');
      });

    // 2. Foto aufnehmen und hochladen
    captureButton.addEventListener('click', () => {
      const ctx = canvas.getContext('2d');
      // Canvas-Größe an Video anpassen
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      // Bild in Canvas zeichnen
      ctx.drawImage(video, 0, 0);

      // Bild als Blob extrahieren (JPEG, Qualität 0.9)
      canvas.toBlob(blob => {
        // FormData für den Upload
        const formData = new FormData();
        formData.append('photo', blob, 'snapshot.jpg');

        // per Fetch an deinen PHP-Handler senden
        fetch('../api/fotoupload.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(json => {
          if (json.success) {
            alert('Foto erfolgreich hochgeladen: ' + json.filename);
          } else {
            alert('Upload fehlgeschlagen: ' + json.error);
          }
        })
        .catch(err => {
          console.error('Upload-Fehler:', err);
          alert('Fehler beim Hochladen');
        });
      }, 'image/jpeg', 0.9);
    });


  </script>

  
  

     
</body>