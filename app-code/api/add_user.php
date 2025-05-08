<?php

include "../config.php";

$sql = "insert into users (username, email) VALUES ('ueli', 'ueli@ksw.ch');"
    
$result = $conn->query($sql);



// Close connection
$conn->close();
?>