<?php

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
#
##########################################
#


function readGlobalConf()
{
    #  Declare the Global Configuration
    #  Environment Script
    #
    $confFile = "/app/switch/spconf/global.conf";

    #  Build the command to set the
    #  global configuration, and
    #  show the settings
    #
    $cmd = ". $confFile; set";

    #  Define our global environment array
    #
    array( $globalEnvs );

    #  Open the command
    #
    if ( $fp = popen( $cmd, "r"  ) )
    {
        #  Read all of the enviroment settings
        #
        while ( $line = fgets( $fp ) )
        {
            #  Remove any CR/LF and whitespace
            #
            $line = trim( $line );

            #  If we have a NAME=VALUE pair
            #
            if ( preg_match( '/^(\w+)=(.*?)$/', $line, $matches ) )
            {
                #  Add the pair to our
                #  global environment
                #
                $globalEnvs[ $matches[ 1 ] ] = $matches[ 2 ];
            }
        }

        #  Close the command
        #
        pclose( $fp );
    }

    #  Return the global enviroment
    #
    return $globalEnvs;
}


function dbConnect()
{
    global $dbUp;

    #  Get the global environment variables
    #
    $globalEnvs = readGlobalConf();

    #  Extract the database parameters
    #  from the global definitions
    #
    $dbUser = $globalEnvs[ 'DBUSER' ];
    $dbPass = $globalEnvs[ 'DBPASS' ];
    $dbName = $globalEnvs[ 'DBNAME' ];
    $dbHost = $globalEnvs[ 'DBHOST' ];

    #  If there isn't any dbHost, then
    #  default it to 'localhost'
    #
    if ( !strlen( $dbHost ) ) $dbHost = 'localhost';

    #  Redefine the user error constants - PHP 4 only
    #
    define("FATAL", E_USER_ERROR);
    define("ERROR", E_USER_WARNING);
    define("WARNING", E_USER_NOTICE);
    
    #  Set the error reporting level for this script
    #
    error_reporting( FATAL | ERROR | WARNING );

    #  Set the error handler
    #
    set_error_handler( dbErrorHandler );

    #  Declare our database connection
    #  flag, and set it to 'false' for now
    #
    $dbUp = false;

#echo "dbName = ", $dbName, "\n";
#echo "dbUser = ", $dbUser, "\n";
#echo "dbPass = ", $dbPass, "\n";
#echo "dbHost = ", $dbHost, "\n";
    
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


function OCIDBConnect( $dbName, $dbUser, $dbPass )
{
    #  Connect to the OCI Database 
    #
    $ociDBConn = OCILogon( $dbUser, $dbPass, $dbName );

    #  Return the connection
    #
    return $ociDBConn;
}


function OCIDBDisconnect( $ociDBConn )
{
    #  If we have a valid connection,
    #  disconnect from the database
    #
    if( $ociDBConn )
    {
        OCILogoff( $ociDBConn );
    }
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



function getINIValue( $attribute )
{
    #  Build the SQL to retrieve an INI value
    #
    $sql = "SELECT value FROM SPNET_INI WHERE attribute='$attribute'";

    array( $row );

    #  Perform the SQL
    #
    if ( $result = mysql_query( $sql ) )
    {
        #  Retrieve the row
        #
        $row = mysql_fetch_row( $result );
    }

    #  Return the first argument
    #  in the row parameter
    #
    return $row[ 0 ];
}




function dbErrorHandler( $errNo, $errStr, $errFile, $errLine, $errContext )
{
    #  Depending on the error, display the error page
    #
    switch( $errNo )
    {
        case ERROR:
        case FATAL:
?>
<html>
<head>
<title>Boo</title>
</head>
<body bgcolor="#000000">

<?

echo "ERRNO: ", $errNo, "<br>\n";
echo "ERRSTR: ", $errStr, "<br>\n";
echo "ERRFILE: ", $errFile, "<br>\n";
echo "ERRLINE: ", $errLine, "<br>\n";
echo "ERRCONTEXT: ", $errContext, "<br>\n";
echo "<br>\n";
?>

</body>
</html>
<?
            exit( 1 );
            break;

        case WARNING:
            break;
    }
}



?>
