<?php
//ADD PERSON/BAND TO RESERVATION MODULE
//Created by Hallie Pritchett
//This pages edits a band record during the reservation process

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connects to the database and functions.inc file
include("../includes/dbconnect.inc");
include("../includes/functions.inc");


    $reservation_id = $_GET['reservation_id'];
    $repeat_id = $_GET['repeat_id'];
    $return_url = $_GET['return_url'];
    $person_id = $_GET['person_id'];

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

      //display the prepopulated edit record form, with buttons to update record and select a different band
      $display_block .= "<h2 align=\"left\">Add person to reservation - edit band record</h2>
      <div id=\"text\" align=\"left\">
      <h3 align=\"left\">Required *</h3>
      <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\">
      <input type=hidden name=\"id\" value=\"$id\">
      Band name: <br ><input type=\"text\" name=\"band_name\" value=\"" . stripslashes($band_name) . "\"> <strong>*</strong><br />
      Band comment: <br /><input type=\"text\" name=\"band_comment\" value=\"" . stripslashes($band_comment);
      $display_block .= "\"><br /><br />
      <input type=\"hidden\" name=\"reservation_id\" value=\"$reservation_id\">
      <input type=\"hidden\" name=\"repeat_id\" value=\"$repeat_id\">
      <input type=\"hidden\" name=\"return_url\" value=\"$return_url\">
      <input type=\"hidden\" name=\"person_id\" value=\"$person_id\">
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
      $band_name = trim($_POST['band_name']);
	  $band_comment = trim($_POST['band_comment']);
      $person_id = $_POST['person_id'];
      $reservation_id = $_POST['reservation_id'];
      $repeat_id = $_POST['repeat_id'];
      $return_url = $_POST['return_url'];

      //MySQL command to update row in band table
	  $sql = "UPDATE band SET band_name='$band_name',band_comment='$band_comment' WHERE id=$id";
      $result = mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



       $display_block .= "<h2 align=\"left\">Add person to reservation</h2>";
      $display_block .= "<div id=\"res_links\"><a href=\"add_person.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url\">Select a different person</a> | <a href=\"cancel_reservation.php?reservation_id=$reservation_id\">Start over</a></div>";
      $display_block .= "<div id=\"text\" align=\"left\"><h3>Record updated for " . stripslashes($band_name) . "</h3>" . stripslashes($band_name) . '<br /><br /><strong>Comments: </strong><br />' . stripslashes($band_comment) . "<br /><br />";

       $display_block .= "<a href=\"edit_band_on_res.php?cmd=edit&id=$id&reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&person_id=$person_id\">Edit</a> | ";
      $display_block .= "<a href=\"add_new_band.php?reservation_id=$reservation_id&repeat_id=$repeat_id&return_url=$return_url&&person_id=$person_id\">Continue with reservation</a>";
      $display_block .= "<br /></td></tr></table></div>";
    }
 
//end section

 


?>

<html>
<head>
<title>Band records</title>
<link href="includes/person_band_res.css" rel="stylesheet" type="text/css" />
<!--
Form validation - makes sure a person is selected from the drop down menu & that band name field is not blank
-->

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




