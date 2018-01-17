<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/* 20090417 wilson: adjust duration depending upon room or equip only reservation.
 */

// Get the room info if any
//
$query = "SELECT rr.room_name
            FROM reservation_entry re, reservation_room rr
           WHERE re.room_id = rr.id
             AND re.id = $reservation_id";
// die and show mysql error number and messages, if tdere is any error witd query
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($result)>0)
{
    $row = mysql_fetch_array($result);
    $room_disp = $row[room_name];
}
else
{
    $room_disp = 'none';
}

// Get the reservation info.
$query = "SELECT *,
                 FROM_UNIXTIME(actual_start_time, '%j') AS actual_start_value,
                 FROM_UNIXTIME(actual_end_time, '%j') AS actual_end_value,
                 FROM_UNIXTIME(actual_start_time, '%Y/%m/%d') AS actual_start_date,
                 FROM_UNIXTIME(actual_end_time, '%Y/%m/%d') as actual_end_date
            FROM reservation_entry WHERE id = $reservation_id";

// die and show mysql error number and messages, if tdere is any error witd query
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($result)>0)
{
    echo "<table cellpadding=\"0\">";
    while($row = mysql_fetch_array($result))
    {
        echo "<tr >";
        echo "<td><b>Reservation ID:</b>&nbsp;&nbsp;</td>";
        echo "<td>$row[id]</td>";
        echo "</tr>";

        if ( $row[room_id] > 0 )
        {
            echo "<tr>";
            echo "<td><b>From: </b> </td>";
            echo "<td>". utf_period_2_date($row[start_time]) . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>To: </b> </td>";
            echo "<td>". utf_period_2_date($row[end_time]) . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Duration: </b> </td>";
            echo "<td>". (calc_utf_period_diff($row[end_time], $row[start_time]))/60 . " hour(s)</td>";
            echo "</tr>";
        }
        else
        {
            $res_duration = $row['actual_end_value'] - $row['actual_start_value'];
            $res_duration = $res_duration == 0 ? 1 : $res_duration; //default to 1, if 0.
            
            echo "<tr>";
            echo "<td><b>From: </b> </td>";
            echo "<td>". $row['actual_start_date'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>To: </b> </td>";
            echo "<td>". $row['actual_end_date'] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Duration: </b> </td>";
            echo "<td>". $res_duration . " day(s)</td>";
            echo "</tr>";
        }
    }
        echo "<tr>";
        echo "<td><b>Room: </b> </td>";
        echo "<td>$room_disp</td>";
        echo "</tr>";
    echo "</table>";
}
else
{
     echo "No Reservation found.";
}
mysql_free_result($result);
?>
