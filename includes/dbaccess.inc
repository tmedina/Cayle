<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

##########################################
#
#  Database access functions for
#  MySQL database
#
#  This file contains the following
#  functions:
#
#    dbConnect()  - connect to the MySQL
#                   database as defined
#                   in the 'spf_loadmysql'
#                   script on a Mediation
#                   server
#
#  dbDisconnect() - disconnect from the
#                   MySQL database
##########################################

#########################################################################
#  2009-03-17    wilson    initial creation.
#
#########################################################################


function dbConnect()
{
    global $dbUp;

    include ("config.inc");

    #  If there isn't any dbHost, then
    #  default it to 'localhost'
    #
    if ( !strlen( $dbHost ) ) $dbHost = 'localhost';

    #  Declare our database connection
    #  flag, and set it to 'false' for now
    #
    $dbUp = false;

    #DEBUG:
    #echo "dbName = $dbName<br/> ";
    #echo "dbUser = $dbUser<br/> ";
    #echo "dbPass = $dbPass<br/> ";
    #echo "dbHost = $dbHost<br/>";
    
    #  Connect to the database, and set
    #  out database connection to 'false' 
    #  if we fail
    #
    mysql_connect( $dbHost, $dbUser, $dbPass );

    #  Select the databse, and if successful
    #  set our return flag to true
    #
    if ( mysql_select_db( $dbName ) )
        $dbUp = true;

    return $dbUp; 
}


function dbDisconnect()
{
    global $dbUp;

    #  Close the connection
    #
    mysql_close();

    #  Set our database connect flag
    #  to false
    #
    $dbUp = false;
}

?>
