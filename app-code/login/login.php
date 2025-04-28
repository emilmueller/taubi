<?php
echo("demo/test login page to set credentials & activate session<br>You are logged in as ueli now");
echo("<br><a href='/app/'>Continue to app</a>");
session_start();
$_SESSION["id"]=1;
$_SESSION["username"]="ueli";
$_SESSION["logged_in"]=true;
$_SESSION["email"]="ueli@ksw.ch";

?>

