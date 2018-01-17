<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
// cancel_entry.php 2009-03-13 wilson


require_once "grab_globals.inc.php";
include "config.inc.php";
include "functions.inc";
include "dbsys.inc";
include "mrbs_auth.inc";
include "mrbs_sql.inc";
include ("../reservation/themes/default.inc");


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

if (!getWritable($create_by, getUserName()))
{
  showAccessDenied($day, $month, $year, $area, isset($room) ? $room : "");
  exit;
}

print_header($day, $month, $year, $area, isset($room) ? $room : "");
include ("../includes/header.inc");


?>

<script language="javascript"
  type="text/javascript">

<!-- hide script from older browsers
function validateForm()
        {

        if (form.cancel_initials.value == "") {
            alert( "Please enter your initials to override" );
            form.cancel_initials.focus();
            return false ;
        }

        if (form.cancel_comment.value == "") {
            alert( "Please enter a comment to override" );
            form.cancel_comment.focus();
            return false ;
        }


             return true;
        } -->
</script>
<form class="form_general" id="main" action="cancel_entry_handler.php" method="post" onSubmit="return validateForm();">

    <div id="div_cancellation_type">
    <label for="rooms">Select a cancellation type: </label>
      <select id="rooms" name="cancel_type">
        <?php
        $sql = "SELECT mc.id, mc.name, mc.amount
                  FROM misc_charge mc, misc_charge_type mct
                 WHERE mc.misc_charge_type_id = mct.id
                   AND mc.is_active = 1
                   AND mct.name = 'cancellation'
              ORDER BY mc.name";
        $res = sql_query($sql);
        if ($res)
        {
          for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
          {
            echo "<option value=\"".$row['id']."\">".$row['name']."</option>\n";
          }
        }
        ?>
      </select>
    </div>
<div id="div_cancellation_initials">
      <label for="rooms">Enter your initials: </label><input type="text" size=2 name="cancel_initials" maxlength="3" value="">
  </div>

    <div id="div_cancellation_comment">
      <label for="rooms">Enter cancellation comment: </label><textarea name="cancel_comment" rows="2" cols="20"></textarea>
    </div>
    <div id="div_submit">
      <input type="hidden" name="day" value="<?php echo $day; ?>">
      <input type="hidden" name="month" value="<?php echo $month; ?>">
      <input type="hidden" name="year" value="<?php echo $year; ?>">
      <input type="hidden" name="area" value="<?php echo $area; ?>">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="series" value="<?php echo $series; ?>">
      <input type="hidden" name="returl" value="<?php echo $returl; ?>">
      <label></label><input type="submit" value="submit">
    </div>
</form>

<div id="div_return">
  <a href="<?php echo htmlspecialchars($HTTP_REFERER) ?>"><?php echo get_vocab("returnprev") ?></a>
</div>
