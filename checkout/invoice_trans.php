<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * invoice_trans.php
 *  Show all invoice trans in tabular format, except for payments.
 *
 * 2009-04-17 wilson: initial. Moved all trans display from other invoice php pages to this page.
 */

echo "<table class=invoice_trans>";
echo "<tr>";
echo "<th class=invoice_type>Type</th>";
echo "<th class=invoice_desc>Description</th>";
echo "<th class=invoice_amt>Amount ($)</th>";
echo "<th class=invoice_action>Action</th>";
echo "</tr>";

/* Display Room Charge */
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
            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Room Charge</td>";
            echo "<td class=invoice_desc>" . $row['room_name'] . "</td>";
            echo "<td class=invoice_amt>" . number_format($row['amount'],2) . "</td>";
            echo "<td class=invoice_action>&nbsp;</td>";
            echo "</tr>";
        }
    }

/* Display Room Discount */
    // Show all transaction with room id, misc charges of room rates
    $query = "SELECT mc.desc, rt.amount, rt.id AS rt_remove_id
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
                 AND mc.misc_charge_type_id = mct.id
                 AND rt.reservation_entry_id = $reservation_id
                 AND mct.name = 'room_rates'";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Room Discount</td>";
            echo "<td class=invoice_desc>" . $row['desc'] . "</td>";
            echo "<td class=invoice_amt_neg>" . number_format($row['amount'],2) . "</td>";
            echo "<td class=invoice_action><a href=invoice.php?reservation_id=$reservation_id&rt_remove_id=$row[rt_remove_id]>Remove</a></td>";
            echo "</tr>";
        }
    }

/* Display Equipment transactions*/

    $query = "SELECT eq.equip_description AS equip_desc, rt.amount AS equip_amount
                FROM equipment eq, reservation_transaction rt
               WHERE rt.equipment_id = eq.id
                 AND rt.reservation_entry_id = $reservation_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $equip_desc = $row['equip_desc'];
            $equip_amount = $row['equip_amount'];

                echo "<tr class=invoice_trans>";
                echo "<td class=invoice_type>Equipment Charge</td>";
                echo "<td class=invoice_desc>$equip_desc</td>";
                echo "<td class=invoice_amt>" . number_format($equip_amount,2) . "</td>";
                echo "<td class=invoice_action>&nbsp;</td>";
                echo "</tr>";
        }
    }
/*
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
                 AND re.is_cancelled != 1";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $rt_id       = $row['rt_id'];
            $equip_id    = $row['equip_id'];
            $equip_desc  = $row['equip_desc'];
            $start_time  = $row['start_time'];
            $end_time    = $row['end_time'];
            $start_day_of_year = $row['start_day_of_year'];
            $end_day_of_year   = $row['end_day_of_year'];

            // Hourly rental
            if ( $room_id > 0 )
            {
                $duration    = (calc_utf_period_diff($end_time, $start_time) ) / 60;
                $hourly_rate = $row['rental_price_per_hr'];
                $charge      = $duration * $hourly_rate;
                echo "<tr class=invoice_trans>";
                echo "<td class=invoice_type>Equipment Charge</td>";
                echo "<td class=invoice_desc>$equip_desc - $$hourly_rate/hr</td>";
                echo "<td class=invoice_amt>" . number_format($charge,2) . "</td>";
                echo "<td class=invoice_action>&nbsp;</td>";
                echo "</tr>";
            }
            // Daily rental
            else
            {
                //echo "in else end: $end_day_of_year start: $start_day_of_year <br/>";
                $duration    = $end_day_of_year - $start_day_of_year;
                $duration    = $duration == 0 ? 1 : $duration;
                $daily_rate  = $row['rental_price_per_day'];
                $charge      = $duration * $daily_rate;
                //echo $equip_desc . " " . $duration . " day x $" . $daily_rate . "/day = $" . number_format($charge,2) . "<br/>";
                echo "<tr class=invoice_trans>";
                echo "<td class=invoice_type>Equipment Charge</td>";
                echo "<td class=invoice_desc>$equip_desc - $$daily_rate/day</td>";
                echo "<td class=invoice_amt>" . number_format($charge,2) . "</td>";
                echo "<td class=invoice_action>&nbsp;</td>";
                echo "</tr>";
            }
        }
    }
*/

/* Display bar charge transactions */
    // Query bar charge transactions for this reservation id
    $query = "SELECT rt.id AS rt_id, rt.bar_charge_id AS bc_id, rt.amount AS rt_amt, rt.qty AS rt_qty,
                     bc.name AS bc_name, bc.amount AS bc_amt
                FROM reservation_transaction rt, bar_charge bc
               WHERE rt.bar_charge_id = bc.id
                 AND rt.reservation_entry_id = $reservation_id
                 AND rt.bar_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all bar charges associated with this reservation
    if (mysql_num_rows($result)>0)
    {
        //initialize total field
        $tot_rt_amt = 0;

        while($row = mysql_fetch_array($result))
        {
            $row_rt_id    = $row['rt_id'];
            $row_bc_id    = $row['bc_id'];
            $row_rt_amt   = $row['rt_amt'];
            $row_rt_qty   = $row['rt_qty'];
            $row_bc_name  = $row['bc_name'];
            $row_bc_amt   = $row['bc_amt'];
            $tot_rt_amt   = $tot_rt_amt + $row_rt_amt;

            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Bar Charge</td>";
            echo "<td class=invoice_desc>$row_bc_name x $row_rt_qty </td>";
            echo "<td class=invoice_amt>" . number_format($row_rt_amt,2) . "</td>";
            echo "<td class=invoice_action><a href='$refresh_page?reservation_id=$reservation_id&action=remove&bc_id=$row_bc_id'>Remove</a></td>";
            echo "</tr>";
        }
    }


/* Display Misc Charge - Cancellation transactions less than 24 hours */

    // Query less than 24 hour cancellation transactions for this reservation id
    $query = "SELECT rt.id AS rt_id, rt.misc_charge_id AS mc_id, rt.amount AS less_24_amt, mct.name as mct_name,
                mc.name as mc_name, rt.updated_at as less_24_date, rt.comment as less_24_comment
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
               AND mct.id = mc.misc_charge_type_id
                AND mct.name = 'cancellation'
                AND mc.name = 'Less than 24 hours'
                 AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all less than 24 hour cancellations for this reeservation
    if (mysql_num_rows($result)>0)
    {
        //initialize amount
        $row_less_24_amt = 0;

        while($row = mysql_fetch_array($result))
        {
            $row_l24_rt_id    = $row['rt_id'];
            $row_l24_mc_id    = $row['mc_id'];
            $row_less_24_amt   = $row['less_24_amt'];
            $row_l24_mc_name  = $row['mc_name'];
            $row_l24_mct_name   = $row['mct_name'];
            $row_less_24_date = $row['less_24_date'];
            $row_less_24_comment = $row['less_24_comment'];

            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Misc Charge</td>";
            echo "<td class=invoice_desc>$row_l24_mct_name - $row_l24_mc_name</td>";
            echo "<td class=invoice_amt>" . number_format($row_less_24_amt,2) . "</td>";
            echo "<td class=invoice_action>&nbsp;</td>";
            echo "</tr>";
        }

    }

/* Display Misc Charge - Cancellation transactions more than 24 hours */

    // Query less than 24 hour cancellation transactions for this reservation id
    $query = "SELECT rt.id AS rt_id, rt.misc_charge_id AS mc_id, rt.amount AS less_24_amt, mct.name as mct_name,
                mc.name as mc_name, rt.updated_at as less_24_date, rt.comment as less_24_comment
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
               AND mct.id = mc.misc_charge_type_id
                AND mct.name = 'cancellation'
                AND mc.name = 'More than 24 hours'
                 AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all less than 24 hour cancellations for this reeservation
    if (mysql_num_rows($result)>0)
    {
        //initialize amount
        $row_less_24_amt = 0;

        while($row = mysql_fetch_array($result))
        {
            $row_l24_rt_id    = $row['rt_id'];
            $row_l24_mc_id    = $row['mc_id'];
            $row_less_24_amt   = $row['less_24_amt'];
            $row_l24_mc_name  = $row['mc_name'];
            $row_l24_mct_name   = $row['mct_name'];
            $row_less_24_date = $row['less_24_date'];
            $row_less_24_comment = $row['less_24_comment'];

            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Misc Charge</td>";
            echo "<td class=invoice_desc>$row_l24_mct_name - $row_l24_mc_name</td>";
            echo "<td class=invoice_amt>" . number_format($row_less_24_amt,2) . "</td>";
            echo "<td class=invoice_action>&nbsp;</td>";
            echo "</tr>";
        }

    }

/* Display Misc Charge - No Show */
    // Query less than 24 hour cancellation transactions for this reservation id
    $query = "SELECT rt.id AS rt_id, rt.misc_charge_id AS mc_id, rt.amount AS no_show_amt, mct.name as mct_name,
                mc.name as mc_name, rt.updated_at as no_show_date, rt.comment as no_show_comment
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
               AND mct.id = mc.misc_charge_type_id
                AND mct.name = 'cancellation'
                AND mc.name = 'No Show'
                 AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all less than 24 hour cancellations for this reeservation
    if (mysql_num_rows($result)>0)
    {
        //initialize amount
        $row_no_show_amt = 0;

        //echo "<table>";
        while($row = mysql_fetch_array($result))
        {
            $row_ns_rt_id    = $row['rt_id'];
            $row_ns_mc_id    = $row['mc_id'];
            $row_no_show_amt   = $row['no_show_amt'];
            $row_ns_mc_name  = $row['mc_name'];
            $row_ns_mct_name   = $row['mct_name'];
            $row_no_show_date = $row['no_show_date'];
            $row_no_show_comment = $row['no_show_comment'];


            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Misc Charge</td>";
            echo "<td class=invoice_desc>$row_ns_mct_name - $row_ns_mc_name</td>";
            echo "<td class=invoice_amt>" . number_format($row_no_show_amt,2) . "</td>";
            echo "<td class=invoice_action>&nbsp;</td>";
           //a person cannot delete a cancellation transaction
           //echo "<td><a href='invoice.php?action=remove&id=$reservation_id&rt_id=$row_ns_rt_id'>remove</a></td>";
            echo "</tr>";
        }
    }


/* Display Misc Charge - Coupon transactions */

    // Query less than coupon transactions for this reservation id
    $query = "SELECT rt.id AS coupon_rt_id, rt.misc_charge_id AS coupon_mc_id,
                rt.amount AS coupon_amt, mct.name as coupon_mct_name, mc.name as coupon_mc_name,
                rt.comment as coupon_comments, rt.updated_at as coupon_date
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
               AND mct.id = mc.misc_charge_type_id
                AND mct.name = 'Discount'
                AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all less than 24 hour cancellations for this reeservation
    if (mysql_num_rows($result)>0)
    {
        //initialize amount
        $row_coupon_amt = 0;

       //echo "<table border=1 align=center><caption><h3>Misc Charge Cart</h3></caption>";
        while($row = mysql_fetch_array($result))
        {
            $row_coupon_rt_id    = $row['coupon_rt_id'];
            $row_coupon_mc_id    = $row['coupon_mc_id'];
            $coupon_amt   = $row['coupon_amt'];
            $row_coupon_mct_name  = $row['coupon_mct_name'];
            $coupon_comments   = $row['coupon_comments'];
            $row_coupon_date   = $row['coupon_date'];
            $row_coupon_mc_name = $row['coupon_mc_name'];


            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Misc Discount</td>";
            echo "<td class=invoice_desc>$row_coupon_mct_name - $row_coupon_mc_name - $coupon_comments</td>";
            echo "<td class=invoice_amt_neg>" . number_format($coupon_amt,2) . "</td>";
            echo "<td class=invoice_action><a href='invoice.php?reservation_id=$reservation_id&action=remove_coupon&id=$reservation_id&rt_id=$row_coupon_rt_id'>Remove</a></td>";
            echo "</tr>";
        }
    }


/* Display Waiver transactions */
    // Query less than coupon transactions for this reservation id
    $query = "SELECT rt.id AS waiver_rt_id, rt.misc_charge_id AS waiver_mc_id,
                rt.amount AS waiver_amt, mct.name as waiver_mct_name, mc.name as waiver_mc_name,
                rt.comment as waiver_comments, rt.updated_at as waiver_date
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
                AND mct.id = mc.misc_charge_type_id
                AND mct.name = 'Fee Waiver'
                AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all less than 24 hour cancellations for this reeservation
    if (mysql_num_rows($result)>0)
    {
        //initialize amount
        $row_coupon_amt = 0;

       //echo "<table border=1 align=center><caption><h3>Misc Charge Cart</h3></caption>";
        while($row = mysql_fetch_array($result))
        {
            $row_waiver_rt_id    = $row['waiver_rt_id'];
            $row_waiver_mc_id    = $row['waiver_mc_id'];
            $row_waiver_amt   = $row['waiver_amt'];
            $row_waiver_mct_name  = $row['waiver_mct_name'];
		$row_waiver_initials = $row['waiver_initials'];
            $row_waiver_comment   = $row['waiver_comments'];
            $row_waiver_date   = $row['waiver_date'];
            $row_waiver_mc_name = $row['waiver_mc_name'];


            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Misc Waiver</td>";
            echo "<td class=invoice_desc>$row_waiver_mct_name - $row_waiver_mc_name - $row_waiver_initials - $row_waiver_comment</td>";
            echo "<td class=invoice_amt_neg>" . number_format($row_waiver_amt,2) . "</td>";
            echo "<td class=invoice_action><a href='invoice.php?reservation_id=$reservation_id&action=remove_waiver&id=$reservation_id&rt_id=$row_waiver_rt_id'>Remove</a></td>";
            echo "</tr>";
        }
    }



/* Display Misc Equipment charges*/
    // Query less than coupon transactions for this reservation id
    $query = "SELECT rt.id AS mce_rt_id, rt.misc_charge_id AS mce_mc_id,
                rt.amount AS equip_charge_amt, mct.name as mce_mct_name, mc.name as mce_mc_name,
                rt.comment as equip_charge_comments, rt.updated_at as equip_charge_date
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
               AND mct.id = mc.misc_charge_type_id
                AND mct.name = 'Equipment Charge'
                AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all less than 24 hour cancellations for this reeservation
    if (mysql_num_rows($result)>0)
    {
        //initialize amount
        $row_equip_charge_amt = 0;

       //echo "<table border=1 align=center><caption><h3>Misc Charge Cart</h3></caption>";
        while($row = mysql_fetch_array($result))
        {
            $row_mce_rt_id    = $row['mce_rt_id'];
            $row_mce_mc_id    = $row['mce_mc_id'];
            $equip_charge_amt   = $row['equip_charge_amt'];
            $row_mce_mct_name  = $row['mce_mct_name'];
            $equip_charge_comments   = $row['equip_charge_comments'];
            $row_equip_charge_date   = $row['equip_charge_date'];
            $row_mce_mc_name = $row['mce_mc_name'];


            echo "<tr class=invoice_trans>";
            echo "<td class=invoice_type>Misc Charge</td>";
            echo "<td class=invoice_desc> $row_mce_mct_name - $row_mce_mc_name - $equip_charge_comments</td>";
            echo "<td class=invoice_amt>" . number_format($equip_charge_amt,2) . "</td>";
            echo "<td class=invoice_action><a href='invoice.php?reservation_id=$reservation_id&action=remove_equip_charge&id=$reservation_id&rt_id=$row_mce_rt_id'>Remove</a></td>";
            echo "</tr>";
        }
    }




echo "</table>"; //end of invoice trans table.
?>
