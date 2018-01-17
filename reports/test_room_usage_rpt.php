
<?php
/*
 * Testing UTF date/time conversion functions
 */

include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
include ("../includes/header.inc");
include ("../reservation/themes/default.inc");

// get data from url
$start_date = $_GET['start_date'];  // yyyymmdd format
$end_date = $_GET['end_date'];
$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];

if ( $action == "run_report")
{
    display_report ( $start_date );
}


?>


<?php
// title

echo "<h1>Room Usage Report for $start_date and $end_date </h1><br/>";

// Show current date/time - test date_2_utf_date
//$year          = date("Y");
//$month         = date("m");
//$day           = date("d");
//$hour          = date("H");
//$minute        = date("i");
//$curr_utf_time = date_2_utf_date ($year, $month, $day, $hour, $minute );
//echo "<h3>Current Time: $year/$month/$day $hour:$minute (UTF $curr_utf_time)</h3>";

?>
            <form method="GET" onsubmit="<?php echo $refresh_page ?>">
            <input type=hidden name=action value="run_report">
            Start Date (YYYYMMDD): <input type=text name=start_date size = "15" value="">
            End Date (YYYYMMDD): <input type=text name=end_date size="15" value="">
            <input type=submit value="Run Report">
            </form>
            <div>
               <? display_report($start_date, $end_date); ?>
            </div>

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
$query = "SELECT re.id, re.start_time, re.end_time, re.actual_start_time, re.actual_end_time, rr.room_name
            FROM reservation_entry re, reservation_room rr
           WHERE FROM_UNIXTIME(start_time, '%Y%m%d') >= $start_date
           AND FROM_UNIXTIME(end_time, '%Y%m%d') <= $end_date
            AND re.room_id = rr.id
            AND rr.room_name = 'Room 7'";
          

$result = mysql_query($query) or die ("DB ERROR: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($result)>0)
{
    echo "<table border='1'>";
    //echo "<th>id</th>";
    echo "<th>Room Number</th>";
    echo "<th>start period</th>";
    echo "<th>end period</th>";
    echo "<th>duration</th>";
    //echo "<th>actual start time</th>";
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

        // display data
        echo "<tr>";
        echo "<td>" . $row_room_id . "</td>";
        //echo "<td>" . $id . "</td>";
        echo "<td>" . $start_time . "</td>";
        echo "<td>" . $end_time . "</td>";
       // echo"<td>" . $duration . "</td>";
        echo "<td>" . $period_minute_duration . " minutes </td>";
       // echo "<td>" . $actual_start_time . "</td>";
        //echo "<td>" . $actual_end_time . "</td>";
        //echo "<td>" . $actual_minute_duration . " minutes </td>";
        echo "</tr>";
    }

    // display total
    echo "<tr>";
    echo "<td colspan=3>Total:</td>";
    echo "<td> $tot_period_duration minutes</td>";
    //echo "<td colspan=2>&nbsp;</td>";
    //echo "<td> $tot_actual_duration minutes</td>";
    echo "</tr>";
    echo "</table>";


}}
?>
