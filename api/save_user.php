<?php
session_start();

include "../config.php";

$sql = "UPDATE users Set username = ?, email=? WHERE id = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssi', $_POST['username'], $_POST['email'], $_POST['id']);

    
if($stmt->execute()){
    $_SESSION['username']=$_POST['username'];
    $_SESSION['email']=$_POST['email'];

   
}else{
    error_log("NOT updated");

}

// Close connection
$conn->close();

header("Location: /account/profile.php");
?>