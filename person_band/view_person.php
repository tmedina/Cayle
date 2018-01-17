<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page views person information from the dropdown menu during the reservation process and adds a
//member to an existing band


//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to database and functions.inc file
include("../includes/dbconnect.inc");
include("../includes/functions.inc");

//view a selected person record
if ($_POST[op] != "add_band_member")
 {
    $reservation_id = $_POST['reservation_id'];
    $repeat_id = $_POST['repeat_id'];
    $return_url = $_POST['return_url'];
    $person_id = $_POST[sel_id];

    
    //get person info
    $get_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_comment, person_status FROM person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.is_active=1 AND person.id = $_POST[sel_id]";
    $get_info_res = mysql_query($get_info);

    $person_id = $_POST[sel_id];
    //display person info
    if (mysql_num_rows($get_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_info_res))
        {
            //define variables
            $id = $person_info['id'];
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
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Add person to reservation</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>$person_status<br /><br /><strong>Comments: </strong>$person_comment<br /><br />";

            }

            //if address is supplied show city, state & zip
            else
            {
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Add person to reservation</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname <br />$address<br />$city $state_name $zip<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </em></strong>$person_status<br /><br /><strong>Comments: </em></strong>$person_comment<br /><br />";
            }

            //links to select a different person, edit record & add person to reservation

            $display_block .= "<a href=\"edit_on_res.php?cmd=edit&id=$person_id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Edit</a> | ";
            $display_block .= "<a href=\"confirmation.php?reservation_id=$reservation_id&person_id=$id&repeat_id=$repeat_id&return_url=$return_url\">Add this person to reservation</a>";
			$display_block .= "</td></tr></table></div>";


            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands);

            //if bands exist display them
            if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<div id=\"band\" align=\"left\"><h3 align=\left\">Add person and band to reservation</h3>";
                $display_block .="<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
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

            $display_block .= "<tr><td><strong>$band_name</strong></td>";
            $display_block .= "<td align=\"right\"><a href=\"confirm_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&band_id=$band_id&person_id=$id\">Add this band & person to reservation</a></td></tr>";
            }
            //if person is not a contact for this band just display its name
            else
            {

            $display_block .= "<tr><td>$band_name</td>";
            $display_block .= "<td align=\"right\"><a href=\"confirm_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&band_id=$band_id&person_id=$id\">Add this band & person to reservation</a></td></tr>";
            }
            }
            $display_block .= "</table>";
            }
            //if person isn't a member of any bands insert a line break
            elseif (mysql_num_rows($get_bands_res) == 0)

            {
               $display_block .= "<div id=\"band\" align=\"left\"><h3 align=\left\">Add person and band to reservation</h3>";
               $display_block .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
               $display_block .= "<tr><td><em>No band memberships</em></td></tr>";
               $display_block .= "</table>";
            }
          }

        }

        //set up band drop down menu
        $get_list = "SELECT id, band_name FROM band WHERE is_active = 1 AND id != 1 ORDER BY band_name ASC";
        $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


        //if no records available
        if (mysql_num_rows($get_list_res) <1)
        {

         $display_block . "<p><em>No records available</em></p></div>";
        } else
        {
         //if band records available - get results & create drop down menu
         $display_block .= "<h3>Add band membership</h3><table><tr><td>
         <form method=\"post\" name=\"band\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">

         <select name=\"sel_id\">
         <option value=\"0\">--Select a band--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $band_name = stripslashes($recs['band_name']);
             $display_block .= "<option value=\"$id\">
             $band_name</option>";
         }
            //button to view selected band
            $display_block .= "</select>
             <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
            <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
            <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
            <input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
            <input type=\"hidden\" name=\"op\" value=\"add_band_member\">
            <input type=\"submit\" name=\"submit\" value=\"Add band membership\"></form></td></tr></table>
            <a href=\"create_new_band_on_res.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Create a new band record</a></div>";

        }
        //get all reservations associated with this person
        $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'CLOSED' AND person_id = $person_id ORDER BY start_time ASC";
        $get_reservation_info_res = mysql_query($get_reservation_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        //if reservations exist display them
            if (mysql_num_rows($get_reservation_info_res)> 0)
            {

                $display_block .= "<div id=\"member\" align=\"left\"><h3>Open reservations for this person - <br />&nbsp;&nbsp;past due listed in <font style=\"color:#FF0000\">red</font></h3>";
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
                    $display_block .= "<tr><td style=\"color:#FF0000\">" . utf_period_2_date($start_time) . "</td></tr>";
                    }

                    //if room reservation status is open display
                    elseif ($reservation_status != "UNPAID" && $room_id != "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td></tr>";
                    }
                    //if equipment reservation status is open display
                     elseif ($reservation_status != "UNPAID" && $room_id == "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td></tr>";
                    }
                }

                $display_block .= "</table></div>";

            }
            //if no reservations exist insert a line break
            else

            {
                $display_block .= "<div id=\"member\"><br /></div>";

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


                    $display_block .= "<tr><td width=\"200\">" . $equip_description . "</td></tr>";

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

//add a band member to an existing band
 if ($_POST[op] == "add_band_member")
    {

    $person_id = $_POST['person_id'];
    $band_id = $_POST['sel_id'];
    $reservation_id = $_POST['reservation_id'];
    $repeat_id = $_POST['repeat_id'];
    $return_url = $_POST['return_url'];

    //add row to band member table
    $create_band_member = "INSERT INTO band_member (band_id, person_id) VALUES($band_id, $person_id)";
    $create_band_member_res = mysql_query($create_band_member) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    //get person info
    $get_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_comment, person_status FROM person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.is_active=1 AND person.id = $person_id";
    $get_info_res = mysql_query($get_info);

   //display person info
    if (mysql_num_rows($get_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_info_res))
        {
            //define variables
            $id = $person_info['id'];
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
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Add person to reservation</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>$person_status<br /><br /><strong>Comments: </strong>$person_comment<br /><br />";
            }
            //if address is supplied show city, state & zip
            else
            {
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\"><h3>Add person to reservation</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname <br />$address<br />$city $state_name $zip<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </em></strong>$person_status<br /><br /><strong>Comments: </em></strong>$person_comment<br /><br />";
            }

            //links to select a different person, edit record & add person to reservation
            $display_block .= "<a href=\"edit_on_res.php?cmd=edit&id=$person_id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Edit</a> | ";
            $display_block .= "<a href=\"confirmation.php?reservation_id=$reservation_id&person_id=$id&repeat_id=$repeat_id&return_url=$return_url\">Add this person to reservation</a>";
			$display_block .= "<br /></td></tr></table></div>";

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands);

            //if bands exist display them
            if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<div id=\"band\" align=\"left\"><h3 align=\left\">Add person and band to reservation</h3>";
                $display_block .="<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
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

            $display_block .= "<tr><td><strong>$band_name</strong></td>";
            $display_block .= "<td align=\"right\"><a href=\"confirm_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&band_id=$band_id&person_id=$id\">Add this band & person to reservation</a></td></tr>";
            }
            //if person is not a contact for this band just display its name
            else
            {

            $display_block .= "<tr><td>$band_name</td>";
            $display_block .= "<td align=\"right\"><a href=\"confirm_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&band_id=$band_id&person_id=$id\">Add this band & person to reservation</a></td></tr>";
            }
            }
            $display_block .= "</table>";
            }
            //if person isn't a member of any bands insert a line break
            elseif (mysql_num_rows($get_bands_res) == 0)

            {
               $display_block .= "<div id=\"band\" align=\"left\"><h3 align=\left\">Add person and band to reservation</h3>";
               $display_block .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
               $display_block .= "<tr><td><em>No band memberships</em></td></tr>";
               $display_block .= "</table>";
            }
          }

       }
        //set up band drop down menu
        $get_list = "SELECT id, band_name FROM band WHERE is_active = 1 AND id != 1 ORDER BY band_name ASC";
        $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


        //if no records available
        if (mysql_num_rows($get_list_res) <1)
        {

         $display_block . "<p><em>No records available</em></p>";
        } else
        {
         //if band records available - get results & create drop down menu
         $display_block .="<h3 align=\"left\">Add band membership</h3><table><tr><td>
         <form method=\"post\" name=\"band\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">
         <select name=\"sel_id\">
         <option value=\"0\">--Select a band--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $band_name = stripslashes($recs['band_name']);
             $display_block .= "<option value=\"$id\">
             $band_name</option>";
         }
            //button to view selected band
            $display_block .= "</select>
             <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
            <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
            <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
            <input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
            <input type=\"hidden\" name=\"op\" value=\"add_band_member\">
            <input type=\"submit\" name=\"submit\" value=\"Add band membership\"></form></td></tr></table>";

            $display_block .= "<a href=\"create_new_band_on_res.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Create a new band record</a></div>";

        }
        //get all reservations associated with this person
        $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'closed' AND is_cancelled=0 AND person_id = $person_id ORDER BY start_time ASC";
        $get_reservation_info_res = mysql_query($get_reservation_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



        //if reservations exist display them
            if (mysql_num_rows($get_reservation_info_res)> 0)
            {

                $display_block .= "<div id=\"member\" align=\"left\"><h3>Open reservations for this person -<br />&nbsp;&nbsp;past due listed in <font style=\"color:#FF0000\">red</font></h3>";
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
                    $display_block .= "<tr><td style=\"color:#FF0000\">" . utf_period_2_date($start_time) . "</td></tr>";
                    }

                    //if room reservation status is open display
                    elseif ($reservation_status == "OPEN" && $room_id != "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td></tr>";
                    }
                    //if equipment reservation status is open display
                     elseif ($reservation_status == "OPEN" && $room_id == "")
                    {
                    $display_block .= "<tr><td width=\"200\">" . utf_period_2_date($start_time) . "</td></tr>";
                    }
                }

                $display_block .= "</table></div>";

            }
            //if no reservations exist insert a line break
            else

            {
                $display_block .= "<div id=\"member\"><br /></div>";
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


                    $display_block .= "<tr><td width=\"200\">" . $equip_description . "</td></tr>";

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
    <title>Add person to reservation</title>
    <link href="includes/person_band_res.css" rel="stylesheet" type="text/css" />
   
    </head>
    <body>
     <? include("../includes/header.inc"); ?>

    <div id="page" align="center">
    <h2 align="left">Add person to reservation</h2>
    <? Print $display_block; ?>

    </div>
    </body>
</html>