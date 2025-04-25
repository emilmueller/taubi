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
    <?php 
    include "../config.php";
    $isbn = $_GET['isbn'];
    $url = 'https://api2.isbndb.com/book/'.$isbn;  
    $restKey = $isbnapikey; 

    echo "Nun läufts<br/>";
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

    echo "<pre>";
    print_r($book);
    echo "TITEL: ".$book['title'];
    echo "</pre>";

    // Create connection
			$conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);

			// Check connection
			if ($conn->connect_error) {
				$success=0;
				die("Connection failed: " . $conn->connect_error);
			}

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




      $sql = "insert into books (title, pages, author, publisher, language, book_condition, image_url, reserved_by, date_published, isbn,price) VALUES ( ?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sisssssisss", $title, $pages, $author, $publisher, $language, $book_condition, $image_url, $reserved_by, $date_published, $isbn, $price);
      
      

    curl_close($rest);



    ?>  
</body>