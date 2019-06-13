<?php
//Created by Hallie Pritchett
//This report shows total number of bar items sold during a user-specified time period;
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
$get_list = "SELECT id, name FROM bar_charge ORDER BY name";
$get_list_res = mysql_query($get_list) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );
$display_block .= "<h2 align=\"left\">Total bar charges per bar charge item</h2>";
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
         $display_block .= "<option value=\"\">Show all items</option>";


         $display_block .= "</select><br />";

       $display_block .=  "<br />

            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
 }

if (isset($_POST['run_report']))
{

$barcharge_id = $_POST['sel_id'];

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

//show a single item
if ($barcharge_id != "")
{

$get_count = "SELECT bar_charge.name,
SUM(qty) AS total_sold, bar_charge.is_active
FROM bar_charge, reservation_transaction, reservation_entry
WHERE bar_charge.id = reservation_transaction.bar_charge_id
AND reservation_entry.id = reservation_transaction.reservation_entry_id
AND bar_charge_id IS NOT NULL
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date AND bar_charge.id = $barcharge_id";
$get_count_res = mysql_query($get_count) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_count_res) > 0)

    {

        while ($count_info = mysql_fetch_array($get_count_res))
            {
            $name = $count_info['name'];
            $total_sold = $count_info['total_sold'];
            $is_active = $count_info['is_active'];

            if ($total_sold == NULL)
            {
            $display_block .= "<h2 align=\"left\">Total items sold from " . $req_start_date . " to " . $req_end_date    . "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item name: " . $name . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_barcharge.php\">Select a different item</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><p>No items sold during the requested date range</p></div>";
            }

            else
            {
            $display_block .= "<form method=\"post\" name=\"run_report\" action=\"$_SERVER[PHP_SELF]\">";
            $display_block .= "<h2 align=\"left\">Total items sold from " . $req_start_date . " to " . $req_end_date    . "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item name: " . $name . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_barcharge.php\">Select a different item</a></div>";
            $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
            $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
            $display_block .= "<th>Item name</th><th>Number sold</th><th>Active?</th>";
            $display_block .= "<tr><td>" . $name . "</td><td>" . $total_sold . "</td><td>" . $is_active . "</td></tr>";
            $display_block .= "</table><br />
            <input type=hidden name=utf_start_date value=$utf_start_date>
            <input type=hidden name=utf_end_date value=$utf_end_date>
            <input type=hidden name=barcharge_id value=$barcharge_id>
            <input type=submit name=run_csv value=\"Export report to Excel\"></form></div>";
           
            }
        }
    }
    }


//show all bar charges
elseif ($barcharge_id == "")

{

$get_count = "SELECT bar_charge.name,
SUM(qty) AS total_sold, bar_charge.is_active
FROM bar_charge, reservation_transaction, reservation_entry
WHERE bar_charge.id = reservation_transaction.bar_charge_id
AND reservation_entry.id = reservation_transaction.reservation_entry_id
AND bar_charge_id IS NOT NULL
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date GROUP BY bar_charge.name ORDER BY bar_charge.name ASC;";
$get_count_res = mysql_query($get_count) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_count_res)< 1)
    {
        $display_block .= "<h2 align=\"left\">Total number of all items sold from " . $req_start_date . " to " . $req_end_date    . "</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_barcharge.php\">Select a different item</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><p>No items sold during the requested date range</p></div>";
    }

elseif  (mysql_num_rows($get_count_res)> 0)
    {

            $display_block .= "<h2 align=\"left\">Total number of all items sold from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"total_barcharge.php\">Select a different item</a></div>";
            $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
            $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
            $display_block .= "<th>Item name</th><th>Number sold</th><th>Active?</th>";
            while ($count_info = mysql_fetch_array($get_count_res))
            {

            $name = $count_info['name'];
            $total_sold = $count_info['total_sold'];
            $grand_total_sold += $total_sold;
            $is_active = $count_info['is_active'];
                       
            $display_block .= "<tr><td>" . $name . "</td><td>" . $total_sold . "</td><td>" . $is_active . "</td></tr>";
           
            }
            $display_block .= "<tr><td><b>Total</b></td><td><b>" . $grand_total_sold ."</b></td><td>&nbsp;</td></tr>";
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

$barcharge_id = $_POST['barcharge_id'];
$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT bar_charge.name,
SUM(qty) AS total_sold, bar_charge.is_active
FROM bar_charge, reservation_transaction, reservation_entry
WHERE bar_charge.id = reservation_transaction.bar_charge_id
AND reservation_entry.id = reservation_transaction.reservation_entry_id
AND bar_charge_id IS NOT NULL
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date AND bar_charge.id = $barcharge_id";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

//export total items report to Excel
if (isset($_POST['run_csv_total']))
{

$utf_start_date = $_POST['utf_start_date'];
$utf_end_date = $_POST['utf_end_date'];

$sql = "SELECT bar_charge.name,
SUM(qty) AS total_sold, bar_charge.is_active
FROM bar_charge, reservation_transaction, reservation_entry
WHERE bar_charge.id = reservation_transaction.bar_charge_id
AND reservation_entry.id = reservation_transaction.reservation_entry_id
AND bar_charge_id IS NOT NULL
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date GROUP BY bar_charge.name ORDER BY bar_charge.name ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 5: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}


?>
<html>
 <head>
    <title>Report: total bar charges per bar charge item</title>
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
