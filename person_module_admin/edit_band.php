<?php
//PERSON/BAND MODULE - ADMINISTRATOR VIEW
//Created by Hallie Pritchett
//This pages edits a band record

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to database
include("../includes/dbconnect.inc");

//set up edit form & prepopulate the fields
if($_GET['cmd']=="edit" || $_POST['cmd']=="edit")
{
   if (!isset($_POST['edit']))
   {
      $id = $_GET["id"];

      $sql = "SELECT * FROM band WHERE id='$id'";
      $result = mysql_query($sql);
      $myrow = mysql_fetch_array($result);
      //define variables
      $band_name = stripslashes($myrow['band_name']);
      $band_comment =stripslashes($myrow['band_comment']);
      $is_active = $myrow['is_active'];

      $display_block .= "<h2 align=\"left\">Edit band record - administrator view</h2>
      <div id=\"text\" align=\"left\">";
      if ($is_active == 1)
      {
      //display the prepopulated edit record form
      
      }else
      {
          //display the prepopulated edit record form
      $display_block .= "<h3 align=\"left\" style=\"color:#FF0000\">This record is inactive</h3>";
      }

      $display_block .= "
      <h3 align=\"left\">Required *</h3>
      <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\" >
      <input type=hidden name=\"id\" value=\"$id\">
      Band name: <br ><input type=\"text\" size=50 name=\"band_name\" value=\"" . stripslashes($band_name) . "\"> <strong>*</strong><br />
      Band comment: <br /><textarea rows=\"3\" cols=\"38\" name=\"band_comment\" value=\"" . stripslashes($band_comment);
      $display_block .= "\">" . stripslashes($band_comment) . "</textarea><br /><br />
      <input type=\"hidden\" name=\"cmd\" value=\"edit\">
      <input type=\"submit\" name=\"edit\" value=\"Update record\"></form></div>";
   

   }
}
//end section

//update table row & display results
if (isset($_POST['edit']))
   {
      //define variables
      $id = $_POST['id'];
      $band_name = $_POST['band_name'];
	  $band_comment = $_POST['band_comment'];
     
      //MySQL command to update row in band table
	  $sql = "UPDATE band SET band_name='$band_name',band_comment='$band_comment' WHERE id=$id";
      $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

      $get_info = "SELECT * FROM band WHERE id = $id";
      $get_info_res = mysql_query($get_info);

      if (mysql_num_rows($get_info_res)> 0)
        {

        while ($band_info = mysql_fetch_array($get_info_res))
        {
            //define variables
             //define variables
            $id = $band_info['id'];
            $band_name = stripslashes($band_info['band_name']);
            $band_comment = stripslashes($band_info['band_comment']);
            $is_active = $band_info['is_active'];

        //if band record is active display its information
            if ($is_active == 1)
            {
            $display_block .= "<h2 align=\"left\">Band record updated - administrator view</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
            $display_block .="<div id=\"text\"><h3 align=\"left\">Record for $band_name - active</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong><em>Comments:</em></strong><br />$band_comment<br /><br />";

            $display_block .= "<a href=\"deactivate_band.php?band_id=$id\" onclick=\"return confirm('Are you sure you want to deactivate this record?')\">Deactivate this record</a> | <a href=\"edit_band.php?cmd=edit&id=$id\">Edit</a></td></tr></table></div>";

            //set up person drop down menu
            $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=1 AND id != 1 ORDER BY lname, fname ASC";
            $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

            if (mysql_num_rows($get_list_res) <1)
            {
            //if no records available
            $display_block . "<div id=\"band_dropdown\"><p><em>No records available</em></p></div>";
            }
            //if records available create a drop down menu
            else

            {
            $display_block .= "<div id=\"band_dropdown\"><h3 align=\"left\">Add a band member</h3>
            <table><tr><td>
            <form method=\"post\" name=\"person\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_person_form();\">
            <select name=\"sel_id\">
            <option value=\"0\">--Select a person--</option>";

            while($recs = mysql_fetch_array($get_list_res))
            {
             $person_id = $recs['id'];
             $display_name = stripslashes($recs['display_name']);
             $phone = $recs['phone'];

             $display_block .= "<option value=\"$person_id\">
             $display_name - $phone</option>";
            }
            //button to add band member
            $display_block .="</select>";
            $display_block .= "<input type=\"hidden\" name=\"band_id\" value=\"$id\">
            <input type=\"submit\" name=\"add_band_member\"value=\"Add a band member\"></form></td></tr></table></div>";

            }
            //look for band members
            $get_people = "SELECT person.id, fname, lname, phone, contact_person, person.is_active FROM person, band, band_member WHERE band_member.person_id = person.id AND band.id = band_member.band_id AND band_id = '$id' ORDER BY lname, fname ASC";
            $get_people_res = mysql_query($get_people);

            //if band members exist display them
            if (mysql_num_rows($get_people_res)> 0)
            {
            $display_block .= "<div id=\"member\" align=\"left\"><h3 align=\"left\">Band members - contact person(s) shown in bold - <br />&nbsp;&nbsp;&nbsp;band members/contacts with inactive records shown in <span style=\"color:#FF0000\">red</span> </h3>";
            $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";
            while ($person_info = mysql_fetch_array($get_people_res))
            {

            //define variables
            $fname = stripslashes($person_info['fname']);
            $lname = stripslashes($person_info['lname']);
            $phone = $person_info['phone'];
            $person_id = $person_info['id'];
            $contact_person = $person_info['contact_person'];
            $is_active = $person_info['is_active'];

            //display contact person(s) in bold
            if ($contact_person == 1 && $is_active == 1)
            {
            
            $display_block .= "<tr><td><strong>$fname $lname - $phone </strong></td>
            <td align=\"right\"><a href=\"delete_band_contact.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to remove this person as band contact?')\">Remove person as band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";

            }

            //display band members who aren't band contacts
            elseif ($is_active == 1)

            {

            $display_block .= "<tr><td>$fname $lname - $phone </td>
            <td align=\"right\"><a href=\"add_band_contact.php?person_id=$person_id&band_id=$id\">Make this person a band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";

            }elseif ($contact_person == 1 && $is_active == 0)
            {

            $display_block .= "<tr><td style=\"color:#FF0000\"><strong>$fname $lname - $phone </strong></td>
            <td align=\"right\"><a href=\"delete_band_contact.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to remove this person as band contact?')\">Remove person as band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";
            }elseif ($is_active == 0)

            {

            $display_block .= "<tr><td style=\"color:#FF0000\">$fname $lname - $phone </td>
            <td align=\"right\"><a href=\"add_band_contact.php?person_id=$person_id&band_id=$id\">Make this person a band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";
            }
            }
            $display_block .= "</table></div>";
            //if no band members exist insert a line break
            }


            elseif (mysql_num_rows($get_people_res) == 0)

            {
                $display_block .= "<div id=\"member\"><br /></div>";
            }

      
            //if band is inactive display its information
            }else
            {
            
            $display_block .= "<h2 align=\"left\">Band record updated - administrator view</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
            $display_block .= "<div id=\"text\"><h3 align=\"left\" style=\"color:#FF0000\">Record for $band_name - inactive</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong>Comments:</strong><br />$band_comment<br /><br />";

            $display_block .= "<a href=\"activate_band.php?band_id=$id\" onclick=\"return confirm('Are you sure you want to activate this record?')\">Activate this record</a> | <a href=\"edit_band.php?cmd=edit&id=$id\">Edit</a></td></tr></table></div>";
            //look for band members
            $get_people = "SELECT person.id, fname, lname, phone, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id AND band.id = band_member.band_id AND band_id = '$id' ORDER BY lname, fname ASC";
            $get_people_res = mysql_query($get_people);

            //if band members exist display them
            if (mysql_num_rows($get_people_res)> 0)
            {
            $display_block .= "<div id=\"member\" align=\"left\"><h3 align=\"left\">Band members - contact person(s) shown in bold - <br />&nbsp;&nbsp;&nbsp;band members/contacts with inactive records shown in <span style=\"color:#FF0000\">red</span> </h3>";
            $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";
            while ($person_info = mysql_fetch_array($get_people_res))
            {

            //define variables
            $fname = stripslashes($person_info['fname']);
            $lname = stripslashes($person_info['lname']);
            $phone = $person_info['phone'];
            $person_id = $person_info['id'];
            $contact_person = $person_info['contact_person'];

            //display contact person(s) in bold
            if ($contact_person == 1)
            {

            $display_block .= "<tr><td><strong>$fname $lname - $phone </strong></td></tr>";

            }

            //display band members who aren't band contacts
            else

            {

            $display_block .= "<tr><td>$fname $lname - $phone </td></tr>";


            }
            }
            $display_block .= "</table>";
            $display_block .= "<h3 style=\"color:#FF0000\">Activate record to add/delete/update band members & contacts</h3></div>";
            //if no band members exist insert a line break
            } elseif (mysql_num_rows($get_people_res) == 0)

            {
                $display_block .= "<div id=\"member\" align=\"left\"><h3 style=\"color:#FF0000\">Activate record to add/delete/update band members & contacts</h3></div>";
            }

            }

        
        }
    }
 }
//end section

 //add a band member to an existing band
 if (isset($_POST['add_band_member']))
    {

    $band_id = $_POST['band_id'];
    $person_id = $_POST['sel_id'];
    
    //add row to band member table
    $create_band_member = "INSERT INTO band_member (band_id, person_id) VALUES('$band_id', '$person_id')";
    $create_band_member_res = mysql_query($create_band_member) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    //get band info
    $get_info = "SELECT * FROM band WHERE id = '$band_id'";
    $get_info_res = mysql_query($get_info);

    //display band info
    if (mysql_num_rows($get_info_res)> 0)
    {

        while ($band_info = mysql_fetch_array($get_info_res))
        {
            //define variables
            $id = $band_info['id'];
            $band_name = stripslashes($band_info['band_name']);
            $band_comment = stripslashes($band_info['band_comment']);
            $is_active = $band_info['is_active'];

            //if record is active do this
            if ($is_active == 1)
            {

            $display_block .= "<h2 align=\"left\">View/create band records - administrator view</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
            $display_block .= "<div id=\"text\"><h3 align=\"left\">Record for $band_name</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong><em>Comments:</em></strong><br />$band_comment<br /><br />";
            $display_block .= "<br /><a href=\"deactivate_band.php?band_id=$id\" onclick=\"return confirm('Are you sure you want to deactivate this record?')\">Deactivate this record</a> | <a href=\"edit_band.php?cmd=edit&id=$band_id\">Edit</a></td></tr></table></div>";

            }
            //if record is inactive do this
            else

            {
            $display_block .= "<h2 align=\"left\">View/create band records - administrator view</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
            $display_block .= "<div id=\"text\"><h3 align=\"left\">Record for $band_name - inactive</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong><em>Comments:</em></strong><br />$band_comment</td></tr></table></div>";
            $display_block .= "<h3 style=\"color:#FF0000\">Record must be active to add/delete/update band members & contacts</h3></div> ";

            }
            }

            //set up person drop down menu
            $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=1 AND id != 1 ORDER BY lname, fname ASC";
            $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

            if (mysql_num_rows($get_list_res) <1)
            {
            //if no records available
            $display_block . "<div id=\"band_dropdown\"><p><em>No records available</em></p></div>";
            } else
            {
            //if records available create a dropdown menu
            $display_block .= "<div id=\"band_dropdown\"><h3 align=\"left\">Add a band member</h3>
            <table><tr><td>
            <form method=\"post\" name=\"person\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_person_form();\">
            <select name=\"sel_id\">
            <option value=\"0\">--Select a person--</option>";

            while($recs = mysql_fetch_array($get_list_res))
            {
             //define variables
             $person_id = $recs['id'];
             $display_name = stripslashes($recs['display_name']);
             $phone = $recs['phone'];

             $display_block .= "<option value=\"$person_id\">
             $display_name - $phone</option>";
            }

            //button to add a band member
            $display_block .= "</select><input type=\"hidden\" name=\"band_id\" value=\"$band_id\">
            <input type=\"submit\" name=\"add_band_member\" value=\"Add a band member\"></form></td></tr></table></div>";

            }
            //look for band members
            $get_people = "SELECT person.id, fname, lname, phone, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id AND band.id = band_member.band_id AND band_id = '$id' ORDER BY lname, fname ASC";
            $get_people_res = mysql_query($get_people);

            //if band members exist display them
            if (mysql_num_rows($get_people_res)> 0)
            {
             $display_block .= "<div id=\"member\" align=\"left\"><h3 align=\"left\">Band members - contact person(s) shown in bold - <br />&nbsp;&nbsp;&nbsp;band members/contacts with inactive records shown in <span style=\"color:#FF0000\">red</span> </h3>";
            $display_block .="<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";
            while ($person_info = mysql_fetch_array($get_people_res))
            {

            //define variables
            $fname = stripslashes($person_info['fname']);
            $lname = stripslashes($person_info['lname']);
            $phone = $person_info['phone'];
            $person_id = $person_info['id'];
            $contact_person = $person_info['contact_person'];

            //display contact person(s) in bold
            if ($contact_person == 1 && $is_active == 1)
            {

            $display_block .= "<tr><td><strong>$fname $lname - $phone </strong></td>
            <td align=\"right\"><a href=\"delete_band_contact.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to remove this person as band contact?')\">Remove person as band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";

            }

            //display band members who aren't band contacts
            elseif ($is_active == 1)

            {

            $display_block .= "<tr><td>$fname $lname - $phone </td>
            <td align=\"right\"><a href=\"add_band_contact.php?person_id=$person_id&band_id=$id\">Make this person a band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";

            }elseif ($contact_person == 1 && $is_active == 0)
            {

            $display_block .= "<tr><td style=\"color:#FF0000\"><strong>$fname $lname - $phone </strong></td>
            <td align=\"right\"><a href=\"delete_band_contact.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to remove this person as band contact?')\">Remove person as band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";
            }elseif ($is_active == 0)

            {

            $display_block .= "<tr><td style=\"color:#FF0000\">$fname $lname - $phone </td>
            <td align=\"right\"><a href=\"add_band_contact.php?person_id=$person_id&band_id=$id\">Make this person a band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";
            }
            }
            $display_block .= "</table>";
            //if no band members exist insert a line break
            }


            elseif (mysql_num_rows($get_people_res) == 0)

            {
                $display_block .= "<br />";
            }
            
            $display_block .= "</table></div>";
            //if no band members exist insert a line break
            } elseif (mysql_num_rows($get_people_res) == 0)

            {
                $display_block .= "<div id=\"member\"><br /></div>";
            }

    }
//end section


?>

<html>
<head>
    <title>Band records - admiminstrator</title>
    <link href="includes/person_band_admin.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" type="text/javascript">

        <!--
         function validate_person_form()
        {
            valid = true;

            if ( document.person.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select a person from the drop down menu" );
            valid = false;
            }

         return valid;
         }

         function checkform ( form )
        {

        if (form.band_name.value == "") {
            alert( "Please enter a band name" );
            form.band_name.focus();
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




