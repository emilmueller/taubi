<!DOCTYPE html>
<html data-bs-theme="dark" lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fetch Book from DB</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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

    


    
    
    

    // Create connection
      

			
//       id INT AUTO_INCREMENT PRIMARY KEY,
//       reserved_by INT,
// publisher VARCHAR(255),
// book_condition VARCHAR(255),
// language VARCHAR(255),
// image_url VARCHAR(255),
// title VARCHAR(255),
// pages INT,
// date_published VARCHAR(255),
// author VARCHAR(255),
// isbn VARCHAR(255),
// price VARCHAR(255)




    $sql = "insert into books (title, pages, author, publisher, language,  image_url, date_published, isbn) VALUES ( ?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssss", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn);
      
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

    //$stmt->execute();




    curl_close($rest);

    

    ?> 



    <!-- Books Section -->
  <div class="container md-8">
    <h2>Mein Buch</h2>

 
    
    <!-- Book Form  -->
    <img src="<?php echo $image_url ?>" class="col-md-4" alt="Buchbild">
    <div class="row align-items-center">
      <div class="col-lg-2 mb-2">
          <label for="titleInput" class="col-form-label">Titel</label>
      </div>  
      <div class="col-lg-10">
          <input type="text" id="titleInput" class="form-control" placeholder="Titel" value="<?php echo $title ?>" />
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-lg-2 mb-2">
          <label for="authorInput" class="col-form-label">Autor:in</label>
      </div>  
      <div class="col-lg-10">
          <input type="text" id="authorInput" class="form-control" placeholder="Autor:in" value="<?php echo $author ?>" />
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-lg-2 mb-2">
          <label for="puglisherInput" class="col-form-label">Verlag</label>
      </div>  
      <div class="col-lg-10">
          <input type="text" id="publisherInput" class="form-control" placeholder="Verlag" value="<?php echo $publisher ?>" />
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-lg-2 mb-2">
          <label for="yaerInput" class="col-form-label">Jahr</label>
      </div>  
      <div class="col-lg-10">
          <input type="text" id="yearInput" class="form-control" placeholder="Jahr" value="<?php echo $date_published ?>" />
      </div>
    </div>
      
    

  </div>

     
</body>