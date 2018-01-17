<?php
//
//Created by Marline Santiago-Cook
//Exit session
//
session_start();


session_unset();
session_destroy();

header("location:login-form.php");

?>

