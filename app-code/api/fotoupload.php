<?php
// fotoupload.php

header('Content-Type: application/json');

// Verzeichnispfad für Uploads (schreibbar machen!)
$uploadDir = dirname(__DIR__).'/bookcovers/';

// print_r($_FILES);
// error_log($_FILES);

// if (!is_dir($uploadDir)) {
//     mkdir($uploadDir, 0755, true);
// }

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo 'Keine Datei erhalten oder Upload-Fehler.';
    exit;
}

 




// Dateiinformationen
$tmpName = $_FILES['photo']['tmp_name'];
$origName = basename($_FILES['photo']['name']);
$ext = pathinfo($origName, PATHINFO_EXTENSION);

// Lade Bild in Speicher
switch (strtolower($text)) {
    case 'jpg':
    case 'jpeg':
        $image = imagecreatefromjpeg($tmpName);
        break;
    case 'png':
        $image = imagecreatefrompng($tmpName);
        break;
    case 'gif':
        $image = imagecreatefromgif($tmpName);
        break;
    default:
        echo "Nur JPG, PNG oder GIF erlaubt.";
        exit;
}

if (!$image) {
    echo "Fehler beim Laden des Bildes.";
    exit;
}




// Generiere eindeutigen Dateinamen
$fileBaseName = uniqid('cam_', false);
$filename =  $fileBaseName.'.'. $ext;
$target = $uploadDir . $filename;

// Neue Breite definieren (z. B. max. 800px)
$newWidth = 300;
$width = imagesx($image);
$height = imagesy($image);
$newHeight = floor($height * ($newWidth / $width));

// Neues leeres Bild erstellen und skalieren
$resizedImage = imagecreatetruecolor($newWidth, $newHeight);
imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

// Speichern als JPEG
if (imagejpeg($resizedImage, $targetPath, 85)) {
    echo "Foto erfolgreich verkleinert und gespeichert: " . $newFileName;
} else {
    echo "Fehler beim Speichern.";
}


// // error_log("------->".$fileBaseName." --- ".$target);
// // Datei verschieben
// if (move_uploaded_file($tmpName, $target)) {
//     echo "Foto hochgeladen.";
//     error_log("---> ".$target." saved.");
// } else {
//     echo "Fehler beim Speichern der Datei";
// }
?>