<?php
//Created by Hallie Pritchett
//This report shows all bar charges for a person 
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
         $display_block .= "<h2 align=\"left\">Bar charges by person</h2>";
         $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
         $display_block .= "<h3>Select an active person record to view</h3><p><em>No records available</em></p>";
     } else
     {
        //if records available - get results & create drop down menu
         $display_block .= "<h2 align=\"left\">Bar charges by person</h2>";
         $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
         $display_block .="<div id=\"text\" align=\"left\">
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
         $display_block .= "</select>";

         //start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .= "<input type='date' name='start_date'/>";
$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .= "<input type='date' name='end_date'/>";
    $display_block .=  "<br /><br />
         
         <input type=\"submit\" name=\"run_report\" value=\"View bar charges\"></form></div>";

     }

     //View the drop down list of inactive person records
     //set up person drop down menu - get fname, lname, phone
     $get_list = "SELECT id, CONCAT_WS(', ', lname, fname) as display_name, phone FROM person WHERE is_active=0 AND id != 1 ORDER BY lname, fname ASC";
     $get_list_res = mysql_query($get_list) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

     if (mysql_num_rows($get_list_res) <1)
     {
         //if no records available
         $display_block .= "<div id=\"band\"><h3>Select an inactive person record to view</h3><p><em>No records available</em></p></div>";
     } else
     {
         //if records available - get results & create drop down menu
         $display_block .="<div id=\"band\" align=\"left\">
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
         $display_block .= "</select>";
         
         //start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .= "<input type='date' name='start_date'/>";
$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .= "<input type='date' name='end_date'/>";
    $display_block .=  "<br /><br />

         
         <input type=\"submit\" name=\"run_report\" value=\"View bar charges\"></form></div>";

     }
}
 //end section

if (isset($_POST['run_report']))
{           include("../includes/dbconnect.inc");
            $person_id = $_POST['sel_id'];

						parse_date($_POST['start_date'], $start_month, $start_day, $start_year);
						parse_date($_POST['end_date'], $end_month, $end_day, $end_year);

            //actual end day is 2AM the next calendar day
            $actual_end_day = $end_day+1;
            $req_start_date = $start_month . "/" . $start_day . "/" . $start_year;
            $req_end_date = $end_month . "/" . $end_day . "/" . $end_year;
            //day starts at noon
            $utf_start_date = date_2_utf_date($start_year, $start_month, $start_day, "12", "00");
            //day ends at 2AM
            $utf_end_date = date_2_utf_date($end_year, $end_month, $actual_end_day, "02", "00");


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
            //get all bar charges affiliated with this person
            $get_barcharges = "SELECT reservation_entry_id, bar_charge.name, qty, reservation_transaction.updated_at
            FROM reservation_transaction, reservation_entry, bar_charge
            WHERE reservation_transaction.reservation_entry_id = reservation_entry.id
            AND reservation_transaction.bar_charge_id = bar_charge.id
            AND bar_charge_id IS NOT NULL
            AND start_time >= $utf_start_date
            AND end_time <= $utf_end_date
            AND person_id = $person_id ORDER BY reservation_entry_id, bar_charge.name;";
            $get_barcharges_res = mysql_query($get_barcharges) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );


            //if bands exist display them
            
           if (mysql_num_rows($get_barcharges_res)> 0)
            {
                $display_block .= "<h2 align=\"left\">Bar charges for " . $fname . " " . $lname .  " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"barcharges_by_person.php\">Select a different person</a></div>";
                $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
                $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                $display_block .= "<th>Reservation id</th><th>Bar charge name</th><th>Quantity</th><th>Date purchased</th>";

            while ($res_info = mysql_fetch_array($get_barcharges_res))
            {
                //define variables
                $id = $res_info['reservation_entry_id'];
                $name = $res_info['name'];
                $qty = $res_info['qty'];
                $updated_at = $res_info['updated_at'];
                
            $display_block .= "<tr><td>" . $id. "</td><td>" . $name . "</td><td>" . $qty . "</td><td>" . $updated_at . "</td></tr>";
           
            }
            $display_block .= "</table><br />
            <input type=hidden name=person_id value=$person_id>
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
            else

            {
                $display_block .= "<h2 align=\"left\">Bar charges for " . $fname . " " . $lname .  " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"barcharges_by_person.php\">Select a different person</a></div>";
                $display_block .= "<div id=\"text\" align=\"left\"><p>No bar charges for this person</p></div>";
            }
            }

            elseif ($is_active == 0)
            {
              
            //get all bar charges affiliated with this person
            $get_barcharges = "SELECT reservation_entry_id, bar_charge.name, qty, reservation_transaction.updated_at
            FROM reservation_transaction, reservation_entry, bar_charge
            WHERE reservation_transaction.reservation_entry_id = reservation_entry.id
            AND reservation_transaction.bar_charge_id = bar_charge.id
            AND bar_charge_id IS NOT NULL
            AND start_time >= $utf_start_date
            AND end_time <= $utf_end_date
            AND person_id = $person_id ORDER BY reservation_entry_id, bar_charge.name;";
            $get_barcharges_res = mysql_query($get_barcharges) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );

            //if bands exist display them

           if (mysql_num_rows($get_barcharges_res)> 0)
            {
                $display_block .= "<h2 align=\"left\">Bar charges for " . $fname . " " . $lname .  " <span style=\"color:#FF0000\">(inactive)</span> from " . $req_start_date . " to " . $req_end_date    . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"barcharges_by_person.php\">Select a different person</a></div>";
                $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
                $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
                $display_block .= "<th>Reservation id</th><th>Bar charge name</th><th>Quantity</th><th>Date purchased</th>";

            while ($res_info = mysql_fetch_array($get_barcharges_res))
            {
                //define variables
                $id = $res_info['reservation_entry_id'];
                $name = $res_info['name'];
                $qty = $res_info['qty'];
                $updated_at = $res_info['updated_at'];

            $display_block .= "<tr><td>" . $id. "</td><td>" . $name . "</td><td>" . $qty . "</td><td>" . $updated_at . "</td></tr>";

            }
            $display_block .= "</table><br />
            <input type=hidden name=person_id value=$person_id>
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
            else

            {
                $display_block .= "<h2 align=\"left\">Bar charges for " . $fname . " " . $lname .  " <span style=\"color:#FF0000\">(inactive)</span> from " . $req_start_date . " to " . $req_end_date    . "</h2>";
                $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"barcharges_by_person.php\">Select a different person</a></div>";
                $display_block .= "<div id=\"text\" align=\"left\"><p>No bar charges for this person</p></div>";
            }

            }
            }
            }
}

//export single item report to Excel
if (isset($_POST['run_csv']))
{

$person_id = $_POST['person_id'];
$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT reservation_entry_id, bar_charge.name, qty, reservation_transaction.updated_at
            FROM reservation_transaction, reservation_entry, bar_charge
            WHERE reservation_transaction.reservation_entry_id = reservation_entry.id
            AND reservation_transaction.bar_charge_id = bar_charge.id
            AND bar_charge_id IS NOT NULL
            AND start_time >= $utf_start_date
            AND end_time <= $utf_end_date
            AND person_id = $person_id ORDER BY reservation_entry_id, bar_charge.name;";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}
?>
<html>
    <head>
    <title>Report: bar charges by person</title>
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
