<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * placeholder for all the pieces of the invoice from person, band, equipment, bar charges, and misc transactions
 * wilson
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
<div id="invoice" align="center">
<h2 align="left">Invoice</h2>
<?php
    if (!isset($reservation_id) || $reservation_id == "")
    {
        echo "<div id='err_msg'>ERROR: Missing Reservation ID</div>";
        exit(1);
    }
?>

    <table width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr  valign=top>
            <td width=33%>
                <div id="customer_info">
                <?php include ("person_info.php"); ?>
                </div>
            </td>
            <td width=33%>
                <div id="reservation_info">
                <?php include ("reservation_info.php"); ?>
                </div>
            </td>
            <td width=33%>
                <div id="equipment_info">
                <?php include ("equipment_info.php"); ?>
                </div>
            </td>
        </tr>
     </table>
<br>


    <table class="invoice" width="100%" valign="middle">
        <tr class="invoice">
            <th class="invoice">Room Discount</th>
            <th class="invoice">Bar Charges</th>
            <th class="invoice">Miscellaneous Charges</th>
        </tr>
        <tr class="invoice">
            <td align="left" width="15%" class="invoice" >
                <div id="room_info">
                <?php include ("room_info.php"); ?>
                </div>
            </td>
            <td align="center" width="15%" class="invoice">
                <div id="bar_charge">
                <?php include ("bar_charge_info.php"); ?>
                </div>
            </td>
            <td align="center" width="70%" class="invoice">
                <div id="misc_charge">
                <?php include ("misc_charge_info.php"); ?>
                </div>
            </td>
        </tr>
    </table>


<br/>

<h2 align="left">Amount due</h2>
<?php include ("invoice_trans.php"); ?>
<?php include ("invoice_total.php"); ?>

<br>

<?php include ("make_payment.php"); ?>
<br>
</div>
</html>