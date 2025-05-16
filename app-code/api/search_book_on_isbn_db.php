<?php 
    include "../config.php";
    

    $isbn = $_GET['isbn'];
    error_log("IIIIIIIIIIIIIIIIIIIIIISBN: ".$isbn);
    $url = 'https://api2.isbndb.com/book/'.$isbn;  
    $restKey = $isbnapikey; 

    
    $headers = array(  
      "Content-Type: application/json",  
      "Authorization: " . $restKey  
    );  
    
    $rest = curl_init();  
    curl_setopt($rest,CURLOPT_URL,$url);  
    curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);  
    curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);  
    
    $response = curl_exec($rest); 
    error_log(json_encode($book, JSON_PRETTY_PRINT));
    echo json_decode($response,true);
    
    curl_close($rest);

    

    ?> 