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

    if($_POST['action']=='isbn_search'){  //new Book
        $sql = "insert into books (title, pages, author, publisher, language,  image_url, date_published, isbn, book_condition, price, sold_by ) VALUES ( ?,?,?,?,?,?,?,?,?,?,?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissssssssi", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn, $book_condition, $price, $_SESSION['id']);
        
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

    }else if ($_POST['action']=='db_search'){ //Edit Book
        $id = $_POST['id'];

    

        $sql = "UPDATE books SET title=?, pages=?, author=?, publisher=?, language=?,  image_url=?, date_published=?, isbn=?, book_condition=?, price=?, sold_by=? WHERE id=$id";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissssssssi", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn, $book_condition, $price, $_SESSION['id']);
        
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
    }


   



    
    $stmt->execute();

    header("Location:/account");
    
    

?>