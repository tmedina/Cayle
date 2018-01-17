<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page checks to see if unpaid reservations exist for the person making the reservation; if no overdues
//exist the reservation is made for person & band; if overdues exist route to override page

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
     
    $reservation_id = $_GET['reservation_id'];
    $person_id = $_GET['person_id'];
    $band_id = $_GET['band_id'];
    $repeat_id = $_GET['repeat_id'];
    $return_url = $_GET['return_url'];

    //check to see if person has any unpaid reservations
    $check_unpaid = "SELECT COUNT(*) AS status_count FROM reservation_entry WHERE reservation_status=\"UNPAID\" AND person_id=$person_id";
    $check_unpaid_res = mysql_query($check_unpaid) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

    //if any unpaid reservations exist, send reservation to override form
    if (mysql_num_rows($check_unpaid_res)> 0)
    {
    while($results = mysql_fetch_array($check_unpaid_res))

        {

        $status_count = $results['status_count'];


        if ($status_count > 0)

        {
        header("Location:override_band_form.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id&band_id=$band_id");

        }

        //if no unpaid reservations exist add person to reservation
        elseif ($status_count == 0)
        {

  

        if (isset($repeat_id) && $repeat_id > 0)
        {

        $add_person = "UPDATE reservation_entry SET person_id = $person_id, band_id = $band_id, is_pending=0, reservation_status='$OPEN' WHERE repeat_id = $repeat_id";
        $add_person_res = mysql_query($add_person) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

        }
        else

        {

        $add_person = "UPDATE reservation_entry SET person_id = $person_id, band_id = $band_id, is_pending=0, reservation_status='$OPEN' WHERE id = $reservation_id";
        $add_person_res = mysql_query($add_person) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

        }

        $is_pending = $_POST['is_pending'];

        header("Location:$return_url");
        
        }
        }
    }
?>
