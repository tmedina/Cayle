<?php
/*
 * room usage - all rooms
 * with actual and scheduled start time, end time, duration
 * 20090419 beth
 */
include ("../includes/dbconnect.inc");
//include ("../includes/header.inc");
$start_date = $_GET['start_date'];  // yyyymmdd format
$end_date = $_GET['end_date'];
$run_csv = $_GET['run_csv'];
$run_report = $_GET['run_report'];
$rpt_name = $_GET['rpt_name'];

$sql = "SELECT re.id, rr.room_name, FROM_UNIXTIME(re.actual_start_time, '%Y%m%d%T') as actual_start_time,
                FROM_UNIXTIME(actual_end_time, '%Y%m%d%T') as actual_end_time,
                ((re.actual_end_time - re.actual_start_time)/60) as actual_duration_minutes,
                ((re.end_time - re.start_time)/2) as scheduled_duration_minutes
            FROM reservation_entry re, reservation_room rr
           WHERE FROM_UNIXTIME(start_time, '%Y%m%d') >= $start_date
           AND FROM_UNIXTIME(end_time, '%Y%m%d') <= $end_date
            AND re.room_id = rr.id
            ";
    //$result = mysql_query($query) or die ("DB ERROR: " . mysql_errno() . "-" . mysql_error() . "bottom sql" );
    //echo $sql;
    if (isset($run_csv))
    {
        $csvReport = "Y";
        include ("../includes/csv_download.inc");
    }

    //exit(0);

include ("../includes/functions.inc");
include ("../includes/header.inc");
include ("../reservation/themes/default.inc");


// get data from url

$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];


//echo "Action: $action<br/>";




?>

  <html>
  <head>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
  <h2>Usage Report - All Rooms <? echo $start_date ?> to <? echo $end_date?></h2>
    <div>
        <? display_report($start_date) ?>
    </div>
</body>
</html>


<?php
function display_report($start_date)

{
//echo "start the function";
$start_date = $_GET['start_date'];  // yyyymmdd format
$end_date = $_GET['end_date'];
//echo "start and end" . $start_date . " " . $end_date;

 if ($start_date == "")
    {
        echo "<div id=err_msg>Error: Please enter a start date.</div>";
        return (1);
    }
    elseif ( $end_date == "" )
    {
        echo "<div id=err_msg>Error: Please enter an end date.</div>";
        return (1);
    }


// create query
$query = "SELECT re.id, rr.room_name, FROM_UNIXTIME(re.actual_start_time, '%Y%m%d%T') as astart_time,
                FROM_UNIXTIME(actual_end_time, '%Y%m%d%T') as aend_time,
                ((re.actual_end_time - re.actual_start_time)/60) as actual_duration_minutes,
                ((re.end_time - re.start_time)/2) as scheduled_duration_minutes
            FROM reservation_entry re, reservation_room rr
           WHERE FROM_UNIXTIME(start_time, '%Y%m%d') >= $start_date
           AND FROM_UNIXTIME(end_time, '%Y%m%d') <= $end_date
            AND re.room_id = rr.id
            ";


$result = mysql_query($query) or die ("DB ERROR: " . mysql_errno() . "-" . mysql_error() . "top sql" );

if (mysql_num_rows($result)<=0)
{
    echo "No results for this query.";
}
else
{
    echo "<table border='1'>";
    echo "<th>Room Number</th>";
    echo "<th>Reservation Id</th>";
    echo "<th>Actual Start Time</th>";
    echo "<th>Actual End Time</th>";
    echo "<th>Actual Duration (minutes)</th>";
    echo "<th>Scheduled Duration (minutes)</th>";
    //echo "<th>actual end time</th>";
    //echo "<th>actual duration</th>";

    // initialize total fields
    $tot_period_duration = 0;
    $tot_actual_duration = 0;

    while($row = mysql_fetch_array($result))
    {
        // get data returned from query
        $id = $row['id'];

        // convert utf period to period date string
        $start_time = utf_period_2_date($row['start_time']);
        $end_time   = utf_period_2_date($row['end_time']);

        // convert utf date to date string if populated
        if ( isset($row['actual_start_time']) && isset($row['actual_end_time']) )
        {
            $actual_start_time = utf_date_2_date($row['actual_start_time']);
            $actual_end_time   = utf_date_2_date($row['actual_end_time']);
        }
        else
        {
            $actual_start_time = 0;
            $actual_end_time   = 0;
        }

        // calculate and total up period duration
        $period_minute_duration = calc_utf_period_diff( $row['end_time'], $row['start_time'] );
        $tot_period_duration += $period_minute_duration;

        // calculate and total up actual duration if populated
        if ( isset($row['actual_start_time']) && isset($row['actual_end_time']) )
        {
            $actual_minute_duration = calc_utf_date_diff( $row['actual_end_time'], $row['actual_start_time'] );
        }
        else
        {
            $actual_minute_duration = 0;
        }
       // $tot_actual_duration += $actual_minute_duration;
        $row_room_id = $row['room_name'];
        $duration = $row['duration'];
        $actual_duration_minutes = $row['actual_duration_minutes'];
        $scheduled_duration_minutes = $row['scheduled_duration_minutes'];
        $start_time_actual = $row['astart_time'];
        $end_time_actual = $row['aend_time'];


        // display data
        echo "<tr>";
        echo "<td>" . $row_room_id . "</td>";
        echo "<td>" . $id . "</td>";
        echo "<td>" . $start_time_actual . "</td>";
        echo "<td>" . $end_time_actual . "</td>";
        echo"<td>" . number_format($actual_duration_minutes) . " minutes </td>";
        echo "<td>" . number_format($scheduled_duration_minutes) . " minutes </td>";
       // echo "<td>" . $actual_start_time . "</td>";
        //echo "<td>" . $actual_end_time . "</td>";
        //echo "<td>" . $actual_minute_duration . " minutes </td>";
        echo "</tr>";
    }

    
    echo "</table>";


}
}

?>
<?php
function export_report($start_date, $end_date)
{

$sql = "SELECT re.id, rr.room_name, FROM_UNIXTIME(re.actual_start_time, '%Y%m%d%T') as actual_start_time,
                FROM_UNIXTIME(actual_end_time, '%Y%m%d%T') as actual_end_time,
                ((re.actual_end_time - re.actual_start_time)/60) as actual_duration_minutes,
                ((re.end_time - re.start_time)/2) as scheduled_duration_minutes
            FROM reservation_entry re, reservation_room rr
           WHERE FROM_UNIXTIME(start_time, '%Y%m%d') >= $start_date
           AND FROM_UNIXTIME(end_time, '%Y%m%d') <= $end_date
            AND re.room_id = rr.id
                ";
    //$result = mysql_query($query) or die ("DB ERROR: " . mysql_errno() . "-" . mysql_error() . "bottom sql" );
    //echo $sql;
    $csvReport = "Y";
    include ("../includes/csv_download.inc");
}
?>
