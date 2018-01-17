<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * 20090417 wilson: moved transaction display to invoice_trans.php
 */


//if equipment transaction amounts have been set, set the already charged flag
//
$query = "SELECT sum(amount) as equip_sum_amt
            FROM reservation_transaction
           WHERE reservation_entry_id = $reservation_id
             AND equipment_id > 0";
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
$equip_trans_already_exist = "NO";
if (mysql_num_rows($result)>0)
{
    $row = mysql_fetch_array($result);
    $equip_sum_amt = $row['equip_sum_amt'];
    if ( $equip_sum_amt > 0 )
    {
        $equip_trans_already_exist = "YES";
    }
}

//see if this is equipment only reservation or equipment with room reservation
//
$query = "SELECT room_id FROM reservation_entry WHERE id = $reservation_id";
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
$room_id = 0;
if (mysql_num_rows($result)>0)
{
    $row = mysql_fetch_array($result);
    $room_id = $row['room_id'];
}

//get all equipments associated with this reservation
//
$query = "SELECT rt.id AS rt_id, eq.equip_description AS equip_desc,
                 eq.id as equip_id, eq.rental_price_per_hr, eq.rental_price_per_day,
                 re.start_time as start_time, re.end_time as end_time,
                 from_unixtime(re.actual_start_time,'%j') AS start_day_of_year, from_unixtime(re.actual_end_time,'%j') AS end_day_of_year
            FROM reservation_entry re, reservation_transaction rt, equipment eq
           WHERE re.id = rt.reservation_entry_id
             AND eq.id = rt.equipment_id
             AND re.id = $reservation_id
             and re.is_cancelled != 1";

// die and show mysql error number and messages, if there is any error with query
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

echo "<b>Equipment Rentals:</b><br/>";

if (mysql_num_rows($result)>0)
{
    $total_charge = 0;

    while($row = mysql_fetch_array($result))
    {
        $rt_id       = $row['rt_id'];
        $equip_id    = $row['equip_id'];
        $equip_desc  = $row['equip_desc'];
        $start_time  = $row['start_time'];
        $end_time    = $row['end_time'];
        $start_day_of_year = $row['start_day_of_year'];
        $end_day_of_year   = $row['end_day_of_year'];

        echo $equip_desc . "<br/>";
        
        // Hourly rental
        if ( $room_id > 0 )
        {
            $duration    = (calc_utf_period_diff($end_time, $start_time) ) / 60;
            $hourly_rate = $row['rental_price_per_hr'];
            $charge      = $duration * $hourly_rate;
            //moved to invoice_trans.php echo $equip_desc . " " . $duration . " hr x $" . $hourly_rate . "/hr = $" . number_format($charge,2) . "<br/>";

        }
        // Daily rental
        else
        {
            //echo "in else end: $end_day_of_year start: $start_day_of_year <br/>";
            $duration    = $end_day_of_year - $start_day_of_year;
            $duration    = $duration == 0 ? 1 : $duration;
            $daily_rate  = $row['rental_price_per_day'];
            $charge      = $duration * $daily_rate;
            //moved to invoice_trans.php echo $equip_desc . " " . $duration . " day x $" . $daily_rate . "/day = $" . number_format($charge,2) . "<br/>";

        }
        $total_charge += $charge;

        if ( $equip_trans_already_exist != "YES")
        {
            $sql = "UPDATE reservation_transaction
                       SET amount = $charge
                     WHERE id = $rt_id";

            mysql_query($sql) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        }
    }
    //echo "<b>Total Equipment Charges: $" . number_format($total_charge,2) . "</b>";
}
else
{
     echo "None";
}
?>