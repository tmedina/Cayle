<?php
// title
$rpt_name = $_GET['rpt_name'];
echo "<h3>Select Date for $rpt_name </h3><br/>";


// Show current date/time - test date_2_utf_date
//$year          = date("Y");
//$month         = date("m");
//$day           = date("d");
//$hour          = date("H");
//$minute        = date("i");
//$curr_utf_time = date_2_utf_date ($year, $month, $day, $hour, $minute );
//echo "<h3>Current Time: $year/$month/$day $hour:$minute (UTF $curr_utf_time)</h3>";

?>
            <form method="GET" action="<? echo $rpt_name ?>">
            <input type=hidden name=action value="run_report">
            Start Date (YYYYMMDD): <input type=text name=start_date size = "15" value="">
            End Date (YYYYMMDD): <input type=text name=end_date size="15" value="">
            <input type=hidden name=action2 value="export">
            <input type=submit name=run_report value="Run Report">
            <input type=submit name=run_csv value="Export Report to Excel">
            </form>