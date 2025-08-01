<?php
include_once '../config.php';
include_once '../api/is_banned.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
  
  header('Location: ../login/');
  exit;
}else{
  error_log(isBanned()." -> ".$_SESSION['id']);
  if(isBanned()){
		
		header('Location: ../app/error_page.php?message=Du wurdest vom Admin gesperrt. Wende Dich an <a href="mailto:admin@code-camp.ch">admin@code-camp.ch</a>.&redirect=/login');
    exit;
	}
}

?>