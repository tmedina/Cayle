<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * show user all equipment that has not been inspected
 * equip that has not been inspected cannot be rented
 *
 * 12apr09 beth
 */
include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
include ("../includes/header.inc");
include ("../reservation/themes/default.inc");

$reservation_id = $_GET['reservation_id'] == "" ? $reservation_id : $_GET['reservation_id'];
$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];
$equipment_id = $_GET['equipment_id'] == "" ? $equipment_id : $_GET['equipment_id'];
//$equipment_id = $_GET['equipment_id'];
$inpsect_mc_id  = $_GET['inspect_mc_id'];
$inspect_comment = $_GET['inspect_comment'];
$action = $_GET['action'];
$rt_id = $_GET['rt_id'];
//$reservation_id = $_GET['reservation_id'];
$mc_id = $_GET['mc_id'];

// if equip passes inspection, send back to available equipment
if ($action == "pass-fail" && isset($reservation_id) && isset($equipment_id))
{
    create_pass_fail_trans ( $reservation_id, $equipment_id, $mc_id, $inspect_comment );
}

if ($action == "inspect")
{
    show_pass_fail ($reservation_id, $equipment_id, $mc_id, $inspect_comment);
}
if ($action == "pass-fail" && !isset($equipment_id))
{
    $_GET['equipment_id'];
    echo "equipment_id is $equipment_id";
}
?>
<html>
<head>
<div id="inspect_main">
    <div id="inspect_items">
        <?php
          // show all equip waiting to be inspected
          show_inspect_list($reservation_id);
        ?>

    </div>
    <div>
        <?php
            show_pass_fail ($reservation_id, $equipment_id,$mc_id, $inspect_comment)
        ?>
    </div>

</div>
</head>
</html>
<?php
//define functions

//show the list of equipment awaiting inspection
function show_inspect_list( $reservation_id )
{
    // Query equipment that is waiting to be inspected for a reservation that is not in the future
    $query = "SELECT eq.id as equipment_id,
                     eq.equip_serial_number as serial_num,
                     eq.equip_type as eq_type,
                     eq.equip_manufacturer as manuf,
                     eq.equip_model as model,
                     rt.comment as inspect_comment,
                     rt.id as rt_id,
                     re.reservation_status as res_status,
                     re.id as reservation_id
             FROM equipment eq, reservation_transaction rt, reservation_entry re
             WHERE eq.is_awaiting_inspection > 0
             AND re.id = rt.reservation_entry_id
             AND rt.equipment_id = eq.id
             AND rt.reservation_room_id is null
             AND re.reservation_status = 'UNPAID'
             ORDER BY re.id, rt.id";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all bar charges associated with this reservation
    if (mysql_num_rows($result)>0)
    {

        echo "<h2>Equipment Waiting Inspection</h2>";
        echo "The initials of the person performing the inspection must be entered in the
                                comments field.";
        echo "<table border=1>";
        echo "<tr><th>Serial Number</th><th>Type</th><th>Manufacturer</th><th>Model</th>
                <th>Comments</th>";
        while($row = mysql_fetch_array($result))
        {
            $equipment_id    = $row['equipment_id'];
            $row_serial_number    = $row['serial_num'];
            $row_equip_type   = $row['eq_type'];
            $row_manufacturer   = $row['manuf'];
            $row_model  = $row['model'];
            $inspect_comment   = $row['inspect_comment'];
            $rt_id = $row['rt_id'];
            $res_status = $row['res_status'];
            $reservation_id = $row['reservation_id'];



            echo "<tr>";
            echo "<td>($reservation_id - $rt_id) $row_serial_number</td>";
            echo "<td>$row_equip_type</td>";
            echo "<td>$row_manufacturer</td>";
            echo "<td>$row_model</td>";
            echo "<td><a href='$refresh_page?reservation_id=$reservation_id&equipment_id=$equipment_id&action=inspect'>Inspect</a></td>";
            echo "</tr>";
        }

    }
    else
    {
         echo "None";
    }
}

//show the fields to create the reservation transaction for pass or fail inspection
function show_pass_fail ( $reservation_id )
{
    // get the list of waiver types available
    $query = "SELECT mc.id as mc_id, mc.name as mc_name, mct.name as mct_name
              FROM misc_charge mc, misc_charge_type mct
              WHERE mc.is_active = 1
              and mct.id = mc.misc_charge_type_id
              and mct.name = 'Inspection'
              ORDER BY mc.name";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() . "show_pass_fail" );

    if (mysql_num_rows($result)>0)
    {

    ?>

        <form method="GET" onsubmit="<?php echo $refresh_page ?>">

            <input type=hidden name=reservation_id value="<? echo $reservation_id ?>">
            <input type=hidden name=equipment_id value="<?php echo $equipment_id ?>">
            <input type=hidden name=action value="pass-fail">
            <tr><td><?php ($reservation_id - $rt_id) $row_serial_number ?></td>
            	<td><?php $row_equip_type ?></td>
            	<td><?php $row_manufacturer ?></td>
            	<td><?php $row_model ?></td>
            Comments: <input type=text name=inspect_comment size = "40" value="">
            <select id="mc_id" name="mc_id">
              <option value="">--select a status--
    <?php
            while($row = mysql_fetch_array($result))
            {
    ?>
              <option value="<? echo $row[mc_id] ?>"> <? echo $row[mc_name]; ?>
<?php
            } // end while
?>
            </select>


            <input type=submit value="Update Status">
        </form>
<?php
    }
    else
    {
         echo "No statuses available.";
    }

}
//insert the pass_fail transaction into the reservatin transaction table
function create_pass_fail_trans ( $reservation_id, $equipment_id, $mc_id, $inspect_comment )
{


if ($equipment_id == "")
    {
        echo "Error: No equipment is selected.";
        return (1);
    }

elseif ( $inspect_comments == "")
    {
        echo "Error: Please enter comment.";
        return(1);
    }

    $query = "SELECT id, comment, misc_charge_id, equipment_id
                FROM reservation_transaction
               WHERE reservation_entry_id = $reservation_id
                 AND equipment_id = $equipment_id
                 AND misc_charge_id = $coupon_mc_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    $row = mysql_fetch_array($result);


    {
        //echo "in insert";
        $query = "INSERT INTO reservation_transaction (reservation_entry_id, comment, misc_charge_id, equipment_id)
                       VALUES ($reservation_id, '$inspect_comment', $mc_id,$equipment_id)";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
}

?>