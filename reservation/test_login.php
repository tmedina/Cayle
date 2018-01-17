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

include "mrbs_auth.inc";
include ("../reservation/themes/default.inc");
include ("../includes/header.inc");

if (!getAuthorised(1))
{
  showAccessDenied($day, $month, $year, $area, isset($room) ? $room : "");
  exit;
}

echo "PASSED";

?>
