<?php
//PERSON/BAND MODULE - ADMINISTRATOR VIEW
//Created by Hallie Pritchett
//This page activates a person record that has been deactivated

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database
include("../includes/dbconnect.inc");

    $person_id = $_GET['person_id'];

    //set person record to is active
    $activate = "UPDATE person SET is_active=1 WHERE id=$person_id";
    $activate_res = mysql_query($activate) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    $get_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_status, person_comment, person.is_active, user_name FROM person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.id = $person_id";
    $get_info_res = mysql_query($get_info);

    //display person info
    if (mysql_num_rows($get_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_info_res))
        {
            //define variables
            $person_id = $person_info['id'];
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
            $is_active = $person_info['is_active'];
            $user_name = $person_info['user_name'];

            //if record is active & has an address do this
            if ($is_active == 1 && $address != "")
            {
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .="<div id=\"text\" align=\"left\"><h3>Record for $fname $lname - active</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>User name: </strong>" . $user_name . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            $display_block .= "<a href=\"band.php\">Add band membership</a> | <a href=\"deactivate_person.php?person_id=$person_id\" onclick=\"return confirm('Are you sure you want to deactivate this record?')\">Deactivate</a> | <a href=\"edit.php?cmd=edit&id=$person_id\">Edit</a></td></tr></table></div>";
            }

            //if record is active but doesn't have an address do this
            elseif ($is_active == 1 && $address == "")
            {
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>User name: </strong>" . $user_name . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            $display_block .= "<a href=\"band.php\">Add band membership</a> | <a href=\"deactivate_person.php?person_id=$person_id\" onclick=\"return confirm('Are you sure you want to deactivate this record?')\">Deactivate</a> | <a href=\"edit.php?cmd=edit&id=$person_id\">Edit</a></td></tr></table></div>";

            }

            //if record is inactive and has an address do this
            elseif ($is_active == 0 && $address != "")
            {

            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h4 style=\"color:#FF0000\">Record for $fname $lname - inactive</h4>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>User name: </strong>" . $user_name . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";         
            $display_block .= "<a href=\"activate_person.php?person_id=$person_id\" onclick=\"return confirm('Are you sure you want to activate this record?')\">Activate</a> | <a href=\"edit.php?cmd=edit&id=$person_id\">Edit</a></td></tr></table>";
            $display_block .= "<h3 style=\"color:#FF0000\">Record must be active to add band membership</h3></div> ";

            }

            //if record is inactive and doesn't have an address do this
            elseif ($is_active == 0 && $address == "")
            {
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h4 style=\"color:#FF0000\">Record for $fname $lname - inactive</h4>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>User name: </strong>" . $user_name . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br />";
            $display_block .= "<a href=\"activate_person.php?person_id=$person_id\" onclick=\"return confirm('Are you sure you want to activate this record?')\">Activate</a> | <a href=\"edit.php?cmd=edit&id=$person_id\">Edit</a></td></tr></table>";
            $display_block .= "<h3 style=\"color:#FF0000\">Record must be active to add band membership</h3></div> ";
            }

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person, band.is_active FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND person_id = '$person_id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands);

            //if bands exist display them
            if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<div id=\"band\"><h3>Band membership - person is contact for bands listed in bold - <br />&nbsp;bands with inactive records are shown in <span style=\"color:#FF0000\">red</span></h3>";
                $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";
                
            while ($band_info = mysql_fetch_array($get_bands_res))
            {
                //define variables
                $band_name = stripslashes($band_info['band_name']);
                $band_comment = stripslashes($band_info['band_comment']);
                $band_id = $band_info['id'];
                $contact_person = $band_info['contact_person'];
                $is_active = $band_info['is_active'];

            //if person is a contact for this band display band name in bold
            if ($contact_person == 1 && $is_active == 1)
            {

            $display_block .= "<tr><td width=\"250\"><strong>$band_name</strong></td><td align=\"right\"><a href=\"band_record.php?band_id=$band_id\">View band record</a></td></tr>";
            }elseif ($contact_person == 1 && $is_active == 0)
            {

            $display_block .= "<tr><td style=\"color:#FF0000\" width=\"250\"><strong>$band_name</strong></td><td align=\"right\"><a href=\"band_record.php?band_id=$band_id\">View band record</a></td></tr>";
            }
            //if person is not a contact for this band just display its name
            elseif ($contact_person == 0 && $is_active == 1)
            {

            $display_block .= "<tr><td width=\"250\">$band_name</td><td align=\"right\"><a href=\"band_record.php?band_id=$band_id\">View band record</a></td></tr>";
            } elseif ($contact_person == 0 && $is_active == 0)
            {

            $display_block .= "<tr><td style=\"color:#FF0000\" width=\"250\">$band_name</td><td align=\"right\"><a href=\"band_record.php?band_id=$band_id\">View band record</a></td></tr>";
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
            $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'closed' AND person_id = $person_id ORDER BY start_time ASC";
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

                    //if room reservation status is unpaid display in red
                    if ($reservation_status == "UNPAID")
                    {
                    $display_block .= "<tr><td style=\"color:#FF0000\">" . utf_period_2_date($start_time) . "</td><td>Res status=$reservation_status <a href=\"../checkout/invoice.php?reservation_id=$reservation_id\">View invoice</a></td></tr>";
                    }

                    //if room reservation status is open display
                    elseif ($reservation_status != "UNPAID" && $room_id != "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td><td>Res status=$reservation_status <a href=\"../reservation/view_entry.php?id=$reservation_id\">View reservation</a></td></tr>";
                    }
                    //if equipment reservation status is open display
                     elseif ($reservation_status != "UNPAID" && $room_id == "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td><td>Res status=$reservation_status <a href=\"../equipment_rental/view_detail.php?id=$reservation_id\">View reservation</a></td></tr>";
                    }
                }

                $display_block .= "</table></div>";

            }
            //if no reservations exist insert a line break
            else

            {
                $display_block .= "<div id=\"member\"><br /></div>";
            }

    }

 //end section


?>

 <html>
    <head>
    <title>Person records - administrator view</title>
    <link href="includes/person_band_admin.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
     <? include("../includes/header.inc"); ?>

    <div id="page" align="center">
    <h2 align="left">View/create person records - administrator view</h2>
    <? Print $display_block; ?>
    
    </div>
  
    </body>
</html>
