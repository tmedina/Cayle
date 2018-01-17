<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//  config.inc
// 2009-03-18    wilson    initial creation.
// 2009-03-29    wilson    added reservation status
// database parameters
$dbUser = "";
$dbPass = "";
$dbName = "nucis_space_tables_prod";
$dbHost = "localhost";

// reservation status constant
//
$PENDING_CUSTOMER="PENDING_CUSTOMER";
$PENDING_EQUIPMENT="PENDING_EQUIPMENT";
$OPEN="OPEN";
$IN_PROGRESS="IN_PROGRESS";
$UNPAID="UNPAID";
$CLOSED="CLOSED";

$salted_pwd='';
?>
