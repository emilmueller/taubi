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
    
    $res = json_decode($response, true);
    // error_log($res);
    if(!isset($res['book'])){
      error_log("NOT FOUND");
      // http_response_code(500);
      echo json_encode(['error' => "Buch mit ISBN-Nummer ". $isbn." nicht gefunden."]);
    }else{
      error_log("BOOOOOOOOOK FOUND");
      header('Content-Type: application/json'); 
      echo json_encode($response, JSON_PRETTY_PRINT);
    }
   
    
    

    curl_close($rest);
    
    

    

    ?> 