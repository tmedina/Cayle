<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// 2009-03-03 wilson: update actual_start_time and take user back to returl.
// 20090319 wilson: remove mrbs authorization

require_once "grab_globals.inc.php";
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include "mrbs_auth.inc";
include "mrbs_sql.inc";
include ("../reservation/themes/default.inc");
//include ("../includes/header.inc");

// Get form variables
$id = get_form_var('id', 'int');
$returl = get_form_var('returl', 'string');

// Get the check in date
$day   = date("d");
$month = date("m");
$year  = date("Y");
$hour  = date("H");
$minute = date("i");

//DEBUG: echo "time: $day - $month - $year - $hour - $minute";

$checkintime = mktime($hour, $minute, 0,
                    $month, $day, $year,
                    is_dst($month, $day, $year, $hour));





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
  $result = mrbsCheckInEntry(getUserName(), $id, $checkintime);
  sql_commit();
  if ($result)
  {
    Header("Location: $returl");
    exit();
  }
}

// If you got this far then we got an access denied.
showAccessDenied($day, $month, $year, $area, "");
?>
