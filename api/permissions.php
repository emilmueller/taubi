<?php
    require_once '../config.php';

    //For Use from PHP
   
    $sql = "SELECT * from permissions;";

    $result = $conn->query($sql);
    $permissions = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Decode tags JSON array to actual array (optional)
            //$row['name'] = json_decode($row['name']);
            $permissions[] = $row;
        }
        $result->free();
    }

        // Close connection
    $conn->close();

?>