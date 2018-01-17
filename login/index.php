<?php session_start();
if($_SESSION['logged'] != 1){ header("location:login.php"); }
?>

include("config.inc.php");
// include("dbsys.inc");

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
