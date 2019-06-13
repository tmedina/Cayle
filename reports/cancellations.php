<?php
//Created by Hallie Pritchett
//This report shows all waivers applied during a user-specified time period;
//the business day starts at 12PM & ends at 2AM
//
//TO DO:
// - form validation
// - have selected year set automatically to current year
// - change is_active boolean to string

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

$display_block .= "<h2 align=\"left\">View cancellations</h2>";
$display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
$display_block .= "<div id=\"dropdown\" align=\"left\"><form method=\"post\" name=\"run_report\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">";
//start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .= "<input type='date' name='start_date' />";
$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .= "<input type='date' name='end_date' />";
    $display_block .=  "<br /><br />

            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
 }

if (isset($_POST['run_report']))
{

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

//show all waivers

$get_can = "SELECT reservation_entry.id, fname, lname, band_name, start_time, misc_charge.name AS cancellation_type, cancellation_initials, cancellation_comments,
reservation_transaction.amount, reservation_transaction.updated_at
FROM reservation_entry, reservation_transaction, misc_charge, person, band
WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
AND reservation_transaction.misc_charge_id = misc_charge.id
AND reservation_entry.person_id = person.id AND reservation_entry.band_id = band.id AND is_cancelled=1
AND start_time >= $utf_start_date AND end_time <= $utf_end_date
AND misc_charge_type_id=1 ORDER BY lname, fname, start_time ASC;";
$get_can_res = mysql_query($get_can) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_can_res) == 0)
{
    $display_block .= "<h2 align=\"left\">Total cancellations for reservations with start times between " . $req_start_date . " to " . $req_end_date    . "</h2>";
    $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"cancellations.php\">Select a different date range</a></div>";
    $display_block .= "<div id=\"text\" align=\"left\"><p>No cancellations available during the requested date range</p></div>";
}


elseif (mysql_num_rows($get_can_res) > 0)

    {

        $display_block .= "<h2 align=\"left\">Total cancellations for reservations with start times between " . $req_start_date . " to " . $req_end_date    . "</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"cancellations.php\">Select a different date range</a></div>";
        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
        $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
        $display_block .= "<th>Reservation id</th><th>Person name</th><th>Band</th><th>Reservation start time</th><th>Cancellation type</th><th>Cancelled by</th><th>Cancellation comments</th><th>Cancellation fee</th><th>Cancellation date/time</th>";

        while ($can_info = mysql_fetch_array($get_can_res))
            {
            $res_id = $can_info['id'];
            $fname = $can_info['fname'];
            $lname = $can_info['lname'];
            $band_name = $can_info['band_name'];
            $start_time = $can_info['start_time'];
            $cancellation_initials = $can_info['cancellation_initials'];
            $cancellation_type = $can_info['cancellation_type'];
            $cancellation_comments = $can_info['cancellation_comments'];
            $amount = $can_info['amount'];
            $updated_at = $can_info['updated_at'];
            $total_can += $amount;

            
            $display_block .= "<tr><td>" . $res_id . "</td><td>" . $fname . " " . $lname . "</td><td>" . $band_name . "</td><td>" . utf_period_2_date($start_time) . "</td><td>" . $cancellation_type . "</td><td>" . $cancellation_initials . "</td><td>" . $cancellation_comments . "</td><td>" . $amount . "</td><td>" . $updated_at . "</td></tr>";
            
            }
            $display_block .= "<tr><td colspan=\"7\"><b>Total cancellation charges for this time period</td><td colspan=\"2\">" . number_format($total_can, 2) . "</td></tr>";
            $display_block .= "</table><br />
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
        }
    }

//export single item report to Excel
if (isset($_POST['run_csv']))
{

$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT reservation_entry.id, fname, lname, band_name, start_time, misc_charge.name AS cancellation_type, cancellation_initials, cancellation_comments,
reservation_transaction.amount, reservation_transaction.updated_at
FROM reservation_entry, reservation_transaction, misc_charge, person, band
WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
AND reservation_transaction.misc_charge_id = misc_charge.id
AND reservation_entry.person_id = person.id AND reservation_entry.band_id = band.id AND is_cancelled=1
AND start_time >= $utf_start_date AND end_time <= $utf_end_date
AND misc_charge_type_id=1 ORDER BY lname, fname, start_time ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}


?>
<html>
 <head>
    <title>Report: cancellations</title>
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
