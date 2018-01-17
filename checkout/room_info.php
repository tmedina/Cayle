<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
//GET ROOM CHARGE FOR CHECKOUT
//Created by Marline Santiago-Cook
//
//These includes are already called in the calling page invoice.php
//include ("../includes/dbconnect.inc");
//include ("../includes/functions.inc");

// Get the query string variables
$reservation_id = $_GET['reservation_id'];
$room_mc_id          = $_GET['room_mc_id'];
$rm_id          = $_GET['room_id'];
$rt_remove_id   = $_GET['rt_remove_id'];
$page           = "room_info";
$room_trans_already_exist = "NO";

// get the room id if not supplied
if ( $rm_id == "" )
{
    $query = "SELECT room_id FROM reservation_entry where id = $reservation_id";
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    $row = mysql_fetch_array($result);
    $rm_id = $row['room_id'];
}

// Get the cancellation transaction if any
$query = "SELECT rt.id
            FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
           WHERE rt.reservation_entry_id = $reservation_id
             AND rt.misc_charge_id = mc.id
             AND mc.misc_charge_type_id = mct.id
             AND mct.name = 'cancellation'";

$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
$row = mysql_fetch_array($result);
$cancel_id = $row['id'];

// create the room reservation transaction if not exist and if there is no cancellation.
//
if ( $rm_id != "" && $cancel_id == "" )
{
    $query = "SELECT id FROM reservation_transaction
               where reservation_entry_id = $reservation_id
                 and reservation_room_id = $rm_id";

    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    if (mysql_num_rows($result)==0)
    {
        $query = "INSERT INTO reservation_transaction (reservation_entry_id, reservation_room_id)
                      VALUES ($reservation_id, $rm_id)";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
    else
    {
        $room_trans_already_exist="YES";
    }
}

if ( $rm_id == "" )
{
    echo "No Room transaction found.";
}
else
{
    // get the room transaction details
    $query = "SELECT rt.id AS rt_id, re.room_id,
                     rt.id as room_id, room_name, room_charge_day, room_charge_night, room_charge_drummer, room_charge_employee_day, room_charge_employee_night,
                     re.start_time as start_time, re.end_time as end_time
                FROM reservation_entry re, reservation_transaction rt, reservation_room rm
               WHERE re.id = rt.reservation_entry_id
                 AND rm.id = rt.reservation_room_id
                 AND re.id =  $reservation_id";

    //echo $query;
    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $rt_id       = $row['rt_id'];
            $room_id    = $row['room_id'];
            $room_name = $row['room_name'];
            $room_charge_day = $row['room_charge_day'];
            $room_charge_night = $row['room_charge_night'];
            $start_time  = $row['start_time'];
            $end_time    = $row['end_time'];
            $duration_hour  = calc_utf_period_diff( $end_time, $start_time )/60;
            $str_start_time = strtotime( utf_period_2_date($start_time) );
            $str_end_time   = strtotime( utf_period_2_date($end_time) );
            $day_number     = date('N', $str_start_time);
            $start_hour     = date('H', $str_start_time);
            $end_hour       = date('H', $str_end_time);
            $room_charge_drummer = $row['room_charge_drummer'];
            $room_charge_employee_day = $row['room_charge_employee_day'];
            $room_charge_employee_night = $row['room_charge_employee_night'];

            //echo "start time: " . utf_period_2_date($start_time) . " end_time: " . utf_period_2_date($end_time) . "<br/>";

            //echo "day number: $day_number start hour: $start_hour <br/>";

            //TEST
            //echo "debug: day_number is manually set to 4<br/>";
            //$day_number = 4;

            // weekdays or weekend
            //
            if ( $day_number > 5 ) //weekend
            {
                //echo "weekend charge";
                $charge = $duration_hour * $room_charge_night;
                $duration_night = $duration_hour;
            }
            else // weekday
            {
                // start time after 5pm, use night charge
                if ( $start_hour >= 17 || ($start_hour >= 0 && $start_hour <= 2)  )
                {
                    //echo "weekday night charge";
                    $charge = $duration_hour * $room_charge_night;
                    $duration_night = $duration_hour;
                }
                else // start time before 5pm, use day charge
                {
                    if ( $end_hour <= 17 || ($end_hour >= 0 && $end_hour <= 2) ) //end time before 5 pm
                    {
                        //echo "weekday day charge";
                        $charge = $duration_hour * $room_charge_day;
                        $duration_day = $duration_hour;
                    }
                    else // mixed
                    {

                        $duration_day = 17 - $start_hour;

                        if ($end_hour == 0)
                        {
                            $end_hr = 24;
                        }
                        elseif ($end_hour == 1)
                        {
                            $end_hr = 25;
                        }
                        elseif ($end_hour == 2)
                        {
                            $end_hr = 26;
                        }
                        $duration_night = $end_hour - 17;


                        $charge = ($duration_day * $room_charge_day) + ($duration_night * $room_charge_night);

                        //echo "weekday day $room_charge_day and night charge $room_charge_night - dur day: $duration_day dur night: $duration_night <br/>";

                    }
                }
            }

            //DEBUG print to the box in invoice.php
            //echo "<br/><br/> <b> $room_name: $duration_hour hr  = $ $charge </b>";

            if ( $room_trans_already_exist != "YES")
            {
                // update the room reservation transaction
                $query = "UPDATE reservation_transaction SET amount = $charge
                           WHERE reservation_entry_id = $reservation_id
                             AND reservation_room_id = $rm_id";
                //echo $query;
                mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
            }
        }
    }



    if ( $room_mc_id != "")
    {
        //echo "<br/>duration day: $duration_day duration_night: $duration_night <br/>";

        // create discount transaction
        //create_disc_trans ( $reservation_id, $room_mc_id, $duration_hour, $room_charge_drummer, $room_charge_employee_day, $room_charge_employee_night );

        // get the name of misc charge
        //
        $query = "SELECT mc.name AS mc_name
                    FROM misc_charge mc
                   WHERE mc.id = $room_mc_id";

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        if (mysql_num_rows($result)>0)
        {
            $row = mysql_fetch_array($result);
            //echo "MC NAME: " . $row['mc_name'] . "<br/>";
            $mc_name = $row['mc_name'];
        }

        // if it's a drummer rate
        if ( $mc_name == "Drummer Rate" )
        {
            //echo "in drummer rate $duration_hour $room_charge_drummer";
            $disc_amt = $charge - ($duration_hour * $room_charge_drummer);
        }
        elseif ( $mc_name == "Employee Rate")
        {
            //echo "in employee rate";
            if ( $duration_day > 0 && $duration_night == 0 ) // day only
            {
                //create discount tran in the amount of $amount
                $disc_amt = $charge;
            }
            elseif ( $duration_day == 0 && $duration_night > 0 ) // night only
            {
                $disc_amt = $duration_night * $room_charge_employee_night;
            }
            elseif ( $duration_day > 0 && $duration_night > 0 )
            {
                $disc_amt = ($duration_day * $room_charge_day) + ( ($duration_night * $room_charge_night) -  ($duration_night * $room_charge_employee_night) );
            }
        }

        $disc_amt *= -1;
        //echo "<br/> DISC AMT: $disc_amt <br/>";
        //echo "<br/> AFTER DISC AMT: $charge - $disc_amt <br/>";


        // create the disc transaction
        //
        $query = "SELECT * from reservation_transaction
                  WHERE reservation_entry_id = $reservation_id
                    AND misc_charge_id = $room_mc_id";
        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        if (mysql_num_rows($result)>0)
        {
            $query = "UPDATE reservation_transaction SET amount = $disc_amt
                       WHERE reservation_entry_id = $reservation_id
                         AND misc_charge_id = $room_mc_id";

        }
        else
        {
            $query = "INSERT reservation_transaction (reservation_entry_id, misc_charge_id, amount)
                       VALUES ( $reservation_id, $room_mc_id, $disc_amt )";
        }

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        //echo "succesffully create/update misc charge<br/>";

    }


    if ( $rt_remove_id != "" && $rt_remove_id > 0 )
    {
        $query = "DELETE FROM reservation_transaction where id = $rt_remove_id";

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }

/* Moved to invoice_trans.php
    echo "<table border=1><tr><th>Room Number</th><th>Amount</th><th>Remove</th></tr>";
    $room_total_chg = 0;

    // Show all transaction related to this room
    $query = "SELECT rr.room_name, rt.amount
                FROM reservation_transaction rt, reservation_room rr
               WHERE rt.reservation_room_id = rr.id
                 AND rt.reservation_entry_id = $reservation_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            echo "<tr>";
            echo "<td>" . $row['room_name'] . "</td>";
            echo "<td>" . "$" . number_format($row['amount'],2) . "</td>";
            echo "</tr>";
            $room_total_chg += $row['amount'];
        }
    }
*/

    // Show all transaction with room id, misc charges of room rates
    $query = "SELECT mc.desc, rt.amount, rt.id AS rt_remove_id
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
                 AND mc.misc_charge_type_id = mct.id
                 AND rt.reservation_entry_id = $reservation_id
                 AND mct.name = 'room_rates'";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // if there is already a discount, do not show the apply discount
    // initialize to false
    $hide_apply_discount = 0;

    if (mysql_num_rows($result)>0)
    {
//        while($row = mysql_fetch_array($result))
//        {

//            echo "<tr>";
//            echo "<td>" . $row['desc'] . "</td>";
//            echo "<td>" . "$" . number_format($row['amount'],2) . "</td>";
//            echo "<td><a href=invoice.php?reservation_id=$reservation_id&rt_remove_id=$row[rt_remove_id]>Remove</a></td>";
//            echo "</tr>";
//            $room_total_chg += $row['amount'];
//        }
        $hide_apply_discount = 1; //set to true
    }

//    echo "<tr><td>Total:</td><td>$" . number_format($room_total_chg,2) . "</td></tr>";
//    echo "</table>";


    ?>

    <form onsubmit="invoice.php">

        <?php
    if ( ! $hide_apply_discount && $cancel_id == "")
    {
        $query = "SELECT mc.id AS room_mc_id, mc.name AS mc_name, mc.desc AS mc_desc
                    FROM misc_charge mc, misc_charge_type mct
                   WHERE mc.misc_charge_type_id = mct.id
                     AND mct.name = 'room_rates'";

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        if (mysql_num_rows($result)>0)
        {
            while($row = mysql_fetch_array($result))
            {
                //$checked = ($row['mc_desc'] == "Standard Rate") ? "CHECKED" : "";
        ?>
                <input type="radio" name="room_mc_id" value="<?php echo $row['room_mc_id'] ?>" <?php echo $checked ?> /> <?php echo $row['mc_desc']?>
                <br/>
        <?php
            }
        ?>
                <input type=hidden name=reservation_id value=<?php echo $reservation_id?>>
                <br/>
                <center><input type=submit name="apply_room_rate" value="Apply"></center>
<?php

        }
    }
    elseif ( $hide_apply_discount )
    {
        echo "<div id=warn_msg>";
        echo "Discount already applied. ";
        echo "To choose a different discount, ";
        echo "remove discount from invoice. ";
        echo "</div>";
    }
    elseif ( $cancel_id != "" )
    {
        echo "None";
    }
?>

    </form>
<?php
}
?>