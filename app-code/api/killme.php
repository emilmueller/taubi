

<?php 
    include "../config.php";

    $sql = "SELECT image,image_url FROM books where id=".$_GET['id'].";";
    $res = $conn->query($sql);

    $row = $res->fetch_assoc();

    // header('Content-Type: image/jpeg');
    pinrt_r($row['image_url']);


?>