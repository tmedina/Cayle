<?php
//This report shows total number of rentals per rental item over a user-specified time period;
//the business day starts at 12PM & ends at 2AM
//
//TO DO:
// - form validation
// - export to Excel
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
$get_list = "SELECT id, equip_description FROM equipment ORDER BY equip_description";
$get_list_res = mysql_query($get_list) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );
$display_block .= "<h2 align=\"left\">Total rentals per rental item - test</h2>";
$display_block .= "<div id=\"links_create\"><a href=\"index_rpt.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
$display_block .= "<div id=\"dropdown\" align=\"left\"><form method=\"post\" name=\"run_report\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return validate_form();\">";
//start date dropdowns
//start date dropdowns
$display_block .= "<h3>Select a start date</h3>";
$display_block .= "<input type='date' name='start_date' />";
$display_block .= "<br />";

//end date dropdowns
$display_block .= "<h3>Select an end date</h3>";
$display_block .= "<input type='date' name='end_date' />";
$display_block .= "<br />";

$display_block .= "<h3>Select a piece of equipment</h3>";

 $display_block .= "<select name=\"sel_id\">
         <option value=\"0\">--Select an item--</option>";

         while($recs = mysql_fetch_array($get_list_res))
         {
             //define variables
             $id = $recs['id'];
             $equip_description = stripslashes($recs['equip_description']);

            $display_block .= "<option value=\"$id\">$equip_description</option>";
         }
         $display_block .= "<option value=\"\">Show all equipment</option>";


         $display_block .= "</select><br />";

       $display_block .=  "<br />

            <input type=\"submit\" name=\"run_report\" value=\"Run report\">
            </form></div>";
 }

if (isset($_POST['run_report']))
{

$equipment_id = $_POST['sel_id'];


parse_date($_POST['start_date'], $start_month, $start_day, $start_year);
parse_date($_POST['end_date'], $end_month, $end_day, $end_year);

$run_csv = $_POST['run_csv'];
//actual end day is 2AM the next calendar day
$actual_end_day = $end_day+1;
$req_start_date = $start_month . "/" . $start_day . "/" . $start_year;
$req_end_date = $end_month . "/" . $end_day . "/" . $end_year;
//day starts at noon
$utf_start_date = date_2_utf_date($start_year, $start_month, $start_day, "12", "00");
//day ends at 2AM
$utf_end_date = date_2_utf_date($end_year, $end_month, $actual_end_day, "02", "00");

//show a single piece of equipment
if ($equipment_id != "")
{

$get_count = "SELECT equip_description,
SUM(IF(room_id IS NULL, 1,0)) AS offsite_rentals,
SUM(IF(room_id IS NOT NULL, 1,0)) AS inhouse_rentals,
COUNT(*) AS total_rentals
FROM equipment, reservation_transaction, reservation_entry
WHERE equipment.id = reservation_transaction.equipment_id
AND reservation_transaction.reservation_entry_id = reservation_entry.id
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date AND equipment.id = $equipment_id";
$get_count_res = mysql_query($get_count) or die ("ERROR 2: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_count_res) > 0)

    {

        while ($count_info = mysql_fetch_array($get_count_res))
        {
            $equip_description = $count_info['equip_description'];
            $offsite_rentals = $count_info['offsite_rentals'];
            $inhouse_rentals = $count_info['inhouse_rentals'];
            $total_rentals = $count_info['total_rentals'];
          
            if ($total_rentals == NULL)
            {
            $display_block .= "<h2 align=\"left\">Total number of rentals for " . $equip_description . " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index_rpt.php\">Back to reports index</a> | <a href=\"total_equipment_rentals.php\">Select a different item</a>&nbsp;</div>";
            $display_block .= "<div id=\"text\" align=\"left\"><p>No count available for the requested date range</p></div>";
            }

            else
            {
            
            $display_block .= "<h2 align=\"left\">Total number of rentals for " . $equip_description . " from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"first_links\"><a href=\"index_rpt.php\">Back to reports index</a> | <a href=\"total_equipment_rentals.php\">Select a different item</a></div>";
            $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
            $display_block .= "<th>Equipment name</th><th>Total off-site rentals</th><th>Total in-house rentals</th><th>Total all rentals</th>";
            $display_block .= "<tr><td>" . $equip_description . "</td><td>" . $offsite_rentals . "</td><td>" . $inhouse_rentals . "</td><td>" . $total_rentals . "</tr>";
            $display_block .= "</table></div>";
           
            }
        }

    }

}
//show all equipment
else

{


$get_count = "SELECT equip_description,
SUM(IF(room_id IS NULL, 1,0)) AS offsite_rentals,
SUM(IF(room_id IS NOT NULL, 1,0)) AS inhouse_rentals,
COUNT(*) AS total_rentals
FROM equipment, reservation_transaction, reservation_entry
WHERE equipment.id = reservation_transaction.equipment_id
AND reservation_transaction.reservation_entry_id = reservation_entry.id
AND start_time >= $utf_start_date
AND end_time <= $utf_end_date GROUP BY equip_description ORDER BY equip_description ASC;";
$get_count_res = mysql_query($get_count) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($get_count_res)< 1)
    {
        $display_block .= "<h2 align=\"left\">Total number of rentals for all equipment from " . $req_start_date . " to " . $req_end_date    . "</h2>";
        $display_block .= "<div id=\"links\"><a href=\"index_rpt.php\">Back to reports index</a> | <a href=\"total_equipment_rentals.php\">Select a different item</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><p>No count available for the requested date range</p></div>";
    }

elseif  (mysql_num_rows($get_count_res)> 0)
    {

            $display_block .= "<h2 align=\"left\">Total number of rentals for all equipment from " . $req_start_date . " to " . $req_end_date    . "</h2>";
            $display_block .= "<div id=\"links\"><a href=\"index_rpt.php\">Back to reports index</a> | <a href=\"total_equipment_rentals.php\">Select a different item</a></div>";

            $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
            $display_block .= "<th>Equipment name</th><th>Total off-site rentals</th><th>Total in-house rentals</th><th>Total all rentals</th>";
            while ($count_info = mysql_fetch_array($get_count_res))
            {

            $equip_description = $count_info['equip_description'];
            $offsite_rentals = $count_info['offsite_rentals'];
            $inhouse_rentals = $count_info['inhouse_rentals'];
            $total_rentals = $count_info['total_rentals'];

            $display_block .= "<tr><td>" . $equip_description . "</td><td>" . $offsite_rentals . "</td><td>" . $inhouse_rentals . "</td><td>" . $total_rentals . "</tr>";
           
            }
        $display_block .= "</table></div>";

    }
    }
    
}

if (isset($run_csv))
    {
        $csvReport = "Y";
        include ("../includes/csv_download.inc");
    }

?>
<html>
 <head>
    <title>Test report</title>
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
