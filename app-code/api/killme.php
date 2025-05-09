

<?php 
    include "../config.php";

    $sql = "SELECT image_data,image_url FROM books where id=".$_GET['id'].";";
    $res = $conn->query($sql);

    $row = $res->fetch_assoc();

    header('Content-Type: image/jpeg');
    
    echo $row['image_data'];


    // $imageData = file_get_contents("https://sample-videos.com/img/Sample-jpg-image-100kb.jpg");
    // if ($imageData === FALSE) { 
    //     die("Could not fetch image from the URL."); 
    // } 

    // $sql = "insert into books (title, pages, author, publisher, language,  image_url, date_published, isbn, book_condition, price, sold_by, image_data ) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?);";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("sissssssssib", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn, $book_condition, $price, $_SESSION['id'], $imageData);
    
    // $title = "TEST";

    // $pages = 6;
    // $author = "NOBODY";
    
    // $publisher= "Big Mac";

    // $language = "de";

    // $image_url = "https://sample-videos.com/img/Sample-jpg-image-100kb.jpg";
    // $date_published ="2055";
    // $isbn = "124509234";
    // $book_condition = "top";
    // $price = "cheap";


    // $stmt->execute();



?>