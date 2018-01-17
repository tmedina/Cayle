<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// $Id: view_entry.php 994 2009-01-14 21:48:50Z jberanek $
// 20090228 wilson: removed -1 since it is creating inaccurate end time
// 20090302 wilson: removed description
// 20090302 wilson: show hours instead of periods
// 20090302 wilson: removed type
// 20090303 wilson: added comments
// 20090319 wilson: added fname and lname
// 20090320 wilson: removed copy and copy series
// 20090323 wilson: show check-in and check-out on todays reservation
// 20090327 wilson: show None for null data
// 20090328 wilson: show add or edit equipment links
// 20090330 wilson: set return url to go to calendar view
// 20090331 wilson: do not allow user to edit/cancel series once it's chekced in
// 20090331 wilson: add view calendar link
// 20090401 wilson: do not allow user to cancel entry once it's checked in
// 20090401 wilson: do not show band name if band is None
// 20090408 wilson: add view invoice link
// 20090610 wilson: change $my_curr_ds to 03:00 am
// 20090610 wilson: add temporary logic to allow data rebuild.

require_once "grab_globals.inc.php";
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include ("../reservation/themes/default.inc");



// Get form variables
$day = get_form_var('day', 'int');
$month = get_form_var('month', 'int');
$year = get_form_var('year', 'int');
$area = get_form_var('area', 'int');
$room = get_form_var('room', 'int');
$id = get_form_var('id', 'int');
$series = get_form_var('series', 'int');


if (empty($area))
{
  $area = get_default_area();
}


if (empty($series))
{
  $series = 0;
}
else
{
  $series = 1;
}

if ($series)
{
  $sql = "
   SELECT $tbl_repeat.name,
          $tbl_repeat.description,
          $tbl_repeat.create_by,
          $tbl_room.room_name,
          $tbl_area.area_name,
          $tbl_repeat.type,
          $tbl_repeat.room_id,
          " . sql_syntax_timestamp_to_unix("$tbl_repeat.timestamp") . " AS last_updated,
          ($tbl_repeat.end_time - $tbl_repeat.start_time) AS duration,
          $tbl_repeat.start_time,
          $tbl_repeat.end_time,
          $tbl_repeat.rep_type,
          $tbl_repeat.end_date,
          $tbl_repeat.rep_opt,
          $tbl_repeat.rep_num_weeks

   FROM  $tbl_repeat, $tbl_room, $tbl_area
   WHERE $tbl_repeat.room_id = $tbl_room.id
      AND $tbl_room.area_id = $tbl_area.id
      AND $tbl_repeat.id=$id
   ";
}
else
{
  $sql = "
   SELECT $tbl_entry.name,
          $tbl_entry.description,
          $tbl_entry.create_by,
          $tbl_room.room_name,
          $tbl_area.area_name,
          $tbl_entry.type,
          $tbl_entry.room_id,
          " . sql_syntax_timestamp_to_unix("$tbl_entry.timestamp") . " AS last_updated,
          ($tbl_entry.end_time - $tbl_entry.start_time) AS duration,
          $tbl_entry.start_time,
          $tbl_entry.end_time,
          $tbl_entry.repeat_id,
          $tbl_entry.comments,
          $tbl_entry.actual_start_time,
          $tbl_entry.actual_end_time,
          person.fname,
          person.lname,
          band.band_name

   FROM  $tbl_entry, $tbl_room, $tbl_area, person, band
   WHERE $tbl_entry.room_id = $tbl_room.id
      AND $tbl_room.area_id = $tbl_area.id
      AND $tbl_entry.person_id = person.id
      AND $tbl_entry.band_id = band.id
      AND $tbl_entry.id=$id
   ";
}

$res = sql_query($sql);
if (! $res)
{
  fatal_error(0, sql_error());
}

if (sql_count($res) < 1)
{
  fatal_error(0,
              ($series ? get_vocab("invalid_series_id") : get_vocab("invalid_entry_id"))
    );
}

$row = sql_row_keyed($res, 0);
sql_free($res);

$name         = htmlspecialchars($row['name']);
$description  = htmlspecialchars($row['description']);
$create_by    = htmlspecialchars($row['create_by']);
$room_name    = htmlspecialchars($row['room_name']);
$area_name    = htmlspecialchars($row['area_name']);
$type         = $row['type'];
$room_id      = $row['room_id'];
$updated      = time_date_string($row['last_updated']);
// need to make DST correct in opposite direction to entry creation
// so that user see what he expects to see
$duration     = $row['duration'] - cross_dst($row['start_time'],
                                             $row['end_time']);                                     
$comments     = htmlspecialchars($row['comments']);
$fname        = $row['fname'];
$lname        = $row['lname'];
$band_name    = $row['band_name'];
$band_name_disp = $band_name == "None" ? "" : "($band_name)";

$start_time   = $row['start_time'];
$end_time     = $row['end_time'];

// If we dont know the right date then use the end date of the reservation 
if (!isset($day) or !isset($month) or !isset($year))
{
  $day   = date("d", $end_time);
  $month = date("m", $end_time);
  $year  = date("Y", $end_time);
}
print_header($day, $month, $year, $area, isset($room) ? $room : "");
include ("../includes/header.inc");

echo "<div id=\"page\" align=\"center\">";

if (isset($row['actual_start_time']) || $row['actual_start_time'] != NULL )
{
    $actual_start_time = time_date_string($row['actual_start_time']);
}
else
{
    $actual_start_time = NULL;
}
if (isset($row['actual_end_time']) || $row['actual_end_time'] != NULL )
{
    $actual_end_time = time_date_string($row['actual_end_time']);
}
else
{
    $actual_end_time = NULL;
}

if ($enable_periods)
{
  list($start_period, $start_date) =  period_date_string($start_time);
}
else
{
  $start_date = time_date_string($start_time);
}

if ($enable_periods)
{
  // 20090228 wilson: removed -1 since it is creating inaccurate end time
  //list( , $end_date) =  period_date_string($row['end_time'], -1);
  list( $end_period, $end_date) =  period_date_string($row['end_time']);
  //echo "DEBUG end date: " . $end_date . " AND end period: " . $end_period . "\n";
}
else
{
  $end_date = time_date_string($row['end_time']);
}


$rep_type = 0;

if ($series == 1)
{
  $rep_type     = $row['rep_type'];
  $rep_end_date = utf8_strftime('%A %d %B %Y',$row['end_date']);
  $rep_opt      = $row['rep_opt'];
  $rep_num_weeks = $row['rep_num_weeks'];
  // I also need to set $id to the value of a single entry as it is a
  // single entry from a series that is used by del_entry.php and
  // edit_entry.php
  // So I will look for the first entry in the series where the entry is
  // as per the original series settings
  $sql = "SELECT id
          FROM $tbl_entry
          WHERE repeat_id=\"$id\" AND entry_type=\"1\"
          ORDER BY start_time
          LIMIT 1";
  $res = sql_query($sql);
  if (! $res)
  {
    fatal_error(0, sql_error());
  }
  if (sql_count($res) < 1)
  {
    // if all entries in series have been modified then
    // as a fallback position just select the first entry
    // in the series
    // hopefully this code will never be reached as
    // this page will display the start time of the series
    // but edit_entry.php will display the start time of the entry
    sql_free($res);
    $sql = "SELECT id
            FROM $tbl_entry
            WHERE repeat_id=\"$id\"
            ORDER BY start_time
            LIMIT 1";
    $res = sql_query($sql);
    if (! $res)
    {
      fatal_error(0, sql_error());
    }
  }
  $row = sql_row_keyed($res, 0);
  $id = $row['id'];
  sql_free($res);
}
else
{
  $repeat_id = $row['repeat_id'];

  if ($repeat_id != 0)
  {
    $res = sql_query("SELECT rep_type, end_date, rep_opt, rep_num_weeks
                      FROM $tbl_repeat WHERE id=$repeat_id");
    if (! $res)
    {
      fatal_error(0, sql_error());
    }

    if (sql_count($res) == 1)
    {
      $row = sql_row_keyed($res, 0);

      $rep_type     = $row['rep_type'];
      $rep_end_date = utf8_strftime('%A %d %B %Y',$row['end_date']);
      $rep_opt      = $row['rep_opt'];
      $rep_num_weeks = $row['rep_num_weeks'];
    }
    sql_free($res);
  }
}


$enable_periods ? toPeriodString($start_period, $duration, $dur_units) : toTimeString($duration, $dur_units);

$repeat_key = "rep_type_" . $rep_type;

// Now that we know all the data we start drawing it

?>

<!-- 20090303 wilson: added fname and lname -->
<h2 align="left"><?php echo "Reservation details for " . $fname . " " . $lname . " " . $band_name_disp ?></h2>

<table class="view" border="1" width="100%" cellpadding="5" cellspacing="0">
<tr id="wrapper">
<td id="room_info" width="33%">
<h3>Room</h3>
<hr>
 <table id="entry">
   <!-- 20090302 wilson: removed description
   <tr>
    <td><?php echo get_vocab("description") ?>:</td>
    <td><?php echo nl2br($description) ?></td>
   </tr>
   -->

  

   <tr>
    <td><?php echo get_vocab("room") ?>:</td>
    <td><?php    echo  nl2br($area_name . " - " . $room_name) ?></td>
   </tr>
   <tr>
    <td><?php echo get_vocab("start_date") ?>:</td>
    <td><?php    echo $start_date ?></td>
   </tr>
   <tr>
    <td><?php echo get_vocab("duration") ?>:</td>
    <!-- 20090302 wilson: show hours instead of periods-->
    <td>
    <?php
      if ( $dur_units == "days" ) {
          echo $duration . " " . $dur_units;
      }
      else {
          echo $arr_period_time_diff[$duration];
      }
    ?>
    </td>
   </tr>
   <tr>
    <td><?php echo get_vocab("end_date") ?>:</td>
    <td><?php    echo $end_date ?></td>
   </tr>
   <!-- 20090302 wilson: removed type
   <tr>
    <td><?php echo get_vocab("type") ?>:</td>
    <td><?php    echo empty($typel[$type]) ? "?$type?" : $typel[$type] ?></td>
   </tr>
   -->

   <!-- 20090403 wilson: removed createby
   <tr>
    <td><?php echo get_vocab("createdby") ?>:</td>
    <td><?php    echo $create_by ?></td>
   </tr>
   -->
   
   <tr>
    <td><?php echo get_vocab("lastupdate") ?>:</td>
    <td><?php    echo $updated ?></td>
   </tr>
   <tr>
    <td><?php echo get_vocab("rep_type") ?>:</td>
    <td><?php    echo get_vocab($repeat_key) ?></td>
   </tr>
   <tr>
    <td><?php echo get_vocab("actual_start_time") ?>:</td>
    <td><?php   echo isset($actual_start_time) ? $actual_start_time : "None" ?></td>
   </tr>
   <tr>
    <td><?php echo get_vocab("actual_end_time") ?>:</td>
    <td><?php    echo isset($actual_end_time) ? $actual_end_time : "None" ?></td>
   </tr>
    <!-- 20090303 wilson: added comments -->
   <tr>
    <td><?php echo get_vocab("comments") ?>:</td>
    <td><?php echo nl2br($comments) ?></td>
   </tr>
<?php

if($rep_type != 0)
{
  $opt = "";
  if (($rep_type == 2) || ($rep_type == 6))
  {
    // Display day names according to language and preferred weekday start.
    for ($i = 0; $i < 7; $i++)
    {
      $daynum = ($i + $weekstarts) % 7;
      if ($rep_opt[$daynum])
      {
        $opt .= day_name($daynum) . " ";
      }
    }
  }
  if ($rep_type == 6)
  {
    echo "<tr><td>".get_vocab("rep_num_weeks")." ".get_vocab("rep_for_nweekly").":</td><td>$rep_num_weeks</td></tr>\n";
  }

  if ($opt)
  {
    echo "<tr><td>".get_vocab("rep_rep_day").":</td><td>$opt</td></tr>\n";
  }

  echo "<tr><td>".get_vocab("rep_end_date").":</td><td>$rep_end_date</td></tr>\n";
}

?>
</table>
</td> <!-- end room_info -->
<td id="equip_info" width="33%" valign="top">
<h3>Equipment</h3>
<hr>
<?
// Get all the equipments reserved with this reservation
//
$sql = "SELECT equip_type, equip_manufacturer, equip_model, equip_description
          FROM reservation_transaction AS rt, reservation_entry AS re, equipment AS eq
         WHERE rt.reservation_entry_id = re.id
           AND rt.equipment_id = eq.id
           AND re.id = $id";

$res = sql_query($sql);

//echo "<tr>";
//echo "<td>Equipment(s):</td><td>\n";

if ( $res )
//if (mysql_num_rows($res)>0)
{

    while($row=mysql_fetch_array($res))
    {
        $eq_type  = $row['equip_type'];
        $eq_manu  = $row['equip_manufacturer'];
        $eq_model = $row['equip_model'];
        $eq_desc  = $row['equip_description'];

        //echo "<table><tr>";
        //echo "<td>$eq_type</td>\n";
        //echo "<td>$eq_manu</td>\n";
        //echo "<td>$eq_model</td>\n";
        //echo "<td>$eq_desc</td>\n";
        //echo "</tr></table>";
        echo "$eq_desc" . "<br/>";
    }
    sql_free($res);
}
else
{
    echo "None";
}
//echo "</td></tr>\n";
?>
</td> <!-- end equip_info -->
<td id="bar_info" width="33%" valign="top">
<h3>Bar charges</h3>
<hr>
<?php
/*
    // Query bar charge transactions for this reservation id
    $sql = "SELECT rt.id AS rt_id, rt.bar_charge_id AS bc_id, rt.amount AS rt_amt, rt.qty AS rt_qty,
                     bc.name AS bc_name, bc.amount AS bc_amt
                FROM reservation_transaction rt, bar_charge bc
               WHERE rt.bar_charge_id = bc.id
                 AND rt.reservation_entry_id = $id
                 AND rt.bar_charge_id > 0
                 AND rt.is_active = 1";

    $res = sql_query($sql);

    //echo "<tr>";
    //echo "<td>Equipment(s):</td><td>\n";

    if ( $res )
    //if (mysql_num_rows($res)>0)
    {
        $tot_rt_amt = 0;
        echo "<table>";
        while($row=mysql_fetch_array($res))
        {
            $tot_rt_amt += $row[rt_amt];
            echo "<tr>";
            echo "<td>$row[bc_name] x $row[rt_qty] </td>";
            echo "<td>= \$" . number_format($row[rt_amt],2) . "</td>";
            echo "</tr>";
        }
        echo "<tr><td></td></tr>";
        echo "<tr><td>TOTAL </td><td>= \$" . number_format($tot_rt_amt,2) . "</td></tr>";
        echo "</table>";
        sql_free($res);
    }
    else
    {
        echo "None";
    }
    //echo "</td></tr>\n";
*/
$refresh_page="view_entry.php";
include ("bar_charge_info.php");

?>
</td> <!-- end bar info -->


<?php

// Need to tell all the links where to go back to after an edit or delete
//if (isset($HTTP_REFERER))
//{
//  $returl = $HTTP_REFERER;
//}
// If we haven't got a referer (eg we've come here from an email) then construct
// a sensible place to go to afterwards
//else
//{
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
//}
//$returl = urlencode($returl);
?>

<div id="view_entry_nav">
<tr id="action">
<td id="room_action" width="33%">
  <div>
    <?php

    if ( isset($actual_end_time) )
    {
        echo "<a href=\"../checkout/invoice.php?reservation_id=$id\">View Invoice</a>";
    }
    if ( ! isset($actual_end_time) )
    {
        if (! $series)
        {
          echo "<a href=\"edit_entry.php?id=$id&amp;returl=$returl\">". get_vocab("editentry") ."</a>";
        }

        if ($repeat_id || $series )
        {
            if (! isset($actual_start_time) || $actual_start_time == NULL )
            {
                echo " | ";
                echo "<a href=\"edit_entry.php?id=$id&amp;edit_type=series&amp;day=$day&amp;month=$month&amp;year=$year&amp;returl=$returl\" onclick=\"return confirm('This will delete this and future Series Room and Equipment reservations. Continue?');\">".get_vocab("editseries")."</a>";
            }
        }
    }
     ?>
  </div>

    <div>
    <?php
    if ( ! $series && ! isset($actual_start_time) && ! isset($actual_end_time))
    {
      echo "<a href=\"cancel_entry.php?id=$id&amp;series=0&amp;returl=$returl\">".get_vocab("cancelentry")."</a>";
    }

    if ( ($repeat_id || $series) )
    {
        if (! isset($actual_start_time) || $actual_start_time == NULL )
        {
            echo " | ";
            echo "<a href=\"cancel_entry.php?id=$id&amp;series=1&amp;day=$day&amp;month=$month&amp;year=$year&amp;returl=$returl\">".get_vocab("cancelseries")."</a>";
        }
    }

    ?>
  </div>


 <!-- remove copy and copy series
  <div>
    <?php

    // Copy and Copy series
    if ( ! $series )
    {
      echo "<a href=\"edit_entry.php?id=$id&amp;copy=1&amp;returl=$returl\">". get_vocab("copyentry") ."</a>";
    }

    if ($repeat_id)
    {
      echo " - ";
    }

    if ($repeat_id || $series )
    {
      echo "<a href=\"edit_entry.php?id=$id&amp;edit_type=series&amp;day=$day&amp;month=$month&amp;year=$year&amp;copy=1&amp;returl=$returl\">".get_vocab("copyseries")."</a>";
    }

    ?>
  </div>
  -->


  <!-- not needed
  <div>
    <?php
    if ( ! $series )
    {
      echo "<a href=\"del_entry.php?id=$id&amp;series=0&amp;returl=$returl\" onclick=\"return confirm('".get_vocab("confirmdel")."');\">".get_vocab("deleteentry")."</a>";
    }

    if ($repeat_id)
    {
      echo " - ";
    }

    if ($repeat_id || $series )
    {
      echo "<a href=\"del_entry.php?id=$id&amp;series=1&amp;day=$day&amp;month=$month&amp;year=$year&amp;returl=$returl\" onClick=\"return confirm('".get_vocab("confirmdel")."');\">".get_vocab("deleteseries")."</a>";
    }

    ?>
  </div>
  -->
  </td> <!-- end room_action -->
  <td id="equip_action">
  <div>
      <?php
      if ( ! isset($actual_end_time) )
      {
        if ( ! isset($eq_desc) )
        {
            // create the equipment reservation start and end datetime
            //
            $eq_start_time = mktime(12, $start_period, 0, $month, $day, $year);
            $eq_end_time   = mktime(12, $end_period, 0, $month, $day+1, $year);
            $return_url    = "../reservation/view_entry.php?id=$id";
            echo "<a href=\"../equipment_rental/index.php?reservation_id=$id&amp;start_time=$eq_start_time&amp;end_time=$eq_end_time&amp;return_url=$return_url&amp;action=room_equip_res\">Add/remove equipment</a>\n";
        }
        else
        {
            echo "<a href=\"../equipment_rental/view_detail.php?id=$id\">Add/remove equipment</a>\n";
        }
      }
      ?>
  </div>
  </td> <!-- end equip_action -->

  <td id="bar_action">
  <div>
    <?php
    /*
    if ( ! isset($actual_end_time))
    {
      echo "<a href=\"../bar_charge/add_to_reservation.php?id=$id\">Add/Remove Bar Charges</a>";
    }
    */
    ?>
  </div>
  </td> <!-- end bar_action -->


  </tr> <!-- end action -->
</div> <!-- end view_entry_nav -->

  </tr> <!-- end wrapper -->
</table> <!-- end wrapper -->

<div>
<?php
//if (isset($HTTP_REFERER)) //remove the link if displayed from an email
//{
?>
<div>
<br>
</div>
<div id="check_in_out">
<?php
    //20090610 change my_curr_ds to 03:00 am
    //date
    if ( date("H") == "00" || date("H") == "01" || date("H") == "02" || date("H") == "03" )
    {
        $my_curr_ds = date("Ymd") - 1;
    }
    else
    {
        $my_curr_ds = date("Ymd");
    }
    $my_start_ds   = period_date_string_yyyymmdd ( $start_time );
    $my_end_ds     = period_date_string_yyyymmdd ( $end_time );
    $my_curr_d     = (int)$my_curr_ds;
    $my_start_d    = (int)$my_start_ds;
    $my_end_d      = (int)$my_end_ds;

    //echo "my_start_d: $my_start_d my_curr_d: $my_curr_d ";

    //20090610 temporary logic to allow data rebuild.
    //if ( $my_start_d == $my_curr_d )
    //{
        if (! isset($actual_start_time) || $actual_start_time == NULL )
        {
            echo "<div>";
            echo "<a href=\"check_in.php?id=$id&amp;returl=$returl\" onclick=\"return confirm('".get_vocab("confirmcheckin")."');\">".get_vocab("checkin")."</a>";
            echo "</div>";
        }

        if ( (isset($actual_start_time) || $actual_start_time != NULL )&& (! isset($actual_end_time) || $actual_end_time == NULL ) )
        {
            echo "<div>";
            echo "<a href=\"check_out.php?id=$id&amp;returl=$returl&amp;end_time=$end_time\" onclick=\"return confirm('".get_vocab("confirmcheckout")."');\">".get_vocab("checkout")."</a>";
            echo "</div>";
        }
    //}
?>
</div>
<div id="links">
<a href="<?php echo $returl ?>">Return to calendar</a>
</div>

<?php
//}
?>
</div>
</div>
<?php
//include "trailer.inc";
?>
