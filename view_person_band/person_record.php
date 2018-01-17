<?php
//PERSON/BAND MODULE
//Created by Hallie Pritchett
//This page shows a person record from the search box and band record

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database and functions.inc file
 include("../includes/dbconnect.inc");
 include("../includes/functions.inc");

    $person_id = $_GET['person_id'];

  //view person record from band record

    //get person info
    $get_info = "SELECT fname, lname, address, city, state_name, zip, phone, email, person_status, person_comment from person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.is_active=1 AND person.id = $person_id";
    $get_info_res = mysql_query($get_info);
    
    //display person info
    if (mysql_num_rows($get_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_info_res))
        {
            //define variables
            
            $fname = stripslashes($person_info['fname']);
            $lname = stripslashes($person_info['lname']);
            $address = stripslashes($person_info['address']);
            $city = stripslashes($person_info['city']);
            $state_name = stripslashes($person_info['state_name']);
            $zip = stripslashes($person_info['zip']);
            $phone = $person_info['phone'];
            $email = stripslashes($person_info['email']);
            $person_status = stripslashes($person_info['person_status']);
            $person_comment = stripslashes($person_info['person_comment']);

            //if address not supplied don't show city, state or zip
            if ($address == "")
            {
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for $fname $lname</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname <br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </em></strong>$person_status<br /><br /><strong>Comments: </em></strong>$person_comment<br /><br />";
            }
            else
            //if address is supplied show city, state & zip
            {
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for $fname $lname</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname <br />$address<br />$city $state_name $zip<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </em></strong>$person_status<br /><br /><strong>Comments: </em></strong>$person_comment<br /><br />";
            }
            $display_block .= "<a href=\"band.php\">Add band membership</a> | <a href=\"edit.php?cmd=edit&id=$person_id\">Edit</a></td></tr></table></div>";

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$person_id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands);

            //if bands exist display them
            if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<div id=\"band\"><h3>Band membership - person is contact for bands listed in bold</h3>";
                $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";

            while ($band_info = mysql_fetch_array($get_bands_res))
            {
                //define variables
                $band_name = stripslashes($band_info['band_name']);
                $band_comment = stripslashes($band_info['band_comment']);
                $band_id = $band_info['id'];
                $contact_person = $band_info['contact_person'];

            //if person is a contact for this band display band name in bold
            if ($contact_person == 1)
            {

            $display_block .= "<tr><td width=\"250\"><strong>$band_name</strong></td><td align=\"right\"><a href=\"band_record.php?band_id=$band_id\">View band record</a></td></tr>";
            }
            //if person is not a contact for this band just display its name
            else
            {

            $display_block .= "<tr><td width=\"250\">$band_name</td><td align=\"right\"><a href=\"band_record.php?band_id=$band_id\">View band record</a></td></tr>";
            }
            }
            $display_block .= "</table></div>";

            }
            //if person isn't a member of any bands insert a line break
            elseif (mysql_num_rows($get_bands_res) == 0)

            {
                $display_block .= "<div id=\"band\"><br /></div>";
            }
        }

        //get all reservations associated with this person
        $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'CLOSED' AND person_id = $person_id ORDER BY start_time ASC";
        $get_reservation_info_res = mysql_query($get_reservation_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


        //if reservations exist display them
            if (mysql_num_rows($get_reservation_info_res)> 0)
            {

                $display_block .= "<div id=\"member\" align=\"left\"><h3>Open reservations for this person - past due listed in <font style=\"color:#FF0000\">red</font></h3>";
                $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";

                while ($res_info = mysql_fetch_array($get_reservation_info_res))
                {
                    $reservation_id = $res_info['id'];
                    $reservation_status = $res_info['reservation_status'];
                    $start_time =  $res_info['start_time'];
                    $room_id = $res_info['room_id'];
                    
                    if ($reservation_status == "UNPAID")
                    {
                    $display_block .= "<tr><td style=\"color:#FF0000\">" . utf_period_2_date($start_time) . "</td><td><a href=\"../checkout/invoice.php?reservation_id=$reservation_id\">View invoice</a></td></tr>";
                    }

                    //if room reservation status is open display
                    elseif ($reservation_status != "UNPAID" && $room_id != "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td><td><a href=\"../reservation/view_entry.php?id=$reservation_id\">View reservation</a></td></tr>";
                    }
                    //if equipment reservation status is open display
                     elseif ($reservation_status != "UNPAID" && $room_id == "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td><td><a href=\"../equipment_rental/view_detail.php?id=$reservation_id\">View reservation</a></td></tr>";
                    }
                }

                $display_block .= "</table></div>";

            }
            //if no reservations exist insert a line break
            else

            {
                $display_block .= "<div id=\"member\" align=\"left\"><br /></div>";
            }
            //get all equipment awaiting inspection associated with this person
            $get_reservation_info = "SELECT reservation_entry.id, start_time, equip_description
            FROM reservation_entry, reservation_transaction, equipment
            WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
            AND reservation_transaction.equipment_id = equipment.id AND is_awaiting_inspection=1
            AND person_id=$person_id ORDER BY start_time;";
            $get_reservation_info_res = mysql_query($get_reservation_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

            //if equipment exist display it
            if (mysql_num_rows($get_reservation_info_res)> 0)
            {

                $display_block .= "<div id=\"member\" align=\"left\"><h3>Returned equipment pending inspection for this person</h3>";
                $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";

                while ($res_info = mysql_fetch_array($get_reservation_info_res))
                {
                    $reservation_id = $res_info['id'];
                    $equip_description = $res_info['equip_description'];
                    $start_time =  $res_info['start_time'];


                    $display_block .= "<tr><td width=\"200\">" . $equip_description . "<td><a href=\"../equipment_rental/inspection.php\">Inspect equipment</a></td></tr>";

                }

                $display_block .= "</table></div>";

            }
            //if no equipment awaiting inspection insert a line break
            else

            {
                $display_block .= "<div id=\"member\" align=\"left\"><br /></div>";
            }


        
    }
 
 //end section


?>

<html>
    <head>
    <link href="includes/person_band.css" rel="stylesheet" type="text/css" />
    <title>Person records</title>

    </head>
    <body>
    <? include("../includes/header.inc"); ?>

    <div id="page" align="center">
    <h2 align="left">View/create person records</h2>
    <? Print $display_block; ?>
    </div>
    </body>
</html>
