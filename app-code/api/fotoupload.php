<?php
// fotoupload.php

header('Content-Type: application/json');

// Verzeichnispfad für Uploads (schreibbar machen!)
$uploadDir = __DIR__.'/../bookcovers/';
error_log("------->".$uploadDir);


// if (!is_dir($uploadDir)) {
//     mkdir($uploadDir, 0755, true);
// }

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'error' => 'Keine Datei erhalten oder Upload-Fehler.'
    ]);
    exit;
}

// Dateiinformationen
$tmpName = $_FILES['photo']['tmp_name'];
$origName = basename($_FILES['photo']['name']);
$ext = pathinfo($origName, PATHINFO_EXTENSION);

// Generiere eindeutigen Dateinamen
$filename = uniqid('cam_', true) . '.' . $ext;
$target = $uploadDir . $filename;

// Datei verschieben
if (move_uploaded_file($tmpName, $target)) {
    echo json_encode([
        'success' => true,
        'filename' => $filename
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Konnte Datei nicht speichern.'
    ]);
}
?>