<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

/*
 * function.inc
 * 20090411 wilson: added calc_room_charge
 * 20090331 wilson: create utf_period_2_date, utf_date_2_date, date_2_utf_date,
 *                  calc_utf_period_diff, calc_utf_date_diff
 *
 * NOTES:
 * Please note the format of following fields in table reservation_entry:
 *   start_time         = UTF Period format
 *   end_time           = UTF Period format
 *   actual_start_time  = UTF Date format
 *   actual_end_time    = UTF Date format
 *
 */

include ("../reservation/config.inc.php");


// This function converts period (utf date format) into regular date time string format (yyyy-mm-dd hh:mm:ss)
// if the period is between midnight and 02:00 am, the date will be increased by 1
//
function utf_period_2_date ( $utf_period )
{
    global $periods;

    $arrdate = (  getdate($utf_period) );

    // parse date
    $year = $arrdate[year];
    $mon = $arrdate[mon];
    $day = $arrdate[mday];

    // parse time
    $sec = sprintf("%02d", $arrdate[seconds]);
    $min = $arrdate[minutes];
    $period = $periods[$min];

    // increase the date by 1 if period is between midnight and 02:00
    if ( $period == "00:00" || $period == "00:30" || $period == "01:00" || $period == "01:30" || $period == "02:00")
    {
        $newdate = mktime(0,0,0,$mon,$day+1,$year);
    }
    else
    {
        $newdate = mktime(0,0,0,$mon,$day,$year);
    }

    // parse the new date
    $newdatearr = getdate( $newdate );
    $newyear    = $newdatearr[year];
    $newmon     = sprintf("%02d", $newdatearr[mon]);
    $newday     = sprintf("%02d", $newdatearr[mday]);

    // create the date and time
    $date = $newyear. "-" . $newmon . "-" . $newday . " " . $period . ":" . $sec;

    return $date;
}

function pretty_date ( $utf_period )
{
    global $periods;

    $arrdate = (  getdate($utf_period) );

    // parse date
    $year = $arrdate[year];
    $mon = $arrdate[mon];
    $day = $arrdate[mday];

    // parse time
    $sec = sprintf("%02d", $arrdate[seconds]);
    $min = $arrdate[minutes];
    $period = $periods[$min];

    // increase the date by 1 if period is between midnight and 02:00
    if ( $period == "00:00" || $period == "00:30" || $period == "01:00" || $period == "01:30" || $period == "02:00")
    {
        $newdate = mktime(0,0,0,$mon,$day+1,$year);
    }
    else
    {
        $newdate = mktime(0,0,0,$mon,$day,$year);
    }

    // parse the new date
    $newdatearr = getdate( $newdate );
    $newyear    = $newdatearr[year];
    $newmon     = sprintf("%02d", $newdatearr[mon]);
    $newday     = sprintf("%02d", $newdatearr[mday]);

    // create the date and time
    //$date = $newyear. "-" . $newmon . "-" . $newday . " " . $period . ":" . $sec;
    $date = $newmon. "/" . $newday . "/" . $newyear . " " . $period;
    return $date;
}


function utf_period_2_date_arr ( $utf_period )
{
    global $periods;

    $arrdate = (  getdate($utf_period) );

    // parse date
    $year = $arrdate[year];
    $mon = $arrdate[mon];
    $day = $arrdate[mday];

    // parse time
    $sec = sprintf("%02d", $arrdate[seconds]);
    $min = $arrdate[minutes];
    $period = $periods[$min];

    // increase the date by 1 if period is between midnight and 02:00
    if ( $period == "00:00" || $period == "00:30" || $period == "01:00" || $period == "01:30" || $period == "02:00")
    {
        $newdate = mktime(0,0,0,$mon,$day+1,$year);
    }
    else
    {
        $newdate = mktime(0,0,0,$mon,$day,$year);
    }

    // parse the new date
    $newdatearr = getdate( $newdate );
    $newyear    = $newdatearr[year];
    $newmon     = sprintf("%02d", $newdatearr[mon]);
    $newday     = sprintf("%02d", $newdatearr[mday]);

    // create the date and time
    //$date = $newyear. "-" . $newmon . "-" . $newday . " " . $period . ":" . $sec;

    $date_arr['year'] = $newyear;
    $date_arr['month'] = $newmon;
    $date_arr['day'] = $newday;
    $date_arr['hour'] = substr($period,0,2);
    $date_arr['minute'] = substr($period,3,2);

    return $date_arr;
}

// This function converts date (utf date format) into regular date time string format (yyyy-mm-dd hh:mm:ss)
// example: convert 1238517780 to 2009-03-31 12:43:00
function utf_date_2_date ( $utf_date )
{
    $arrdate = (  getdate($utf_date) );

    // add leading zeroes
    //
    $mon    = sprintf("%02d", $arrdate[mon]);
    $day    = sprintf("%02d", $arrdate[mday]);
    $hour   = sprintf("%02d", $arrdate[hours]);
    $minute = sprintf("%02d", $arrdate[minutes]);
    $sec    = sprintf("%02d", $arrdate[seconds]);

    $date   = $arrdate[year] . "-" . $mon . "-" . $day . " " . $hour . ":" . $minute . ":" . $sec;

    return $date;
}

function utf_date_2_date_arr ( $utf_date )
{
    $arrdate = (  getdate($utf_date) );

    // add leading zeroes
    //
    $mon    = sprintf("%02d", $arrdate[mon]);
    $day    = sprintf("%02d", $arrdate[mday]);
    $hour   = sprintf("%02d", $arrdate[hours]);
    $minute = sprintf("%02d", $arrdate[minutes]);
    $sec    = sprintf("%02d", $arrdate[seconds]);

    $date_arr['year']   = $arrdate[year];
    $date_arr['mon']    = $mon;
    $date_arr['day']    = $day;
    $date_arr['hour']   = $hour;
    $date_arr['minute'] = $minute;
    $date_arr['sec']    = $sec;

    return $date_arr;
}

// Converts regular date into utf format
//
function date_2_utf_date($year, $month, $day, $hour, $minute )
{
    $utf_date = mktime($hour, $minute, 0, $month, $day, $year);

    return $utf_date;
}

// This function calculates period differences (in utf date format) and
// return the difference in minutes
// example: $start_period = 1238516760; //2009-03-31 01:00:00
//          $end_period   = 1238516880; // 2009-03-31 02:00:00
//          $min_diff   = calc_utf_period_diff ($end_period, $start_period);
//          echo $min_diff;   // prints 60
function calc_utf_period_diff( $upper_period, $lower_period )
{
    $time_diff    = $upper_period - $lower_period;
    $periods_diff = $time_diff/60;
    $minutes_diff = $periods_diff*30;

    return $minutes_diff;
}

// This function calculates date differences (in utf date format) and
// return the difference in minutes
// example: $start_time = 1238516760; //2009-03-31 01:00:00
//          $end_time   = 1238516880; // 2009-03-31 02:00:00
//          $min_diff   = calc_utf_date__diff ($end_time, $start_time);
//          echo $min_diff;   // prints 60
function calc_utf_date_diff( $upper_date, $lower_date )
{
    $time_diff    = $upper_date - $lower_date;
    $minutes_diff = $time_diff/60;

    return $minutes_diff;
}

function calc_room_charge ( $reservation_id )
{
    // get the room transaction details
    $query = "SELECT re.room_id, room_name, room_charge_day, room_charge_night, room_charge_drummer,
                     room_charge_employee_day, room_charge_employee_night,
                     re.start_time as start_time, re.end_time as end_time
                FROM reservation_entry re, reservation_room rm
               WHERE rm.id = re.room_id
                 AND re.id =  $reservation_id";

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
            }//weekday

            //DEBUG
            //echo "<br/><br/> <b> $room_name: $duration_hour hr  = $ $charge </b>";
        } //while
    } //if numrows

    return $charge;
    
}//function
?>
