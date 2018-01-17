<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// cancel_entry.php 2009-03-13 wilson
// 20090320 wilson: remove mrbs authorization
// 20090411 wilson: calculate the cancellation charge
// 20090416 wilson: add cancel series logic
// 20090417 wilson: add reservation status
// 20090417 wilson: add deactivate reservation transaction.


require_once "grab_globals.inc.php";
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include "mrbs_auth.inc";
include "mrbs_sql.inc";
include ("../includes/functions.inc");

// Get form variables
$day = get_form_var('day', 'int');
$month = get_form_var('month', 'int');
$year = get_form_var('year', 'int');
$area = get_form_var('area', 'int');
$id = get_form_var('id', 'int');
$series = get_form_var('series', 'int');
$returl = get_form_var('returl', 'string');
$cancel_type = get_form_var('cancel_type', 'int');
$cancel_comment = get_form_var('cancel_comment', 'string');


// call function to determine room charge
//
$charge = calc_room_charge ( $id );
//echo "Room charge is: $charge <br/>";

//initialize reservation status to UNPAID
$reservation_status = $UNPAID;

// Get the type of misc charge cancellation
$query = "SELECT name FROM misc_charge WHERE id = $cancel_type";
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
if (mysql_num_rows($result)>0)
{
    while($row = mysql_fetch_array($result))
    {
        $mc_name = $row['name'];
    }
}

// Determine the charge depending on misc charge cancellation
//
if ($mc_name == "Less than 24 hours")
{
    $charge *= 0.5;
}
elseif ( $mc_name == "More than 24 hours")
{
    $charge = 0;
    $reservation_status = $CLOSED;
}

// Cancel the reservation entry table
//
if ( $series )
{
    $query = "SELECT repeat_id FROM reservation_entry where id = $id";
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
    if (mysql_num_rows($result)>0)
    {
        $row = mysql_fetch_array($result);
        $repeat_id = $row['repeat_id'];
    }
    $query = "UPDATE $tbl_entry set reservation_status = '$reservation_status', is_cancelled = 1, cancellation_comments = '$cancel_comment' WHERE repeat_id=$repeat_id";
}
else
{
    $query = "UPDATE $tbl_entry set reservation_status = '$reservation_status', is_cancelled = 1, cancellation_comments = '$cancel_comment' WHERE id=$id";
}
//echo $query;
//echo "<br/>";
mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

// Deactive all reservation transaction
$query = "UPDATE reservation_transaction
             SET is_active = 0
           WHERE reservation_entry_id = $id";
mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

// Create the cancellation transaction
//
$query = "INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount)
               VALUES ($id, $cancel_type, $charge)";
//echo $query;
//echo "<br/>";
mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

if (empty($returl))
{
  switch ($default_view)
  {
    case "month":
      $returl = "month.php";
      break;
    case "week":
      $returl = "week.php";
      break;
    default:
      $returl = "day.php";
  }
  $returl .= "?year=$year&month=$month&day=$day&area=$area";
}

Header("Location: $returl");
?>