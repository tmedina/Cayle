<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// $Id: del_entry.php 994 2009-01-14 21:48:50Z jberanek $

require_once "grab_globals.inc.php";
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include "mrbs_auth.inc";
include "mrbs_sql.inc";
include ("../reservation/themes/default.inc");
include ("../includes/header.inc");

// Get form variables
$day = get_form_var('day', 'int');
$month = get_form_var('month', 'int');
$year = get_form_var('year', 'int');
$area = get_form_var('area', 'int');
$id = get_form_var('id', 'int');
$series = get_form_var('series', 'int');
$returl = get_form_var('returl', 'string');

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

if (getAuthorised(1) && ($info = mrbsGetEntryInfo($id)))
{
  $day   = strftime("%d", $info["start_time"]);
  $month = strftime("%m", $info["start_time"]);
  $year  = strftime("%Y", $info["start_time"]);
  $area  = mrbsGetRoomArea($info["room_id"]);

  if (MAIL_ADMIN_ON_DELETE)
  {
    include_once "functions_mail.inc";
    // Gather all fields values for use in emails.
    $mail_previous = getPreviousEntryData($id, $series);
  }
  sql_begin();
  $result = mrbsDelEntry(getUserName(), $id, $series, 1);
  sql_commit();
  if ($result)
  {
    // Send a mail to the Administrator
    (MAIL_ADMIN_ON_DELETE) ? $result = notifyAdminOnDelete($mail_previous) : '';
    Header("Location: $returl");
    exit();
  }
}

// If you got this far then we got an access denied.
showAccessDenied($day, $month, $year, $area, "");
?>
