<?php

$spConfig = '';

#  If the session file exists
#
if ( is_file( '/app/switch/spconf/spsession.cfg' ) )
{
     #  Read the session file
     #
     $spConfig = implode( '', file( '/app/switch/spconf/spsession.cfg') );
}

#  If sessions are enabled
#
if ( eregi( 'y|yes|1|true', $spConfig ) )
{
    #  Retrieve the session ID
    #
    $sessID = isset( $_REQUEST['switch_sess'] ) ? $_REQUEST['switch_sess'] : '';

    #  If the session ID has any length
    #
    if ( strlen( $sessID ) )
    {
        #  Restart our session
        #
        session_id( $sessID );
        session_start();
    }

    #  If the username is not set,
    #  redirect the user to a 
    #  non-logged in page
    #
    if ( !strlen( $_SESSION['username'] ) )
    {
        header( "Location: nonlogin.php" );
        exit( 0 );
    }
    else
    {
        #  Update the timestamp of the
        #  user's last access
        #
        $_SESSION['last_access'] = mktime();
    }
}
?>
