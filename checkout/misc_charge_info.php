<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * checkout/misc_charge_info.php
 * Allow user to add/edit coupon and waivers
 * and see cancellations associated with each reservation
 * and assess charges for damaged equipment if necessary
 * user must enter comments for any transaction
 *
 * 20090417 wilson: moved all transaction display to invoice_trans.php
 * 20090404 beth
 */

// Get the query string variables
$reservation_id = $_GET['reservation_id'] == "" ? $reservation_id : $_GET['reservation_id'];
$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];
//coupon misc charge id
$mc_id  = $_GET['mc_id'];
//waiver misc id
$mcw_id = $_GET['mcw_id'];
//equipment charge misc id
$mce_id = $_GET['mce_id'];
$coupon_amt = $_GET['coupon_amt'];
$waiver_amt = $_GET['waiver_amt'];
$equip_charge_amt = $_GET['equip_charge_amt'];
$equip_charge_comments = $_GET['equip_charge_comments'];
$coupon_initials = $_GET['coupon_initials'];
$waiver_initials = $_GET['waiver_initials'];
$coupon_comments = $_GET['coupon_comments'];
$waiver_comments = $_GET['waiver_comments'];
$action = $_GET['action'];
$rt_id = $_GET['rt_id'];

//echo "Action: $action rt_id= $rt_id <br/>";

// Die if no reservation id
if ( ! isset($reservation_id) )
{
    die ("ERROR: Missing Reservation ID");
}
// create the coupon transaction if an amount and comment has been entered
if ( $action == "add_coupon" && isset ($reservation_id) && isset($coupon_amt) && isset($coupon_comments))
{
    create_coupon_trans ( $reservation_id, $mc_id, $coupon_amt, $coupon_comments );
}
// create the waiver transaction if an amount and comment has been entered
if ($action == "add_waiver" && isset ($reservation_id) && isset($waiver_amt) && isset($waiver_comments))
{
    create_waiver_trans ( $reservation_id, $mcw_id, $waiver_amt, $waiver_comments );
}
// add a charge for broken equipment if an amount and comment has been entered
if ($action == "add_equip_charge" && isset ($reservation_id) && isset($equip_charge_amt) && isset($equip_charge_comments))
{
    create_equip_charge_trans ( $reservation_id, $mce_id, $equip_charge_amt, $equip_charge_comments );
}
// if action is remove
if ( $action == "remove_coupon" && isset($reservation_id) && isset($rt_id) )
{
    //echo "in remove coupon <br/>";
    remove_coupon_trans ( $reservation_id, $rt_id );
}
// if action is remove
if ( $action == "remove_waiver" && isset($reservation_id) && isset($rt_id) )
{
    remove_waiver_trans ( $reservation_id, $rt_id );
}
// if action is remove
if ( $action == "remove_equip_charge" && isset($reservation_id) && isset($rt_id) )
{
    remove_equip_charge_trans ( $reservation_id, $rt_id );
}

?>

<form>
<div>
<table cellpadding="3" cellspacing="0">
<tr>
    <div id="rt_main">
    <div id="rt_items">
        <?php
          // show all coupon items for user to add
          show_coupon_list($reservation_id);
        ?>
    </div>
    </div>
    </tr>


    <tr>
    <div>
    <div id="rt_trans">
          <?php
            // show waiver items for user to add
            show_waiver_list ($reservation_id);
          ?>
    </div>
    </div>
    </tr>

    <tr>
    <div id="rt_main">
    <div id="rt_items">
        <?php
          // show all equipment charge items for user to add
          show_equip_charge_list($reservation_id);
        ?>
    </div>
    </div>
    </tr>
</table>

<!-- 20090417 wilson: moved to invoice_trans.php
  <table border="1" width="80%">

  <tr><th>Charge Type</th><th>Date</th><th>Comment</th><th>Amount</th><th>Remove</th></tr>
  <tr>
  <td>
    <div id="mc_items">
        <?php
          // show all charges already created by the system for
          //cancellations less than 24 hours in advance
          display_less_24_info($reservation_id);
        ?>
    </div>
  </td>
  <td>
    <div id="mc_items">
          <?php
            // show all charges already created by the system for
            //cancellations when the customer just doesnt show up
            display_no_show_info($reservation_id);
          ?>
    </div>
  </td>
  <td>
    <div id="mc_items">
          <?php
            // display each coupon entered
            display_coupon_amt($reservation_id);
          ?>
    </div>
  </td>
  <td>
    <div id="mc_items">
          <?php
            // display each waiver entered
            display_waiver_amt($reservation_id);
          ?>
    </div>
  </td>
  <td>
    <div id="mc_items">
          <?php
            // display each equipment charge entered
            display_equip_charge_amt($reservation_id);
          ?>
    </div>
  </td>


</tr>
<br /><br />
</table>
end of 20090417 wilson: moved to invoice_trans.php-->

</div>
</form>

<?php
// FUNCTIONS definition
//
//create the equip_charge transaction when button is clicked
function create_coupon_trans ( $reservation_id, $mc_id, $coupon_amt, $coupon_comments )
{
    //echo "in create coupon";
    //echo "resid: $reservation_id coupon id: $mc_id amt: $coupon_amt comm: $coupon_comments";

    if ($mc_id == "")
    {
        echo "<div id=err_msg>Error: Please select a coupon.</div>";
        return (1);
    }
    elseif ( $coupon_amt == "" )
    {
        echo "<div id=err_msg>Error: Please enter amount.</div>";
        return (1);
    }
    elseif ( $coupon_comments == "")
    {
        echo "<div id=err_msg>Error: Please enter comment.</div>";
        return(1);
    }

    $query = "INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount, comment)
                   VALUES ($reservation_id, $mc_id, $coupon_amt*-1,'$coupon_comments')";
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    
}

//create the equip_charge transaction when button is clicked
function create_equip_charge_trans ( $reservation_id, $mce_mc_id, $equip_charge_amt, $equip_charge_comments )
{

    if ($mce_mc_id == "")
    {
        echo "<div id=err_msg>Error: Please select an equipment charge type.</div>";
        return (1);
    }
    elseif ( $equip_charge_amt == "" )
    {
        echo "<div id=err_msg>Error: Please enter amount.</div>";
        return (1);
    }
    elseif ( $equip_charge_comments == "")
    {
        echo "<div id=err_msg>Error: Please enter comment.</div>";
        return(1);
    }


    //echo "in insert";
    $query = "INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount, comment)
                   VALUES ($reservation_id, $mce_mc_id, $equip_charge_amt,'$equip_charge_comments')";
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}

function create_waiver_trans ( $reservation_id, $waiver_mc_id, $waiver_amt, $waiver_comments )
{
    
if ($waiver_mc_id == "")
    {
        echo "<div id=err_msg>Error: Please select a waiver.</div>";
        return (1);
    }
    elseif ( $waiver_amt == "" )
    {
        echo "<div id=err_msg>Error: Please enter amount.</div>";
        return (1);
    }
    elseif ( $waiver_comments == "")
    {
        echo "<div id=err_msg>Error: Please enter comment.</div>";
        return(1);
    }

    $query = "INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount, comment)
                   VALUES ($reservation_id, $waiver_mc_id, -1*$waiver_amt,'$waiver_comments')";
    
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}
function display_less_24_info ( $reservation_id )
{
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

        echo "<table width=100% border=1><tr><th>Description</th><th>Date</th><th>Comment</th><th>Amount</th><th>Remove</th></tr>";
        while($row = mysql_fetch_array($result))
        {
            $row_l24_rt_id    = $row['rt_id'];
            $row_l24_mc_id    = $row['mc_id'];
            $row_less_24_amt   = $row['less_24_amt'];
            $row_l24_mc_name  = $row['mc_name'];
            $row_l24_mct_name   = $row['mct_name'];
            $row_less_24_date = $row['less_24_date'];
            $row_less_24_comment = $row['less_24_comment'];


            echo "<tr>";
            echo "<td>($row_l24_rt_id) $row_l24_mct_name - $row_l24_mc_name</td>";
            echo "<td>$row_less_24_date</td>";
            echo "<td>$row_less_24_comment</td>";
            echo "<td>$" . number_format($row_less_24_amt,2) . "</td>";
           //a person cannot delete a cancellation transaction
            echo "</tr>";
        }

    }
    else
    {
         echo "<div id=err_msg><tr>No cancellations less than 24 hours in advance.<br /></tr></div>";
    }
}


function display_no_show_info ( $reservation_id )
{
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

        while($row = mysql_fetch_array($result))
        {
            $row_ns_rt_id    = $row['rt_id'];
            $row_ns_mc_id    = $row['mc_id'];
            $row_no_show_amt   = $row['no_show_amt'];
            $row_ns_mc_name  = $row['mc_name'];
            $row_ns_mct_name   = $row['mct_name'];
            $row_no_show_date = $row['no_show_date'];
            $row_no_show_comment = $row['no_show_comment'];


            echo "<tr>";
            echo "<td>($row_ns_rt_id) $row_ns_mct_name - $row_ns_mc_name</td>";
            echo "<td>$row_no_show_date</td>";
            echo "<td>$row_no_show_comment</td>";
            echo "<td>$" . number_format($row_no_show_amt,2) . "</td>";
           //a person cannot delete a cancellation transaction
            echo "</tr>";
        }

    }
    else
    {
         echo "<div id=err_msg><tr>No no-show cancellations.<br /></tr></div>";
    }
}

function display_coupon_amt ( $reservation_id )
{
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

        while($row = mysql_fetch_array($result))
        {
            $row_coupon_rt_id    = $row['coupon_rt_id'];
            $row_coupon_mc_id    = $row['coupon_mc_id'];
            $coupon_amt   = $row['coupon_amt'];
            $row_coupon_mct_name  = $row['coupon_mct_name'];
            $coupon_comments   = $row['coupon_comments'];
            $row_coupon_date   = $row['coupon_date'];
            $row_coupon_mc_name = $row['coupon_mc_name'];


            echo "<tr>";
            echo "<td>($row_coupon_rt_id) $row_coupon_mct_name - $row_coupon_mc_name</td>";
            echo "<td>$row_coupon_date</td>";
            echo "<td>$coupon_comments</td>";
            echo "<td>$" . number_format($coupon_amt,2) . "</td>";
            echo "<td><a href='invoice.php?reservation_id=$reservation_id&action=remove_coupon&id=$reservation_id&rt_id=$row_coupon_rt_id'>remove</a></td>";
            echo "</tr>";
        }

    }
    else
    {
         echo "<div id=err_msg><tr>No coupons.<br /></tr></div>";
    }
}

function display_equip_charge_amt ( $reservation_id )
{
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

        while($row = mysql_fetch_array($result))
        {
            $row_mce_rt_id    = $row['mce_rt_id'];
            $row_mce_mc_id    = $row['mce_mc_id'];
            $equip_charge_amt   = $row['equip_charge_amt'];
            $row_mce_mct_name  = $row['mce_mct_name'];
            $equip_charge_comments   = $row['equip_charge_comments'];
            $row_equip_charge_date   = $row['equip_charge_date'];
            $row_mce_mc_name = $row['mce_mc_name'];


            echo "<tr>";
            echo "<td>($row_mce_rt_id) $row_mce_mct_name - $row_mce_mc_name</td>";
            echo "<td>$row_equip_charge_date</td>";
            echo "<td>$equip_charge_comments</td>";
            echo "<td>$" . number_format($equip_charge_amt,2) . "</td>";
            echo "<td><a href='invoice.php?reservation_id=$reservation_id&action=remove_equip_charge&id=$reservation_id&rt_id=$row_mce_rt_id'>remove</a></td>";
            echo "</tr>";
        }

    }
    else
    {
         echo "<div id=err_msg><tr>No equipment charges.<br /></tr></div>";
    }
}

function display_waiver_amt ( $reservation_id )
{
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

        while($row = mysql_fetch_array($result))
        {
            $row_waiver_rt_id    = $row['waiver_rt_id'];
            $row_waiver_mc_id    = $row['waiver_mc_id'];
            $row_waiver_amt   = $row['waiver_amt'];
            $row_waiver_mct_name  = $row['waiver_mct_name'];
            $row_waiver_comment   = $row['waiver_comments'];
            $row_waiver_date   = $row['waiver_date'];
            $row_waiver_mc_name = $row['waiver_mc_name'];


            echo "<tr>";
            echo "<td>($row_waiver_rt_id) $row_waiver_mct_name - $row_waiver_mc_name</td>";
            echo"<td>$row_waiver_date</td>";
            echo "<td>$row_waiver_comment</td>";
            echo "<td>$" . number_format($row_waiver_amt,2) . "</td>";
            echo "<td><a href='invoice.php?reservation_id=$reservation_id&action=remove_waiver&id=$reservation_id&rt_id=$row_waiver_rt_id'>remove</a></td>";
            echo "</tr>";
        }

    }
    else
    {
         echo "<div id=err_msg><tr>No waivers.<br /></tr></div>";
    }
}

function show_coupon_list ( $reservation_id )
{
    // get the list of coupons for the dropdown box
    $query = "SELECT mc.id as coupon_mc_id, mc.name as coupon_mc_name, mct.name as coupon_mct_name
              FROM misc_charge mc, misc_charge_type mct
              WHERE mc.is_active = 1
              and mct.id = mc.misc_charge_type_id
              and mct.name = 'Discount'
              ORDER BY mc.name";
    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
    ?>

        <form method="GET" onsubmit="<?php echo $refresh_page ?>">
            <td align="right">
            <select id="mc_id" name="mc_id">
              <option value="">--select a coupon--
    <?php
            while($row = mysql_fetch_array($result))
            {
    ?>
              <option value="<? echo $row[coupon_mc_id] ?>"> <? echo $row[coupon_mc_name]; ?>
<?php
            } // end while
?>
            </select>&nbsp; &nbsp;&nbsp;
            </td>
            <td>Amount: $ <input type=text name=coupon_amt size=2>&nbsp; &nbsp; &nbsp; </td>
            <td>Comments: <input type=text name=coupon_comments size = "40" value=""></td>
            <input type=hidden name=reservation_id value="<? echo $reservation_id ?>">
            <input type=hidden name=action value="add_coupon">
            <td><input type=submit value="Add"></td>
        </form>
<?php
    }
    else
    {
         echo "<div id=err_msg>No coupons available.</div>";
    }

}
function show_equip_charge_list ( $reservation_id )
{
    // get the list of coupons
    $query = "SELECT mc.id as mce_id, mc.name as mce_mc_name, mct.name as mce_mct_name
              FROM misc_charge mc, misc_charge_type mct
              WHERE mc.is_active = 1
              and mct.id = mc.misc_charge_type_id
              and mct.name = 'Equipment Charge'
              ORDER BY mc.name";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
    ?>

        <form method="GET" onsubmit="<?php echo $refresh_page ?>">
            <input type=hidden name=reservation_id value="<? echo $reservation_id ?>">
            <input type=hidden name=action value="add_equip_charge">
            <td align="right">
            <select id="mce_id" name="mce_id">
              <option value="">--select an equipment charge--
    <?php
            while($row = mysql_fetch_array($result))
            {
    ?>
              <option value="<? echo $row[mce_id] ?>"> <? echo $row[mce_mc_name]; ?>
<?php
            } // end while
?>
            </select>&nbsp; &nbsp;&nbsp;
            </td>
            <td>Amount: $ <input type=text name=equip_charge_amt size=2>&nbsp; &nbsp; &nbsp; </td>
            <td>Comments: <input type=text name=equip_charge_comments size = "40" value=""></td>
            <td> <input type=submit value="Add"></td>
        </form>
<?php
    }
    else
    {
         echo "<div id=err_msg>No equipment charges available.</div>";
    }

}

function show_waiver_list ( $reservation_id )
{
    // get the list of waiver types available
    $query = "SELECT mc.id as mc_id, mc.name as mc_name, mct.name as mct_name
              FROM misc_charge mc, misc_charge_type mct
              WHERE mc.is_active = 1
              and mct.id = mc.misc_charge_type_id
              and mct.name = 'Fee Waiver'
              ORDER BY mc.name";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
    ?>

        <form method="GET" onsubmit="<?php echo $refresh_page ?>">
            <input type=hidden name=reservation_id value="<? echo $reservation_id ?>">
            <input type=hidden name=action value="add_waiver">

            <td align="right">
            <select id="mcw_id" name="mcw_id">
              <option value="">--select a waiver--
    <?php
            while($row = mysql_fetch_array($result))
            {
    ?>
              <option value="<? echo $row[mc_id] ?>"> <? echo $row[mc_name]; ?>
<?php
            } // end while
?>
            </select>&nbsp; &nbsp;&nbsp; 
            </td>
            <td>Amount: $ <input type=text name=waiver_amt size=2>&nbsp; &nbsp; &nbsp; </td>
            <td>Comments: <input type=text name=waiver_comments size = "40" value=""></td>
            <td><input type=submit value="Add"></td>
        </form>
<?php
    }
    else
    {
         echo "<div id=err_msg>No waivers available.</div>";
    }

}
function remove_coupon_trans ( $reservation_id, $coupon_rt_id )
{
   
    $query = "DELETE FROM reservation_transaction
                    WHERE id = $coupon_rt_id";

    // die and show mysql error number and messages, if there is any error with query
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}
function remove_waiver_trans ( $reservation_id, $waiver_rt_id )
{
  
    $query = "DELETE FROM reservation_transaction
                    WHERE id = $waiver_rt_id";

    // die and show mysql error number and messages, if there is any error with query
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}
function remove_equip_charge_trans ( $reservation_id, $mce_rt_id )
{
   
    $query = "UPDATE reservation_transaction SET is_active='0' where id = $mce_rt_id";

    // die and show mysql error number and messages, if there is any error with query
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}
?>