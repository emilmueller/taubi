<?php 
    include "../config.php";

    $imageData = file_get_contents("https://images.isbndb.com/covers/7521763483567.jpg");
    if ($imageData === FALSE) { 
        die("Could not fetch image from the URL."); 
    } 

    echo $imageData;

?>