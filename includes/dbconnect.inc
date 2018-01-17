<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

include ("dbaccess.inc");

    // Connect to the database

    if ( !dbConnect() )
    {
	echo "<div id='errormsg'>Unable to connect to database<br/>";
	exit (-1);
    }

?>
