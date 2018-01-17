<?php
/*
 * equipment rentals in-house with room
 * with person, amount, and duration
 * 20090419 beth
 */
include ("../includes/dbconnect.inc");
//include ("../includes/header.inc");
$start_date = $_GET['start_date'];  // yyyymmdd format
$end_date = $_GET['end_date'];
$run_csv = $_GET['run_csv'];
$run_report = $_GET['run_report'];
$rpt_name = $_GET['rpt_name'];

$sql = "select mc.name as misc_charge_name, mct.name as misc_charge_type, rt.comment as charge_comment,
                rt.updated_at as charge_date, rt.amount as charge_amount
                from reservation_transaction rt, misc_charge mc, misc_charge_type mct
                where mct.id = mc.misc_charge_type_id
                and mc.id = rt.misc_charge_id
                and mct.name='discount'
                AND rt.misc_charge_id > 0
                AND rt.is_active = 1
                order by mc.name

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
  <h2>Miscellaneous Transactions - Discounts <? echo $start_date ?> to <? echo $end_date?></h2>
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
$query = "select mc.name as misc_charge_name, mct.name as misc_charge_type, rt.comment as charge_comment,
                rt.updated_at as charge_date, rt.amount as charge_amount
                from reservation_transaction rt, misc_charge mc, misc_charge_type mct
                where mct.id = mc.misc_charge_type_id
                and mc.id = rt.misc_charge_id
                and mct.name='discount'
                AND rt.misc_charge_id > 0
                AND rt.is_active = 1
                order by mc.name
            ";


$result = mysql_query($query) or die ("DB ERROR: " . mysql_errno() . "-" . mysql_error() . "top sql" );

if (mysql_num_rows($result)<=0)
{
    echo "No results for this query.";
}
else
{
    echo "<table border='1'>";
    echo "<th>Type</th>";
    echo "<Th>Amount</th>";
    echo "<th>Comments</th>";
    echo "<th>Date</th>";
    

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
        $mc_name = $row['misc_charge_name'];
        $mct_type = $row['misc_charge_type'];
        $rt_comment = $row['charge_comment'];
        $amount = $row['charge_amount'];
        $charge_date = $row['charge_date'];



        // display data
        echo "<tr>";
        echo "<td>" . $mct_type . " " . $mc_name . "</td>";
        echo "<td> $" . $amount . "</td>";
        echo "<td>" . $rt_comment . "</td>";
        echo "<td>" . $charge_date . "</td>";
        echo "</tr>";
    }


    echo "</table>";


}
}

?>
<?php
function export_report($start_date, $end_date)
{

$sql = "select mc.name as misc_charge_name, mct.name as misc_charge_type, rt.comment as charge_comment,
                rt.updated_at as charge_date, rt.amount as charge_amount
                from reservation_transaction rt, misc_charge mc, misc_charge_type mct
                where mct.id = mc.misc_charge_type_id
                and mc.id = rt.misc_charge_id
                and mct.name='discount'
                AND rt.misc_charge_id > 0
                AND rt.is_active = 1
                order by mc.name
                ";
    //$result = mysql_query($query) or die ("DB ERROR: " . mysql_errno() . "-" . mysql_error() . "bottom sql" );
    //echo $sql;
    $csvReport = "Y";
    include ("../includes/csv_download.inc");
}
?>
