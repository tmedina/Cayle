<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
?>


<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="white">
        <tr><td width="25%"></td>
            <td width="50%" valign="bottom" bgcolor="white" ><a href="../reservation/index.php">
                <img src="../includes/images/header_logo.jpg" alt="Welcome to Nuci's Space" border="0"></a></td>
                <td width="25%"></td>
        </tr>
        </table>
        <table bgcolor="#5F86B9">
        <tr width="100%">
            <td width="25%" align="center" valign="bottom">
                <a href="../person_band/add_new_person.php"><font color="white">Find a Person</font></a>
            </td>
            <td width="25%" align="center" valign="bottom">
                <a href="../person_band/add_new_band.php"><font color="white">Find a Band</font></a>
            </td>
            <td width="25%" align="center" valign="bottom">
                <a href="../checkout/invoice.php"><font color="white">Make Payment</font></a>
            </td>
            <td width="25%" align="center" valign="bottom">
                <a href="../reports/test_room_usage_rpt.php"><font color="white">Run Reports</font></a>
            </td>
       
        </tr>
    </table>
    