<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

// $Id: index.php 994 2009-01-14 21:48:50Z jberanek $

// Index is just a stub to redirect to the appropriate view
// as defined in config.inc.php using the variable $default_view
// If $default_room is defined in config.inc.php then this will
// be used to redirect to a particular room.
require_once "grab_globals.inc.php";
include("config.inc.php");
include("dbsys.inc");
include ("../reservation/themes/default.inc");
//include ("../includes/header.inc");


$day   = date("d");
$month = date("m");
$year  = date("Y");

switch ($default_view)
{
  case "month":
    $redirect_str = "month.php?year=$year&month=$month";
    break;
  case "week":
    $redirect_str = "week.php?year=$year&month=$month&day=$day";
    break;
  default:
    $redirect_str = "day.php?day=$day&month=$month&year=$year";
}

if ( ! empty($default_room) )
{
  $sql = "select area_id from $tbl_room where id=$default_room";
  $res = sql_query($sql);
  if( $res )
  {
    if( sql_count($res) == 1 )
    {
      $row = sql_row_keyed($res, 0);
      $area = $row['area_id'];
      $room = $default_room;
      $redirect_str .= "&area=$area&room=$room";
    }
  }
}

header("Location: $redirect_str");
?>
