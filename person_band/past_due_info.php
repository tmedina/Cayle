<?php
//CHECKOUT MODULE
//Created by Hallie Pritchett
//This page shows a person's unpaid reservations

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database and functions.inc file
include("includes/dbconnect.inc");
include("includes/functions.inc");

        $reservation_id = $_GET['reservation_id'];

        //get person id from reservation
        $get_person_info = "SELECT person_id FROM reservation_entry WHERE id=$reservation_id";
        $get_person_info_res = mysql_query($get_person_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        if (mysql_num_rows($get_person_info_res)> 0)
            {

             while ($person_info = mysql_fetch_array($get_person_info_res))
            {
                $person_id = $person_info['person_id'];
            }
            }

        //get all unpaid reservations associated with this person
        $get_unpaid_reservations = "SELECT * FROM reservation_entry WHERE reservation_status='unpaid' AND person_id = $person_id";
        $get_unpaid_reservations_res = mysql_query($get_unpaid_reservations) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


        //if reservations exist display them
            if (mysql_num_rows($get_unpaid_reservations_res)> 0)
            {

                $display_block .= "<h3>Unpaid reservations listed in <font style=\"color:#FF0000\">red</font></h3>";
                $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";

                while ($res_info = mysql_fetch_array($get_unpaid_reservations_res))
                {
                    $reservation_id = $res_info['id'];
                    $reservation_status = $res_info['reservation_status'];
                    $start_time =  $res_info['start_time'];
                    //if reservation status is unpaid display in red

                    $display_block .= "<tr><td style=\"color:#FF0000\">" . utf_date_2_date($start_time) . "</td><td><a href=\"view_entry.php?reservation_id=$reservation_id\">View reservation</a></td></tr>";
                    
                }

                $display_block .= "</table></div>";

            }
            //if no reservations exist insert a line break
            else

            {
                $display_block .= "No unpaid reservations";
            }

?>

<html>
    <head></head>
    <body>
    <? Print $display_block; ?>
    </body>
</html>
