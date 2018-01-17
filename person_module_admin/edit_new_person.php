<?php
//PERSON/BAND MODULE - ADMINISTRATOR VIEW
//Created by Hallie Pritchett
//This page edits a new person record

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database
include("../includes/dbconnect.inc");

if (!isset($_POST['edit']))
{
// edit newly created record

        //define variables
        $fname = $_GET['fname'];
        $lname = $_GET['lname'];
        $phone = $_GET['phone'];


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
            $fname = stripslashes($person_info['fname']);
            $lname = stripslashes($person_info['lname']);
            $address = stripslashes($person_info['address']);
            $city = stripslashes($person_info['city']);
            $state_id_sel = stripslashes($person_info['state_id']);
            $zip = stripslashes($person_info['zip']);
            $phone = $person_info['phone'];
            list($area_code, $phone_prefix, $phone_suffix)= split("-", $phone);
            $email = stripslashes($person_info['email']);
            $person_status_id_sel = stripslashes($person_info['person_status_id']);
            $person_comment = stripslashes($person_info['person_comment']);
            $user_name = stripslashes($person_info['user_name']);

        //display the prepopulated edit record form
        $display_block .= "<h2 align=\"left\">Edit person record - administrator view</h2>
        <div id=\"text\">
        <h3 align=\"left\">Required *</h3>
        <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
        <input type=hidden name=\"id\" value=\"$person_id\">
      
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
      <tr><td>Email: </td><td><input type=\"text\" size=50 name=\"email\" value=\"$email\"></td></tr>
      <tr><td>Status: </td><td>";

      //set up person_status drop down menu
     $get_list = "SELECT * FROM person_status WHERE is_active=1 ORDER BY person_status ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block .= "<p><em>No records available</em></p>";
     } else
     {
         //if records available - get results & create drop down menu
         $display_block .="<select name=\"person_status_id\">";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $person_status_id = $recs['id'];
             $person_status = stripslashes($recs['person_status']);
             $display_block .= "<option value=\"$person_status_id\"";

              if($person_status_id == $person_status_id_sel)
             {
                 $display_block .= "selected";
             }

             $display_block .=">$person_status</option>";
         }

     }

      $display_block .="</td></tr>";
      $display_block .= "<tr><td>User name:</td><td> <input type=\"text\" size=50 name=\"user_name\" value=\"$user_name\"></td></tr>";
      $display_block .= "<tr><td valign=\"top\">Comment: </td><td><textarea rows=\"3\" cols=\"38\" name=\"person_comment\">$person_comment</textarea></td></tr>";
      //button to submit updates
      $display_block .= "<tr><td colspan=2 align=\"center\"><input type=\"hidden\" name=\"cmd\" value=\"edit\">
      <br /><input type=\"submit\" name=\"edit\" value=\"Update record\"></td></tr></table></form>";


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
            $area_code = $_POST['area_code'];
            $phone_prefix = $_POST['phone_prefix'];
            $phone_suffix = $_POST['phone_suffix'];
            $phone = $area_code . "-" . $phone_prefix . "-" . $phone_suffix;  
            $email = $_POST['email'];
            $person_status_id = $_POST['person_status_id'];
            $person_comment = $_POST['person_comment'];
            $user_name = $_POST['user_name'];

        //MySQL command to update row in person table
        $sql = "UPDATE person SET fname='$fname', lname='$lname', address='$address', city='$city', state_id=$state_id, zip='$zip', phone='$phone', email='$email', person_status_id=$person_status_id, person_comment='$person_comment', user_name='$user_name' WHERE id=$person_id";
        $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        $get_person_info = "SELECT person.id, fname, lname, address, city, state_name, zip, phone, email, person_comment, person_status, user_name FROM person, states, person_status WHERE states.id = person.state_id AND person_status.id = person.person_status_id AND person.id = $person_id";
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
            $user_name = $person_info['user_name'];

            //if address not supplied don't show city, state or zip
            if ($address == "")
            {
            $display_block .= "<h2 align=\"left\">Person record updated - administrator view</h2>";
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
            $display_block .= stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>User name: </strong>" . $user_name . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            }
            //if address is supplied show city, state & zip
            else
            {
            $display_block .= "<h2 align=\"left\">Person record updated - administrator view</h2>";
            $display_block .= "<div id=\"links\"><a href=\"band.php\">View/create band records</a> | <a href=\"create_new_person.php\">Create a new person record</a> | <a href=\"index.php\">Select a different person</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for " . stripslashes($fname) . " " . stripslashes($lname). "</h3>";
            $display_block .= stripslashes($fname) . " " . stripslashes($lname) . "<br />";
            $display_block .= stripslashes($address) . "<br />";
            $display_block .= stripslashes($city) . " " . $state_name . " " . stripslashes($zip) . "<br />";
            $display_block .= $phone . "<br />";
            $display_block .= "<a href=\"mailto:$email\">$email</a><br /><br />";
            $display_block .= "<strong>Status: </strong>" . $person_status . "<br /><br />";
            $display_block .= "<strong>User name: </strong>" . $user_name . "<br /><br />";
            $display_block .= "<strong>Comments: </strong>" . stripslashes($person_comment) . "<br /><br />";
            }

            $display_block .= "<a href=\"band.php\">Add band membership</a> | <a href=\"edit.php?cmd=edit&id=$person_id\">Edit</a></td></tr></table></div>";

            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND band.is_active = 1 AND person_id = '$person_id' ORDER BY band_name ASC";
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
    }
   }
//end section

?>
<html>
    <head>
    <title>Person records - administrator view</title>
    <link href="includes/person_band_admin.css" rel="stylesheet" type="text/css" />
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
