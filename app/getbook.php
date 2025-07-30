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
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
  <link href="../css/taubi.css" rel="stylesheet">
  


 
  
  
</head>
<body id="body">

<!-- Navigation -->  
<?php include 'nav.php'; ?>


    
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
    <h2 id="title">Neues Buch</h2>

     
    
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
            <div class="col-12">
                
                <button id="backButton" type="button"  class="btn btn-secondary  float-end" onclick="window.open('/account', '_self');">Abbrechen</button>
                <button id="okButton" type="button" class="btn btn-secondary  float-end me-1" onclick="submit()">Buch speichern</button>
                <input type="hidden" name="action" value="<?php echo $_GET['action'] ?>" />
                <input type="hidden" name="sold_by" value="<?php echo $_SESSION['id']?>" />
                <input type ="hidden" name="book_id" value = "<?php echo $_GET['book_id'] ?>" />
            </div>
          </div>
          
        </div>
      </div>
    
      </form> 
    

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const action = '<?php echo $_GET['action']; ?>';
      const bookid = '<?php echo $_GET['book_id']; ?>';

      const spinner = document.getElementById('spinner');
      const bookDiv = document.getElementById('bookDiv');

      if (action === "isbn_search") {
        // Spinner anzeigen, Ergebnis ausblenden
        spinner.classList.remove('d-none');
        bookDiv.classList.add('d-none');

        fetch(`../api/search_book_on_isbn_db.php?isbn=${encodeURIComponent(bookid)}`, {
          method: 'GET'
        })
        .then(response => {
          if (!response.ok) throw new Error("Fehler beim Abrufen");
          return response.json();
        })
        .then(rawJson => {
          spinner.classList.add('d-none');

          const res = JSON.parse(rawJson); // Wie jQuerys $.parseJSON
          console.log(res);

          document.getElementById('bookImage').src = res.book.image;
          document.getElementById('image_url_input').value = res.book.image;
          document.getElementById('titleInput').value = res.book.title;
          document.getElementById('publisherInput').value = res.book.publisher;
          document.getElementById('languageInput').value = res.book.language;
          document.getElementById('yearInput').value = res.book.date_published;
          document.getElementById('isbnInput').value = res.book.isbn13;
          document.getElementById('pagesInput').value = res.book.pages;

          const authors = res.book.authors.join(', ');
          document.getElementById('authorInput').value = authors;

          bookDiv.classList.remove('d-none');
        })
        .catch(error => {
          spinner.classList.add('d-none');

          document.getElementById('title').textContent = "Buch von Hand erfassen";
          document.getElementById('bookImage').src = '/bookcovers/image-not-found.png';
          document.getElementById('image_url_input').value = '/bookcovers/image-not-found.png';
          document.getElementById('isbnInput').value = bookid;

          bookDiv.classList.remove('d-none');

          console.error('Fehler:', error);
        });

      } else if (action === "db_search") {
        // Spinner anzeigen, Ergebnis ausblenden
        spinner.classList.remove('d-none');
        bookDiv.classList.add('d-none');

        fetch('../api/get_books.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            bookid: bookid
          })
        })
        .then(response => {
          if (!response.ok) throw new Error("Fehler beim Abrufen");
          return response.json();
        })
        .then(response => {
          spinner.classList.add('d-none');

          document.getElementById('title').textContent = "Buchdaten bearbeiten";

          const res = JSON.parse(JSON.stringify(response)); // wie jQuerys "trick"

          document.getElementById('bookImage').src = res[0].image_url;
          document.getElementById('image_url_input').value = res[0].image_url;
          document.getElementById('titleInput').value = res[0].title;
          document.getElementById('publisherInput').value = res[0].publisher;
          document.getElementById('languageInput').value = res[0].language;
          document.getElementById('yearInput').value = res[0].date_published;
          document.getElementById('isbnInput').value = res[0].isbn;
          document.getElementById('pagesInput').value = res[0].pages;
          document.getElementById('authorInput').value = res[0].author;
          document.getElementById('zustandInput').value = res[0].book_condition;
          document.getElementById('preisInput').value = res[0].price;

          bookDiv.classList.remove('d-none');
        })
        .catch(error => {
          spinner.classList.add('d-none');
          console.error('Fehler:', error);
        });
      }
    });


    //Foto-Funktionen
    document.getElementById('addPicture').addEventListener('click', function () {      
      if(!isMobileDevice()){
        alert("Ein eigenes Foto kann nur mit dem Handy aufgenommen werden!");
        return;
      }
      document.getElementById('cameraInput').click();
    });

    document.getElementById('cameraInput').addEventListener('change', function () {
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

          canvas.toBlob(function (blob) {
            const formData = new FormData();
            formData.append("photo", blob, "snapshot.jpg");

            fetch('../api/fotoupload.php', {
              method: 'POST',
              body: formData
            })
              .then(response => response.json())
              .then(data => {
                // Wenn deine API einen response.filename zurückgibt
                document.getElementById('bookImage').src = data.filename;
                document.getElementById('image_url_input').value = data.filename;
              })
              .catch(error => {
                alert('Fehler beim Upload: ' + error.message);
              });
          }, "image/jpeg", 0.85);
        };
        img.src = event.target.result;
      };

      reader.readAsDataURL(file);
    });

    function isMobileDevice() {
      return /Mobi|Android|iPhone|iPad/i.test(navigator.userAgent);
    }

    // $('#addPicture').on('click', function () {
    //       $('#cameraInput').click();
    //     });


    //     $('#cameraInput').on('change', function() {
            
            
    //         const file = this.files[0];

    //         if (!file) {
    //             alert("Bitte zuerst ein Foto aufnehmen.");
    //             return;
    //         }

    //         const reader = new FileReader();
    //         reader.onload = function (event) {
    //             const img = new Image();
    //             img.onload = function () {
    //                 const maxWidth = 400;
    //                 const scale = maxWidth / img.width;
    //                 const canvas = document.getElementById('canvas');
    //                 canvas.width = maxWidth;
    //                 canvas.height = img.height * scale;
    //                 const ctx = canvas.getContext("2d");
    //                 ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    //                 canvas.toBlob(function(blob) {
    //                     const formData = new FormData();
    //                     formData.append("photo", blob, "snapshot.jpg");
    //                     $.ajax({
    //                       url: '../api/fotoupload.php',
    //                       type: 'POST',
    //                       data: formData,
    //                       processData: false,
    //                       contentType: false,
    //                       success: function(response) {
    //                         //alert(response.filename);
    //                         $('#bookImage').attr('src', response.filename);
    //                         $('#image_url_input').attr('value', response.filename);
    //                       },
    //                       error: function(request, status, err) {
    //                         alert('Fehler beim Upload: ' + request.responseText);
    //                       }
    //                     });
                        
    //                 }, "image/jpeg", 0.85);
    //             };
    //             img.src = event.target.result;
    //         };
    //         reader.readAsDataURL(file);
           
    //     });



    
    </script>
  
 

  
  

     
</body>