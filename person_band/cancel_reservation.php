<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page cancels a reservation in progress

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

    //connect to the db
     include("../includes/dbconnect.inc");
    
    //define variables
    $reservation_id = $_GET['reservation_id'];

    $cancel_reservation_transaction = "DELETE FROM reservation_transaction WHERE reservation_entry_id=$reservation_id;";
    $cancel_reservation_transaction_res = mysql_query($cancel_reservation_transaction) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
	
	$cancel_reservation = "DELETE FROM reservation_entry WHERE id=$reservation_id;";
    $cancel_reservation_res = mysql_query($cancel_reservation) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    header("Location:../reservation/day.php");

?>
