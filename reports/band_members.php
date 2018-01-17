<?php
//Created by Hallie Pritchett
//This report shows a band members by band
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

    //set up band drop down menu for active bands
     $get_list = "SELECT id, band_name FROM band WHERE is_active=1 AND id != 1 ORDER BY band_name ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


     //if no records available
     if (mysql_num_rows($get_list_res) <1)
     {

         $display_block .= "<h2 align=\"left\">View band members by band</h2>";
         $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
         
         $display_block . "<h3>Select an active band record to view</h3><p><em>No records available</em></p>";
     } else
     {
         //if band records available - get results & create drop down menu
         $display_block .= "<h2 align=\"left\">View band members by band</h2>";
         $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
         $display_block .="<div id=\"dropdown\" align=\"left\">
         <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">
         <h3>Select an active band record to view</h3>
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
            
            <input type=\"submit\" name=\"run_report\" value=\"View band members\"></form><br />";

        }

        //set up band drop down menu for inactive bands
     $get_list = "SELECT id, band_name FROM band WHERE is_active=0 AND id != 1 ORDER BY band_name ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );


     //if no records available
     if (mysql_num_rows($get_list_res) <1)
     {

         $display_block .= "<h3>Select an inactive band record to view</h3><p><em>No records available</em></h3>";
     } else
     {
         //if band records available - get results & create drop down menu

         $display_block .="
         <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">
         <h3>Select an inactive band record to view</h3>
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
            
            <input type=\"submit\" name=\"run_report\" value=\"View band members\"></form>";

        }

       }

//end section

if (isset($_POST['run_report']))
{          
            include("../includes/dbconnect.inc");
            $band_id = $_POST['sel_id'];

            $get_band = "SELECT band_name, is_active FROM band where id=$band_id";
            $get_band_res = mysql_query($get_band) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

            if (mysql_num_rows($get_band_res)> 0)
            {
                while ($band_info = mysql_fetch_array($get_band_res))
                {
                    $band_name = $band_info['band_name'];
                    $is_active = $band_info['is_active'];

            //if band is active do this
            if ($is_active == 1)
            {
            //get all bands affiliated with this person
            $get_members = "SELECT fname, lname, contact_person, person.is_active FROM person, band_member
            WHERE person.id = band_member.person_id AND band_id = $band_id ORDER BY lname, fname ASC;";
            $get_members_res = mysql_query($get_members) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

            //if band members exist display them

           if (mysql_num_rows($get_members_res)> 0)
            {
                $display_block .= "<h2 align=\"left\">Current band members for " . $band_name . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_members.php\">Select a different band</a></div>";
                $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
                $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                $display_block .= "<th>Member name</th><th>Contact person?</th><th>Active person record?</th>";

            while ($member_info = mysql_fetch_array($get_members_res))
            {
                //define variables
                $fname = $member_info['fname'];
                $lname = $member_info['lname'];
                $contact_person = $member_info['contact_person'];
                $is_active = $member_info['is_active'];

            $display_block .= "<tr><td>" . $fname . " " . $lname . "</td><td>" . $contact_person . "</td><td>" . $is_active . "</td></tr>";



            }
            $display_block .= "</table><br />
            <input type=hidden name=band_id value=$band_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
            else

            {
                $display_block .= "<h2 align=\"left\">Current band members for " . $band_name . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_members.php\">Select a different band</a></div>";
                $display_block .= "<div id=\"text\" align=\"left\"><p>No members for this band</p></div>";
            }
            }

            elseif ($is_active == 0)
            {

            //get all bands affiliated with this person
            $get_members = "SELECT fname, lname, contact_person, person.is_active FROM person, band_member
            WHERE person.id = band_member.person_id AND band_id = $band_id ORDER BY lname, fname ASC;";
            $get_members_res = mysql_query($get_members) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );


            //if bands exist display them

           if (mysql_num_rows($get_members_res)> 0)
            {
                $display_block .= "<h2 align=\"left\" >Current band members for " . $band_name . "<span style=\"color:#FF0000\"> - inactive</span></h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_members.php\">Select a different band</a></div>";
                $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
                $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                $display_block .= "<th>Member name</th><th>Contact person?</th><th>Active person record?</th>";

            while ($member_info = mysql_fetch_array($get_members_res))
            {
               //define variables
                $fname = $member_info['fname'];
                $lname = $member_info['lname'];
                $contact_person = $member_info['contact_person'];
                $is_active = $member_info['is_active'];

            $display_block .= "<tr><td>" . $fname . " " . $lname . "</td><td>" . $contact_person . "</td><td>" . $is_active . "</td></tr>";

            }
            $display_block .= "</table><br />
            <input type=hidden name=band_id value=$band_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
            else

            {
                $display_block .= "<h2 align=\"left\">Current band members for " . $band_name . "<span style=\"color:#FF0000\"> - inactive</span></h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"band_members.php\">Select a different band</a></div>";
                $display_block .= "<div id=\"text\" align=\"left\"><p>No band members for this band</p></div>";
            }

            }
            }
            
}
}
//export single item report to Excel
if (isset($_POST['run_csv']))
{

$band_id = $_POST['band_id'];

$sql = "SELECT fname, lname, contact_person, person.is_active FROM person, band_member
            WHERE person.id = band_member.person_id AND band_id = $band_id ORDER BY lname, fname ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}
?>
<html>
    <head>
    <title>Report: band members by band</title>
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
