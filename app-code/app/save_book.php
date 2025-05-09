<!-- <?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        $response = [
            "error" => "not authorized"
        ];
        echo json_encode($response);
    exit();
}
?> -->
<?php 
    include "../config.php";

    $sql = "insert into books (title, pages, author, publisher, language,  image_url, date_published, isbn, book_condition, price, sold_by, image ) VALUES ( ?,?,?,?,?,?,?,?,?,?,?,?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssssssib", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn, $book_condition, $price, $_SESSION['id'], $imageData);
    
    $title = $_POST['title'];

    $pages = $_POST['pages'];
    $author = $_POST['author'];
    
    $publisher= $_POST['publisher'];

    $language = $_POST['language'];

    $image_url = $_POST['image_url'];
    $date_published =$_POST['date_published'];
    $isbn = $_POST['isbn'];
    $book_condition = $_POST['book_condition'];
    $price = $_POST['price'];


    $imageData = file_get_contents($image_url);
    if ($imageData === FALSE) { 
        die("Could not fetch image from the URL."); 
    } 



    
    $stmt->execute();

    header("Location:index.php");
    
    

?>