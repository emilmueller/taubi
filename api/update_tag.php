<?php
session_start();

require_once "../config.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$tag_id =$_POST['id'];
$tag_name = $_POST['name'];


if(!isset($tag_id) or $tag_id==''){
    
    $sql = "INSERT into tags (name) VALUES (?);";
}else{
    $sql = "UPDATE tags SET name=? WHERE id=$tag_id;";
}

try{

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
    
        echo json_encode([
            "success" => false,
            "message" => "Prepare fehlgeschlagen: " . $conn->error
        ]);
        exit;
    }

    $stmt->bind_param('s',$tag_name);

    if(!$stmt->execute()){

        
        
        //error_log("Tag ".$_POST['id']." NOT updated");
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $stmt->error]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    // error_log("Affected Rows: ".$stmt->affected_rows
}catch (mysqli_sql_exception $e){
    // Duplikatfehler speziell behandeln
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo json_encode([
            "success" => false,
            "message" => "Diesen Tag gibt es bereits."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Datenbankfehler: " . $e->getMessage()
        ]);
    }


}
