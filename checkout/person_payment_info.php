<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
//GET PERSON/BAND FOR CHECKOUT
//Created by Hallie Pritchett

//Initialize display block
$display_block = "";

        $reservation_id = $_GET['reservation_id'];

        //get person id from reservation
        $get_person_id = "SELECT person_id FROM reservation_entry WHERE id=$reservation_id";
        $get_person_id_res = mysql_query($get_person_id) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        if (mysql_num_rows($get_person_id_res)> 0)
            {

             while ($row = mysql_fetch_array($get_person_id_res))
            {
                $person_id = $row['person_id'];
            }
            }

        //get all unpaid reservations associated with this person
        $get_unpaid_reservations = $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'CLOSED' AND person_id = $person_id ORDER BY start_time ASC";
        $get_unpaid_reservations_res = mysql_query($get_unpaid_reservations) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        //echo $get_unpaid_reservations;

        //if reservations exist display them
            if (mysql_num_rows($get_unpaid_reservations_res)> 0)
            {

                
                $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";

                while ($res_info = mysql_fetch_array($get_unpaid_reservations_res))
                {
                    $unpaid_reservation_id = $res_info['id'];
                    $reservation_status = $res_info['reservation_status'];
                    $start_time =  $res_info['start_time'];
                    $room_id = $res_info['room_id'];

                    //if room reservation status is unpaid display in red
                    if ($reservation_status == "UNPAID" && $reservation_id != $unpaid_reservation_id)
                    {
                    $display_block .= "<tr><td style=\"color:#FF0000\">" . utf_period_2_date($start_time) . "</td><td><a href=\"../checkout/invoice.php?reservation_id=$unpaid_reservation_id\">View invoice</a></td></tr>";
                    }

                }

                $display_block .= "</table></div>";

            }
            //if no reservations exist insert a line break
            else

            {
                $display_block .= "No unpaid reservations";
            }
Print $display_block;
?>
