<?

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * show the total for the invoice
 * all charges and not including payments
 * 19apr09 beth
 */

$query = "SELECT DISTINCT reservation_entry_id, id, sum(amount) as sum_amount
            FROM reservation_transaction
           WHERE reservation_entry_id = $reservation_id
            AND is_active>0
            AND (misc_charge_id NOT IN
            (select mc.id from misc_charge mc, misc_charge_type mct
            WHERE mct.id = mc.misc_charge_type_id
            AND mct.name = 'payment')
            OR misc_charge_id is null)";

// die and show mysql error number and messages, if there is any error with query
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

$row = mysql_fetch_array($result);

echo "<table class=invoice_trans>";
echo "<tr>";
echo "<th class=invoice_type></th>";
echo "<th class=invoice_desc align=right>Total:</th>";
echo "<th class=invoice_amt align=right>" . number_format($row['sum_amount'],2) . "</th>";
echo "<th class=invoice_action></th>";
echo "</tr>";
echo "</table>";

mysql_free_result($result);


?>
