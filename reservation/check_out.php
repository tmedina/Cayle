<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// 2009-03-03 wilson: update actual_end_time and send user to checkout module.
//20090320 wilson: remove mrbs authentication
//20090408 wilson: forward user to checkout module
//20090409 wilson: if actual end time is 15 mins or more over the scheduled end time,
//                 warn user to adjust the end time reservation or
//                 ignore and continue to check out process.

require_once "grab_globals.inc.php";
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include "mrbs_auth.inc";
include "mrbs_sql.inc";
include ("../reservation/themes/default.inc");
include ("../includes/functions.inc");

// Get form variables
$id = get_form_var('id', 'int');
$returl = get_form_var('returl', 'string');
$end_time = get_form_var('end_time', 'int');
$action   = get_form_var('action', 'string');

// Get the scheduled check out date
$end_time_arr = utf_period_2_date_arr( $end_time );
$day          = $end_time_arr['day'];
$month        = $end_time_arr['month'];
$year         = $end_time_arr['year'];
$hour         = $end_time_arr['hour'];
$minute       = $end_time_arr['minute'];
$scheduledout = mktime($hour, $minute, 0,
                       $month, $day, $year,
                       is_dst($month, $day, $year, $hour));

// Get the actual check out date
$day   = date("d");
$month = date("m");
$year  = date("Y");
$hour  = date("H");
$minute = date("i");
$checkouttime = mktime($hour, $minute, 0,
                    $month, $day, $year,
                    is_dst($month, $day, $year, $hour));

// Calculate differences
$diff = calc_utf_date_diff( $checkouttime, $scheduledout );

// if actual end time is 15 mins or more over the scheduled end time,
// create warning msg for user to adjust the end time reservation accordingly
if ( $diff >= 15 && $action == "")
{
?>
<html>
<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include ("../includes/header.inc"); ?>
    <br>
    <div id="warn_msg">
        <?php
        echo "<br>This reservation is $diff minutes over the scheduled time. <br/><br/>";
        //echo "Please edit reservation and update scheduled <b>End Time</b> accordingly<br/><br/>";
        echo "<a href='edit_entry.php?id=$id'>Edit end time</a><br/>";
        echo "<a href='check_out.php?id=$id&action=ignore'>Continue with scheduled end time</a><br/>";
        ?>
    </div>
<?php
    exit (0);
}

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
}

//DEBUG: echo "\nChecking in $id, time $checkintime and return to $returl";
if (getAuthorised(1) && ($info = mrbsGetEntryInfo($id)))
{
  
  $day   = strftime("%d", $info["start_time"]);
  $month = strftime("%m", $info["start_time"]);
  $year  = strftime("%Y", $info["start_time"]);
  $area  = mrbsGetRoomArea($info["room_id"]);

  sql_begin();
  $result = mrbsCheckOutEntry(getUserName(), $id, $checkouttime);
  sql_commit();
  if ($result)
  {
    Header("Location: ../checkout/invoice.php?reservation_id=$id");
    exit();
  }
}

// If you got this far then we got an access denied.
showAccessDenied($day, $month, $year, $area, "");
?>
