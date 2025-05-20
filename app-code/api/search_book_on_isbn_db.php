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
    
    $res = unserialize($response);
    error_log($res);
    if(isset($res['errorMessage'])){
      error_log("NOT FOUND");
    }else{
      error_log("BOOOOOOOOOK FOUND");
    }
   
    
    header('Content-Type: application/json'); 
    echo json_encode($response, JSON_PRETTY_PRINT);

    curl_close($rest);
    
    

    

    ?> 