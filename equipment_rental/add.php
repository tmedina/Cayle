<?php
/*
 * Add Equipment to Reservation
 * 03-21-2009
 * osmerg
 */

include ("../includes/dbconnect.inc");
//include ("../reservation/themes/default.inc");
include ("../includes/functions.inc");
include ("../reservation/functions.inc");

//include ("../reservation/language.inc");
//include ("../reservation/config.inc.php");
//echo "period_to_datetime: " . period_to_datetime ( "1237651740" );

$reservation_id = $_GET['reservation_id'];
$return_url     = $_GET['return_url'];
$start_time     = $_GET['start_time'];
$end_time       = $_GET['end_time'];
$action         = $_GET['action'];
$change_end_date = $_GET['change_end_date'];

//initialize room_id to empty string
$room_id = NULL;

if ( ! isset($start_time) && ! isset($end_time) )
{
    die("Error: Missing parameters start time and end time");
}


if ( $change_end_date )
{
    list($year, $month, $day ) = split('[/.-]', $end_time);
    //echo "New end time: $year $month $day <br/>";
    $end_time = mktime(12, count($periods)-1, 0, $month, $day-1, $year);
    //echo "New utf end time: $end_time <br/>";
}
//debug
//echo "start_time $start_time End Time: $end_time</br>";

//if reservation_id variable is set an does not equal empty string
if ( isset ($reservation_id) && $reservation_id != "")
{

    $get_room_id = "SELECT id, room_id, start_time, end_time FROM reservation_entry
                          WHERE id = $reservation_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($get_room_id) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    $row=mysql_fetch_array($result);
    $room_id = $row['room_id'];

    $start_time = $row['start_time'];
    $end_time = $row['end_time'];
}

list($disp_start_date, $disp_start_time) = split(' ', utf_period_2_date($start_time));
list($disp_end_date, $disp_end_time) = split(' ', utf_period_2_date($end_time));

?>
<html>
    <head>
        <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
        <style type="text/css">@import url(../includes/jscalendar/calendar-win2k-1.css);</style>
        <script type="text/javascript" src="../includes/jscalendar/calendar.js"></script>
        <script type="text/javascript" src="../includes/jscalendar/lang/calendar-en.js"></script>
        <script type="text/javascript" src="../includes/jscalendar/calendar-setup.js"></script>
    </head>
    <body>
        <?php
        include ("../includes/header.inc");
        ?>

        <div id="page" align="center">

            <?php
            //print header if equipment only reservation
            if ($room_id == 0){
                echo "<h2 align='left'>Equipment only reservation</h2><br/>";
            }
            ?>
            <?php
            // Allow user to change end date if this is a new equip only reservation
            // (coming from calendar)
            if (!isset($reservation_id)||$reservation_id == "" || ( $room_id == NULL ))
            {
                ?>

            <h3>Start date: <?php echo $disp_start_date; ?>&nbsp;&nbsp;&nbsp;End date: <?php echo $disp_end_date; ?></h3>

            <form name="calendar" action="add.php">
                <input type="text" readonly id="end_time" name="end_time" />
                <input type="hidden" id="start_time" name="start_time" value="<?php echo $start_time ?>" />
                <button id="trigger">...</button><br><br>
                <button id="change_end_date" name="change_end_date" value="1">Change End Date</button>
            </form>

            <script type="text/javascript">
                Calendar.setup(
                {
                    inputField  : "end_time",    // id of the input field
                    ifFormat    : "%Y-%m-%d",    // the date format
                    button      : "trigger"      // id of the button
                }
            );
            </script>
            <?php
        }


                               /*
                                *
                                * inner query: get every piece of equipment that has reservation id within date range
                                * and is available and is NOT cancelled. The sort by equipment and manufacturer.
                                * outer query: get all equipment not included in the inner query
                                */
        // is for equipment only reservation
        // or
        // is for adding equipment to equipment only reservation
        //
        if (!isset($reservation_id)||$reservation_id == "" || ( $room_id == NULL ))
        {
            $get_equip="SELECT * from equipment WHERE inhouse_only = 0
                                AND is_available = 1 AND is_awaiting_inspection = 0 AND id NOT IN
                                    (SELECT eq.id FROM reservation_transaction AS rt, reservation_entry AS re, equipment AS eq
                                         WHERE rt.reservation_entry_id = re.id
                                         AND re.is_cancelled = 0
                                         AND rt.equipment_id = eq.id
                                         AND eq.is_available = 1
                                         AND eq.is_awaiting_inspection = 0
                                         AND start_time <= $end_time
                                         AND end_time >= $start_time)
                                            ORDER BY equip_type, equip_manufacturer";
        }
        // is for adding equipment to a room reservation
        else
        {
            $get_equip="SELECT * from equipment WHERE is_available = 1 AND is_awaiting_inspection = 0 AND id NOT IN
                                    (SELECT eq.id FROM reservation_transaction AS rt, reservation_entry AS re, equipment AS eq
                                         WHERE rt.reservation_entry_id = re.id
                                         AND re.is_cancelled = 0
                                         AND rt.equipment_id = eq.id
                                         AND eq.is_available = 1
                                         AND eq.is_awaiting_inspection = 0
                                         AND start_time <= $end_time
                                         AND end_time >= $start_time)
                                            ORDER BY equip_type, equip_manufacturer";
        }

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($get_equip) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        // echo $get_equip;
        $get_equip_result=mysql_query($get_equip);
        ?>


        <?php if (mysql_num_rows($get_equip_result)>0){
            // If the number of rows greater
            // than 0 begin while loop
            $counter=0;
            ?>

            <!-- Start form -->
            <div id="text" align="left">
                <h2 align="left">List of available equipment</h2><br/>
                <form method="GET" name="select_form" action="handler.php" >

                    <table border="1" cellspacing="0" cellpadding="5" bordercolor="#003366" border-style="solid">
                        <tr>
                            <th>Type</th>
                            <th>Manufacturer</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>In_house only?</th>
                            <th>Rental/day</th>
                            <th>Rental/hr</th>
                            <th>Select</th>
                        </tr>
                        <?php
                        while($equip_info=mysql_fetch_array($get_equip_result))
                        //loop: fetch array and assign variable to each result
                        {
                            ?>

                            <?php
                            $counter++;

                            $equip_id=$equip_info['id'];
                            $equip_type=$equip_info['equip_type'];
                            $equip_manufacturer=$equip_info['equip_manufacturer'];
                            $equip_model=$equip_info['equip_model'];
                            $equip_description=$equip_info['equip_description'];
                            $inhouse_only=$equip_info['inhouse_only'];
                            $rental_price_per_day=$equip_info['rental_price_per_day'];
                            $rental_price_per_hr=$equip_info['rental_price_per_hr'];

                            // If variable is 1 set to 'Yes'
                            // else set to 'No'
                            if($inhouse_only==TRUE){
                                $inhouse_only="Yes";

                            }
                            else{
                                $inhouse_only="No";
                            }

                            ?>

                        <!-- loop thru array and create table row for each result -->
                        <tr>
                            <td> <?php echo $equip_type ?> </td>
                            <td> <?php echo $equip_manufacturer ?> </td>
                            <td> <?php echo $equip_model ?> </td>
                            <td> <?php echo $equip_description ?> </td>
                            <td> <?php echo $inhouse_only ?> </td>
                            <td>$<?php echo $rental_price_per_day ?> </td>
                            <td>$<?php echo $rental_price_per_hr ?> </td>

                            <!-- checkboxes so equipment can be selected -->
                            <td><input type="checkbox" name="select_equip<?php echo $counter ?>" value="<?php echo $equip_id ?>"></td>
                        </tr>

                                <?php
                            }
                            ?>
                    </table><br />
                    <input type="hidden" name="start_time" value="<?php echo $start_time?>">
                    <input type="hidden" name="end_time" value="<?php echo $end_time?>">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id?>">
                    <input type="hidden" name="return_url" value="<?php echo $return_url?>">
                    <input type="hidden" name="action" value="<?php echo $action ?>">
                    <!--<input type="button" value="Back" onclick="location.href='../reservation/index.php'">-->
                    <input type="button" value="Back" onClick="history.go(-1);return true;">
                    <input type="submit" name="btn" value="Next">
                </form>
            </div>
        </div>
        <?php
    }
    //if no equipment available print statement
    else{
        echo "<div id='text' align='left'>";
        echo "THERE IS NO EQUIPMENT AVAILABLE";
        echo "<br /><br />";
        echo "<a href=../reservation/index.php>View calendar</a></div>";
    }
    echo $display_page;
    ?>
    </body>
</html>