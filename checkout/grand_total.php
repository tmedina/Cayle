<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
/*
 * shows the total of all reservation transactions
 * 041209 beth
 */

$query = "SELECT id, sum(amount) as sum_amount
            FROM reservation_transaction
           WHERE reservation_entry_id = $reservation_id
           AND is_active>0
            ";

// die and show mysql error number and messages, if there is any error with query
$result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );



$row = mysql_fetch_array($result);
//format the number like currency
echo "<h2>Balance due: $" . number_format($row['sum_amount'],2);
echo "</h2>";
if ($row['sum_amount']<0)
{
    echo "<div id=err_msg>Total is less than zero. Please adjust payments.</div>";
}
elseif ( $row['sum_amount'] == 0 )
{
    //echo "this is your id" . $reservation_id;
   // $query = "SELECT reservation_status from reservation_entry where id=$reservation_id";

    // die and show mysql error number and messages, if there is any error with the query
    $query = "UPDATE reservation_entry SET reservation_status = 'CLOSED' WHERE id=$reservation_id ";
    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}
else
{
    $query = "UPDATE reservation_entry SET reservation_status = 'UNPAID' WHERE id=$reservation_id ";
    // die and show mysql error number and messages, if there is any error with query
    $result = mysql_query($query) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );
}
?>