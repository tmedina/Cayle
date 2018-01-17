<? error_reporting(0); ?>
<html>
<head>
  <title>Unpaid Reservations</title>
  <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />

</head>
<body>
  <? include("../includes/header.inc"); ?>
  <div id="page" align="center">
    <h2>Unpaid Reservations</h2>
    <table>
      <tr>
      <th>Name</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>View Invoice</th>
      </tr>
<?php
//session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");

$unpaid = "select reservation_entry.id, person_id, fname, lname,
reservation_entry.start_time, reservation_entry.end_time
  from reservation_entry
  join person on person.id=reservation_entry.person_id
  where reservation_status='UNPAID' order by reservation_entry.start_time asc";

$get_unpaid = mysql_query($unpaid) or die ("ERROR:1 " . mysql_errno()
. "-" . mysql_error() );
$parity = 0;
while($row = mysql_fetch_array($get_unpaid)){
  $parity = ($parity + 1) % 2;
  ?>
  <tr class="parity_<?php echo $parity ?>">
    <td class="unpaid"><?php echo $row['fname'] . ' ' . $row['lname'] ?></td>
    <td class="unpaid"><?php echo pretty_date($row['start_time']) ?></td>
    <td class="unpaid"><?php echo pretty_date($row['end_time']) ?></td>
    <td class="unpaid"><a href="../checkout/invoice.php?reservation_id=<?php echo
$row['id']?>">View Invoice</a></td>
  </tr>

<?php
}



?></table></div></body></html>

