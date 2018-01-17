<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include ("../includes/dbconnect.inc");
include ("../includes/functions.inc");
//include ("../includes/header.inc");
include ("../reservation/themes/default.inc");

$reservation_id = $_GET['reservation_id'];
?>
<html>
<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include ("../includes/header.inc"); ?>
<br/>
<div id="invoice" align="center">
<?php
    if (!isset($reservation_id) || $reservation_id == "")
    {
        echo "<div id='err_msg'>ERROR: Missing Reservation ID</div>";
        exit(1);
    }
?>

    <table class=invoice width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr class=invoice valign=top>
            <td class=invoice width=33%>
                <div id="customer_info">
                <?php include ("person_info.php"); ?>
                </div>
            </td>
            <td class=invoice width=33%>
                <div id="reservation_info">
                <?php include ("reservation_info.php"); ?>
                </div>
            </td>
            <td class=invoice width=33%>
                <div id="equipment_info">
                <?php include ("equipment_info.php"); ?>
                </div>
            </td>
        </tr>
     </table>
<br>

<div id="invoice_action">
    <table class=invoice width="100%" border="1">
        <tr class=invoice >
            <th>Room Discount</th>
            <th>Bar Charges</th>
            <th>Misc Charges</th>
        </tr>
        <tr class=invoice >
            <td class=invoice align="left" width="15%">
                <div id="room_info">
                <?php include ("room_info.php"); ?>
                </div>
            </td>
            <td class=invoice align="center" width="15%">
                <div id="bar_charge">
                <?php include ("bar_charge_info.php"); ?>
                </div>
            </td>
            <td class=invoice align="center" width="70%">
                <div id="misc_charge">
                <?php include ("misc_charge_info.php"); ?>
                </div>
            </td>
        </tr>
    </table>
</div>

<br/>

<center><h2>Invoice</h2></center>
<?php include ("invoice_trans.php"); ?>
<?php include ("invoice_total.php"); ?>

<br>

<?php include ("make_payment.php"); ?>
<br>
</div>
</html>