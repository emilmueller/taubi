<?php 
    include "../config.php";

    $sql = "SELECT image FROM books where id=38;";
    $row = $conn->query($sql);

    header("Content-Type: image/jpeg");
    echo $row['image'];


?>