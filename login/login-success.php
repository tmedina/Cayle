<?php session_start();
if($_SESSION['logged'] != 1){ header("location:login.php"); }
?>
<h4>Success!</h4>

