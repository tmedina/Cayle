<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
?>
<table>
<tr>
    <td>
    	<a href='payment.php?reservation_id=<?php echo $reservation_id?>'>Proceed to make payment</a>
    </td>
</tr>
</table>