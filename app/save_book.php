<?php 
    session_start();
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

         $stmt->execute();

         $book_id = $conn->insert_id;
         

    }else if ($_POST['action']=='db_search'){ //Edit Book
        $id = $_POST['book_id'];
        error_log(print_r($_POST,true));

        $sql = "UPDATE books SET title=?, pages=?, author=?, publisher=?, language=?,  image_url=?, date_published=?, isbn=?, book_condition=?, price=?, sold_by=? WHERE id=$id";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sissssssssi", $title, $pages, $author, $publisher, $language, $image_url,  $date_published, $isbn, $book_condition, $price, $sold_by);
        
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
        if(isset($_POST['sold_by'])){
            $sold_by = $_POST['sold_by']; //für Bücher Update
        }else{
            $sold_by = $_SESSION['id']; //für neu erfasste Bücher
        }

        $stmt->execute();

        $book_id=$id;
        
    }

    $tags = $_POST['tag'];

    //Handle Tags
    $sql = "DELETE from book_tags WHERE book_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i',$book_id);
    $stmt->execute();

    if(isset($tags)){       

        $sql = "INSERT into book_tags (book_id, tag_id) VALUES (?,?);";
        $stmt = $conn->prepare($sql);
        foreach($tags as $key => $value){
            if(is_numeric($value)){
                $stmt->execute([$book_id, (int)$value]);
            }
        }

    }
    




   

    $redirect = $_GET['redirect'] ?? '/account';

    
   






   

    header("Location: $redirect");
    
    

?>