<?php
//Created by Hallie Pritchett
//This report shows a person's band memberships
//
//TO DO:
// - form validation
// - change is_active & contact_person boolean to string


//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
if (!isset($_POST['run_report']))

//connect to the database & the function.inc file
include("../includes/dbconnect.inc");
include("../includes/functions.inc");

if (!isset($_POST['run_report']))
{
    // view the drop down list of active person record names & phone numbers available

     //set up person drop down menu - get fname, lname, phone
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=1 AND id != 1 ORDER BY lname, fname ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block .= "<h2 align=\"left\">View band membership by person</h2>";
         $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
         $display_block .= "<h3>Select an active person record to view</h3><p><em>No records available</em></p>";
     } else
     {
        //if records available - get results & create drop down menu
         $display_block .= "<h2 align=\"left\">Band membership by person</h2>";
         $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
         $display_block .="<div id=\"dropdown\" align=\"left\">
         <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">
         <h3>Select a person record to view</h3>
         <select name=\"sel_id\">
         <option value=\"0\">--Select a person--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $display_name = stripslashes($recs['display_name']);
             $phone = $recs['phone'];

             $display_block .= "<option value=\"$id\">
                $display_name - $phone</option>";
         }
         //button to select a person from the drop down menu
         $display_block .= "</select>
         
         <input type=\"submit\" name=\"run_report\" value=\"View band memberships\"></form><br />";

     }

     //View the drop down list of inactive person records
     //set up person drop down menu - get fname, lname, phone
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=0 AND id != 1 ORDER BY lname, fname ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block .= "<h3>Select an inactive person record to view</h3><p><em>No records available</em></p>";
     } else
     {
         //if records available - get results & create drop down menu
         $display_block .="
         <form method=\"post\" action=\"$_SERVER[PHP_SELF]\"  onsubmit=\"return validate_person_form();\">
         <h3>Select an inactive person record to view</h3><select name=\"sel_id\">
         <option value=\"0\">--Select a person--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $display_name = stripslashes($recs['display_name']);
             $phone = $recs['phone'];

             $display_block .= "<option value=\"$id\">
                $display_name - $phone</option>";
         }
         //button to select a person from the drop down menu
         $display_block .= "</select>
         
         <input type=\"submit\" name=\"run_report\" value=\"View band memberships\"></form></div>";

     }
}
 //end section

if (isset($_POST['run_report']))
{           include("../includes/dbconnect.inc");
            $person_id = $_POST['sel_id'];

            $get_person = "SELECT fname, lname, is_active FROM person where id=$person_id";
            $get_person_res = mysql_query($get_person) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

            if (mysql_num_rows($get_person_res)> 0)
            {
                while ($person_info = mysql_fetch_array($get_person_res))
                {
                    $fname = $person_info['fname'];
                    $lname = $person_info['lname'];
                    $is_active = $person_info['is_active'];
                
            
            if ($is_active == 1)
            {
            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person, band.is_active FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND person_id = '$person_id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );


            //if bands exist display them
            
           if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<h2 align=\"left\">Current band memberships for " . $fname . " " . $lname . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_membership.php\">Select a different person</a></div>";
                $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
                $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                $display_block .= "<th>Band name</th><th>Band comment</th><th>Contact person?</th><th>Active band record?</th>";

            while ($band_info = mysql_fetch_array($get_bands_res))
            {
                //define variables
                $band_name = stripslashes($band_info['band_name']);
                $band_comment = stripslashes($band_info['band_comment']);
                $band_id = $band_info['id'];
                $contact_person = $band_info['contact_person'];
                $is_active = $band_info['is_active'];

            
            $display_block .= "<tr><td>" . $band_name. "</td><td>" . $band_comment . "</td><td>" . $contact_person . "</td><td>" . $is_active . "</td></tr>";
            

           
            }
            $display_block .= "</table><br />
            <input type=hidden name=person_id value=$person_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
            else

            {
                $display_block .= "<h2 align=\"left\">Current band memberships for " . $fname . " " . $lname    . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_membership.php\">Select a different person</a></div>";
                $display_block .= "<div id=\"text\" align=\"left\"><p>No band memberships for this person</p></div>";
            }
            }

            elseif ($is_active == 0)
            {
              
            //get all bands affiliated with this person
            $get_bands = "SELECT band.id, band_name, band_comment, contact_person, band.is_active FROM person, band, band_member WHERE band_member.person_id = person.id
            AND band.id = band_member.band_id AND person_id = '$person_id' ORDER BY band_name ASC";
            $get_bands_res = mysql_query($get_bands) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );


            //if bands exist display them

           if (mysql_num_rows($get_bands_res)> 0)
            {
                $display_block .= "<h2 align=\"left\">Current band memberships for " . $fname . " " . $lname . "<span style=\"color:#FF0000\"> - inactive</span></h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_membership.php\">Select a different person</a></div>";
                $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
                $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                $display_block .= "<th>Band name</th><th>Band comment</th><th>Contact person?</th><th>Active band record?</th>";

            while ($band_info = mysql_fetch_array($get_bands_res))
            {
                //define variables
                $band_name = stripslashes($band_info['band_name']);
                $band_comment = stripslashes($band_info['band_comment']);
                $band_id = $band_info['id'];
                $contact_person = $band_info['contact_person'];
                $is_active = $band_info['is_active'];


            $display_block .= "<tr><td>" . $band_name. "</td><td>" . $band_comment . "</td><td>" . $contact_person . "</td><td>" . $is_active . "</td></tr>";



            }
            $display_block .= "</table><br />
            <input type=hidden name=person_id value=$person_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
            else

            {
                $display_block .= "<h2 align=\"left\">Current band memberships for " . $fname . " " . $lname    . "<span style=\"color:#FF0000\"> - inactive</span></h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_membership.php\">Select a different person</a></div>";
                $display_block .= "<div id=\"text\" align=\"left\"><p>No band memberships for this person</p></div>";
            }

            }
            }
            }
}

//export single item report to Excel
if (isset($_POST['run_csv']))
{

$person_id = $_POST['person_id'];

$sql = "SELECT band.id, band_name, band_comment, contact_person, band.is_active
FROM person, band, band_member WHERE band_member.person_id = person.id
AND band.id = band_member.band_id AND person_id = '$person_id' ORDER BY band_name ASC";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}
?>
<html>
    <head>
    <title>Report: band members by person</title>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" type="text/javascript">
        <!--

        function validate_form()
        {
            valid = true;

            if ( document.person.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select a person from the drop down menu" );
            valid = false;
            }

         return valid;
         }

          function validate_person_form()
        {
            valid = true;

            if ( document.person_menu.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select a person from the drop down menu" );
            valid = false;
            }

         return valid;
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
