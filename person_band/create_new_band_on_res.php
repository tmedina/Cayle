<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This page creates a new band record during the reservation process

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to database
include("../includes/dbconnect.inc");
   
    $reservation_id = $_GET['reservation_id'];
    $repeat_id = $_GET['repeat_id'];
    $return_url = $_GET['return_url'];
    $person_id = $_GET['person_id'];

//form to create a new band
 if (!isset($_POST['submit_band']))
    {
            $display_block .= "<h2 align=\"left\">Add person to reservation - create a new band record</h2>";
            $display_block .= "<div id=\"links_create\"><a href=\"add_new_band.php?person_id=$person_id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Back to reservation</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><h3 align=\"left\">Required *</h3>
            <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
            
            Band name: <br /><input type=\"text\" name=\"band_name\" /> <strong>*</strong><br />
            Comments: <br /><input type=\"text\" name=\"band_comment\" /><br /><br />
            <input type=\"reset\" value=\"Clear form\">
            <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
            <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
            <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
            <input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
            <input type=\"submit\" name=\"submit_band\" value=\"Submit\"></form>";
            
            
    }
  //end section

  //add a new band record to db & show it
  if (isset($_POST['submit_band']))
        {

        $band_name = trim($_POST['band_name']);
        $reservation_id = $_POST['reservation_id'];
        $repeat_id = $_POST['repeat_id'];
        $return_url = $_POST['return_url'];
        $person_id = $_POST['person_id'];
        
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
            $is_active = $band_info['is_active'];

            //if band is active do this
            if ($is_active == 1)
            {
            $display_block .= "<h2 align=\"left\">Add person to reservation - record created</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
            $display_block .="<div id=\"text\" align=\"left\"><h3 style=\"color:#FF0000\">Record already exists for $band_name</h3>";
            $display_block .= "<table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>$band_name <br /><br /><strong>Comments:</strong><br />$band_comment<br /><br />";
            //link to edit record
            $display_block .= "<a href=\"edit_band_on_res.php?cmd=edit&id=$id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Edit</a> | ";
            $display_block .= "<a href=\"add_new_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Continue with reservation</a>";
            $display_block .= "<br /></td></tr></table><br />";
            }

            //if band is inactive to this
            elseif ($is_active == 0)

            {

            $display_block .= "<h2 align=\"left\">View/create band records</h2>";
            $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
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
        $band_id = $_POST['id'];
        $band_name = trim($_POST['band_name']);
        $band_comment = trim($_POST['band_comment']);
        //add row to band table
        $query = ("INSERT INTO band (band_name, band_comment) VALUES ('$band_name', '$band_comment')");
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



        //display new band record
        $display_block .= "<h2 align=\"left\">Add person to reservation - record created</h2>";
        $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><h3>Record for:  $band_name</h3><table width=\"300\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\"><tr><td>" . stripslashes($band_name) . "<br /><br/><strong>Comments:</strong><br />" . stripslashes($band_comment) . "<br /><br />";

        //link to edit record

        $display_block .= "<a href=\"edit_new_band_on_res.php?band_name=" . stripslashes($band_name) . "&band_comment=" . stripslashes($band_comment) . "&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Edit</a> | ";
        $display_block .= "<a href=\"add_new_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Continue with reservation</a>";
        $display_block .= "<br /></td></tr></table></div>";
       
    }
        }
//end section

?>

<html>
    <head>
    <title>Band records</title>
    <link href="includes/person_band_res.css" rel="stylesheet" type="text/css" />
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
