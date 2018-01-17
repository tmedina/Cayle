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
include ("../includes/config.inc");
include ("../reports/Blowfish.php");

$reservation_id = $_GET['reservation_id'];
?>
<html>
<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <? include ("../includes/header.inc"); ?>
<div id="invoice" align="center">
<h2 align="left">Invoice</h2>

     <table width="100%" cellpadding="0" cellspacing="5" border="0">
        <tr valign=top>
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


<div id="invoice_trans">
<h2 align="left">Amount due</h2>
<?php include ("invoice_trans.php"); ?>
<?php include ("invoice_total.php"); ?>
</div>
<br>
<div id="payment_info">
<fieldset class="payment_info">
<legend>Payment</legend>
<?php include ("payment_info.php"); ?>
</fieldset>
</div>


<div id="total_info">
<fieldset class="total_info">
<legend>Balance</legend>
    <br /><br />
    <?php include ("grand_total.php"); ?>
    <br /><br /><br />
</fieldset>
</div>


<div id="open_res">
<center>
<form method="GET" name="complete" action="../reservation/index.php">
        <input type="button" value="Back" onClick="location.href='invoice.php?reservation_id=<?php echo $reservation_id?>'">
        <input type="button" value="Print" onClick="window.print()">
        <input type=submit value="Done">
</form>
</center>

<br>

<fieldset>
<legend>Unpaid Reservations</legend>
<?php include ("person_payment_info.php"); ?>
</fieldset>
</div>
<br>
</div>
</html>