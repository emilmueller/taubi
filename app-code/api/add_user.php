<?php

include "../config.php";

$sql = "INSERT INTO users (username, email) VALUES ('ueli', 'ueli@ksw.ch');"
    
$result = $conn->query($sql);



// Close connection
$conn->close();
?>