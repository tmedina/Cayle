<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

$reservation_id = $_GET['reservation_id'];
$return_url     = $_GET['return_url'];
$start_time     = $_GET['start_time'];
$end_time       = $_GET['end_time'];
$action         = $_GET['action'];

header("Location: add.php?reservation_id=$reservation_id&return_url=$return_url&start_time=$start_time&end_time=$end_time&return_url=$return_url&action=$action");

?>
