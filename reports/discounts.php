<?php
//Created by Hallie Pritchett
//This report shows discounts by type during a user-specified time period;
//the business day starts at 12PM & ends at 2AM
//
//TO DO:
// - form validation
// - have selected year set automatically to current year

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");

if (!isset($_POST['run_report']))
{
$get_list = "SELECT id, name FROM misc_charge WHERE misc_charge_type_id = 4 OR misc_charge_type_id=5 ORDER BY misc_charge_type_id, name ASC";
$get_list_res = mysql_query($get_list) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );
$display_block .= "<h2 align=\"left\">View discounts applied</h2>";
$display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
$display_block .= "<div id=\"dropdown\" align=\"left\"><form method=\"post\" name=\"run_report\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">";
//start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .= "<input type='date' name='start_date' />";
$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .= "<input type='date' name='end_date' />";
$display_block .= "<br />";

$display_block .= "<h3>Select an item</h3>";

 $display_block .= "<select name=\"sel_id\">
         <option value=\"0\">--Select an item--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $name = stripslashes($recs['name']);

            $display_block .= "<option value=\"$id\">$name</option>";
         }
            $display_block .= "<option value=\"\">Show all discounts</option>";
           $display_block .= "</select><br />";
           $display_block .=  "<br />

            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
 }

if (isset($_POST['run_report']))
{

$discount_id = $_POST['sel_id'];

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

//show a single type discount
if ($discount_id != "")
{

$get_discount_name = "SELECT name FROM misc_charge where id=$discount_id";
$get_discount_name_res = mysql_query($get_discount_name) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

 if (mysql_num_rows($get_discount_name_res)> 0)
            {
                while ($discount_name_info = mysql_fetch_array($get_discount_name_res))
                {
                    $name = $discount_name_info['name'];
$get_discount = "SELECT reservation_entry.id, fname, lname, band_name, start_time, misc_charge.name, reservation_transaction.comment,
reservation_transaction.amount, reservation_transaction.updated_at
FROM reservation_entry, reservation_transaction, misc_charge, person, band
WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
AND reservation_transaction.misc_charge_id = misc_charge.id
AND reservation_entry.person_id = person.id
AND reservation_entry.band_id = band.id
AND start_time >= $utf_start_date  AND end_time <= $utf_end_date
AND misc_charge.id=$discount_id ORDER BY start_time ASC;";
$get_discount_res = mysql_query($get_discount) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_discount_res) == 0)

          {

            $display_block .= "<h2 align=\"left\">Reservation date range: " . $req_start_date . " to " . $req_end_date    . "<br />&nbsp;&nbsp;&nbsp;&nbsp;Discount type: " . $name . "</h3>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"discounts.php\">Select a different discount</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><p>No discounts available during the requested date range</p></div>";
           }

 elseif (mysql_num_rows($get_discount_res) > 0)

            {

        $display_block .= "<h2 align=\"left\">Reservation date range: " . $req_start_date . " to " . $req_end_date    . "<br />&nbsp;&nbsp;&nbsp;&nbsp;Discount type: " . $name . "</h3>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"discounts.php\">Select a different discount</a></div>";
        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
        $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
        $display_block .= "<th>Reservation id</th><th>Person name</th><th>Band</th><th>Reservation start time</th><th>Discount comment</th><th>Discount amount</th><th>Discount date/time</th>";

        while ($discount_info = mysql_fetch_array($get_discount_res))
            {
            $res_id = $discount_info['id'];
            $fname = $discount_info['fname'];
            $lname = $discount_info['lname'];
            $band_name = $discount_info['band_name'];
            $start_time = $discount_info['start_time'];
            $comment = $discount_info['comment'];
            $amount = $discount_info['amount'];
            $updated_at = $discount_info['updated_at'];
            $total_discounted += $amount;

            $display_block .= "<tr><td>" . $res_id . "</td><td>" . $fname . " " . $lname . "</td><td>" . $band_name . "</td><td>" . utf_period_2_date($start_time) . "</td><td>" . $comment . "</td><td>" . $amount . "</td><td>" . $updated_at . "</td></tr>";

            }
            $display_block .= "<tr><td colspan=\"5\"><b>Total amount of discounts for this time period</td><td colspan=\"2\">" . number_format($total_discounted, 2) . "</td></tr>";
            $display_block .= "</table><br />
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=hidden name=discount_id value=$discount_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
        }
        }
       }
    }
        //show all discount types
        elseif ($discount_id == "")
        {

        $get_discount = "SELECT reservation_entry.id, fname, lname, band_name, start_time, misc_charge.name, reservation_transaction.comment,
        reservation_transaction.amount, reservation_transaction.updated_at
        FROM reservation_entry, reservation_transaction, misc_charge, person, band
        WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
        AND reservation_transaction.misc_charge_id = misc_charge.id 
        AND reservation_entry.person_id = person.id
        AND reservation_entry.band_id = band.id
        AND misc_charge.id IN (SELECT id FROM misc_charge WHERE misc_charge_type_id = 4 OR misc_charge_type_id=5)
        AND start_time >= $utf_start_date  AND end_time <= $utf_end_date
        ORDER BY misc_charge.name, lname, fname, start_time ASC;";
        $get_discount_res = mysql_query($get_discount) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_discount_res) == 0)

          {

            $display_block .= "<h2 align=\"left\">Show all discount types for reservation date range: " . $req_start_date . " to " . $req_end_date    . "</h3>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"discounts.php\">Select a different discount</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><p>No discounts available during the requested date range</p></div>";
           }

 elseif (mysql_num_rows($get_discount_res) > 0)

            {

        $display_block .= "<h2 align=\"left\">Show all discount types for reservation date range: " . $req_start_date . " to " . $req_end_date    . "</h3>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"discounts.php\">Select a different discount</a></div>";
        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
        $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
        $display_block .= "<th>Reservation id</th><th>Person name</th><th>Band</th><th>Reservation start time</th><th>Discount type</th><th>Discount comment</th><th>Discount amount</th><th>Discount date/time</th>";

        while ($discount_info = mysql_fetch_array($get_discount_res))
            {
            $res_id = $discount_info['id'];
            $fname = $discount_info['fname'];
            $lname = $discount_info['lname'];
            $band_name = $discount_info['band_name'];
            $start_time = $discount_info['start_time'];
            $name = $discount_info['name'];
            $comment = $discount_info['comment'];
            $amount = $discount_info['amount'];
            $updated_at = $discount_info['updated_at'];
            $total_discounted += $amount;

            $display_block .= "<tr><td>" . $res_id . "</td><td>" . $fname . " " . $lname . "</td><td>" . $band_name . "</td><td>" . utf_period_2_date($start_time) . "</td><td>" . $name . "</td><td>" . $comment . "</td><td>" . $amount . "</td><td>" . $updated_at . "</td></tr>";

            }
            $display_block .= "<tr><td colspan=\"6\"><b>Total amount of discounts for this time period</td><td colspan=\"2\">" . number_format($total_discounted, 2) . "</td></tr>";
            $display_block .= "</table><br />
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=hidden name=discount_id value=$discount_id>
            <input type=submit name=run_csv_total value=\"Export report to Excel\"></form></div>";

        }

     }
   
   }


//export single item report to Excel
if (isset($_POST['run_csv']))
{

$discount_id = $_POST['discount_id'];
$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT reservation_entry.id, fname, lname, band_name, start_time, misc_charge.name, reservation_transaction.comment,
reservation_transaction.amount, reservation_transaction.updated_at
FROM reservation_entry, reservation_transaction, misc_charge, person, band
WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
AND reservation_transaction.misc_charge_id = misc_charge.id
AND reservation_entry.person_id = person.id
AND reservation_entry.band_id = band.id
AND start_time >= $utf_start_date  AND end_time <= $utf_end_date
AND misc_charge.id=$discount_id ORDER BY start_time ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 5: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

//export single item report to Excel
if (isset($_POST['run_csv_total']))
{

$discount_id = $_POST['discount_id'];
$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT reservation_entry.id, fname, lname, band_name, start_time, misc_charge.name, reservation_transaction.comment,
        reservation_transaction.amount, reservation_transaction.updated_at
        FROM reservation_entry, reservation_transaction, misc_charge, person, band
        WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
        AND reservation_transaction.misc_charge_id = misc_charge.id
        AND reservation_entry.person_id = person.id
        AND reservation_entry.band_id = band.id
        AND misc_charge.id IN (SELECT id FROM misc_charge WHERE misc_charge_type_id = 4 OR misc_charge_type_id=5)
        AND start_time >= $utf_start_date  AND end_time <= $utf_end_date
        ORDER BY misc_charge.name, lname, fname, start_time ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 6: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}
?>
<html>
 <head>
    <title>Report: discounts applied</title>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />

    <script language="JavaScript" type="text/javascript">
        <!--

        function validate_form()
        {
            valid = true;

            if ( document.run_report.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select equipment from the drop down menu" );
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
