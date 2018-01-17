<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page edits a new person record created during the reservation process

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the databaase
include("../includes/dbconnect.inc");


if (!isset($_POST['edit']))
{
// edit newly created record

        //define variables
        $fname = $_GET['fname'];
        $lname = $_GET['lname'];
        $phone = $_GET['phone'];
        $reservation_id = $_GET['reservation_id'];
        $repeat_id = $_GET['repeat_id'];
        $return_url = $_GET['return_url'];

        //MySQL command to get person info from person table
        $get_person_id = "SELECT * FROM person WHERE fname = '$fname' AND lname = '$lname' AND phone = '$phone'";
        $get_person_id_res = mysql_query ($get_person_id);

    //display person info
    if (mysql_num_rows($get_person_id_res)> 0)
    {

        while ($person_info = mysql_fetch_array($get_person_id_res))
        {
            //define variables
            $person_id = $person_info['id'];
            $fname = trim(stripslashes($person_info['fname']));
            $lname = trim(stripslashes($person_info['lname']));
            $address = trim(stripslashes($person_info['address']));
            $city = trim(stripslashes($person_info['city']));
            $state_id_sel = trim(stripslashes($person_info['state_id']));
            $zip = trim(stripslashes($person_info['zip']));
            $phone = trim($person_info['phone']);
            $email = trim(stripslashes($person_info['email']));
            //$person_status_id_sel = trim(stripslashes($person_info['person_status_id']));
            $person_comment = trim(stripslashes($person_info['person_comment']));

      //display the prepopulated edit record form
      $display_block .= "<h2 align=\"left\">Add person to reservation - edit person record</h2>
      <div id=\"text\">
      <h3 align=\"left\">Required *</h3>
      <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
      <input type=hidden name=\"id\" value=\"$person_id\">
      <table border=\"0\">
      <tr><td>First name:</td><td> <input type=\"text\" size=50 name=\"fname\" value=\"$fname\"> <strong>*</strong></td></tr>
      <tr><td>Last name:</td><td> <input type=\"text\" size=50 name=\"lname\" value=\"$lname\"> <strong>*</strong></td></tr>
      <tr><td>Address:</td><td> <input type=\"text\" size=50 name=\"address\" value=\"$address\"></td></tr>
      <tr><td>City:</td><td> <input type=\"text\" size=41 name=\"city\" value=\"$city\"> ";


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
      <tr><td>Phone: </td><td><input type=\"text\" size=50 name=\"phone\" value=\"$phone\"> <strong>*</strong></td></tr>
      <tr><td>Last name:</td><td> <input type=\"text\" size=50 name=\"lname\" value=\"$lname\"> <strong>*</strong></td></tr>
      <tr><td>Email: </td><td><input type=\"text\" size=50 name=\"email\" value=\"$email\"></td></tr>";
      $display_block .="<tr><td valign=\"top\">Comment: </td><td><textarea rows=\"3\" cols=\"38\" name=\"person_comment\">$person_comment</textarea></td></tr>";
      //button to submit updates
      $display_block .= "<tr><td colspan=2 align=\"center\"><input type=\"hidden\" name=\"cmd\" value=\"edit\">
      <br />
      <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
      <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
      <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
      <input type=\"submit\" name=\"edit\" value=\"Update record\"></td></tr></table></form></div>";


    }
}
}
//end section

//display edited information
if (isset($_POST['edit']))
   {
      //define variables
            $person_id = $_POST['id'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state_id = $_POST['state_id'];
            $zip = $_POST['zip'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            //$person_status_id = $_POST['person_status_id'];
            $person_comment = $_POST['person_comment'];
            $reservation_id = $_POST['reservation_id'];
            $repeat_id = $_POST['repeat_id'];
            $return_url = $_POST['return_url'];

     //MySQL command to update row in person table
	  $sql = "UPDATE person SET fname='$fname', lname='$lname', address='$address', city='$city', state_id=$state_id, zip='$zip', phone='$phone', email='$email', person_comment='$person_comment' WHERE id=$person_id";
      $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



      $get_person_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_comment, person_status from person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.id = $person_id";
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

            //if address not supplied don't show city, state or zip
            if ($address == "")
            {

            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). " updated</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            }
            //if address is supplied show city, state & zip
            else
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). " updated</h3>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">" . stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            }

            $display_block .= "<a href=\"edit_on_res.php?cmd=edit&id=$person_id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Edit</a> | ";
            $display_block .= "<a href=\"add_new_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&fname=$fname&lname=$lname&phone=$phone\">Continue with reservation</a>";
			$display_block .= "<br /></td></tr></table></div>";

            
        }
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
            form.fname.focus();
            return false ;
        }

        if (form.phone.value == "") {
            alert( "Please enter a phone number" );
            form.fname.focus();
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

