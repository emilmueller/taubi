<?php
// fotoupload.php

header('Content-Type: application/json');

// Verzeichnispfad für Uploads (schreibbar machen!)
$uploadDir = dirname(__DIR__).'/bookcovers/';


if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo 'Keine Datei erhalten oder Upload-Fehler.';
    exit;
}

 




// Dateiinformationen
$tmpName = $_FILES['photo']['tmp_name'];
$origName = basename($_FILES['photo']['name']);
$ext = pathinfo($origName, PATHINFO_EXTENSION);



// Generiere eindeutigen Dateinamen
$fileBaseName = uniqid('cam_', false);
$filename =  $fileBaseName.'.'. $ext;
$target = $uploadDir . $filename;




//  error_log("------->".$fileBaseName." --- ".$target);
// Datei verschieben
if (move_uploaded_file($tmpName, $target)) {
    echo json_encode([
        'success' => true,
        'message' => "Upload erfolgreich",
        'filename' => $target

    ]);
    
    error_log("---> Foto ".$target." saved.");
} else {
    echo json_encode([
        'success' => false,
        'message' => "Fehler beim Speichern der Datei."
        
    ]);
}
?>