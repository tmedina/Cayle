<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * PHP Handler Class
 * 03-21-2009
 * Osmerg
 */
include ("../includes/dbconnect.inc");
include ("../reservation/themes/default.inc");
//include ("../includes/header.inc");

// get url query strings
$return_url     = $_GET['return_url'];
$reservation_id = $_GET['reservation_id'];
$start_time     = $_GET['start_time'];
$end_time       = $_GET['end_time'];
$action         = $_GET['action'];

// store the equipments selected by user from previous
// screen into an array from url query strings
$j=0;
for ( $i=0; $i < 99; $i++ )
{
    $eq_id = $_GET['select_equip' . $i];
    if (isset ($eq_id) )
    {
        $eq_arr[$j] = $eq_id;
        $j++;
    }
}

//validation: if no checkboxes are selected display error
if ( count($eq_arr) == 0 )
{
    die ("Error: Please select one or more equipment");
}

// create the timestamp
$timestamp = date("Y-m-d H:i:s" );

// if the reservation id is not set that means this is for an outside equipment rental
// so we need to create a new reservation
// if the reservation is is already set, that means this is equipment for room reservation
// so the reservation already exist and do not need to create a new reservation
if ( ! isset( $reservation_id ) || $reservation_id == NULL )
{
    // Get the max id from table reservation entry

    $query = "select max(id) AS max_id from reservation_entry";
    $result=mysql_query($query);
    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $max_id=$row[max_id];
        }
    }
    else
    {
        die('Error getting max reservation id from table reservation_entry');
    }

    // create new reservation id
    $reservation_id = $max_id + 1;

    // create new reservation
    $sql = "INSERT INTO reservation_entry ( id, start_time, end_time, description, created_at, updated_at )
            VALUES ( $reservation_id, $start_time, $end_time, 'equipment res $reservation_id', '$timestamp', '$timestamp' )";

    if ( !mysql_query($sql) )
    {
        die('Error creating new reservation id $reservation_id: ' . mysql_error());
    }
}

// create an entry for each equipment to be reserved into table reservation_equipment
foreach ( $eq_arr as $eq )
{
    $sql = "INSERT INTO reservation_transaction (reservation_entry_id, equipment_id, created_at, updated_at)
                 VALUES ($reservation_id, $eq, '$timestamp', '$timestamp')";

    if ( !mysql_query($sql) )
    {
        die('Error creating new reservation_transaction (res_id: $reservation_entry_id, eq_id: $eq' . mysql_error());
    }
}

// if this is a room reservation equipment, take user back to room reservation view page
if ( $action == 'room_equip_res' )
{
    header("Location: ../reservation/view_entry.php?id=$reservation_id");
}
// if this is adding equipments to an existing equipment reservation, take user to equipment reservation detail page
elseif ( $action == 'add' )
{
    header("Location: view_detail.php?id=$reservation_id");
}
// otherwise send user to person module, to select customer for this equipment reservation
else
{
    // create return url
    $returl="../equipment_rental/view_detail.php?id=$reservation_id";

    // bring the user to the person module to select customer
    header("Location: ../person_band/add_person.php?reservation_id=$reservation_id&return_url=$returl");
}
?>