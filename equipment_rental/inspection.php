<?php
/* 
 * show user all equipment that has not been inspected
 * equip that has to be inspected after being rented offsite
 * if the equipment passes inspection, and the customer owes no more money, close the reservation
 * if the equipment fails, send it to the admin inspection area
 * requires the user to enter a comment
 *
 * 12apr09 beth
 * 
 */
include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
//include ("../includes/header.inc");
include ("../includes/config.inc");
//include ("../reservation/themes/default.inc");
include ("../includes/header.inc");

$reservation_id = $_GET['reservation_id'] == "" ? $reservation_id : $_GET['reservation_id'];
$refresh_page = $_GET['refresh_page'] == "" ? $refresh_page : $_GET['refresh_page'];
$equipment_id = $_GET['equipment_id'] == "" ? $equipment_id : $_GET['equipment_id'];
//$equipment_id = $_GET['equipment_id'];
$inpsect_mc_id  = $_GET['inspect_mc_id'];
$comment = $_GET['comment'];
$action = $_GET['action'];
$rt_id = $_GET['rt_id'];
//$reservation_id = $_GET['reservation_id'];
$mc_id = $_GET['mc_id'];
$equip_desc = $_GET['equip_desc'];
$mc_name = $_GET['name'];

//echo "$comment $action $reservation_id $equipment_id $mc_id";

// if equip passes inspection, send back to available equipment
if ($action == "update_status" && isset($reservation_id) && isset($equipment_id))
{
    create_pass_fail_trans ( $reservation_id, $equipment_id, $mc_id, $comment );
}

if ($action == "inspect")
{
    show_pass_fail ($reservation_id, $equipment_id, $mc_id, $comment);
}
if ($action == "pass-fail" && !isset($equipment_id))
{
    $_GET['equipment_id'];
    echo "equipment_id is $equipment_id";
}
?>

<html>
<head>

    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    
    
</head>
<body>

<div id="page">
<h2 align="left">Inspect Equipment</h2>
<div id="inspect_main">
    
    <div id="inspect_items">

        <?php
          // show all equip waiting to be inspected
          show_inspect_list($reservation_id);
        ?>
        
        
    </div>
    
    
</div>
</div>
</body>
</html>
<?php
//define functions

//show the list of equipment awaiting inspection
function show_inspect_list( $reservation_id )
{

    // Query equipment that is waiting to be inspected for a reservation that has already taken place
/*
    $query = "select distinct eq.id as equipment_id, re.id as reservation_id, eq.equip_serial_number as serial_num,
                eq.equip_model as model, eq.equip_type as eq_type, eq.equip_manufacturer as manuf, rt.comment
                from equipment eq, reservation_entry re, reservation_transaction rt, person p
                where eq.id = rt.equipment_id
                and p.id = re.person_id
                and re.id = rt.reservation_entry_id
                and re.reservation_status = 'unpaid'
                and re.actual_end_time is not null
                and eq.is_awaiting_inspection > 0
                and eq.is_available > 0
                group by eq.id
                ";
*/

    $query = "select distinct eq.id as equipment_id, re.id as reservation_id, eq.equip_serial_number as serial_num,
                eq.equip_model as model, eq.equip_type as eq_type, eq.equip_manufacturer as manuf, rt.comment
                from equipment eq, reservation_entry re, reservation_transaction rt, person p
                where eq.id = rt.equipment_id
                and p.id = re.person_id
                and re.id = rt.reservation_entry_id
                and re.actual_end_time is not null
                and eq.is_awaiting_inspection > 0
                and eq.is_available > 0
                group by eq.id
                ";

    // Die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    // Loop through the result and show all equipment waiting inspection
    if (mysql_num_rows($result)>0)
    {
        
       
        echo "The initials of the person performing the inspection must be entered in the
                                comments field.<br>";
        
       echo "<br><table border='1' cellspacing='0' cellpadding='5' bordercolor='#003366' border-style='solid'>";
        echo "<tr><th>Serial Number</th><th>Type</th><th>Manufacturer</th><th>Model</th>
                <th>View Invoice</th><th>Inspect</th>";
        while($row = mysql_fetch_array($result))
        {
             $equipment_id    = $row['equipment_id'];
            $row_serial_number    = $row['serial_num'];
            $row_equip_type   = $row['eq_type'];
            $row_manufacturer   = $row['manuf'];
            $row_model  = $row['model'];
            $comment   = $row['comment'];
            $rt_id = $row['rt_id'];
            $res_status = $row['res_status'];
            $reservation_id = $row['reservation_id'];
            $row_fname = $row['first_name'];
            $row_lname = $row['last_name'];
            $equip_desc = $row_serial_number . " " . $row_manufacturer . " " . $row_model;
            $person_name = $row_fname . " " . $row_lname;

            echo "<tr>";
            echo "<td>$row_serial_number</td>";
            echo "<td>$row_equip_type</td>";
            echo "<td>$row_manufacturer</td>";
            echo "<td>$row_model</td>";
            //echo "<td>$comment</td>";
            echo "<td><a href='../checkout/invoice.php?reservation_id=$reservation_id'>View Invoice</a></td>";
            echo "<td><a href='$refresh_page?reservation_id=$reservation_id&equipment_id=$equipment_id&equip_desc=$equip_desc&action=inspect'>Inspect</a></td>";
            echo "</tr>";
        }
        
    }
    else
    {
         echo "<br /><br />No equipment waiting to be inspected.";
    }
}

//show the fields to create the reservation transaction for pass or fail inspection
function show_pass_fail ( $reservation_id )
{
    $equipment_id = $_GET['equipment_id'];
    $equip_desc = $_GET['equip_desc'];
    //echo $equipment_id;
    // get the list of inspection results available
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

        <form method="GET" onsubmit="<? echo $refresh_page ?>">
            <input type=hidden name=reservation_id value="<? echo $reservation_id ?>">
            <input type=hidden name=equipment_id value="<? echo $equipment_id ?>">
            <input type=hidden name=equip_desc value="<? echo equip_desc ?>">
            <input type=hidden name=action value="update_status">
            <br><br><b>Equipment Selected - <? echo $equip_desc ?></b><br><br>
            Inspector's initials: <input type=text name=comment size = "3" value="">
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
        </body>
<?php
    }
    else
    {
         echo "No statuses available.";
    }

}
//insert the pass_fail transaction into the reservation transaction table and update the equipment and
//reservation status accordingly
function create_pass_fail_trans ( $reservation_id, $equipment_id, $mc_id, $comment )
{

//echo "In create_pass_fail_trans";
//echo "Reservation id: $reservation_id\n";
//echo "Equipment id: $equipment_id\n";
//echo "MC ID: $mc_id\n";
//echo "comment: $comment\n";

$comment = $_GET['comment'];
//echo $comment;
if ($equipment_id == "")
{
    echo "Error: No equipment is selected.";
    return (1);
}    
elseif ( $comment == "")
{
    echo "Error: Please enter comment.";
    return(1);
}

    $query = "SELECT id, comment, misc_charge_id, equipment_id
                FROM reservation_transaction
               WHERE reservation_entry_id = $reservation_id
                 AND equipment_id = $equipment_id
                 AND misc_charge_id = $mc_id";

    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    $row = mysql_fetch_array($result);

    //insert the inspection result into the reservation transaction table
    $query = "INSERT INTO reservation_transaction (reservation_entry_id, equipment_id, misc_charge_id, comment)
                       VALUES ($reservation_id, $equipment_id, $mc_id, '$comment')";    
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    
    //determine if the inspection result=passed
    //$query_inspect_result = "select name from misc_charge where id=$mc_id and name='passed'";
    $query_inspect_result = "select name from misc_charge where id=$mc_id";
    $result = mysql_query($query_inspect_result) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    $row = mysql_fetch_array($result);

   // if the inspection result=passed
    if($row[name] == 'Passed')
    {

/*
 * Do not do this as reservation status will be set to CLOSED when payment is made.
    //find the sum of the reservation transactions
        $amount = "select sum(amount) as sum_amount from reservation_transaction where reservation_entry_id=$reservation_id";
        $result = mysql_query($amount) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        $amount =  mysql_fetch_array($result);


        //if the sum of the reservation transactions is 0
        if($amount==0)
        {
             //update the entry to closed
             $query_2 = "update reservation_entry set reservation_status='CLOSED' where id = $reservation_id";
             $result = mysql_query($query_2) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        }
*/

        //say the equipment is no longer awaiting inspection
        $query_3 = "update equipment set is_awaiting_inspection=0, is_available=1 where id=$equipment_id";
        $result = mysql_query($query_3) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        //delete the cc number
        $query_4 = "update credit_card_info set cc_number='' where reservation_id=$reservation_id";
        $result = mysql_query($query_4) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
    else  //failed
    {
        //say the equipment is no longer awaiting inspection
        $query_3 = "update equipment set is_awaiting_inspection=1,is_available=0 where id=$equipment_id";
        $result = mysql_query($query_3) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    }
    //if the equipment fails inspection, it moves to the admin inspection page
}

?>