<?php
/*
 * bar_charge/add_to_reservation.php
 * Allow user to add/edit/view bar charges associated with the reservation
 * Called from reservation/view_entry.php
 *
 * 20090401 wilson: initial
 * 20090418 wilson: add number format
 */

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include ("../includes/dbconnect.inc");
?>

<html>
<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php include ("../includes/header.inc") ?>

<br>
<div id="page" align="left">

<?php

// Get the query string variables
$reservation_id = $_GET['id'];
$bc_id  = $_GET['bc_id'];
$bc_amt = $_GET['bc_amt'];
$action = $_GET['action'];

// Die if no reservation id
if ( ! isset($reservation_id) )
{
    die ("ERROR: Missing Reservation ID");
}

// if user has selected a bar charge item, create reservation transaction
if ( isset($reservation_id) && isset($bc_id) && isset($bc_amt) )
{
    create_bar_charge_trans ( $reservation_id, $bc_id, $bc_amt );
}

// if action is remove
if ( $action == "remove" && isset($reservation_id) && isset($bc_id) )
{
    remove_bar_charge_trans ( $reservation_id, $bc_id );
}

?>

<div id="bc_main">
  <table width="50%" border=1>
  <tr valign="top">
  <td width="50%">
    <div id="bc_items">
        <?php
          // show all active bar charges items for user to add
          show_bar_charge_items($reservation_id);
        ?>
    </div>
    <br>
  </td>
  <td width="50%">
    <div id="bc_trans">
          <?php
            // show bar charges cart containing
            // the current bar charges associated with this reservation
            show_bar_charge_trans($reservation_id);
          ?>
    </div>
    <br>
  </td>
  </tr>
  <tr>
    <td colspan=2>
        <div id="bc_navigation">
        <center>
        <a href="../reservation/view_entry.php?id=<?php echo $reservation_id ?>">Done</a>
        </center>
    </div>
    </td>
  </tr>
  </table>
</div>
</div>
</body>
</html>

<?php
// FUNCTIONS definition
//
function create_bar_charge_trans ( $reservation_id, $bc_id, $bc_amt )
{
    $query = "SELECT id, qty, amount
                FROM reservation_transaction
               WHERE reservation_entry_id = $reservation_id
                 AND bar_charge_id = $bc_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    $row = mysql_fetch_array($result);

    // if transaction already exist, update the transaction
    if ( isset($row[id]) )
    {
        $new_qty = $row[qty] + 1;
        $new_amt = $row[amount] + $bc_amt;

        $query = "UPDATE reservation_transaction
                     SET qty = $new_qty, amount = $new_amt
                   WHERE id = $row[id]";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
    // if not exist, create new transaction
    else
    {
        $query = "INSERT INTO reservation_transaction (reservation_entry_id, bar_charge_id, qty, amount)
                       VALUES ($reservation_id, $bc_id, 1, $bc_amt )";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
}

function remove_bar_charge_trans ( $reservation_id, $bc_id )
{
    $query = "DELETE FROM reservation_transaction
                    WHERE reservation_entry_id = $reservation_id
                      AND bar_charge_id = $bc_id";

    // die and show mysql error number and messages, if there is any error with query
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

}
function show_bar_charge_trans ( $reservation_id )
{
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

        echo "<table border=1 align=center><caption><h3>Bar Charge Cart</h3></caption>";
        while($row = mysql_fetch_array($result))
        {
            $row_rt_id    = $row['rt_id'];
            $row_bc_id    = $row['bc_id'];
            $row_rt_amt   = $row['rt_amt'];
            $row_rt_qty   = $row['rt_qty'];
            $row_bc_name  = $row['bc_name'];
            $row_bc_amt   = $row['bc_amt'];
            $tot_rt_amt   = $tot_rt_amt + $row_rt_amt;
            
            echo "<tr>";
            //echo "<td>$row_rt_id</td>";
            //echo "<td>$row_bc_id</td>";
            //echo "<td>$row_rt_qty</td>";
            echo "<td>$row_bc_name x $row_rt_qty </td>";
            echo "<td>$" . number_format($row_rt_amt,2) . "</td>";
            echo "<td><a href='add_to_reservation.php?action=remove&id=$reservation_id&bc_id=$row_bc_id'>remove</a></td>";
            //echo "<td>$row_bc_amt</td>";
            echo "</tr>";
        }
        echo "<tr><td><b>Total</b></td><td>";
        echo "$" . number_format($tot_rt_amt,2);
        echo "</td></tr></b>";
        echo "</table>";
    }
    else
    {
         echo "No bar charges for this reservation";
    }
}

function show_bar_charge_items( $reservation_id )
{
    // get the list of bar charges
    $query = "SELECT id, name, amount
              FROM bar_charge
              WHERE is_active = 1
              ORDER BY name";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
        echo "<table border=1 align=center><caption><h3>Bar Charge Items</h3></caption>";
        while($row = mysql_fetch_array($result))
        {
    ?>
            <tr>
            <form method="GET" onsubmit="add_to_reservation.php">
              <td><label><? echo $row[name] ?></label></td>
              <td><label><? echo '$' . number_format($row[amount],2) ?></label></td>
              <input type=hidden name=id value="<? echo $reservation_id ?>">
              <input type=hidden name=bc_id value="<? echo $row[id] ?>">
              <input type=hidden name=bc_amt value="<? echo $row[amount] ?>">
              <td><input type=submit value="Add"></td>
            </form>
            </tr>
    <?php
        }
        echo "</table>";
    }
    else
    {
         echo "No bar charges available.";
    }
}
?>