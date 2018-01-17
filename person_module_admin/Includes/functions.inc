<?php
/*
 * function.inc
 * 
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

//include ("../reservation/config.inc.php");


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
?>