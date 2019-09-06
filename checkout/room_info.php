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
    $charge = calc_room_charge($reservation_id);

    if ( $room_trans_already_exist != "YES")
    {
        // update the room reservation transaction
        $query = "UPDATE reservation_transaction SET amount = $charge
                  WHERE reservation_entry_id = $reservation_id
                  AND reservation_room_id = $rm_id";
        //echo $query;
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
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

        $discounted_charge = $charge;
        // if it's a drummer rate
        if ( $mc_name == "Drummer Rate" )
        {
            $discounted_charge = calc_room_charge($reservation_id, 1);
            $disc_amt = ($charge - $discounted_charge) * -1;
        }
        elseif ( $mc_name == "Employee Rate")
        {
            $discounted_charge = calc_room_charge($reservation_id, 2);
            $disc_amt = ($charge - $discounted_charge) * -1;
            //echo "discounted charge:".$discounted_charge."<br/>";
        }

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
        //echo "Removed discount";
        $query = "DELETE FROM reservation_transaction where id = $rt_remove_id";

        // die and show mysql error number and messages, if there is any error with query
        $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }

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
