<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

?>
<h6 align="center">A product of the UGA Terry College of Business<br /> Master of Internet Technology program<br />Version 1 - May, 2009</h6>
