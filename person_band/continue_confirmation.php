<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page contines a reservation if override is confirmed

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

 //connect to the db
     include("../includes/dbconnect.inc");
     include("../includes/config.inc");

//define variables
    $reservation_id = $_GET['reservation_id'];
    $person_id = $_GET['person_id'];
    $repeat_id = $_GET['repeat_id'];
    $return_url = $_GET['return_url'];

//if repeat id exists do this

        if (isset($repeat_id) && $repeat_id != 0)
        {

        $add_person = "UPDATE reservation_entry SET person_id=$person_id, is_pending=0, reservation_status='OPEN', band_id=1 WHERE repeat_id = $repeat_id";
        $add_person_res = mysql_query($add_person) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

        }
        //if repeat id doesn't exist do this
        else

        {

        //$add_person = "UPDATE reservation_entry SET person_id=$person_id, is_pending=0, reservation_status='OPEN', band_id=1 WHERE id = $reservation_id";
        $add_person = "UPDATE reservation_entry SET reservation_status='OPEN' WHERE id = $reservation_id";
        $add_person_res = mysql_query($add_person) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );

        }

        $is_pending = $_POST['is_pending'];

        header("Location:$return_url");
?>
