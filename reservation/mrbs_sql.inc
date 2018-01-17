<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// $Id: mrbs_sql.inc 954 2008-11-09 21:59:45Z jberanek $
// 2009-03-03 wilson: added Ss to mrbsCreateSingleEntry
// 2009-03-18 wilson: added mrbsCheckInEntry
// 2009-03-18 wilson: added is_cancelled check on mrbsCheckFree
// 20090327 wilson: add actual_start_time, actual_end_time, person_id, band_id, band_size
// 20090327 wilson: add mrbsUpdateEditSingleRes
// 20090328 wilson: cancel only res if it is not checked in yet.
// 20090331 wilson: Removed select only future series in mrbsDelete
//                  since id is not a good indicator since edited entry will get a new id
// 20090331 wilson: mrbsDelete: delete only reservations that have NOT been checked in
//                  and that are NOT cancelled and current&future entries.
// 20090401 wilson: we only need to create 1 cancellation transaction even for series reservation
// 20090408 wilson: added check of is_pending flag in mrbsCheckFree
// 20090408 wilson: added reservation_status and is_pending

/**
 *  mrbsCheckFree()
 * 
 * Check to see if the time period specified is free
 * 
 * $room_id   - Which room are we checking
 * $starttime - The start of period
 * $endtime   - The end of the period
 * $ignore    - An entry ID to ignore, 0 to ignore no entries
 * $repignore - A repeat ID to ignore everything in the series, 0 to ignore no series
 * 
 * Returns:
 *   nothing   - The area is free
 *   something - An error occured, the return value is human readable
 */

include ("../includes/config.inc");

function mrbsCheckFree($room_id, $starttime, $endtime, $ignore, $repignore)
{
  global $tbl_entry;
  global $enable_periods;
  global $periods;

  // Select any meetings which overlap ($starttime,$endtime) for this room:
  $sql = "SELECT id, name, start_time FROM $tbl_entry WHERE
                 start_time < $endtime AND end_time > $starttime
          AND room_id = $room_id
          AND is_cancelled != 1
          AND is_pending != 1";

  if ($ignore > 0)
  {
    $sql .= " AND id <> $ignore";
  }
  if ($repignore > 0)
  {
    $sql .= " AND repeat_id <> $repignore";
  }
  $sql .= " ORDER BY start_time";

  $res = sql_query($sql);
  if (! $res)
  {
    return sql_error();
  }
  if (sql_count($res) == 0)
  {
    sql_free($res);
    return "";
  }
  // Get the room's area ID for linking to day, week, and month views:
  $area = mrbsGetRoomArea($room_id);

  // Build a string listing all the conflicts:
  $err = "";
  for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
  {
    $starts = getdate($row['start_time']);
    $param_ym = "area=$area&amp;year=$starts[year]&amp;month=".$starts['mon'];
    $param_ymd = $param_ym . "&amp;day=" . $starts['mday'];

    if ($enable_periods)
    {
      $p_num =$starts['minutes'];
      $startstr = utf8_strftime('%A %d %B %Y, ',
                                $row['start_time']) . $periods[$p_num];
    }
    else
    {
      $startstr = utf8_strftime('%A %d %B %Y %H:%M:%S',
                                $row['start_time']);
    }

    $err .= "<li><a href=\"view_entry.php?id=".$row['id']."\">".$row['name']."</a>"
      . " ( " . $startstr . ") "
      . "(<a href=\"day.php?$param_ymd\">".get_vocab("viewday")."</a>"
      . " | <a href=\"week.php?room=$room_id&amp;$param_ymd\">".get_vocab("viewweek")."</a>"
      . " | <a href=\"month.php?room=$room_id&amp;$param_ym\">".get_vocab("viewmonth")."</a>)</li>\n";
  }

  return $err;
}

/** mrbsDelEntry()
 * 
 * Delete an entry, or optionally all entrys.
 * 
 * $user   - Who's making the request
 * $id     - The entry to delete
 * $series - If set, delete the series, except user modified entrys
 * $all    - If set, include user modified entrys in the series delete
 *
 * Returns:
 *   0        - An error occured
 *   non-zero - The entry was deleted
 */
function mrbsDelEntry($user, $id, $series, $all)
{
  global $tbl_entry, $tbl_repeat;

  $repeat_id = sql_query1("SELECT repeat_id FROM $tbl_entry WHERE id=$id");
  if ($repeat_id < 0)
  {
    return 0;
  }

  $sql = "SELECT create_by, id, entry_type FROM $tbl_entry WHERE ";
   
  if ($series)
  {
    $start_time = sql_query1("SELECT start_time FROM $tbl_entry WHERE id=$id");

    // data to be deleted
    // select only reservations that have NOT been checked in and that are NOT cancelled and current&future entries
    $sql .= "repeat_id=$repeat_id and actual_start_time IS NULL and is_cancelled = 0 and start_time >= $start_time";

    // 20090331 Removed: id is not a good indicator since edited entry will get a new id
    // select only future series
    //$sql .= "repeat_id=$repeat_id AND id >= $id";
  }
  else
  {
    $sql .= "id=$id";
  }

  $res = sql_query($sql);

  $removed = 0;

  for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
  {
    if(!getWritable($row['create_by'], $user))
    {
      continue;
    }
   
    if ($series && $row['entry_type'] == 2 && !$all)
    {
      continue;
    }

    // if series reservation, delete all reservation transaction
    // this will always be future series reservations so we are removing just equipment transaction
    sql_command("DELETE FROM reservation_transaction WHERE reservation_entry_id =" .  $row['id'] );

    if (sql_command("DELETE FROM $tbl_entry WHERE id=" . $row['id']) > 0)
    {
      $removed++;
    }
  }

/*
 * at this point all future entries have been deleted.
 * but the old entries are still exist and is associated with repeat_id that we need to delete below
 * so remove (disassociate) the old entries with this repeat id
 */
  if ($series && $repeat_id > 0 )
  {
      sql_command("UPDATE $tbl_entry SET repeat_id = 0, entry_type = 0 where repeat_id=$repeat_id");
  }

    
  if ($series && $repeat_id > 0 &&
      sql_query1("SELECT count(*) FROM $tbl_entry WHERE repeat_id=$repeat_id") == 0)
  {
    sql_command("DELETE FROM $tbl_repeat WHERE id=$repeat_id");
  }
  
  return $removed > 0;
}

/** mrbsCreateSingleEntry()
 * 
 * Create a single (non-repeating) entry in the database
 * 
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $entry_type  - Entry type
 * $repeat_id   - Repeat ID
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 * $comments    - Comments
 * $actual_start_time - Actual start time
 * $actual_end_time   - Actual end time
 * $person_id         - Person ID
 * $band_id           - Band ID
 * $band_size         - Band Size
 * $reservation_status - Reservation Status
 * $is_pending         - is_pending
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateSingleEntry($starttime, $endtime, $entry_type, $repeat_id,
                               $room_id, $owner, $name, $type, $description,
                               $comments, $actual_start_time, $actual_end_time,
                               $person_id, $band_id, $band_size, $reservation_status, $is_pending)
{

  global $tbl_entry;
//DEBUG
//echo "DEBUG START: $starttime END: $endtime ACTUAL: $actual_start_time $actual_end_time $person_id $band_id $band_size <br/>";
//exit(0);
  $name        = addslashes($name);
  $description = addslashes($description);
  $comments    = addslashes($comments);
  $person_id   = $person_id == NULL ? "NULL" : $person_id;
  $band_id     = $band_id == NULL ? "NULL" : $band_id;

  // if this is not todays reservation (future reservation), 
  // set the following fields to NULL 
  // because future  reservation cannot be checked in or out until the day of the reservation
  //
  if ( $actual_start_time != NULL && ($actual_start_time < $starttime) )
  {
    $band_size         = NULL;
    $actual_start_time = NULL;
    $actual_end_time   = NULL;
  }

  $band_size         = $band_size == NULL ? "NULL" : $band_size;
  $actual_start_time = $actual_start_time == NULL ? "NULL" : $actual_start_time;
  $actual_end_time   = $actual_end_time == NULL ? "NULL" : $actual_end_time;

  // make sure that any entry is of a positive duration
  // this is to trap potential negative duration created when DST comes
  // into effect
  if ($endtime > $starttime)
  {
    $sql = "INSERT INTO $tbl_entry (  start_time,   end_time,   entry_type,    repeat_id,   room_id,
                                      create_by,    name,       type,          description,     comments,
                                      actual_start_time, actual_end_time, person_id, band_id, band_size, reservation_status, is_pending )
                            VALUES ($starttime, $endtime, $entry_type, $repeat_id, $room_id,
                                    '$owner',     '$name',    '$type',       '$description', '$comments',
                                    $actual_start_time, $actual_end_time, $person_id, $band_id, $band_size, '$reservation_status', $is_pending )";
//echo $sql . "<br/>";

    if (sql_command($sql) < 0)
    {
      return 0;
    }

    return sql_insert_id("$tbl_entry", "id");
  }
  else
  {
    return 0;
  }
}

/** mrbsCreateRepeatEntry()
 * 
 * Creates a repeat entry in the data base
 * 
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $rep_type    - The repeat type
 * $rep_enddate - When the repeating ends
 * $rep_opt     - Any options associated with the entry
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 *
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateRepeatEntry($starttime, $endtime, $rep_type, $rep_enddate,
                               $rep_opt, $room_id, $owner, $name, $type,
                               $description, $rep_num_weeks)
{
  global $tbl_repeat;

  $name        = addslashes($name);
  $description = addslashes($description);

  // Let's construct the sql statement:
  $sql_coln = array(); $sql_val = array();

  // Mandatory things:
  $sql_coln[] = 'start_time';    $sql_val[] = $starttime;
  $sql_coln[] = 'end_time';    $sql_val[] = $endtime;
  $sql_coln[] = 'rep_type';    $sql_val[] = $rep_type;
  $sql_coln[] = 'end_date';   $sql_val[] = $rep_enddate;
  $sql_coln[] = 'room_id';   $sql_val[] = $room_id;
  $sql_coln[] = 'create_by';   $sql_val[] = '\''.$owner.'\'';
  $sql_coln[] = 'type';      $sql_val[] = '\''.$type.'\'';
  $sql_coln[] = 'name';      $sql_val[] = '\''.$name.'\'';

  // Optional things, pgsql doesn't like empty strings!
  if (!empty($rep_opt))
  {
    $sql_coln[] = 'rep_opt';   $sql_val[] = '\''.$rep_opt.'\'';
  }
  else
  {
    $sql_coln[] = 'rep_opt';   $sql_val[] = '\'0\'';
  }
  if (!empty($description))
  {
    $sql_coln[] = 'description';   $sql_val[] = '\''.$description.'\'';
  }
  if (!empty($rep_num_weeks))
  {
    $sql_coln[] = 'rep_num_weeks';   $sql_val[] = $rep_num_weeks;
  }

  $sql = 'INSERT INTO ' . $tbl_repeat .
    ' (' . implode(', ',$sql_coln) . ') '.
    'VALUES (' . implode(', ',$sql_val) . ')';

  if (sql_command($sql) < 0)
  {
    return 0;
  }

  return sql_insert_id("$tbl_repeat", "id");
}

/** same_day_next_month()
* Find the same day of the week in next month, same week number.
*
* Return the number of days to step forward for a "monthly repeat,
* corresponding day" serie - same week number and day of week next month.
* This function always returns either 28 or 35.
* For dates in the 5th week of a month, the resulting day will be in the 4th
* week of the next month if no 5th week corresponding day exist.
* :TODO: thierry_bo 030510: repeat 5th week entries only if 5th week exist.
* If we want a 5th week repeat type, only 5th weeks have to be booked. We need
* also a new "monthly repeat, corresponding day, last week of the month" type.
*
* @param    integer     $time           timestamp of the day from which we want to find
*                                       the same day of the week in next month, same
*                                       week number
* @return   integer     $days_jump      number of days to step forward to find the next occurence (28 or 35)
* @var      integer     $days_in_month  number of days in month
* @var      integer     $day            day of the month (01 to 31)
* @var      integer     $weeknumber     week number for each occurence ($time)
* @var      boolean     $temp1          first step to compute $days_jump
* @var      integer     $next_month     intermediate next month number (1 to 12)
* @global   integer     $_initial_weeknumber    used only for 5th weeks repeat type
 */
function same_day_next_month($time)
{
  global $_initial_weeknumber;

  $days_in_month = date("t", $time);
  $day = date("d", $time);
  $weeknumber = (int)(($day - 1) / 7) + 1;
  $temp1 = ($day + 7 * (5 - $weeknumber) <= $days_in_month);

  // keep month number > 12 for the test purpose in line beginning with "days_jump = 28 +..."
  $next_month = date("n", mktime(11, 0 ,0, date("n", $time), $day +35, date("Y", $time))) + (date("n", mktime(11, 0 ,0, date("n", $time), $day +35, date("Y", $time))) < date("n", $time)) * 12;

  // prevent 2 months jumps if $time is in 5th week
  $days_jump = 28 + (($temp1 && !($next_month - date("n", $time) - 1)) * 7);

  /* if initial week number is 5 and the new occurence month number ($time + $days_jump)
   * is not changed if we add 7 days, then we can add 7 days to $days_jump to come
   * back to the 5th week (yuh!) */
  $days_jump += 7 * (($_initial_weeknumber == 5) && (date("n", mktime(11, 0 ,0, date("n", $time), $day + $days_jump, date("Y", $time))) == date("n", mktime(11, 0 ,0, date("n", $time), $day + $days_jump + 7, date("Y", $time)))));

  return $days_jump;
}

/** mrbsGetRepeatEntryList
 * 
 * Returns a list of the repeating entrys
 * 
 * $time     - The start time
 * $enddate  - When the repeat ends
 * $rep_type - What type of repeat is it
 * $rep_opt  - The repeat entrys
 * $max_ittr - After going through this many entrys assume an error has occured
 * $_initial_weeknumber - Save initial week number for use in 'monthly repeat same week number' case
 * 
 * Returns:
 *   empty     - The entry does not repeat
 *   an array  - This is a list of start times of each of the repeat entrys
 */
function mrbsGetRepeatEntryList($time, $enddate, $rep_type, $rep_opt,
                                $max_ittr, $rep_num_weeks)
{
  $sec   = date("s", $time);
  $min   = date("i", $time);
  $hour  = date("G", $time);
  $day   = date("d", $time);
  $month = date("m", $time);
  $year  = date("Y", $time);

  global $_initial_weeknumber;
  $_initial_weeknumber = (int)(($day - 1) / 7) + 1;
  $week_num = 0;
  $start_day = date('w', mktime($hour, $min, $sec, $month, $day, $year));
  $cur_day = $start_day;

  $entrys = "";
  for ($i = 0; $i < $max_ittr; $i++)
  {
    $time = mktime($hour, $min, $sec, $month, $day, $year);
    if ($time > $enddate)
    {
      break;
    }

    $entrys[$i] = $time;

    switch($rep_type)
    {
      // Daily repeat
      case 1:
        $day += 1;
        break;
      
      // Weekly repeat
      case 2:
        $j = $cur_day = date("w", $entrys[$i]);
        // Skip over days of the week which are not enabled:
        while (($j = ($j + 1) % 7) != $cur_day && !$rep_opt[$j])
        {
          $day += 1;
        }

        $day += 1;
        break;
      
      // Monthly repeat
      case 3:
        $month += 1;
        break;
      
      // Yearly repeat
      case 4:
        $year += 1;
        break;
   
      // Monthly repeat on same week number and day of week
      case 5:
        $day += same_day_next_month($time);
        break;

      // n Weekly repeat
      case 6:
        // Loop until we hit the end time
        while ($time <= $enddate)
        {
          $day++;
          $cur_day = ($cur_day + 1) % 7;

          if (($cur_day % 7) == $start_day)
          {
            $week_num++;
          }

          if (($week_num % $rep_num_weeks == 0) &&
              ($rep_opt[$cur_day] == 1))
          {
            break;
          }
        }

        break;   
            
      // Unknown repeat option
      default:
        return;
    }
  }

  return $entrys;
}

/** mrbsCreateRepeatingEntrys()
 * 
 * Creates a repeat entry in the data base + all the repeating entrys
 * 
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $rep_type    - The repeat type
 * $rep_enddate - When the repeating ends
 * $rep_opt     - Any options associated with the entry
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 * $comments    - Comments
 * $actual_start_time - Actual start time
 * $actual_end_time   - Actual end time
 * $person_id         - person_id
 * $band_id           - Band id
 * $band_size         - Band size
 * $reservation_status - Reservation status
 * $is_pending         - is pending
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateRepeatingEntrys($starttime, $endtime, $rep_type,
                                   $rep_enddate, $rep_opt, $room_id, $owner,
                                   $name, $type, $description, $rep_num_weeks,
                                   $comments, $actual_start_time, $actual_end_time,
                                   $person_id, $band_id, $band_size, $reservation_status, $is_pending )
{
  global $max_rep_entrys;
   
  $reps = mrbsGetRepeatEntryList($starttime, $rep_enddate,
                                 $rep_type, $rep_opt,
                                 $max_rep_entrys, $rep_num_weeks);

  if (count($reps) > $max_rep_entrys)
  {
    return 0;
  }

  if (empty($reps))
  {
    $ent = mrbsCreateSingleEntry($starttime, $endtime, 0, 0,
                                 $room_id, $owner, $name, $type,
                                 $description, $comments, $actual_start_time,
                                 $actual_end_time, $person_id, $band_id, $band_size, $reservation_status, $is_pending);
    return $ent;
  }
   
  $ent = mrbsCreateRepeatEntry($starttime, $endtime, $rep_type,
                               $rep_enddate, $rep_opt, $room_id,
                               $owner, $name, $type, $description,
                               $rep_num_weeks);
    
  if ($ent)
  {
    for ($i = 0; $i < count($reps); $i++)
    {
      // calculate diff each time and correct where events
      // cross DST
      $diff = $endtime - $starttime;
      $diff += cross_dst($reps[$i], $reps[$i] + $diff);
    
      $ent_id = mrbsCreateSingleEntry($reps[$i],
                                      $reps[$i] + $diff,
                                      1,
                                      $ent,
                                      $room_id,
                                      $owner,
                                      $name,
                                      $type,
                                      $description,
                                      $comments,
                                      $actual_start_time,
                                      $actual_end_time,
                                      $person_id,
                                      $band_id,
                                      $band_size,
                                      $reservation_status,
                                      $is_pending );
    }
  }
  return $ent;
}

/* mrbsGetEntryInfo()
 *
 * Get the booking's entrys
 * 
 * $id = The ID for which to get the info for.
 * 
 * Returns:
 *    nothing = The ID does not exist
 *    array   = The bookings info
 */
function mrbsGetEntryInfo($id)
{
  global $tbl_entry;

  $sql = "SELECT start_time, end_time, entry_type, repeat_id, room_id,
                 timestamp, create_by, name, type, description, comments, person_id,
                 actual_start_time, actual_end_time, person_id, band_id, band_size, reservation_status, is_pending
          FROM $tbl_entry WHERE (id = $id)";

  $res = sql_query($sql);
  if (! $res)
  {
    return;
  }

  $ret = "";
  if (sql_count($res) > 0)
  {
    $row = sql_row_keyed($res, 0);

    if ($row)
    {
      $ret = $row;
    }
  }
  sql_free($res);

  return $ret;
}

function mrbsGetRoomArea($id)
{
  global $tbl_room;

  $id = sql_query1("SELECT area_id FROM $tbl_room WHERE (id = $id)");
  if ($id <= 0)
  {
    $id = 0;
  }

  return $id;
}

/** mrbsCancelEntry()
 *
 * Set an entry cancellation flag, or optionally all future entrys.
 *
 * $user   - Who's making the request
 * $id     - The entry to cancel
 * $series - If set, delete all future series
 * $all    - n/a
 *
 * Returns:
 *   0        - An error occured
 *   non-zero - The entry was deleted
 */
function mrbsCancelEntry($user, $id, $series, $all, $cancel_type, $cancel_comment)
{

  global $tbl_entry, $tbl_repeat;

  $repeat_id = sql_query1("SELECT repeat_id FROM $tbl_entry WHERE id=$id");
  if ($repeat_id < 0)
  {
    return 0;
  }

  $sql = "SELECT create_by, id, entry_type FROM $tbl_entry 
           WHERE actual_start_time IS NULL AND ";

  if ($series)
  {
    $sql .= "repeat_id=$repeat_id";
  }
  else
  {
    $sql .= "id=$id";
  }

  $res = sql_query($sql);

  $removed = 0;

  for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
  {
    if(!getWritable($row['create_by'], $user))
    {
      continue;
    }

    if ($series && $row['entry_type'] == 2 && !$all)
    {
      continue;
    }

    if (sql_command("UPDATE $tbl_entry set is_cancelled = 1, cancellation_comments = '" . $cancel_comment . "' WHERE id=" . $row['id']) > 0)
    {
        $removed++;

        // 20090401 wilson: we only need to create 1 cancellation transaction even for series reservation
        if ($removed == 1)
        {
            sql_command("INSERT INTO reservation_transaction (reservation_entry_id, misc_charge_id, amount) VALUES (" . $row['id'] . "," . $cancel_type . ",(SELECT amount FROM misc_charge WHERE id =" . $cancel_type ."))");
        }
    }
  }

  //if ($repeat_id > 0 &&
  //    sql_query1("SELECT count(*) FROM $tbl_entry WHERE repeat_id=$repeat_id") == 0)
  //{
  //  sql_command("DELETE FROM $tbl_repeat WHERE id=$repeat_id");
  //}

  return $removed > 0;
}

/** mrbsCheckInEntry()
 *
 * Check in an entry (set actual_start_time in reservation_entry table)
 *
 * $user   - Who's making the request
 * $id     - The entry to delete
 * $checkintime - The check in time
 * Returns:
 *   0        - An error occured
 *   non-zero - The entry was checked in
 */
function mrbsCheckInEntry($user, $id, $checkintime)
{
  global $tbl_entry;
  global $IN_PROGRESS;

  $retcd = sql_command("UPDATE $tbl_entry set actual_start_time = $checkintime, reservation_status = \"$IN_PROGRESS\" where id = $id");

  return $retcd;
}


/** mrbsCheckOutEntry()
 *
 * Check out an entry (set actual_end_time in reservation_entry table)
 *
 * $user   - Who's making the request
 * $id     - The entry to delete
 * $checkouttime - The check in time
 * Returns:
 *   0        - An error occured
 *   non-zero - The entry was checked in
 */
function mrbsCheckOutEntry($user, $id, $checkouttime)
{
  global $tbl_entry;
  global $UNPAID;

  $retcd = sql_command("UPDATE $tbl_entry set actual_end_time = $checkouttime, reservation_status = \"$UNPAID\" where id = $id");

  return $retcd;
}
/** mrbsUpdateEditSingleRes( id )
 *
 * when a single reservation is edited, mrbs will create new res id and delete the old one
 * this function will update all tables that uses reservation_id using the new id
 */
function mrbsUpdateEditSingleRes( $old_id, $new_id )
{
    $retcd = sql_command("UPDATE reservation_transaction set reservation_entry_id = $new_id where reservation_entry_id = $old_id");
    $retcd = sql_command("UPDATE credit_card_info set reservation_id = $new_id where reservation_id = $old_id");
    $retcd = sql_command("UPDATE payment set reservation_id = $new_id where reservation_id = $old_id");

    return TRUE;
}

?>