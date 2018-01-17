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
</select>";

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
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$start_month = $_POST['start_month'];
$start_day = $_POST['start_day'];
$start_year = $_POST['start_year'];
$end_month = $_POST['end_month'];
$end_day = $_POST['end_day'];
$end_year = $_POST['end_year'];
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