<?php
//CHECKOUT MODULE
//Created by Hallie Pritchett
//This page shows person information

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database
include("includes/dbconnect.inc");

$get_person_info = "SELECT band.id, fname,lname, address, city, state_name, zip, phone, email, person_comment, person_status, band_name, band_comment FROM person, states, person_status, reservation_entry, band WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND reservation_entry.person_id = person.id AND reservation_entry.band_id = band.id AND person.is_active=1 AND reservation_entry.id = $reservation_id";
$get_person_info_res = mysql_query($get_person_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

//display person info
    if (mysql_num_rows($get_person_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_person_info_res))
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
            $band_id = $person_info['id'];
            $band_name = stripslashes($person_info['band_name']);
            $band_comment = stripslashes($person_info['band_comment']);
           
           
            //if reservation includes a band & if address not supplied don't show city, state or zip
            if ($address == "" && $band_id != 1)
            {
            
            $display_block .= "$fname $lname ($band_name)<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a>";
            
            }
            //if reservation includes a band & if address is supplied show city, state & zip
            elseif ($address != "" && $band_id != 1)
            {
            
            $display_block .= "$fname $lname ($band_name)<br />$address<br />$city $state_name $zip<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a>";
            
            }
            //if reservation doesn't include a band & if address is not supplied don't show city, state & zip
            elseif ($address == "" && $band_id == 1)
            {
            
            $display_block .= "$fname $lname<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a>";
            }
           
            elseif ($band_id == 1)
            {
            
            $display_block .= "$fname $lname <br />$address<br />$city $state_name $zip<br />$phone<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a>";
           
            }
        }
    }
//Print $display_block;
?>

