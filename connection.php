<?php
$localhost="localhost";
$username="root";
$password="";
$database="updated-case1";

session_start();
ob_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$connect=mysqli_connect($localhost,$username,$password,$database);
?>
