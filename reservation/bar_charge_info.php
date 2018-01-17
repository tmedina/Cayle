<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * Allow user to add/edit/view bar charges associated with the reservation
 *
 * 20090417 wilson: moved transaction display to invoice_trans.php
 * 20090403 wilson: initial
 */

// Get the query string variables
$id = $_GET['id'] == "" ? $id : $_GET['id'];
$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];
$bc_id  = $_GET['bc_id'];
$bc_qty = $_GET['bc_qty'];
$action = $_GET['action'];
//echo "Res: $id Ref page: $refresh_page <br/>";

// if user has selected a bar charge item, create reservation transaction
if ( $action == "add" )
{
    create_bar_charge_trans ( $id, $bc_id, $bc_qty );
}

// if action is remove
if ( $action == "remove" && isset($id) && isset($bc_id) )
{
    remove_bar_charge_trans ( $id, $bc_id );
}
?>

<div id="bc_main">
    <div id="bc_items">
        <?php
          // show all active bar charges items for user to add only if the item has not been checked out
          if ( ! isset($actual_end_time) )
          {
            show_bar_charge_items($id);
          }
        ?>
    </div>
    <div id="bc_trans">
          <?php
            // show bar charges cart containing
            // the current bar charges associated with this reservation
            show_bar_charge_trans($id);  //moved to invoice_trans.php
          ?>
    </div>
</div>

<?php
// FUNCTIONS definition
//
function create_bar_charge_trans ( $id, $bc_id, $bc_qty )
{
    //echo "Res ID: $id bc_id: $bc_id bc_qty: $bc_qty";
    //exit (0);

    if ($bc_id == "")
    {
        echo "<div id=err_msg>Error: Please select a bar charge item.</div>";
        return (1);
    }
    elseif ( $bc_qty == "" )
    {
        echo "<div id=err_msg>Error: Please enter quantity.</div>";
        return (1);
    }

    $query = "SELECT id, qty, amount
                FROM reservation_transaction
               WHERE reservation_entry_id = $id
                 AND bar_charge_id = $bc_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    $row = mysql_fetch_array($result);

    // if transaction already exist, update the transaction
    if ( isset($row[id]) )
    {
        $new_qty = $row[qty] + $bc_qty;
        $new_amt = $row[amount] + $bc_amt;

        $query = "UPDATE reservation_transaction
                     SET qty = $new_qty, amount = $row[amount] + (SELECT bc.amount * $bc_qty FROM bar_charge bc WHERE id = $bc_id)
                   WHERE id = $row[id]";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
    // if not exist, create new transaction
    else
    {
        $query = "INSERT INTO reservation_transaction (reservation_entry_id, bar_charge_id, qty, amount)
                       VALUES ($id, $bc_id, $bc_qty,
                                (SELECT bc.amount * $bc_qty FROM bar_charge bc WHERE id = $bc_id)
                              )";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
}

function remove_bar_charge_trans ( $id, $bc_id )
{
    $query = "DELETE FROM reservation_transaction
                    WHERE reservation_entry_id = $id
                      AND bar_charge_id = $bc_id";

    // die and show mysql error number and messages, if there is any error with query
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}

function show_bar_charge_trans ( $id )
{
    // Query bar charge transactions for this reservation id
    $query = "SELECT rt.id AS rt_id, rt.bar_charge_id AS bc_id, rt.amount AS rt_amt, rt.qty AS rt_qty,
                     bc.name AS bc_name, bc.amount AS bc_amt
                FROM reservation_transaction rt, bar_charge bc
               WHERE rt.bar_charge_id = bc.id
                 AND rt.reservation_entry_id = $id
                 AND rt.bar_charge_id > 0
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all bar charges associated with this reservation
    if (mysql_num_rows($result)>0)
    {
        //initialize total field
        $tot_rt_amt = 0;

        echo "<table width=100% border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tr><th align=\"left\">Item x Qty</th><th align=\"left\">Amount</th><th align=\"left\">Action</th></tr>";
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
            echo "<td><a href='$refresh_page?id=$id&action=remove&bc_id=$row_bc_id'>Remove</a></td>";
            //echo "<td>$row_bc_amt</td>";
            echo "</tr>";
        }
        echo "<tr><td colspan=\"3\"><hr></td>";
        echo "<tr><td><b>Total:</b></td><td>";
        echo "$". number_format($tot_rt_amt,2);
        echo "</td><td></td></tr></b>";
        echo "</table>";
    }
}

function show_bar_charge_items( $id )
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
    ?>
        <center>
        <form method="GET" onsubmit="<?php echo $refresh_page ?>">
            <input type=hidden name=id value="<? echo $id ?>">
            <input type=hidden name=action value="add">
            <select id="bc_id" name="bc_id">
              <option value="">--select a bar charge--
    <?php
            while($row = mysql_fetch_array($result))
            {
    ?>
              <option value="<? echo $row[id] ?>"> <? echo $row[name] . " ($" . $row[amount] . ")"; ?><br/>
<?php
            } // end while
?>
            </select>
            Qty: <input type=text name=bc_qty size=1 value=1>
            <input type=submit value="Add"><br><br>
        </form>
        </center>
<?php
    }
    else
    {
         echo "No bar charges available.";
    }
}
?>