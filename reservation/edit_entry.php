<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// $Id: edit_entry.php 1002 2009-01-23 20:36:13Z cimorrison $
// 20090302 wilson: removed full description text area
// 20090302 wilson: added end_period drop down
// 20090302 wilson: hide type and set default type to "I", Internal
// 20090302 wilson: removed duration field
// 20090302 wilson: add end_period to onAllDayClick
// 20090302 wilson: default dur_units to periods
// 20090303 wilson: add comments text area
// 20090320 wilson: remove mrbs authentication
// 20090325 wilson: remove brief description
// 20090327 wilson: add actual_start_time, actual_end_time, person_id, band_id, band_size
//                  so that we wont lose any info when editing a record (create new and delete old)
// 20090331 wilson: add view calendar link
// 20090401 wilson: do not show all day option if reservation is already checked in
// 20090408 wilson: add reservation_status and is_pending
// 20090416 wilson: do not allow user to make reservation in the past
// 20090415 wilson: add End Time must be greater than Start Time check
// 20090529 wilson: allow nucis to create retroactive reservations. temporarily.


require_once('grab_globals.inc.php');
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include "mrbs_auth.inc";
include ("../reservation/themes/default.inc");

global $twentyfourhour_format;

// Get form variables
$day = get_form_var('day', 'int');
$month = get_form_var('month', 'int');
$year = get_form_var('year', 'int');
$hour = get_form_var('hour', 'int');
$minute = get_form_var('minute', 'int');
$period = get_form_var('period', 'int');
$area = get_form_var('area', 'int');
$room = get_form_var('room', 'int');
$id = get_form_var('id', 'int');
$copy = get_form_var('copy', 'int');
$edit_type = get_form_var('edit_type', 'string');
$returl = get_form_var('returl', 'string');
$curday = date("d");
$curmon = date("m");
$curyear = date("Y");
$curhour = date("G");

// If we dont know the right date then make it up
if (!isset($day) or !isset($month) or !isset($year))
{
  $day   = date("d");
  $month = date("m");
  $year  = date("Y");
}

$request_date = mktime(0, 0, 0, $month, $day, $year);

if ( $curhour >= 0 && $curhour <= 1)
{
    $current_date = mktime(0, 0, 0, $curmon, $curday-1, $curyear);
}
else
{
    $current_date = mktime(0, 0, 0, $curmon, $curday, $curyear);
}

if (empty($area))
{
  $area = get_default_area();
}
if (!isset($edit_type))
{
  $edit_type = "";
}

if (!getAuthorised(1))
{
  showAccessDenied($day, $month, $year, $area, isset($room) ? $room : "");
  exit;
}

// This page will either add or modify a booking

// We need to know:
//  Name of booker
//  Description of meeting
//  Date (option select box for day, month, year)
//  Time
//  Duration
//  Internal/External

// Firstly we need to know if this is a new booking or modifying an old one
// and if it's a modification we need to get all the old data from the db.
// If we had $id passed in then it's a modification.
if (isset($id))
{
  $sql = "select name, create_by, description, start_time, end_time,
     type, room_id, entry_type, repeat_id, comments,
     actual_start_time, actual_end_time, person_id, band_id, band_size,
     reservation_status, is_pending, event_type
     from $tbl_entry where $tbl_entry.id=$id";
   
  $res = sql_query($sql);
  if (! $res)
  {
    fatal_error(1, sql_error());
  }
  if (sql_count($res) != 1)
  {
    fatal_error(1, get_vocab("entryid") . $id . get_vocab("not_found"));
  }

  $row = sql_row_keyed($res, 0);
  sql_free($res);

  $name        = $row['name'];
  $create_by   = $row['create_by'];
  $description = $row['description'];
  $start_day   = strftime('%d', $row['start_time']);
  $start_month = strftime('%m', $row['start_time']);
  $start_year  = strftime('%Y', $row['start_time']);
  $start_hour  = strftime('%H', $row['start_time']);
  $start_min   = strftime('%M', $row['start_time']);
  $duration    = $row['end_time'] - $row['start_time'] - cross_dst($row['start_time'], $row['end_time']);
  $type        = $row['type'];
  $room_id     = $row['room_id'];
  $entry_type  = $row['entry_type'];
  $rep_id      = $row['repeat_id'];
  $comments    = $row['comments'];
  $actual_start_time = $row['actual_start_time'];
  $actual_end_time   = $row['actual_end_time'];
  $person_id         = $row['person_id'];
  $band_id           = $row['band_id'];
  $band_size         = $row['band_size'];
  $reservation_status = $row['reservation_status'];
  $is_pending         = $row['is_pending'];
  $event_type         = $row['event_type'];

  if($entry_type >= 1)//if repeating entry
  {
    $sql = "SELECT rep_type, start_time, end_date, rep_opt, rep_num_weeks
            FROM $tbl_repeat WHERE id=$rep_id";
   
    $res = sql_query($sql);
    if (! $res)
    {
      fatal_error(1, sql_error());
    }
    if (sql_count($res) != 1)
    {
      fatal_error(1,
                  get_vocab("repeat_id") . $rep_id . get_vocab("not_found"));
    }

    $row = sql_row_keyed($res, 0);
    sql_free($res);
   
    $rep_type = $row['rep_type'];

    if ($edit_type == "series")
    {
      //$start_day   = (int)strftime('%d', $row['start_time']);
      //$start_month = (int)strftime('%m', $row['start_time']);
      //$start_year  = (int)strftime('%Y', $row['start_time']);
      
      $rep_end_day   = (int)strftime('%d', $row['end_date']);
      $rep_end_month = (int)strftime('%m', $row['end_date']);
      $rep_end_year  = (int)strftime('%Y', $row['end_date']);

      switch ($rep_type)
      {
        case 2:
        case 6:
          $rep_day[0] = $row['rep_opt'][0] != "0";
          $rep_day[1] = $row['rep_opt'][1] != "0";
          $rep_day[2] = $row['rep_opt'][2] != "0";
          $rep_day[3] = $row['rep_opt'][3] != "0";
          $rep_day[4] = $row['rep_opt'][4] != "0";
          $rep_day[5] = $row['rep_opt'][5] != "0";
          $rep_day[6] = $row['rep_opt'][6] != "0";

          if ($rep_type == 6)
          {
            $rep_num_weeks = $row['rep_num'];
          }

          break;

        default:
          $rep_day = array(0, 0, 0, 0, 0, 0, 0);
      }
    }
    else
    {
      $rep_type     = $row['rep_type'];
      $rep_end_date = utf8_strftime('%A %d %B %Y',$row['end_date']);
      $rep_opt      = $row['rep_opt'];
    }
  }
}
else
{
  // It is a new booking. The data comes from whichever button the user clicked
  $edit_type   = "series";
  $name        = "";
  $create_by   = getUserName();
  $description = "";
  $start_day   = $day;
  $start_month = $month;
  $start_year  = $year;
  $comments    = "";
  $is_pending  = 1; // default to pending reservation until customer is assigned.

  // Avoid notices for $hour and $minute if periods is enabled
  (isset($hour)) ? $start_hour = $hour : '';
  (isset($minute)) ? $start_min = $minute : '';
  if (!isset($default_duration))
  {
    $default_duration = (60 * 60);
  }
  $duration    = ($enable_periods ? 60 : $default_duration);
  $type        = "I";
  $room_id     = $room;
  unset($id);

  $rep_id        = 0;
  $rep_type      = 0;
  $rep_end_day   = $day;
  $rep_end_month = $month;
  $rep_end_year  = $year;
  $rep_day       = array(0, 0, 0, 0, 0, 0, 0);
}

// These next 4 if statements handle the situation where
// this page has been accessed directly and no arguments have
// been passed to it.
// If we have not been provided with a room_id
if (empty( $room_id ) )
{
  $sql = "select id from $tbl_room limit 1";
  $res = sql_query($sql);
  $row = sql_row_keyed($res, 0);
  $room_id = $row['id'];

}

// If we have not been provided with starting time
if ( empty( $start_hour ) && $morningstarts < 10 )
{
  $start_hour = "0$morningstarts";
}

if ( empty( $start_hour ) )
{
  $start_hour = "$morningstarts";
}

if ( empty( $start_min ) )
{
  $start_min = "00";
}

// Remove "Undefined variable" notice
if (!isset($rep_num_weeks))
{
  $rep_num_weeks = "";
}

$enable_periods ? toPeriodString($start_min, $duration, $dur_units) : toTimeString($duration, $dur_units);

//now that we know all the data to fill the form with we start drawing it

if (!getWritable($create_by, getUserName()))
{
  showAccessDenied($day, $month, $year, $area, isset($room) ? $room : "");
  exit;
}

print_header($day, $month, $year, $area, isset($room) ? $room : "");
include ("../includes/header.inc");

//Removed following temporarily to allow nucis to create retroactive reservations
//
/*
if ( $request_date < $current_date )
{
    echo "<div id='error_msg'> Error: Cannot create reservation in the past. <br/> </div>";
    echo "<a href=\"day.php?year=$year&amp;month=$month&amp;day=$day\">Back</a>";
    exit (0);
}
*/
?>

<script type="text/javascript">
//<![CDATA[

// do a little form verifying
function validate_and_submit ()
{
  // 20090325 wilson: remove brief description
  // null strings and spaces only strings not allowed
  //if(/(^$)|(^\s+$)/.test(document.forms["main"].name.value))
  //{
  //  alert ( "<?php echo get_vocab("you_have_not_entered") . '\n' . get_vocab("brief_description") ?>");
  //  return false;
  //}


  <?php if( ! $enable_periods ) { ?>

  h = parseInt(document.forms["main"].hour.value);
  m = parseInt(document.forms["main"].minute.value);

  if(h > 23 || m > 59)
  {
    alert ("<?php echo get_vocab("you_have_not_entered") . '\n' . get_vocab("valid_time_of_day") ?>");
    return false;
  }
  <?php } ?>

  // check form element exist before trying to access it
  if ( document.forms["main"].id )
  {
    i1 = parseInt(document.forms["main"].id.value);
  }
  else
  {
    i1 = 0;
  }

  i2 = parseInt(document.forms["main"].rep_id.value);
  if ( document.forms["main"].rep_num_weeks)
  {
     n = parseInt(document.forms["main"].rep_num_weeks.value);
  }
  if ((!i1 || (i1 && i2)) && (document.forms["main"].rep_type.value != 0) && document.forms["main"].rep_type[6].checked && (!n || n < 2))
  {
    alert("<?php echo get_vocab("you_have_not_entered") . '\n' . get_vocab("useful_n-weekly_value") ?>");
    return false;
  }
  
  if ((document.forms["main"].rep_type.value != 0) &&
      (document.forms["main"].rep_type[2].checked ||
      document.forms["main"].rep_type[6].checked))
  {
    ok = false;
    for (j=0; j < 7; j++)
    {
      if (document.forms["main"]["rep_day["+j+"]"].checked)
      {
        ok = true;
        break;
      }
    }
    
    if (ok == false)
    {
      alert("<?php echo get_vocab("you_have_not_entered") . '\n' . get_vocab("rep_rep_day") ?>");
      return false;
    }
  }

  // check that a room(s) has been selected
  // this is needed as edit_entry_handler does not check that a room(s)
  // has been chosen
  if ( document.forms["main"].elements['rooms'].selectedIndex == -1 )
  {
    alert("<?php echo get_vocab("you_have_not_selected") . '\n' . get_vocab("valid_room") ?>");
    return false;
  }

  // Make sure the end time is always > start time

  start_period = parseInt(document.forms["main"].period.value);
  end_period   = parseInt(document.forms["main"].end_period.value);
  if ( start_period >= end_period )
  {
      alert ("End Time must be greater than Start Time");
      return false;
  }
  
  // Form submit can take some times, especially if mails are enabled and
  // there are more than one recipient. To avoid users doing weird things
  // like clicking more than one time on submit button, we hide it as soon
  // it is clicked.
  document.forms["main"].save_button.disabled="true";

  // would be nice to also check date to not allow Feb 31, etc...
  document.forms["main"].submit();

  return true;
}

// set up some global variables for use by OnAllDayClick().   (It doesn't really
// matter about the initial values, but we might as well put in some sensible ones).
var old_duration = '<?php echo $duration;?>';
var old_dur_units = 0;  // This is the index number
var old_hour = '<?php if (!$twentyfourhour_format && ($start_hour > 12)){ echo ($start_hour - 12);} else { echo $start_hour;} ?>';
var old_minute = '<?php echo $start_min;?>';
var old_period = 0; // This is the index number

// Executed when the user clicks on the all_day checkbox.
// 20090302 wilson: add end_period to onAllDayClick
function OnAllDayClick(allday)
{
  var form = document.forms["main"];
  if (form.all_day.checked) // If checking the box...
  {
    // save the old values, disable the inputs and, to avoid user confusion,
    // show the start time as the beginning of the day and the duration as one day
    <?php 
    if ($enable_periods )
    {
      ?>
      old_period = form.period.selectedIndex;
      old_end_period = form.end_period.selectedIndex;
      form.period.value = 0;
      form.period.disabled = true;
      form.end_period.value = '<?php echo count($periods)-1; ?>';
      form.end_period.disabled = true;
      <?php
    }
    else
    { 
      ?>
      old_hour = form.hour.value;
      form.hour.value = '<?php echo $morningstarts; ?>';
      old_minute = form.minute.value;
      form.minute.value = '<?php printf("%02d", $morningstarts_minutes); ?>';
      form.hour.disabled = true;
      form.minute.disabled = true;
      <?php 
    } 
    ?>
    
    old_duration = form.duration.value;
    form.duration.value = '1';  
    old_dur_units = form.dur_units.selectedIndex;
    form.dur_units.value = 'days';  
    form.duration.disabled = true;
    form.dur_units.disabled = true;
  }
  else  // restore the old values and re-enable the inputs
  {
    <?php 
    if ($enable_periods)
    {
      ?>
      form.period.selectedIndex = old_period;
      form.period.disabled = false;
      form.end_period.selectedIndex = old_end_period;
      form.end_period.disabled = false;
      <?php
    }
    else
    { 
      ?>
      form.hour.value = old_hour;
      form.minute.value = old_minute;
      form.hour.disabled = false;
      form.minute.disabled = false;
      <?php 
    } 
    ?>
    form.duration.value = old_duration;
    form.dur_units.selectedIndex = old_dur_units;  
    form.duration.disabled = false;
    form.dur_units.disabled = false;
  }
}
//]]>
</script>

<?php

if (isset($id) && !isset($copy))
{
  if ($edit_type == "series")
  {
    $token = "editseries";
  }
  else
  {
    $token = "editentry";
  }
}
else
{
  if (isset($copy))
  {
    if ($edit_type == "series")
    {
      $token = "copyseries";
    }
    else
    {
      $token = "copyentry";
    }
  }
  else
  {
    $token = "addentry";
  }
}

$room_names = sql_query1("SELECT room_name FROM $tbl_room WHERE id=$room_id");

?>

<form class="form_general" id="main" action="edit_entry_handler.php" method="get">
  <fieldset>
  <legend><?php echo get_vocab($token) . " - " . $room_names ?></legend>

<!-- 20090325 wilson: remove brief description
    <div id="div_name">
      <label for="name"><?php echo get_vocab("namebooker")?>:</label>
      <input id="name" name="name" value="<?php echo htmlspecialchars($name) ?>">
    </div>
-->

<!-- 20090302 wilson: removed full description text area
    <div id="div_description">
      <label for="description"><?php echo get_vocab("fulldescription")?></label>
-->
      <!-- textarea rows and cols are overridden by CSS height and width -->
<!--
      <textarea id="description" name="description" rows="8" cols="40"><?php echo htmlspecialchars ( $description ); ?></textarea>
    </div>
-->

<!-- BEGIN ENTRY FORM -->
    <div id="room_picker">
	    <label>Room:</label>
		<select id="room_id" name="room_id" >
		<option <?php echo $room_names=="Room 7" ? "selected='true'" :  ""; ?> value="1">Room 7</option>
		<option <?php echo $room_names=="Room 8" ? "selected='true'" :  ""; ?> value="2">Room 8</option>
		<option <?php echo $room_names=="Room 9" ? "selected='true'" :  ""; ?> value="3">Room 9</option>
		<option <?php echo $room_names=="Room 10" ? "selected='true'" :  ""; ?> value="4">Room 10</option>
		</select>
	</div><!--end "event_type" div-->
    <div id="event_picker">
	    <label>Event Type:</label>
		<select id="event_type" name="event_type" >
				<!-- TODO: Provide admin function to generate/modify this list -->
				<option selected="true" value="0">Rehearsal</option>
				<option value="1">Event/Closed</option>
				<!--
				<option value="2">Benefit Show</option>
				<option value="3">Camp Amped</option>
				<option value="4">Closed</option>
				<option value="255">Other</option>
				-->
		</select>
	</div><!--end "event_type" div-->
    <div id="div_date">
      <label><?php echo get_vocab("date")?>:</label>
      <?php gendateselector("", $start_day, $start_month, $start_year) ?>
    </div>

    <?php 
    if(! $enable_periods ) 
    { 
    ?>
      <div id="div_time">
        <label><?php echo get_vocab("time")?>:</label>
        <input id="time_hour" name="hour" value="<?php if (!$twentyfourhour_format && ($start_hour > 12)){ echo ($start_hour - 12);} else { echo $start_hour;} ?>" maxlength="2">
        <span>:</span>
        <input id="time_minute" name="minute" value="<?php echo $start_min;?>" maxlength="2">
        <?php
        if (!$twentyfourhour_format)
        {
          echo "<div class=\"group\" id=\"ampm\">\n";
          $checked = ($start_hour < 12) ? "checked=\"checked\"" : "";
          echo "      <label><input name=\"ampm\" type=\"radio\" value=\"am\" $checked>" . utf8_strftime("%p",mktime(1,0,0,1,1,2000)) . "</label>\n";
          $checked = ($start_hour >= 12) ? "checked=\"checked\"" : "";
          echo "      <label><input name=\"ampm\" type=\"radio\" value=\"pm\" $checked>". utf8_strftime("%p",mktime(13,0,0,1,1,2000)) . "</label>\n";
          echo "</div>\n";
        }
        ?>
      </div>
      <?php
    }
    
    else
    {        
      ?>
      
      <div id="div_period">
        <label for="period" ><?php echo get_vocab("start_date")?>:</label>
        <select id="period" name="period">
          <?php
          foreach ($periods as $p_num => $p_val)
          {
            echo "<option value=\"$p_num\"";
            if( ( isset( $period ) && $period == $p_num ) || $p_num == $start_min)
            {
              echo " selected=\"selected\"";
            }
            echo ">$p_val</option>\n";
          }
          ?>
        </select>
      </div>

<!-- 20090302 wilson: added end_period drop down -->
      <div id="div_end_period">
        <label for="end_period" ><?php echo get_vocab("end_date")?>:</label>
        <select id="end_period" name="end_period">
          <?php
          foreach ($periods as $p_num => $p_val)
          {
            //if ( isset( $period) ) {
            //  $len = $p_num - $period;
            //}
            //else {
            //  $len = $p_num - $duration + 1;
            //}
            //if (  (isset( $period ) && $p_num > $period) || ($p_num > ($start_min + $duration)) ) // display only end_period > start period
            //{
                echo "<option value=\"$p_num\"";
                if( ( isset( $period ) && ( $period + 1 ) == $p_num ) || $p_num == ($start_min + $duration) )
                {
                  echo " selected=\"selected\"";
                }
                //echo ">$p_val ($arr_period_time_diff[$len]) </option>\n";
                echo ">$p_val </option>\n";
            //}
          }
          ?>
        </select>
      </div>

    <?php
    }
    // 20090401 wilson: do not show all day option if reservation is already checked in
    //                  unless it is an all day reservation to begin with.
    if ( ! isset($actual_start_time) )
    {
    ?>
    <div id="div_duration">
      <label for="duration"><?php echo get_vocab("duration");?>:</label>
      <div class="group">
        <!-- 20090302 wilson: removed duration field
        <input id="duration" name="duration" value="<?php echo $duration;?>">
        <select id="dur_units" name="dur_units">
          <?php
          if( $enable_periods )
          {
            $units = array("periods", "days");
          }
          else
          {
            $units = array("minutes", "hours", "days", "weeks");
          }

          while (list(,$unit) = each($units))
          {
            echo "        <option value=\"$unit\"";
            if ($dur_units == get_vocab($unit))
            {
              echo " selected=\"selected\"";
            }
            echo ">".get_vocab($unit)."</option>\n";
          }
          ?>
        </select>
        end remove -->


        <div id="ad">
          <input id="all_day" class="checkbox" name="all_day" type="checkbox" value="yes" onclick="OnAllDayClick(this)">
          <label for="all_day"><?php echo get_vocab("all_day"); ?></label>
        </div>

      </div>
    </div>
    <?php
    } // end if duration
    ?>
        <!-- 20090302 wilson: default dur_units to periods -->
        <input id="dur_units" name="dur_units" value="periods" type="hidden">

<?php
    // Determine the area id of the room in question first
    $sql = "select area_id from $tbl_room where id=$room_id";
    $res = sql_query($sql);
    $row = sql_row_keyed($res, 0);
    $area_id = $row['area_id'];
    // determine if there is more than one area
    $sql = "select id from $tbl_area";
    $res = sql_query($sql);
    $num_areas = sql_count($res);
    // if there is more than one area then give the option
    // to choose areas.
    if( $num_areas > 1 )
    {
    
    ?>
    
      <script type="text/javascript">
      //<![CDATA[
      
      function changeRooms( formObj )
      {
        areasObj = eval( "formObj.areas" );

        area = areasObj[areasObj.selectedIndex].value;
        roomsObj = eval( "formObj.elements['rooms']" );

        // remove all entries
        roomsNum = roomsObj.length;
        for (i=(roomsNum-1); i >= 0; i--)
        {
          roomsObj.options[i] = null;
        }
        // add entries based on area selected
        switch (area){
          <?php
          // get the area id for case statement
          $sql = "select id, area_name from $tbl_area order by area_name";
          $res = sql_query($sql);
          if ($res)
          {
            for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
            {
              print "      case \"".$row['id']."\":\n";
              // get rooms for this area
              $sql2 = "select id, room_name from $tbl_room where area_id='".$row['id']."' order by room_name";
              $res2 = sql_query($sql2);
              if ($res2)
              {
                for ($j = 0; ($row2 = sql_row_keyed($res2, $j)); $j++)
                {
                  print "        roomsObj.options[$j] = new Option(\"".str_replace('"','\\"',$row2['room_name'])."\",".$row2['id'] .");\n";
                }
                // select the first entry by default to ensure
                // that one room is selected to begin with
                print "        roomsObj.options[0].selected = true;\n";
                print "        break;\n";
              }
            }
          }
          ?>
        } //switch
      }

      // Create area selector, only if we have Javascript

      this.document.writeln("<div id=\"div_areas\">");
      this.document.writeln("<label for=\"areas\"><?php echo get_vocab("areas") ?>:<\/label>");
      this.document.writeln("          <select id=\"areas\" name=\"areas\" onchange=\"changeRooms(this.form)\">");

      <?php
      // get list of areas
      $sql = "select id, area_name from $tbl_area order by area_name";
      $res = sql_query($sql);
      if ($res)
      {
        for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
        {
          $selected = "";
          if ($row['id'] == $area_id)
          {
            $selected = 'selected=\"selected\"';
          }
          print "this.document.writeln(\"            <option $selected value=\\\"".$row['id']."\\\">".$row['area_name']."<\/option>\");\n";
        }
      }
      ?>
      this.document.writeln("          <\/select>");
      this.document.writeln("<\/div>");

      //]]>
      </script>
      
      
      <?php
    } // if $num_areas

    ?>
    
    <!-- do not allow user to change or select multiple rooms.. put room info in hidden field below
    <div id="div_rooms">
    <label for="rooms"><?php echo get_vocab("rooms") ?>:</label>
    <div class="group">
      <select id="rooms" name="rooms[]" size="5">
        <?php 
        // select the rooms in the area determined above
        $sql = "select id, room_name from $tbl_room where area_id=$area_id order by room_name";
        $res = sql_query($sql);
        if ($res)
        {
          for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
          {
            $selected = "";
            if ($row['id'] == $room_id)
            {
              $selected = "selected=\"selected\"";
            }
            echo "              <option $selected value=\"".$row['id']."\">".$row['room_name']."</option>\n";
            // store room names for emails
            $room_names[$i] = $row['room_name'];
          }
        }
        ?>
      </select>
      <span><?php echo get_vocab("ctrl_click") ?></span>
      </div>
    </div>
    -->
    
    <!-- 20090302 wilson: hide type and set default type to "I", Internal
    <div id="div_type">
      <label for="type"><?php echo get_vocab("type")?>:</label>

      <select id="type" name="type">
        <?php
        for ($c = "A"; $c <= "Z"; $c++)
        {
          if (!empty($typel[$c]))
          { 
            echo "        <option value=\"$c\"" . ($type == $c ? " selected=\"selected\"" : "") . ">$typel[$c] $c</option>\n";
          }
        }
        ?>
      </select>
      </div>
      -->
      <input id="type" name="type" value="I" type="hidden">

    <?php
    if ($edit_type == "series")
    {
    ?>
      <div id="rep_type">
        <label><?php echo get_vocab("rep_type")?>:</label>
        <div class="group">
          <?php
          for ($i = 0; isset($vocab["rep_type_$i"]); $i++)
          {
            echo "      <label><input class=\"radio\" name=\"rep_type\" type=\"radio\" value=\"" . $i . "\"";
            if ($i == $rep_type)
            {
              echo " checked=\"checked\"";
            }
            echo ">" . get_vocab("rep_type_$i") . "</label>\n";
          }
          ?>
        </div>
      </div>

      <div id="rep_end_date">
        <label><?php echo get_vocab("rep_end_date")?>:</label>
        <?php genDateSelector("rep_end_", $rep_end_day, $rep_end_month, $rep_end_year) ?>
      </div>
      
      <div id="rep_day">
        <label><?php echo get_vocab("rep_rep_day")?>:<br><?php echo get_vocab("rep_for_weekly")?></label>
        <div class="group">
          <?php
          // Display day name checkboxes according to language and preferred weekday start.
          for ($i = 0; $i < 7; $i++)
          {
            $wday = ($i + $weekstarts) % 7;
            echo "      <label><input class=\"checkbox\" name=\"rep_day[$wday]\" type=\"checkbox\"";
            if ($rep_day[$wday])
            {
              echo " checked=\"checked\"";
            }
            echo ">" . day_name($wday) . "</label>\n";
          }
          ?>
        </div>
      </div>
      <?php
    }
    else
    {
      $key = "rep_type_" . (isset($rep_type) ? $rep_type : "0");
      ?>
      <fieldset id="rep_info">
      <legend></legend>
        <input type="hidden" name="rep_type" value="0">
        <div>
          <label><?php echo get_vocab("rep_type") ?>:</label>
          <input type="text" value ="<?php echo get_vocab($key) ?>" disabled="disabled">
        </div>
        <?php
        if(isset($rep_type) && ($rep_type != 0))
        {
          $opt = "";
          if ($rep_type == 2)
          {
            // Display day names according to language and preferred weekday start.
            for ($i = 0; $i < 7; $i++)
            {
              $wday = ($i + $weekstarts) % 7;
              if ($rep_opt[$wday])
              {
                $opt .= day_name($wday) . " ";
              }
            }
          }
          if($opt)
          {
            echo "  <div><label>".get_vocab("rep_rep_day").":</label><input type=\"text\" value=\"$opt\" disabled=\"disabled\"></div>\n";
          }

          echo "  <div><label>".get_vocab("rep_end_date").":</label><input type=\"text\" value=\"$rep_end_date\" disabled=\"disabled\"></div>\n";
        }
        ?>
      </fieldset>
      <?php
    }

    /* We display the rep_num_weeks box only if:
       - this is a new entry ($id is not set)
       Xor
       - we are editing an existing repeating entry ($rep_type is set and
         $rep_type != 0 and $edit_type == "series" )
    */
    if ( ( !isset( $id ) ) Xor ( isset( $rep_type ) && ( $rep_type != 0 ) &&
                             ( "series" == $edit_type ) ) )
    {
      ?>
      <label for="rep_num_weeks"><?php echo get_vocab("rep_num_weeks")?>:<br><?php echo get_vocab("rep_for_nweekly")?></label>
      <input type="text" id="rep_num_weeks" name="rep_num_weeks" value="<?php echo $rep_num_weeks?>">
    <?php
    }
    ?>
<!-- 20090303 wilson: add comments text area -->
    <div id="div_comments">
      <label for="comments"><?php echo get_vocab("comments")?></label>
      <!-- textarea rows and cols are overridden by CSS height and width -->
      <textarea id="comments" name="comments" rows="8" cols="40"><?php echo htmlspecialchars ( $comments ); ?></textarea>
    </div>
    <?php
    // In the section below the <div> needs to be inside the <noscript> in order to pass validation
    ?>
    <script type="text/javascript">
      //<![CDATA[
      document.writeln ('<div id="edit_entry_submit">');
      document.writeln ('<input class="submit" type="button" name="save_button" value="<?php echo get_vocab("save")?>" onclick="validate_and_submit()">');
      document.writeln ('<\/div>');
      //]]>
    </script>
    <noscript>
      <div id="edit_entry_submit">
        <input class="submit" type="submit" value="<?php echo get_vocab("save")?>">
      </div>
    </noscript>
    <?php
    // We might be going through edit_entry more than once, for example if we have to log on on the way.  We
    // still need to preserve the original calling page so that once we've completed edit_entry_handler we can
    // go back to the page we started at (rather than going to the default view).  If this is the first time 
    // through, then $HTTP_REFERER holds the original caller.    If this is the second time through we will have 
    // stored it in $returl.
    ?>
    <input type="hidden" name="returl" value="<?php echo htmlspecialchars((isset($returl)) ? $returl : ($HTTP_REFERER)) ?>">
    <!--input type="hidden" name="room_id" value="<?php echo $room_id?>"-->
    <input type="hidden" name="create_by" value="<?php echo $create_by?>">
    <input type="hidden" name="rep_id" value="<?php echo $rep_id?>">
    <input type="hidden" name="edit_type" value="<?php echo $edit_type?>">
    <input type="hidden" name="actual_start_time" value="<?php echo $actual_start_time?>">
    <input type="hidden" name="actual_end_time" value="<?php echo $actual_end_time?>">
    <input type="hidden" name="person_id" value="<?php echo $person_id?>">
    <input type="hidden" name="band_id" value="<?php echo $band_id?>">
    <input type="hidden" name="band_size" value="<?php echo $band_size?>">
    <input type="hidden" name="rooms" value="<?php echo $room_id?>">
    <input type="hidden" name="reservation_status" value="<?php echo $reservation_status?>">
    <input type="hidden" name="is_pending" value="<?php echo $is_pending?>">
    <input type="hidden" name="day" value="<?php echo $_GET['day']?>">
    <input type="hidden" name="month" value="<?php echo $_GET['month']?>">
    <input type="hidden" name="year" value="<?php echo '2018'?>">

    <?php if(isset($id) && !isset($copy)) echo "<input type=\"hidden\" name=\"id\"        value=\"$id\">\n";
    ?>
  </fieldset>
</form>



<?php
echo "<a href=\"day.php?year=$year&amp;month=$month&amp;day=$day\">Cancel</a>";
//include "trailer.inc"
?>
