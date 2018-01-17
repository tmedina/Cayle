<?php

include ("../includes/config.inc");

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//echo "logged: " . $_SESSION["logged"] . "<br>";
//echo "is admin: " . $_SESSION["is_admin"] . "<br>";

/*
 * Allow user to add/edit/view payments associated with the reservation
 * shows error is amount of payments exceeds amount owed
 *
 * 20090411 beth
 */
// The calling page should be connected to db
//

// Get the query string variables
$reservation_id = $_GET['reservation_id'] == "" ? $reservation_id : $_GET['reservation_id'];
$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];
$mc_id  = $_GET['mc_id'];
$mc_amt = $_GET['mc_amt'];
$action = $_GET['action'];
$rt_id = $_GET['rt_id'];

// Die if no reservation id
if ( ! isset($reservation_id) )
{
    die ("ERROR: Missing Reservation ID");
}

// create the payment transaction based on type of payment chosen
if ( $action == "add" )
{
    //check to see if the payment amount will make the total less than zero
    $query = "SELECT id, sum(amount) as trans_total
	                FROM reservation_transaction
	               WHERE reservation_entry_id = $reservation_id
                 	";

	    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if(result<0)
    {
    	echo "<div id=err_msg>Payments are greater than total</div>";
    }
    else
    {
    create_pay_type_trans ( $reservation_id, $mc_id, $mc_amt );
    }
}
if ($action == "show_cc" && $_SESSION["is_admin"] == 1)
{
   
    //check to see if there is a cc number for an equipment only reservation
    $query = "select cc_number from credit_card_info where reservation_id = $reservation_id";


    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    $row = mysql_fetch_array($result);

   if ( ! isset($row['cc_number']) )
   {
    echo "<div id=err_msg>No credit card on file</div>";
   }
   else
   {
        $cc_number = $row['cc_number'];
        
        //decrypt the cc number and show it to be charged
        $query = "SELECT AES_DECRYPT('".$cc_number."','$salted_pwd')";
        //echo $query;
        
        $dec = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        $str_dec = mysql_fetch_array($dec);
        if ($dec <>0)
        {
        $val_dec = $str_dec[0];
        echo "The credit card number is " . $val_dec . "<br><br>";
        }
        else
        {
            echo "<div id=err_msg>No credit card on file</div>";
        }
    }
    
}

// if action is remove
if ( $action == "remove" && isset($rt_id) )
{
    remove_pay_type_trans ( $rt_id );
}
?>

<div id="bc_main">
    <div id="bc_items">

        <?php
        if ( $_SESSION["is_admin"] == 1 )
        {
            echo "<a href='$refresh_page?reservation_id=$reservation_id&action=show_cc'>Show Credit Card Number</a><br /><br />";
            // show all active bar charges items for user to add
        }
        show_pay_type_items($reservation_id);

        ?>
    </div>
    <div id="bc_trans">
          <?php
            // show bar charges cart containing
            // the current bar charges associated with this reservation
            show_pay_type_trans($reservation_id);
          ?>
    </div>
</div>

<?php
// FUNCTIONS definition
//
function create_pay_type_trans ( $reservation_id, $mc_id, $mc_amt)
{


if ($mc_id == "")
    {
        echo "<div id=err_msg>Error: Please select a payment type</div>";
        return (1);
    }
    elseif ( $mc_amt == "" )
    {
        echo "<div id=err_msg>Error: Please enter amount</div>";
        return (1);
    }

    $query = "SELECT id, amount, comment, misc_charge_id
                FROM reservation_transaction
               WHERE reservation_entry_id = $reservation_id
                 AND misc_charge_id = $mc_id";

    // die and show mysql error number and messages, if there is any error with query

    {
        $query = "INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount)
                       VALUES ($reservation_id, $mc_id, -1*$mc_amt)";
        

        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
}

function remove_pay_type_trans ( $rt_id )
{
    //do not delete the payment, just make it inactive
    $query = "UPDATE reservation_transaction SET is_active=0  WHERE id = $rt_id";

    // die and show mysql error number and messages, if there is any error with query
    mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}

function show_pay_type_trans ( $reservation_id )
{
    // Query all payments for this reservation id
    $query = "SELECT rt.id AS rt_id, rt.misc_charge_id AS mc_id, rt.amount AS rt_amt,
                     mc.name AS mc_name, mc.amount AS mc_amt, mct.name as mct_name, rt.updated_at as trans_date
                FROM reservation_transaction rt, misc_charge mc, misc_charge_type mct
               WHERE rt.misc_charge_id = mc.id
                 AND mct.id = mc.misc_charge_type_id
                 AND rt.reservation_entry_id = $reservation_id
                 AND rt.misc_charge_id > 0
                 AND mct.name = 'Payment'
                 AND rt.is_active = 1";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all payments that have been made for reservation
    if (mysql_num_rows($result)>0)
    {
        //initialize total field
        $tot_rt_amt = 0;

        echo "<table border=\"thin\" cellpadding=\"5\" cellspacing=\"0\">";
        echo "<tr><th>Payment Type</th><th>Date</th><th>Amount</th><th>Remove</th></tr>";
        while($row = mysql_fetch_array($result))
        {
            $rt_id    = $row['rt_id'];
            $row_mc_id    = $row['mc_id'];
            $row_rt_amt   = $row['rt_amt'];
            $row_rt_qty   = $row['rt_qty'];
            $row_mc_name  = $row['mc_name'];
            $row_mct_name = $row['mct_name'];
            $row_mc_amt   = $row['mc_amt'];
            $row_rt_date = $row['trans_date'];
           

            echo "<tr>";
            echo "<td>$row_mc_name $row_mct_name</td>";
            echo "<td>$row_rt_date</td>";
            echo "<td>$" . number_format($row_rt_amt,2) . "</td>";
            echo "<td><a href='payment.php?reservation_id=$reservation_id&action=remove&rt_id=$rt_id'>remove</a></td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
    else
    {
         echo "No payments made";
    }
}



function show_pay_type_items( $reservation_id )
{
    // get the list of payment types to show in the dropdown box
    $query = "SELECT mc.id as mc_id, mc.name as mc_name, amount, mct.name as mct_name
              FROM misc_charge mc, misc_charge_type mct
              WHERE mc.is_active = 1
              AND mct.id = mc.misc_charge_type_id
              AND mct.name = 'Payment'
              ORDER BY mc.name";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($result)>0)
    {
    ?>
        
        <form method="GET" onsubmit="<?php echo $refresh_page ?>">
            <input type=hidden name=reservation_id value="<? echo $reservation_id ?>">
            <input type=hidden name=action value="add">
            <select id="mc_id" name="mc_id">
              <option value="">--select a payment type--
    <?php
            while($row = mysql_fetch_array($result))
            {
    ?>
              <option value="<? echo $row[mc_id] ?>"> <? echo $row[mc_name] . " "; ?>
<?php
            } // end while
?>
            </select>&nbsp;&nbsp;&nbsp;
            <b>Amount:</b> <input type=text name=mc_amt size=2>
            <input type=submit value="Add">
            
        </form>
<?php
    }
    else
    {
         echo "No payment types available";
    }
}
?>