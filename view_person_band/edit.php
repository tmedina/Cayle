<?php
//PERSON/BAND MODULE
//Created by Hallie Pritchett
//This page edits a person record

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include("../includes/dbconnect.inc");
include("../includes/functions.inc");

//Initialize cmd
if(!isset($cmd))

 //display all person records
   $result = mysql_query("select * from person");

   while($r=mysql_fetch_array($result))
   {
      //define variables
        $fname=stripslashes($r['fname']);
        $lname=stripslashes($r['lname']);
        $phone=$r['phone'];
        $id=$r['id'];
    }
//end section

//set up edit form & prepopulate the fields
if($_GET['cmd']=="edit" || $_POST['cmd']=="edit")
{
   if (!isset($_POST['edit']))
   {
      $id = $_GET["id"];

      //get info to be edited
      $sql = "SELECT * FROM person WHERE id='$id'";
      $result = mysql_query($sql);
      $myrow = mysql_fetch_array($result);
      //define variables
            $id = $myrow['id'];
            $fname = stripslashes($myrow['fname']);
            $lname = stripslashes($myrow['lname']);
            $address = stripslashes($myrow['address']);
            $city = stripslashes($myrow['city']);
            $state_id_sel = stripslashes($myrow['state_id']);
            $zip = stripslashes($myrow['zip']);
            $phone = $myrow['phone'];
            list($area_code, $phone_prefix, $phone_suffix)= split("-", $phone);
            $email = stripslashes($myrow['email']);
            $person_comment = stripslashes($myrow['person_comment']);
            
      //display the prepopulated edit record form
      $display_block .= "<h2 align=\"left\">Edit person record</h2>
      <div id=\"text\">
      <h3 align=\"left\">Required *</h3>
      <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
      <input type=hidden name=\"id\" value=\"$id\">
      <table border=\"0\">
      <tr><td>First name:</td><td> <input type=\"text\" size=50 name=\"fname\" value=\"$fname\"> <strong>*</strong></td></tr>
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

             if($state_id == $state_id_sel)
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
      $display_block .= "<tr><td valign=\"top\">Comment: </td><td><textarea rows=\"3\" cols=\"38\" name=\"person_comment\">$person_comment</textarea></td></tr>";
      //button to submit updates
      $display_block .= "<tr><td colspan=2 align=\"center\"><input type=\"hidden\" name=\"cmd\" value=\"edit\">
      <br /><input type=\"submit\" name=\"edit\" value=\"Update record\"></td></tr></table></form></div>";
     
        
    }
}
//end section

//display edited information
if (isset($_POST['edit']))
   {
      //define variables
            $id = $_POST['id'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state_id = $_POST['state_id'];
            $zip = $_POST['zip'];
            $area_code = $_POST['area_code'];
            $phone_prefix = $_POST['phone_prefix'];
            $phone_suffix = $_POST['phone_suffix'];
            $phone = $area_code . "-" . $phone_prefix . "-" . $phone_suffix;      
            $email = $_POST['email'];
            $person_comment = $_POST['person_comment'];
            

     //MySQL command to update row in person table
	  $sql = "UPDATE person SET fname='$fname', lname='$lname', address='$address', city='$city', state_id=$state_id, phone='$phone', email='$email', person_comment='$person_comment' WHERE id=$id";
      $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


      //MySQL command to get updated info from the person table
      $get_person_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_comment, person_status from person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.id = $id";
      $get_person_info_res = mysql_query($get_person_info) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



       //display person info
    if (mysql_num_rows($get_person_info_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_person_info_res))
        {

            //define variables
            $id = $person_info['id'];
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

            //if address not supplied don't show city, state or zip
            if ($address == "")
            {
            $display_block .= "<h2 align=\"left\">Person record updated</h2>";
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            }
            //if address is supplied show city, state & zip
            else
            {
            $display_block .= "<h2 align=\"left\">Person record updated</h2>";
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            }
            //links to add band membership & edit record
            $display_block .= "<a href=\"band.php\">Add band membership</a> | <a href=\"edit.php?cmd=edit&id=$id\">Edit</a></td></tr></table></div>";

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$id' ORDER BY band_name ASC";
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

            //get all reservations associated with this person
        
        $get_reservation_info = "SELECT id, room_id, reservation_status, start_time FROM reservation_entry WHERE reservation_status <> 'CLOSED' AND person_id = $id ORDER BY start_time ASC";
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
            AND person_id=$id ORDER BY start_time;";
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
        }
   }
//end section
 
?>

<html>
<head>
<title>Edit person record</title>
<link href="includes/person_band.css" rel="stylesheet" type="text/css" />
<!--
Form validation - makes sure fname, lname & phone are populated
-->
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
            form.fname.focus();
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
            alert( "Please use all numbers for your phone number" );
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

