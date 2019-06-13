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
$display_block .= "<input type='date' name='start_date' />";
$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .= "<input type='date' name='end_date' />";
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
