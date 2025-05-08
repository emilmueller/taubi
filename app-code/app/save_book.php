<?php 

    include "../config.php";

    $sql = "insert into books (title, pages, author, publisher, language,  image_url, date_published, isbn, book_condition, price ) VALUES ( ?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssssss", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn, $book_condition, $price);
    
    $title = $_POST['title'];

    $pages = $_POST['pages'];
    $author = $_POST['author'];
    
    $publisher= $_POST['publisher'];

    $language = $_POST['language'];
    $image_url = $_POST['image_url'];
    $date_published =date_create($_POST['date_published']);
    $isbn = $_POST['isbn'];
    $book_condition = $_POST['book_condition'];
    $price = $_POST['price'];
    
    $stmt->execute();
    
    $bookID = $stmt->lastInsertId();
    echo $bookID;
   // $sql = "insert into book_users "

?>