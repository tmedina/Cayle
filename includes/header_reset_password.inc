<?php

//
// Marline Santiago-Cook
// Separate header to for login and reset password pages
// Excludes links to access all other modules
//


//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
?>

<table border="0" cellspacing="0" cellpadding="3" width="100%" bgcolor="#FFFFFF">
   <tr><td align="left" class="td2"><a href="../login/login-form.php"><img src="../includes/images/new_logo_left.gif" border="0" /></a></td>
    <td align="center" class="td2"><img src="../includes/images/new_logo_center.gif" /></td>
    <td align="right" class="td2"><img src="../includes/images/new_logo_right.gif" /></td></tr>
</table>

      

</table>
        <table bgcolor="#5F86B9" width="100%" >
        <tr width="100%">
        <td width="15%" align="right" class="td2" cellpadding="1" >
	<a href="../login/login-form.php" class="link2">Return to login</a>
        </td>
        </tr>
        </table>
  
   
