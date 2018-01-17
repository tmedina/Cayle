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
//

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
$cancel_initials = get_form_var('cancel_initials', 'string');
$cancel_comment = get_form_var('cancel_comment', 'string');


// get the room transaction details
$query = "SELECT re.room_id, room_name, room_charge_day, room_charge_night, room_charge_drummer,
                 room_charge_employee_day, room_charge_employee_night,
                 re.start_time as start_time, re.end_time as end_time
            FROM reservation_entry re, reservation_room rm
           WHERE rm.id = re.room_id
             AND re.id =  $id";

//DEBUG
//echo $query;

// die and show mysql error number and messages, if there is any error with query
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

if (mysql_num_rows($result)>0)
{
    while($row = mysql_fetch_array($result))
    {
        $room_id    = $row['room_id'];
        $room_name = $row['room_name'];
        $room_charge_day = $row['room_charge_day'];
        $room_charge_night = $row['room_charge_night'];
        $start_time  = $row['start_time'];
        $end_time    = $row['end_time'];
        $duration_hour  = calc_utf_period_diff( $end_time, $start_time )/60;
        $str_start_time = strtotime( utf_period_2_date($start_time) );
        $str_end_time   = strtotime( utf_period_2_date($end_time) );
        $day_number     = date('N', $str_start_time);
        $start_hour     = date('H', $str_start_time);
        $end_hour       = date('H', $str_end_time);
        $room_charge_drummer = $row['room_charge_drummer'];
        $room_charge_employee_day = $row['room_charge_employee_day'];
        $room_charge_employee_night = $row['room_charge_employee_night'];

        //echo "start time: " . utf_period_2_date($start_time) . " end_time: " . utf_period_2_date($end_time) . "<br/>";

        //echo "day number: $day_number start hour: $start_hour <br/>";

        //TEST
        //echo "debug: day_number is manually set to 4<br/>";
        //$day_number = 4;

        // weekdays or weekend
        //
        if ( $day_number > 5 ) //weekend
        {
            //echo "weekend charge";
            $charge = $duration_hour * $room_charge_night;
            $duration_night = $duration_hour;
        }
        else // weekday
        {
            // start time after 5pm, use night charge
            if ( $start_hour >= 17 || ($start_hour >= 0 && $start_hour <= 2)  )
            {
                //echo "weekday night charge";
                $charge = $duration_hour * $room_charge_night;
                $duration_night = $duration_hour;
            }
            else // start time before 5pm, use day charge
            {
                if ( $end_hour <= 17 || ($end_hour >= 0 && $end_hour <= 2) ) //end time before 5 pm
                {
                    //echo "weekday day charge";
                    $charge = $duration_hour * $room_charge_day;
                    $duration_day = $duration_hour;
                }
                else // mixed
                {

                    $duration_day = 17 - $start_hour;

                    if ($end_hour == 0)
                    {
                        $end_hr = 24;
                    }
                    elseif ($end_hour == 1)
                    {
                        $end_hr = 25;
                    }
                    elseif ($end_hour == 2)
                    {
                        $end_hr = 26;
                    }
                    $duration_night = $end_hour - 17;


                    $charge = ($duration_day * $room_charge_day) + ($duration_night * $room_charge_night);

                    //echo "weekday day $room_charge_day and night charge $room_charge_night - dur day: $duration_day dur night: $duration_night <br/>";

                }
            }
        }

        //DEBUG print to the box in invoice.php
        //echo "<br/><br/> <b> $room_name: $duration_hour hr  = $ $charge </b>";


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

        // Cancel the reservation entry table
        //
        $query = "UPDATE $tbl_entry set is_cancelled = 1, cancellation_initials = '$cancel_initials'  ,cancellation_comments = '$cancel_comment' WHERE id=$id";
        //echo $query;
        //echo "<br/>";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        // Create the cancellation transaction
        //
        $query = "INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount)
                       VALUES ($id, $cancel_type, $charge)";
        //echo $query;
        //echo "<br/>";
        mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
        
    }
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
  $returl .= "?year=$year&month=$month&day=$day&area=$area";
}

Header("Location: $returl");
?>