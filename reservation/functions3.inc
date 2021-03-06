<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

// $Id: functions.inc 999 2009-01-22 00:36:30Z cimorrison $

// 20080228 wilson: create function period_date_string_yyyymmddhhmmss
//                  to convert utf8 date/time format into mysql date/time format
//                  yyyy-mm-dd hh:mm:ss

/////////////////////////////////////////
// Set timezone, if one has been provided

global $timezone;

if (isset($timezone))
{
  if (function_exists("date_default_timezone_set"))
  {
    date_default_timezone_set($timezone);
  }
  else
  {
    putenv("TZ=$timezone");
  }
}


// Print the page header
function print_header($day, $month, $year, $area, $room)
{

} // end of print_header()


function show_drop_down_cal($day, $month, $year)
{
 // If we dont know the right date then make it up
  if (!$day)
  {
    $day   = date("d");
  }
  if (!$month)
  {
    $month = date("m");
  }
  if (!$year)
  {
    $year  = date("Y");
  }
  ?>
  <script type="text/javascript">

//<![CDATA[

/*   Script inspired by "True Date Selector"
     Created by: Lee Hinder, lee.hinder@ntlworld.com

     Tested with Windows IE 6.0
     Tested with Linux Opera 7.21, Mozilla 1.3, Konqueror 3.1.0

*/

function daysInFebruary (year)
{
  // February has 28 days unless the year is divisible by four,
  // and if it is the turn of the century then the century year
  // must also be divisible by 400 when it has 29 days
  return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

//function for returning how many days there are in a month including leap years
function DaysInMonth(WhichMonth, WhichYear)
{
  var DaysInMonth = 31;
  if (WhichMonth == "4" || WhichMonth == "6" || WhichMonth == "9" || WhichMonth == "11")
  {
    DaysInMonth = 30;
  }
  if (WhichMonth == "2")
  {
    DaysInMonth = daysInFebruary( WhichYear );
  }
  return DaysInMonth;
}

//function to change the available days in a months
function ChangeOptionDays(formObj, prefix)
{
  var DaysObject = eval("formObj." + prefix + "day");
  var MonthObject = eval("formObj." + prefix + "month");
  var YearObject = eval("formObj." + prefix + "year");

  if (typeof DaysObject.selectedIndex == 'number' && DaysObject.options)
  { // The DOM2 standard way
    // alert("The DOM2 standard way");
    var DaySelIdx = DaysObject.selectedIndex;
    var Month = parseInt(MonthObject.options[MonthObject.selectedIndex].value);
    var Year = parseInt(YearObject.options[YearObject.selectedIndex].value);
  }
  else if (DaysObject.selectedIndex && DaysObject[DaysObject.selectedIndex])
  { // The legacy MRBS way
    // alert("The legacy MRBS way");
    var DaySelIdx = DaysObject.selectedIndex;
    var Month = parseInt(MonthObject[MonthObject.selectedIndex].value);
    var Year = parseInt(YearObject[YearObject.selectedIndex].value);
  }
  else if (DaysObject.value)
  { // Opera 6 stores the selectedIndex in property 'value'.
    // alert("The Opera 6 way");
    var DaySelIdx = parseInt(DaysObject.value);
    var Month = parseInt(MonthObject.options[MonthObject.value].value);
    var Year = parseInt(YearObject.options[YearObject.value].value);
  }

  // alert("Day="+(DaySelIdx+1)+" Month="+Month+" Year="+Year);

  var DaysForThisSelection = DaysInMonth(Month, Year);
  var CurrentDaysInSelection = DaysObject.length;
  if (CurrentDaysInSelection > DaysForThisSelection)
  {
    for (i=0; i<(CurrentDaysInSelection-DaysForThisSelection); i++)
    {
      DaysObject.options[DaysObject.options.length - 1] = null
    }
  }
  if (DaysForThisSelection > CurrentDaysInSelection)
  {
    for (i=0; i<DaysForThisSelection; i++)
    {
      DaysObject.options[i] = new Option(eval(i + 1));
    }
  }
  if (DaysObject.selectedIndex < 0)
  {
    DaysObject.selectedIndex = 0;
  }
  if (DaySelIdx >= DaysForThisSelection)
  {
    DaysObject.selectedIndex = DaysForThisSelection-1;
  }
  else
  {
    DaysObject.selectedIndex = DaySelIdx;
  }
}

//]]>
    </script>

        <form action="day.php" method="get" id="Form1">
          <div>
            <?php
            genDateSelector("", $day, $month, $year); // Note: The 1st arg must match the last arg in the call to ChangeOptionDays below.
            ?>
            <script type="text/javascript">
              //<![CDATA[
              // fix number of days for the $month/$year that you start with
              ChangeOptionDays(document.getElementById('Form1'), ''); // Note: The 2nd arg must match the first in the call to genDateSelector above.
              //]]>
            </script>
            <input type="submit" value="<?php echo get_vocab("goto") ?>">
          </div>
        </form>
<?php
}
function toTimeString(&$dur, &$units)
{
  if ($dur >= 60)
  {
    $dur /= 60;

    if ($dur >= 60)
    {
      $dur /= 60;

      if(($dur >= 24) && ($dur % 24 == 0))
      {
        $dur /= 24;

        if(($dur >= 7) && ($dur % 7 == 0))
        {
          $dur /= 7;

          if (($dur >= 52) && ($dur % 52 == 0))
          {
            $dur  /= 52;
            $units = get_vocab("years");
          }
          else
          {
            $units = get_vocab("weeks");
          }
        }
        else
        {
          $units = get_vocab("days");
        }
      }
      else
      {
        $units = get_vocab("hours");
      }
    }
    else
    {
      $units = get_vocab("minutes");
    }
  }
  else
  {
    $units = get_vocab("seconds");
  }
}


function toPeriodString($start_period, &$dur, &$units)
{
  global $enable_periods;
  global $periods;

  $max_periods = count($periods);

  $dur /= 60;

  if ( $dur >= $max_periods || $start_period == 0 )
  {
    if( $start_period == 0 && $dur == $max_periods )
    {
      $units = get_vocab("days");
      $dur = 1;
      return;
    }

    $dur /= 60;
    if(($dur >= 24) && is_int($dur))
    {
      $dur /= 24;
      $units = get_vocab("days");
      return;
    }
    else
    {
      $dur *= 60;
      $dur = ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
      $units = get_vocab("periods");
      return;
    }
  }
  else
  {
    $units = get_vocab("periods");
  }
}



function genDateSelector($prefix, $day, $month, $year)
{
  if($day   == 0)
  {
    $day = date("d");
  }
  if($month == 0)
  {
    $month = date("m");
  }
  if ($year  == 0)
  {
    $year = date("Y");
  }

  echo "
                  <select name=\"${prefix}day\">";

  for ($i = 1; $i <= 31; $i++)
  {
    echo "
                    <option" . ($i == $day ? " selected=\"selected\"" : "") . ">$i</option>";
  }

  echo "
                  </select>

                  <select name=\"${prefix}month\" onchange=\"ChangeOptionDays(this.form,'$prefix')\">";

  for ($i = 1; $i <= 12; $i++)
  {
    $m = utf8_strftime("%b", mktime(0, 0, 0, $i, 1, $year));

    print "
                    <option value=\"$i\"" . ($i == $month ? " selected=\"selected\"" : "") . ">$m</option>";
  }

  echo "
                  </select>
             <select name=\"${prefix}year\" onchange=\"ChangeOptionDays(this.form,'$prefix')\">";

  $min = min($year, date("Y")) - 5;
  $max = max($year, date("Y")) + 5;

  for ($i = $min; $i <= $max; $i++)
  {
    print "
                    <option value=\"$i\"" . ($i == $year ? " selected=\"selected\"" : "") . ">$i</option>";
  }

  echo "
                  </select>";
}

// Error handler - this is used to display serious errors such as database
// errors without sending incomplete HTML pages. This is only used for
// errors which "should never happen", not those caused by bad inputs.
// If $need_header!=0 output the top of the page too, else assume the
// caller did that. Alway outputs the bottom of the page and exits.
function fatal_error($need_header, $message)
{
  if ($need_header)
  {
    print_header(0, 0, 0, 0, "");
  }
  echo $message;
  include "trailer.inc";
  exit;
}

// Remove backslash-escape quoting if PHP is configured to do it with
// magic_quotes_gpc. Use this whenever you need the actual value of a GET/POST
// form parameter (which might have special characters) regardless of PHP's
// magic_quotes_gpc setting.
function unslashes($s)
{
  if (get_magic_quotes_gpc())
  {
    return stripslashes($s);
  }
  else
  {
    return $s;
  }
}

// Return a default area; used if no area is already known. This returns the
// lowest area ID in the database (no guaranty there is an area 1).
// This could be changed to implement something like per-user defaults.
function get_default_area()
{
  global $tbl_area;
  $area = sql_query1("SELECT id FROM $tbl_area ORDER BY area_name LIMIT 1");
  return ((!isset($area) || ($area < 0)) ? 0 : $area);
}

// Return a default room given a valid area; used if no room is already known.
// This returns the first room in alphbetic order in the database.
// This could be changed to implement something like per-user defaults.
function get_default_room($area)
{
  global $tbl_room;
  $room = sql_query1("SELECT id FROM $tbl_room WHERE area_id=$area ORDER BY sort_order LIMIT 1");
  return ($room < 0 ? 0 : $room);
}

// Get the local day name based on language. Note 2000-01-02 is a Sunday.
function day_name($daynumber)
{
  return utf8_strftime("%A", mktime(0,0,0,1,2+$daynumber,2000));
}

function hour_min_format()
{
  global $twentyfourhour_format;
  if ($twentyfourhour_format)
  {
    return "%H:%M";
  }
  else
  {
    return "%I:%M%p";
  }
}

function period_date_string($t, $mod_time=0)
{
  global $periods;

  $time = getdate($t);
  //DEBUG print_r ( $time );
  $p_num = $time["minutes"] + $mod_time;

  if( $p_num < 0 )
  {
    $p_num = 0;
  }
  if( $p_num >= count($periods) - 1 )
  {
    $p_num = count($periods ) - 1;
  }
  // I have made the separater a ',' as a '-' leads to an ambiguious
  // display in report.php when showing end times.
  //DEBUG return array($p_num, $periods[$p_num] . utf8_strftime(", %A %d %B %Y",$t));
  return array($p_num, $periods[$p_num] . utf8_strftime(", %A %d %B %Y",$t));


}

// 20080228 wilson: create function period_date_string_yyyymmddhhmmss
//                  to convert utf8 date/time format into mysql date/time format
//                  yyyy-mm-dd hh:mm:ss
function period_date_string_yyyymmddhhmmss($t, $mod_time=0)
{
  global $periods;

  $time = getdate($t);
  //DEBUG print_r ( $time );
  $p_num = $time["minutes"] + $mod_time;

  if( $p_num < 0 )
  {
    $p_num = 0;
  }
  if( $p_num >= count($periods) - 1 )
  {
    $p_num = count($periods ) - 1;
  }

  return array($p_num, utf8_strftime("%Y-%m-%d ",$t) . $periods[$p_num]. ":00");

}

// 20080228 wilson: create function period_date_string_yyyymmdd
//                  to convert utf8 date/time format into yyyymmdd format
function period_date_string_yyyymmdd($t, $mod_time=0)
{
  global $periods;

  $time = getdate($t);
  //DEBUG print_r ( $time );
  $p_num = $time["minutes"] + $mod_time;

  if( $p_num < 0 )
  {
    $p_num = 0;
  }
  if( $p_num >= count($periods) - 1 )
  {
    $p_num = count($periods ) - 1;
  }

  return utf8_strftime("%Y%m%d ",$t);

}

// 20080228 wilson: create function period_date_string_yyyymmdd
//                  to convert utf8 date/time format into hhmmss format
function period_date_string_hhmmss($t, $mod_time=0)
{
  global $periods;

  $time = getdate($t);
  //DEBUG print_r ( $time );
  $p_num = $time["minutes"] + $mod_time;

  if( $p_num < 0 )
  {
    $p_num = 0;
  }
  if( $p_num >= count($periods) - 1 )
  {
    $p_num = count($periods ) - 1;
  }

  return $periods[$p_num]. ":00";

}

function period_time_string($t, $mod_time=0)
{
  global $periods;

  $time = getdate($t);
  $p_num = $time["minutes"] + $mod_time;
  if ( $p_num < 0 )
  {
    $p_num = 0;
  }
  if ( $p_num >= count($periods) - 1 )
  {
    $p_num = count($periods ) - 1;
  }
  return $periods[$p_num];
}

function time_date_string($t)
{
  global $twentyfourhour_format;

  if ($twentyfourhour_format)
  {
    return utf8_strftime("%H:%M:%S - %A %d %B %Y",$t);
  }
  else
  {
    return utf8_strftime("%I:%M:%S%p - %A %d %B %Y",$t);
  }
}

// 20080228 wilson: create function period_date_string_yyyymmddhhmmss
//                  to convert utf8 date/time format into mysql date/time format
//                  yyyy-mm-dd hh:mm:ss
function period_to_datetime ($t, $mod_time=0)
{
  global $periods;

  $time = getdate($t);
  //DEBUG print_r ( $time );
  $p_num = $time["minutes"] + $mod_time;

  if( $p_num < 0 )
  {
    $p_num = 0;
  }
  if( $p_num >= count($periods) - 1 )
  {
    $p_num = count($periods ) - 1;
  }

  $hhmm = str_replace(":", "", $periods[$p_num]);

  return utf8_strftime("%Y%m%d ",$t) . $hhmm . "00";

}

// Output a start table cell tag <td> with color class.
// $colclass is an entry type (A-J), zebra stripes if
// empty or row_highlight if highlighted.
// $slots is the number of time slots high that the cell should be
function tdcell($colclass, $slots)
{
  echo "<td class=\"$colclass\"";
  if ($slots > 1)
  // No need to output more HTML than necessary
  {
    echo " rowspan=\"$slots\"";
  }
  echo ">\n";
}

// Display the entry-type color key. This has up to 2 rows, up to 5 columns.
function show_colour_key()
{
  global $typel;
  // set the table width.   Default is 5, but try and avoid rows of unequal length
  switch (count($typel))
  {
    case '6':
      $table_width = 3;
      break;
    case '8':
    case '12':
      $table_width = 4;
      break;
    default:
      $table_width = 5;
  }
  echo "<table id=\"colour_key\"><tr>\n";
  $nct = 0;
  for ($ct = "A"; $ct <= "Z"; $ct++)
  {
    if (!empty($typel[$ct]))
    {
      if (++$nct > $table_width)
      {
        $nct = 1;
        echo "</tr><tr>";
      }
      tdcell($ct, 1);
      echo "<div class=\"celldiv1\" " .  // put the description inside a div which will give clipping in case of long names
      "title=\"$typel[$ct]\">\n";        // but put the name in the title so you can still read it all if you hover over it
      echo "$typel[$ct]</div></td>\n";
    }
  }
  // If there is more than one row and the bottom row isn't complete then
  // pad it out with a single merged cell
  if ((count($typel) > $table_width) && ($nct < $table_width))
  {
    echo "<td colspan=\"" . ($table_width - $nct) . "\"" .
        " id=\"row_padding\">&nbsp;</td>\n";
  }
  echo "</tr></table>\n";
}

// Round time down to the nearest resolution
function round_t_down($t, $resolution, $am7)
{
  return (int)$t - (int)abs(((int)$t-(int)$am7)
                            % $resolution);
}

// Round time up to the nearest resolution
function round_t_up($t, $resolution, $am7)
{
  if (($t-$am7) % $resolution != 0)
  {
    return $t + $resolution - abs(((int)$t-(int)
                                   $am7) % $resolution);
  }
  else
  {
    return $t;
  }
}

// generates some html that can be used to select which area should be
// displayed.
function make_area_select_html( $link, $current, $year, $month, $day )
{
  global $tbl_area;
  $out_html = "
<form id=\"areaChangeForm\" method=\"get\" action=\"$link\">
  <div>
    <select name=\"area\" onchange=\"document.forms['areaChangeForm'].submit()\">";

    $sql = "select id, area_name from $tbl_area order by area_name";
    $res = sql_query($sql);
    if ($res)
    {
      for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
      {
        $selected = ($row['id'] == $current) ? "selected=\"selected\"" : "";
        $out_html .= "
      <option $selected value=\"". $row['id']. "\">" . htmlspecialchars($row['area_name']) . "</option>";
      }
    }
    $out_html .= "
    </select>

    <input type=\"hidden\" name=\"day\"   value=\"$day\">
    <input type=\"hidden\" name=\"month\" value=\"$month\">
    <input type=\"hidden\" name=\"year\"  value=\"$year\">
    <input type=\"submit\" value=\"".get_vocab("change")."\">
    </div>
</form>\n";

  return $out_html;
} // end make_area_select_html

function make_room_select_html( $link, $area, $current, $year, $month, $day )
{
  global $tbl_room;
  $out_html = "
<form id=\"roomChangeForm\" method=\"get\" action=\"$link\">
  <div>
    <select name=\"room\" onchange=\"document.forms['roomChangeForm'].submit()\">";

    $sql = "select id, room_name from $tbl_room where area_id=$area order by sort_order";
    $res = sql_query($sql);
    if ($res)
    {
      for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
      {
        $selected = ($row['id'] == $current) ? "selected=\"selected\"" : "";
        $out_html .= "
      <option $selected value=\"". $row['id']. "\">" . htmlspecialchars($row['room_name']) . "</option>";
      }
    }
    $out_html .= "
    </select>
    <input type=\"hidden\" name=\"day\"   value=\"$day\">
    <input type=\"hidden\" name=\"month\" value=\"$month\">
    <input type=\"hidden\" name=\"year\"  value=\"$year\">
    <input type=\"hidden\" name=\"area\"  value=\"$area\">
    <input type=\"submit\" value=\"".get_vocab("change")."\">
  </div>
</form>\n";

  return $out_html;
} // end make_area_select_html


// This will return the appropriate value for isdst for mktime().
// The order of the arguments was chosen to match those of mktime.
// hour is added so that this function can when necessary only be
// run if the time is between midnight and 3am (all DST changes
// occur in this period.
function is_dst ( $month, $day, $year, $hour="-1" )
{
  if ( $hour != -1  && $hour > 3)
  {
    return( -1 );
  }

  // entering DST
  if( !date( "I", mktime(12, 0, 0, $month, $day-1, $year)) &&
      date( "I", mktime(12, 0, 0, $month, $day, $year)))
  {
    return( 0 );
  }

  // leaving DST
  else if( date( "I", mktime(12, 0, 0, $month, $day-1, $year)) &&
           !date( "I", mktime(12, 0, 0, $month, $day, $year)))
  {
    return( 1 );
  }
  else
  {
    return( -1 );
  }
}

// if crossing dst determine if you need to make a modification
// of 3600 seconds (1 hour) in either direction
function cross_dst ( $start, $end )
{
  // entering DST
  if ( !date( "I", $start) &&  date( "I", $end))
  {
    $modification = -3600;
  }

  // leaving DST
  else if(  date( "I", $start) && !date( "I", $end))
  {
    $modification = 3600;
  }
  else
  {
    $modification = 0;
  }

  return $modification;
}

// checks whether a given day of the week is supposed to be hidden in the display
function is_hidden_day ($dow)
{
  global $hidden_days;
  return (isset($hidden_days) && in_array($dow, $hidden_days));
}

// show color legend
function show_color_legend ()
{
    /*
     *     'A' => "#6699CC", //blue    - reservation set
    'B' => "#99CC00", //green   - checked in
    'C' => "#FF6600", //orange  - less than 30 mins late
    'D' => "#999999", //gray    - checked out
    'E' => "#CC0000", //red     - more than 30 mins late
     */
    include ("Themes/default.inc");
    echo "<table border=1>";
    echo "<tr bgcolor='" . $color_types['A'] . "'><td>&nbsp;<b>Reserved</b>&nbsp;</td></tr>";
    echo "<tr bgcolor='" . $color_types['B'] . "'><td>&nbsp;<b>In-progress</b>&nbsp;</td></tr>";
    echo "<tr bgcolor='" . $color_types['C'] . "'><td>&nbsp;<b>Late (under 30 mins)</b>&nbsp;</td></tr>";
    echo "<tr bgcolor='" . $color_types['E'] . "'><td>&nbsp;<b>Late (over 30 mins)</b>&nbsp;</td></tr>";
    echo "<tr bgcolor='" . $color_types['D'] . "'><td>&nbsp;<b>Completed</b>&nbsp;</td></tr>";
    echo "</table>";

    //foreach ($color_types as $color)
    //{
    //    echo $color[1];
    //}
}
?>
