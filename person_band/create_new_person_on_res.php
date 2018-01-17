<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page creates a new person record during the reservation process

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database & functions.inc file
include("../includes/dbconnect.inc");
include("../includes/functions.inc");

    //define variables
    $reservation_id = $_GET['reservation_id'];
    $repeat_id = $_GET['repeat_id'];
    $return_url = $_GET['return_url']; 

//form to create a new record
if (!isset($_POST['submit_person']) && !isset($_POST['add_band_member']))
    {

            $display_block .= "<h2 align=\"left\">Add person to reservation - create a new person record</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\"><h3 align=\"left\">Required *</h3>
            <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
            <table border=\"0\">
            <tr><td>First name:</td><td> <input type=\"text\" size=50 name=\"fname\" value=\"$fname\" > <strong>*</strong></td></tr>
            <tr><td>Last name:</td><td> <input type=\"text\" size=50 name=\"lname\" value=\"$lname\"> <strong>*</strong></td></tr>
            <tr><td>Address:</td><td> <input type=\"text\" size=50 name=\"address\" value=\"$address\"></td></tr>
            <tr><td>City:</td><td> <input type=\"text\" size=41 name=\"city\" value=\"$city\">&nbsp;";

            //set up states drop down menu
            $get_list = "SELECT * FROM states WHERE is_active=1 ORDER BY state_name ASC";
            $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



                if (mysql_num_rows($get_list_res) <1)
                    {
                    //if no records available
                    $display_block .= "<p><em>No records available</em></p>";
                    } else
                    {
                    //if records available - get results & create drop down menu
                    $display_block .="<select name=\"state_id\">";

                    while($recs = mysql_fetch_array($get_list_res))
                    {
                    //define variables
                    $state_id = $recs['id'];
                    $state_name = stripslashes($recs['state_name']);

                    $display_block .= "<option value=\"$state_id\"";

                    if($state_name == "GA")
                    {
                        $display_block .= "selected";
                    }

                    $display_block .=">$state_name</option>";
                }

            }

            $display_block .= "</select></td></tr>";
            $display_block .= "<tr><td>ZIP: </td><td><input type=\"text\" size=50 name=\"zip\" value=\"$zip\"></td></tr>
            <tr><td>Phone: </td><td><input type=\"text\" size=2 maxlength=\"3\" name=\"area_code\" value=\"$area_code\"> - <input type=\"text\" size=2 maxlength=\"3\" name=\"phone_prefix\" value=\"$phone_prefix\"> - <input type=\"text\" size=2 maxlength=\"4\" name=\"phone_suffix\" value=\"$phone_suffix\"> <strong>*</strong></td></tr>
            <tr><td>Email: </td><td><input type=\"text\" size=50 name=\"email\" value=\"$email\"></td></tr>";
            
            $display_block .= "<tr><td valign=\"top\">Comment: </td><td><textarea rows=\"3\" cols=\"38\" name=\"person_comment\"></textarea></td></tr>";

            $display_block .= "<tr><td colspan=2 align=\"center\">
            <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
            <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
            <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
            <input type=\"reset\" value=\"Clear form\">
            <input type=\"submit\" name=\"submit_person\" value=\"Submit\"></td></tr></table></form></div>";

           
    }
//end section

//create a new person record
 if (isset($_POST['submit_person']))
    {

        //define variables
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $area_code = $_POST['area_code'];
        $phone_prefix = $_POST['phone_prefix'];
        $phone_suffix = $_POST['phone_suffix'];
        $phone = $area_code . "-" . $phone_prefix . "-" . $phone_suffix;      
        $reservation_id = $_POST['reservation_id'];
        $repeat_id = $_POST['repeat_id'];
        $return_url = $_POST['return_url'];

        //check to see if the record already exists in the db
        $check_for_dups = "SELECT fname, lname, phone FROM person WHERE fname = '$fname' AND lname = '$lname' AND phone = '$phone'";
        $check_for_dups_res = mysql_query ($check_for_dups);

    //if a record already exists display it
    if (mysql_num_rows($check_for_dups_res)> 0)

    {

      $get_person_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_comment, person_status, person.is_active FROM person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND fname = '$fname' AND lname='$lname' AND phone = '$phone'";
      $get_person_info_res = mysql_query($get_person_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

       //display person info
    if (mysql_num_rows($get_person_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_person_info_res))
        {

            //define variables
            $person_id = $person_info['id'];
            $fname = $person_info['fname'];
            $lname = $person_info['lname'];
            $address = $person_info['address'];
            $city = $person_info['city'];
            $state_name = $person_info['state_name'];
            $zip = $person_info['zip'];
            $phone = $person_info['phone'];
            $email = $person_info['email'];
            $person_status = $person_info['person_status'];
            $person_comment = $person_info['person_comment'];
            $is_active = $person_info['is_active'];

            //if address not supplied don't show city, state or zip
            if ($is_active == 1 && $address == "")
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\" style=\"color:#FF0000\">Record already exists for " . stripslashes($fname) . " " . stripslashes($lname). "</h4>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            $display_block .= "<a href=\"edit_on_res.php?cmd=edit&id=$person_id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Edit</a> | ";
            $display_block .= "<a href=\"confirmation.php?reservation_id=$reservation_id&person_id=$person_id&repeat_id=$repeat_id&return_url=$return_url\">Add this person to reservation</a>";
			$display_block .= "<br /></td></tr></table></div>";

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$person_id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands);

            //if bands exist display them
            if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<div id=\"band\" align=\"left\"><h3 align=\left\">Band membership - person is contact for bands listed in bold</h3>";
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


        //set up band drop down menu
        $get_list = "SELECT id, band_name FROM band WHERE is_active = 1 AND id != 1 ORDER BY band_name ASC";
        $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


        //if no records available
        if (mysql_num_rows($get_list_res) <1)
        {

         $display_block . "<p><em>No records available</em></p></div>";
        }
        else
        {
         //if band records available - get results & create drop down menu
         $display_block .= "<h3>Add band membership</h3><table><tr><td>
         <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">

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
            <input type=\"hidden\" name=\"add_band_member\">
            <input type=\"submit\" name=\"submit\" value=\"Add band membership\"></form></td></tr></table>
            <a href=\"create_new_band_on_res.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Create a new band record</a></div>";

        }

            //get all reservations associated with this person
        $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'CLOSED' AND person_id = $person_id ORDER BY start_time ASC";
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
                $display_block .= "<div id=\"member\"><br /></div>";
            }


            }

            //if address is supplied show city, state & zip
            elseif ($is_active == 1 && $address != "")
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 style=\"color:#FF0000\">Record already exists for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            $display_block .= "<a href=\"edit_on_res.php?cmd=edit&id=$person_id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Edit</a> | ";
            $display_block .= "<a href=\"confirmation.php?reservation_id=$reservation_id&person_id=$person_id&repeat_id=$repeat_id&return_url=$return_url\">Add this person to reservation</a>";
			$display_block .= "<br /></td></tr></table></div>";

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$person_id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands);

            //if bands exist display them
            if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<div id=\"band\" align=\"left\"><h3 align=\left\">Band membership - person is contact for bands listed in bold</h3>";
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

        //set up band drop down menu
        $get_list = "SELECT id, band_name FROM band WHERE is_active = 1 AND id != 1 ORDER BY band_name ASC";
        $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


        //if no records available
        if (mysql_num_rows($get_list_res) <1)
        {

         $display_block . "<p><em>No records available</em></p></div>";
        }
        else
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
            <input type=\"hidden\" name=\"add_band_member\">
            <input type=\"submit\" name=\"submit\" value=\"Add band membership\"></form></td></tr></table>
            <a href=\"create_new_band_on_res.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Create a new band record</a></div>";

        }

            //get all reservations associated with this person
        $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'CLOSED' AND person_id = $person_id ORDER BY start_time ASC";
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



            elseif ($is_active == 0 && $address != "")
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\">";
            $display_block .= "<h3 style=\"color:#FF0000\">Record already exists for " . stripslashes($fname) . " " . stripslashes($lname). " - inactive</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /></td></tr></table>";
            $display_block .= "<h3 align=\"left\" style=\"color:#FF0000\">Contact administrator to activate record</h3></div> ";

            }

            elseif ($is_active == 0 && $address == "")
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\"><h3 style=\"color:#FF0000\">Record already exists for " . stripslashes($fname) . " " . stripslashes($lname). " - inactive</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /></td></tr></table>";
            $display_block .= "<br /><h3 align=\"left\" style=\"color:#FF0000\">Contact administrator to activate record</h3></div> ";
            }

        }
          }
       
    }
    //if the record doesn't exist create it
    else

    {
        //define variables
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $address = trim($_POST['address']);
        $city = trim($_POST['city']);
        $state_id = trim($_POST['state_id']);
        $zip = trim($_POST['zip']);
        $area_code = $_POST['area_code'];
        $phone_prefix = $_POST['phone_prefix'];
        $phone_suffix = $_POST['phone_suffix'];
        $phone = $area_code . "-" . $phone_prefix . "-" . $phone_suffix; 
        $email = trim($_POST['email']);
        $person_status_id = 1;
        $person_comment = trim($_POST['person_comment']);
        $reservation_id = $_POST['reservation_id'];
        $repeat_id = $_POST['repeat_id'];
        $return_url = $_POST['return_url'];

        //create a row in the person table
        $query = ("INSERT INTO person (fname, lname, address, city, state_id, zip, phone, email, person_status_id, person_comment) VALUES ('$fname', '$lname', '$address', '$city', $state_id, '$zip', '$phone', '$email', '1', '$person_comment')");
        mysql_query ($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        if ($address =="")
        {

         //get the person status based on the person status id submitted
        $get_person_status = "SELECT person_status FROM person_status WHERE id = $person_status_id";
        $get_person_status_res = mysql_query($get_person_status) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

           if (mysql_num_rows($get_person_status_res)> 0)
            {
                while ($results = mysql_fetch_array($get_person_status_res))
                $person_status = stripslashes($results['person_status']);

            }


        //display that record was created
        $display_block .= "<h2 align=\"left\">Add person to reservation - record created</h2>";
        $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
        $display_block .= "<h3 align=\"left\"><table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
        $display_block .= stripslashes($phone) . "<br />";
        $display_block .= "<a href=mailto:" . $email . ">" . $email . "</a><br /><br />";
        $display_block .= "<strong>Status:</strong> " . stripslashes($person_status) . "<br /><br /><strong>Comments:</strong> " . stripslashes($person_comment) . "<br /><br />";

        

        }
        else
        {
        //get the state name based on the state id submitted
        $get_state_name = "SELECT state_name FROM states WHERE id = $state_id";
        $get_state_name_res = mysql_query ($get_state_name) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



            if (mysql_num_rows($get_state_name_res)> 0)
            {
                while ($results = mysql_fetch_array($get_state_name_res))
                $state_name = stripslashes($results['state_name']);

            }

        //get the person status based on the person status id submitted
       $get_person_status = "SELECT person_status FROM person_status WHERE id = $person_status_id";
       $get_person_status_res = mysql_query($get_person_status) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



           if (mysql_num_rows($get_person_status_res)> 0)
            {
                while ($results = mysql_fetch_array($get_person_status_res))
                $person_status = stripslashes($results['person_status']);

            }

        //display that record was created
        $display_block .= "<h2 align=\"left\">Add person to reservation - record created</h2>";
        $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
        $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />" . stripslashes($address) . "<br />";
        
        $display_block .= stripslashes($city) . " " . stripslashes($state_name) . " ";
        $display_block .= stripslashes($zip) . "<br />" .  stripslashes($phone) . "<br />";
        $display_block .= "<a href=mailto:" . $email . ">" . $email . "</a><br /><br />";
        $display_block .= "<strong>Status:</strong> " . stripslashes($person_status) . "<br /><br /><strong>Comments:</strong> " . stripslashes($person_comment) . "<br /><br />";
        }
        //link to edit record
        $display_block .= "<a href=\"edit_new_person_on_res.php?cmd=edit&fname=$fname&lname=$lname&phone=$phone&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Edit</a> | ";
        $display_block .= "<a href=\"add_new_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&fname=$fname&lname=$lname&phone=$phone\">Continue with reservation</a>";
        $display_block .= "<br /></td></tr></table></div>";

        
                
        }
        
    }
//end section

//add a band member to an existing band
 if (isset($_POST['add_band_member']))
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
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Add person to reservation</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$fname $lname<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>$person_status<br /><br /><strong>Comments: </strong>$person_comment<br /><br />";
            }
            //if address is supplied show city, state & zip
            else
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
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
    <title>Person records</title>
    <link href="includes/person_band_res.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" type="text/javascript">
        <!--
        function checkform ( form )
        {

        if (form.fname.value == "") {
            alert( "Please enter a first name" );
            form.fname.focus();
            return false ;
        }

        if (form.lname.value == "") {
            alert( "Please enter a last name" );
            form.lname.focus();
            return false ;
        }

        if (form.area_code.value == "") {
            alert( "Please enter an area code" );
            form.area_code.focus();
            return false ;
        }

        if (isNaN(form.area_code.value)) {
            alert( "Please use all numbers for your area code" );
            form.area_code.focus();
            return false ;
        }

        if (form.phone_prefix.value == "") {
            alert( "Please enter a complete phone number" );
            form.phone_prefix.focus();
            return false ;
        }

        if (isNaN(form.phone_prefix.value)) {
            alert( "Please use all numbers for your phone number" );
            form.phone_prefix.focus();
            return false ;
        }

        if (form.phone_suffix.value == "") {
            alert( "Please enter a complete phone number" );
            form.phone_suffix.focus();
            return false ;
        }

        if (isNaN(form.phone_suffix.value)) {
            alert( "Please use all numbers for your area code" );
            form.phone_suffix.focus();
            return false ;
        }


        return true;
        }
//-->
</script>
    </head>
    <body>
     <? include("../includes/header.inc"); ?>
    <div id="page" align="center">
    <? Print $display_block; ?>
    </div>
    </body>
</html>

