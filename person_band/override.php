<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page lets the user make a reservation for a person with unpaid reservations

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database and config.inc file
    include("../includes/dbconnect.inc");
    include("../includes/config.inc");

   
    //define variables
    $reservation_id = $_POST['reservation_id'];
    $person_id = $_POST['person_id'];
    $repeat_id = $_POST['repeat_id'];
    $return_url = $_POST['return_url'];
    $override_initials = $_POST['override_initials'];
    $override_comment = $_POST['override_comment'];

     if (isset($repeat_id) && $repeat_id != 0)
        {
        $override = "UPDATE reservation_entry SET override_initials='$override_initials', override_comment='$override_comment',  person_id=$person_id, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE repeat_id=$repeat_id";
        $override_res = mysql_query($override) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
            
        }
        //if repeat id doesn't exist do this
        else

        {
        $override = "UPDATE reservation_entry SET override_initials='$override_initials', override_comment='$override_comment',  person_id=$person_id, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE id=$reservation_id";
        $override_res = mysql_query($override) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        }
        
    $is_pending = $_POST['is_pending'];
    header("Location:$return_url");

   ?>
