<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page checks to see if overdues exist for the person making the reservation; if no overdues reservation
//for a person is made; if overdues exist route to override page

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);
//print_r($_POST);
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

	$ev_return_url = $_POST['return_url'];
    $ev_repeat_id = $_POST['repeat_id'];
    $ev_reservation_id = $_POST['reservation_id'];
    $person = $_POST['sel_id'];
	if (isset ($_POST['event_type']))
		$event_type = $_POST['event_type'];
	else $event_type = $_GET['event_type'];
	/*
	echo $event_type;
	echo $person_id;
	echo $reservation_id;
	echo $return_url;
	echo $repeat_id;
*/

	if(($event_type == 1)&&(isset($_POST['event_type']))){
        if (isset($ev_repeat_id) && $ev_repeat_id != 0) {
				$add_person = "UPDATE reservation_entry SET person_id=$person, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE repeat_id = $ev_repeat_id";
				$add_person_res = mysql_query($add_person) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
        }
        //if repeat id doesn't exist do this
        else {
				$add_person = "UPDATE reservation_entry SET person_id=$person, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE id = $ev_reservation_id";
				$add_person_res = mysql_query($add_person) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );
        }

        $is_pending = $_POST['is_pending'];
        header("Location:$ev_return_url");
	}
	else if($event_type == 1){
        if (isset($repeat_id) && $repeat_id != 0) {
				$add_person = "UPDATE reservation_entry SET person_id=$person_id, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE repeat_id = $repeat_id";
				$add_person_res = mysql_query($add_person) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
        }
        //if repeat id doesn't exist do this
        else {
				$add_person = "UPDATE reservation_entry SET person_id=$person_id, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE id = $reservation_id";
				$add_person_res = mysql_query($add_person) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );
        }

        $is_pending = $_POST['is_pending'];
        header("Location:$return_url");
	}
	else{

    //check to see if person has any unpaid reservations
    $check_unpaid = "SELECT COUNT(*) AS status_count FROM reservation_entry WHERE reservation_status=\"UNPAID\" AND person_id=$person_id";
    $check_unpaid_res = mysql_query($check_unpaid) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

	if (mysql_num_rows($check_unpaid_res)> 0){
    //if any unpaid reservations exist, send reservation to override form
    while($results = mysql_fetch_array($check_unpaid_res)) {
        $status_count = $results['status_count'];
        if ($status_count > 0) {
				header("Location:override_form.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id");
        }

        //if no unpaid reservations exist add person to reservation
        elseif ($status_count == 0) {
        
        //if repeat id exists do this

        if (isset($repeat_id) && $repeat_id != 0) {
				$add_person = "UPDATE reservation_entry SET person_id=$person_id, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE repeat_id = $repeat_id";
				$add_person_res = mysql_query($add_person) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );
        }
        //if repeat id doesn't exist do this
        else {
				$add_person = "UPDATE reservation_entry SET person_id=$person_id, is_pending=0, reservation_status='$OPEN', band_id=1 WHERE id = $reservation_id";
				$add_person_res = mysql_query($add_person) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );
        }

        $is_pending = $_POST['is_pending'];
        header("Location:$return_url");
        }//end elsif status_count == 0
    }//end while fetch array
       
    }//end if num rows >0
	}//end else
?>

