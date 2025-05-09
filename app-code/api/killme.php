<?php 
    include "../config.php";

    $imageData = file_get_contents($_POST['image_url']);
    if ($imageData === FALSE) { 
        die("Could not fetch image from the URL."); 
    } 

    echo $imageData;

?>