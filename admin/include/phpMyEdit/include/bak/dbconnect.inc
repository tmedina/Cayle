<?php

//  Build the name for the database
//  configuration file
//
$configDir  = '/app/switch/spconf';
$dbConfFile = '';

//  Open the configuration directory, branch
//  on success
//
if ( $dh = opendir( $configDir ) ) 
{
    //  Look for the config file for
    //  the database
    //
    while ( $file = readdir( $dh ) ) 
    {
        //  If the file matches the 
        //  confinguration name
        //
        if ( preg_match( "/database_id_\d+.conf/", $file ) )
        {
            //  Set our database configuration
            //  file name
            //
            $dbConfFile = $configDir . '/' . $file;

            //  Exit the loop
            //
            break;
        }
    }

    //  Close the directory
    //
    closedir($dh);
}

//  Read the database configuration
//  file
//
$dbConfig = file( $dbConfFile );

//  Predefine our database connection
//  parameters
//
$dbUser = '';
$dbPass = '';
$dbName = '';

//  Perform a regex on the conntents
//  to extract the database connection
//  information
//
if ( preg_match( "#\s*(.*?)/(.*?)@(.*)\s*#", $dbConfig[ 0 ], $matches ) )
{
    //  Extract the username
    //
    $dbUser = $matches[ 1 ];
    $dbPass = $matches[ 2 ];
    $dbName = $matches[ 3 ];
}

//  Set my Oracle Enviroment
//
PutEnv("ORACLE_SID=");
PutEnv("ORACLE_HOME=/app/oracle/product/9.2.0");
PutEnv("TNS_ADMIN=/app/oracle/product/9.2.0/network/admin");

$conn = OCILogon( $dbUser, $dbPass, $dbName );

//  If the database was successfully connected
//
if ( $conn )
{
    $dbUp = true;
}
else
{
    $dbUp = false;
}

?>


