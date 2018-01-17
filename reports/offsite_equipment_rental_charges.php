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

$display_block .= "<h2 align=\"left\">View off-site equipment rental charges</h2>";
$display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
$display_block .= "<div id=\"dropdown\" align=\"left\"><form method=\"post\" name=\"run_report\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">";
//start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .=
"<select name=\"start_month\">
    <option value=\"0\">Month</option>
	<option value=\"01\">January</option>
	<option value=\"02\">February</option>
	<option value=\"03\">March</option>
	<option value=\"04\">April</option>
	<option value=\"05\">May</option>
	<option value=\"06\">June</option>
	<option value=\"07\">July</option>
	<option value=\"08\">August</option>
	<option value=\"09\">September</option>
	<option value=\"10\">October</option>
	<option value=\"11\">November</option>
	<option value=\"12\">December</option>
</select>
<select name=\"start_day\">
    <option value=\"0\">Day</option>
	<option value=\"01\">1</option>
	<option value=\"02\">2</option>
	<option value=\"03\">3</option>
	<option value=\"04\">4</option>
	<option value=\"05\">5</option>
	<option value=\"06\">6</option>
	<option value=\"07\">7</option>
	<option value=\"08\">8</option>
	<option value=\"09\">9</option>
	<option value=\"10\">10</option>
	<option value=\"11\">11</option>
	<option value=\"12\">12</option>
	<option value=\"13\">13</option>
	<option value=\"14\">14</option>
	<option value=\"15\">15</option>
	<option value=\"16\">16</option>
	<option value=\"17\">17</option>
	<option value=\"18\">18</option>
	<option value=\"19\">19</option>
	<option value=\"20\">20</option>
	<option value=\"21\">21</option>
	<option value=\"22\">22</option>
	<option value=\"23\">23</option>
	<option value=\"24\">24</option>
	<option value=\"25\">25</option>
	<option value=\"26\">26</option>
	<option value=\"27\">27</option>
	<option value=\"28\">28</option>
	<option value=\"29\">29</option>
	<option value=\"30\">30</option>
	<option value=\"31\">31</option>
</select>
<select name=\"start_year\">
    <option value=\"0\">Year</option>
	<option value=\"2009\">2009</option>
	<option value=\"2010\">2010</option>
	<option value=\"2011\">2011</option>
	<option value=\"2012\">2012</option>
    <option value=\"2013\">2013</option>
    <option value=\"2014\">2014</option>
    <option value=\"2015\">2015</option>
    <option value=\"2016\">2016</option>
    <option value=\"2017\">2017</option>
    <option value=\"2018\">2018</option>
    <option value=\"2019\">2019</option>
    <option value=\"2020\">2020</option>
</select>";

$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .=
"<select name=\"end_month\">
    <option value=\"0\">Month</option>
	<option value=\"01\">January</option>
	<option value=\"02\">February</option>
	<option value=\"03\">March</option>
	<option value=\"04\">April</option>
	<option value=\"05\">May</option>
	<option value=\"06\">June</option>
	<option value=\"07\">July</option>
	<option value=\"08\">August</option>
	<option value=\"09\">September</option>
	<option value=\"10\">October</option>
	<option value=\"11\">November</option>
	<option value=\"12\">December</option>
</select>
<select name=\"end_day\">
    <option value=\"0\">Day</option>
	<option value=\"01\">1</option>
	<option value=\"02\">2</option>
	<option value=\"03\">3</option>
	<option value=\"04\">4</option>
	<option value=\"05\">5</option>
	<option value=\"06\">6</option>
	<option value=\"07\">7</option>
	<option value=\"08\">8</option>
	<option value=\"09\">9</option>
	<option value=\"10\">10</option>
	<option value=\"11\">11</option>
	<option value=\"12\">12</option>
	<option value=\"13\">13</option>
	<option value=\"14\">14</option>
	<option value=\"15\">15</option>
	<option value=\"16\">16</option>
	<option value=\"17\">17</option>
	<option value=\"18\">18</option>
	<option value=\"19\">19</option>
	<option value=\"20\">20</option>
	<option value=\"21\">21</option>
	<option value=\"22\">22</option>
	<option value=\"23\">23</option>
	<option value=\"24\">24</option>
	<option value=\"25\">25</option>
	<option value=\"26\">26</option>
	<option value=\"27\">27</option>
	<option value=\"28\">28</option>
	<option value=\"29\">29</option>
	<option value=\"30\">30</option>
	<option value=\"31\">31</option>
</select>
<select name=\"end_year\">
    <option value=\"0\">Year</option>
	<option value=\"2009\">2009</option>
	<option value=\"2010\">2010</option>
	<option value=\"2011\">2011</option>
	<option value=\"2012\">2012</option>
    <option value=\"2013\">2013</option>
    <option value=\"2014\">2014</option>
    <option value=\"2015\">2015</option>
    <option value=\"2016\">2016</option>
    <option value=\"2017\">2017</option>
    <option value=\"2018\">2018</option>
    <option value=\"2019\">2019</option>
    <option value=\"2020\">2020</option>
</select>";
    $display_block .=  "<br /><br />

            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
 }

if (isset($_POST['run_report']))
{

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$start_month = $_POST['start_month'];
$start_day = $_POST['start_day'];
$start_year = $_POST['start_year'];
$end_month = $_POST['end_month'];
$end_day = $_POST['end_day'];
$end_year = $_POST['end_year'];

//actual end day is 2AM the next calendar day
$actual_end_day = $end_day+1;
$req_start_date = $start_month . "/" . $start_day . "/" . $start_year;
$req_end_date = $end_month . "/" . $end_day . "/" . $end_year;
//day starts at noon
$utf_start_date = date_2_utf_date($start_year, $start_month, $start_day, "12", "00");
//day ends at 2AM
$utf_end_date = date_2_utf_date($end_year, $end_month, $actual_end_day, "02", "00");

//show all waivers

$get_rev = "SELECT reservation_entry_id, start_time, fname, lname, band_name, equip_description, reservation_status,
reservation_transaction.amount
FROM reservation_transaction, reservation_entry, person, band, equipment
WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
AND reservation_entry.person_id = person.id
AND reservation_entry.band_id = band.id
AND reservation_transaction.equipment_id = equipment.id
AND equipment_id IS NOT NULL
AND reservation_entry.type = 'E'
AND start_time >= $utf_start_date AND end_time <= $utf_end_date
GROUP BY reservation_entry_id ORDER BY reservation_entry_id, equip_description;";
$get_rev_res = mysql_query($get_rev) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_rev_res) == 0)
{
    $display_block .= "<h2 align=\"left\">Off-site equipment rental charges for reservations with start times between<br />&nbsp;&nbsp; " . $req_start_date . " to " . $req_end_date    . "</h2>";
    $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"offsite_equipment_rental_charges.php\">Select a different date range</a></div>";
    $display_block .= "<div id=\"text\" align=\"left\"><p>No off-site equipment revenue available during the requested date range</p></div>";
}


elseif (mysql_num_rows($get_rev_res) > 0)

    {

        $display_block .= "<h2 align=\"left\">Off-site equipment rental charges for reservations with start times between<br />&nbsp;&nbsp; " . $req_start_date . " to " . $req_end_date    . "</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"offsite_equipment_rental_charges.php\">Select a different date range</a></div>";
        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
        $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
        $display_block .= "<th>Reservation id</th><th>Reservation start time</th><th>Person name</th><th>Band</th><th>Equipment name</th><th>Reservation status</th><th>Amount charged</th>";

        while ($rev_info = mysql_fetch_array($get_rev_res))
            {
            $res_id = $rev_info['reservation_entry_id'];
            $fname = $rev_info['fname'];
            $lname = $rev_info['lname'];
            $band_name = $rev_info['band_name'];
            $equip_description = $rev_info['equip_description'];
            $start_time = $rev_info['start_time'];
            $amount = $rev_info['amount'];
            $reservation_status = $rev_info['reservation_status'];
            $grand_total_charges += $amount;
                        
            $display_block .= "<tr><td>" . $res_id . "</td><td>" . utf_period_2_date($start_time) . "</td><td>" . $fname . " " . $lname . "</td><td>" . $band_name . "</td><td>" . $equip_description . "</td><td>" . $reservation_status . "</td><td>" . number_format(abs($amount), 2) . "</td></tr>";
            
            }
            $display_block .= "<tr><td colspan=\"6\"><b>Total charges for this time period</td><td><b>" . number_format(abs($grand_total_charges), 2) . "</b></td></tr>";
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

$sql = "SELECT reservation_entry_id, start_time, fname, lname, band_name, equip_description, reservation_status,
reservation_transaction.amount
FROM reservation_transaction, reservation_entry, person, band, equipment
WHERE reservation_entry.id = reservation_transaction.reservation_entry_id
AND reservation_entry.person_id = person.id
AND reservation_entry.band_id = band.id
AND reservation_transaction.equipment_id = equipment.id
AND equipment_id IS NOT NULL
AND reservation_entry.type = 'E'
AND start_time >= $utf_start_date AND end_time <= $utf_end_date
GROUP BY reservation_entry_id ORDER BY reservation_entry_id, equip_description;";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}


?>
<html>
 <head>
    <title>Report: off-site equipment rental charges</title>
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
