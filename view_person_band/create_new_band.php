<?php
//PERSON/BAND MODULE
//Created by Hallie Pritchett
//This page creates a new band record

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to the database
include("../includes/dbconnect.inc");

//form to create a new band
 if (!isset($_POST['submit_band']))
    {

            $display_block .= "
            <h2 align=\"left\">Create a new band record</h2>
            <div id=\"links_create\"><a href=\"band.php\">Select a different band record&nbsp;&nbsp;&nbsp;</a></div>
            <div id=\"text\" align=\"left\">
            <h3 align=\"left\">Required *</h3>
            <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
            Band name: <br /><input type=\"text\" size=50 name=\"band_name\" /> <strong>*</strong><br /><br />
            Comments: <br /><td><textarea rows=\"3\" cols=\"38\" name=\"band_comment\"></textarea><br /><br />
            <input type=\"reset\" value=\"Clear form\">
            <input type=\"submit\" name=\"submit_band\" value=\"Submit\"></form></div>";
            
    }
  //end section

  //add a new band record to db & show it
  if (isset($_POST['submit_band']))
        {

        $band_name = $_POST['band_name'];

        //check to see if the record already exists in the db
        $check_for_dups = "SELECT * FROM band WHERE band_name='$band_name'";
        $check_for_dups_res = mysql_query ($check_for_dups);

        //if a record already exists display it
        if (mysql_num_rows($check_for_dups_res)> 0)

        {
         while ($band_info = mysql_fetch_array($check_for_dups_res))
        {
            //define variables
            $id = $band_info['id'];
            $band_name = stripslashes($band_info['band_name']);
            $band_comment = stripslashes($band_info['band_comment']);
            $is_active= $band_info['is_active'];

            if ($is_active == 1)
            {

            $display_block .= "<h2 align=\"left\">View/create band records</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
            $display_block .= "<div id=\"text\"><h3 align=\"left\" style=\"color:#FF0000\">Record already exists for $band_name</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong>Comments:</strong><br />$band_comment<br /><br />";
            $display_block .= "<a href=\"edit_band.php?cmd=edit&id=$id\">Edit</a></td></tr></table></div>";
            
            //set up person drop down menu
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=1 AND id != 1 ORDER BY lname, fname ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block . "<p><em>No records available</em></p>";
     }
     //if records available create a drop down menu
     else

     {
        $display_block .= "<div id=\"band_dropdown\"><h3 align=\"left\">Add a band member</h3>
        <table><tr><td>
        <form method=\"post\" name=\"person\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_person_form();\">
        <select name=\"sel_id\">
         <option value=\"\">--Select a person--</option>";

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
         $display_block .= "<input type=\"hidden\" name=\"band_id\" value=\"$band_id\">
         <input type=\"submit\" name=\"add_band_member\"value=\"Add a band member\"></form></td></tr></table></div>";

        }

            //look for band members
            $get_people = "SELECT person.id, fname, lname, phone, contact_person FROM person, band, band_member WHERE band_member.person_id = person.id AND band.id = band_member.band_id AND person.is_active = 1 AND band_id = '$id' ORDER BY lname, fname ASC";
            $get_people_res = mysql_query($get_people);

            //if band members exist display them
            if (mysql_num_rows($get_people_res)> 0)
            {
            $display_block .= "<div id=\"member\" align=\"left\"><h3 align=\"left\">Band members - contact person(s) shown in bold</h3>";
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

            $display_block .= "<tr><td><strong>$fname $lname - $phone </strong></td>
            <td align=\"right\"><a href=\"delete_band_contact.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to remove this person as band contact?')\">Remove person as band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";

            }

            //display band members who aren't band contacts
            else

            {

            $display_block .= "<tr><td>$fname $lname - $phone </td>
            <td align=\"right\"><a href=\"add_band_contact.php?person_id=$person_id&band_id=$id\">Make this person a band contact</a> | <a href=\"person_record.php?person_id=$person_id\">View person record</a> | <a href=\"delete_band_member.php?person_id=$person_id&band_id=$id\" onclick=\"return confirm('Are you sure you want to delete this band member?')\">Delete band member</a></td></tr>";

            }
            }
            $display_block .= "</table></div>";
            //if no band members exist insert a line break
            } elseif (mysql_num_rows($get_people_res) == 0)

            {
                $display_block .= "<div id=\"member\"><br /></div>";
            }

        }


            elseif ($is_active == 0)

            {

            $display_block .= "<h2 align=\"left\">View/create band records</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
            $display_block .="<div id=\"text\" align=\"left\"><h3 style=\"color:#FF0000\">Record already exists for $band_name</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong>Comments:</strong><br />$band_comment</td></tr></table>";
            $display_block .= "<h3 align=\"left\" style=\"color:#FF0000\">Contact administrator to activate record</h3></div>";
            }
       
        }
        }

        //if a record doesn't exist create it
        else
        {
        //define variables
        
        $band_name = trim($_POST['band_name']);
        $band_comment = trim($_POST['band_comment']);
        //add row to band table
        $query = ("INSERT INTO band (band_name, band_comment) VALUES ('$band_name', '$band_comment')");
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



        //display new band record
        $display_block .= "<h2 align=\"left\">View/create band records</h2>";
        $display_block .= "<div id=\"links\"><a href=\"index.php\">View/create person records</a> | <a href=\"create_new_band.php\">Create a new band record</a> | <a href=\"band.php\">Select a different band</a></div>";
        $display_block .= "<div id=\"text\"><h3 align=\"left\">Record created</h3><table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($band_name) . "<br /><br/><strong>Comments:</strong><br />" . stripslashes($band_comment) . "<br /><br />";

        //links to add band member & edit record
        $display_block .="<a href=\"add_band_member.php?band_name=" . stripslashes($band_name) . "&band_comment=" . stripslashes($band_comment) . "\">Add band member</a> | <a href=\"edit_new_band.php?band_name=" . stripslashes($band_name) . "&band_comment=" . stripslashes($band_comment) . "\">Edit</a></td></tr></table></div>";

       
       
    }
        }
//end section

?>

<html>
    <head>
    <title>Band records</title>
    <link href="includes/person_band.css" rel="stylesheet" type="text/css" />
    <!--
    Form validation to make sure band name field is not empty
    -->
    <script language="JavaScript" type="text/javascript">
        <!--
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
