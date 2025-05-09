<?php 
    include "../config.php";

    $sql = "SELECT image FROM books where id=38;";
    $res = $conn->query($sql);

    $row = $res->fetch_assoc();

    header("Content-Type: image/jpeg");
    echo $row['image'];


?>