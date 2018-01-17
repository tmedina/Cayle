<?php
//Created by Hallie Pritchett
//This report shows total cancelled hours by room over a user-specified time period;
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
$get_list = "SELECT id, room_name FROM `reservation_room` WHERE room_name <> 'equipment'";
$get_list_res = mysql_query($get_list) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );
$display_block .= "<h2 align=\"left\">Total cancelled hours by room</h2>";
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

$display_block .= "<br />";

$display_block .= "<h3>Select a room</h3>";

 $display_block .= "<select name=\"sel_id\">
         <option value=\"0\">--Select a room--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $room_name = stripslashes($recs['room_name']);

            $display_block .= "<option value=\"$id\">$room_name</option>";
         }
         $display_block .= "<option value=\"\">Show all rooms</option>";


         $display_block .= "</select><br />";

       $display_block .=  "<br />
            
            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
 }

if (isset($_POST['run_report']))
{

$room_id = $_POST['sel_id'];
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

//show a single room
if ($room_id != "")
{

$get_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_cancelled_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=1
AND room_name <> 'equipment'
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
AND room_id=$room_id;";
$get_hours_res = mysql_query($get_hours) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_hours_res) > 0)
   
    {

        while ($hours_info = mysql_fetch_array($get_hours_res))
        {
            $room_name = $hours_info['room_name'];
            $total_cancelled_hours = $hours_info['total_cancelled_hours'];
            
            if ($total_cancelled_hours == NULL)
            {
            $display_block .= "<h2 align=\"left\">Total cancelled hours for " . $room_name . " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_cancelled_hours_room.php\">Select a different room</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><p>No cancelled hours available for the requested date range</p></div>";
            }

            else
            {
            $display_block .= "<h2 align=\"left\">Total cancelled hours for " . $room_name . " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_cancelled_hours_room.php\">Select a different room</a></div>";
            $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
            $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
            $display_block .= "<th>Room number</th><th>Total cancelled hours</th></th>";
            $display_block .= "<tr><td>" . $room_name . "</td><td>" . $total_cancelled_hours . "</td></tr>";
            $display_block .= "</table><br />
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=hidden name=barcharge_id value=$room_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
            }
        }

    }

}
//show all rooms
else

{


$get_hours = "SELECT room_name, SUM((end_time - start_time)/120) AS total_cancelled_hours
FROM reservation_entry, reservation_room 
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=1
AND room_name <> 'equipment'
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
GROUP BY room_name
ORDER BY reservation_room.id ASC;";
$get_hours_res = mysql_query($get_hours) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_hours_res)< 1)
    {
        $display_block .= "<h2 align=\"left\">Total cancelled hours for all rooms from " . $req_start_date . " to " . $req_end_date    . "</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_cancelled_hours_room.php\">Select a different room</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><p>No cancelled hours available for the requested date range</p></div>";
    }

elseif  (mysql_num_rows($get_hours_res)> 0)
    {
                        
            $display_block .= "<h2 align=\"left\">Total cancelled hours for all rooms from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_cancelled_hours_room.php\">Select a different room</a></div>";
            $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
            $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
            $display_block .= "<th>Room number</th><th>Total cancelled hours</th>";
            while ($hours_info = mysql_fetch_array($get_hours_res))
            {
            
            $room_name = $hours_info['room_name'];
            $total_cancelled_hours = $hours_info['total_cancelled_hours'];
            $grand_total_cancelled_hours += $total_cancelled_hours;
                      
            $display_block .= "<tr><td>" . $room_name . "</td><td>" . $total_cancelled_hours . "</td></tr>";
            
            }

        $display_block .= "<tr><td><b>Total</b></td><td><b>" . $grand_total_cancelled_hours . "</b></td></tr>";
        $display_block .= "</table><br />
        <input type=hidden name=utf_start_date value=$utf_start_date>
        <input type=hidden name=utf_end_date value=$utf_end_date>
        <input type=submit name=run_csv_total value=\"Export report to Excel\"></form></div>";

    }

}
}

//export single item report to Excel
if (isset($_POST['run_csv']))
{

$room_id = $_POST['barcharge_id'];
$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT bar_charge.name,
COUNT(*) AS total_sold, bar_charge.is_active
FROM bar_charge, reservation_transaction, reservation_entry
WHERE bar_charge.id = reservation_transaction.equipment_id
AND reservation_transaction.reservation_entry_id = reservation_entry.id
AND room_name <> 'equipment'
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date AND bar_charge.id = $room_id";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

//export total items report to Excel
if (isset($_POST['run_csv_total']))
{

$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT room_name, SUM((end_time - start_time)/120) AS total_cancelled_hours
FROM reservation_entry, reservation_room
WHERE reservation_entry.room_id = reservation_room.id
AND is_cancelled=1
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date
GROUP BY room_name
ORDER BY reservation_room.id ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 5: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

?>
<html>
 <head>
    <title>Report: total cancelled hours by room</title>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />

    <script language="JavaScript" type="text/javascript">
        <!--

        function validate_form()
        {
            valid = true;

            if ( document.run_report.sel_id.selectedIndex == 0 )
            {
            alert ( "Please select a room from the drop down menu" );
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
