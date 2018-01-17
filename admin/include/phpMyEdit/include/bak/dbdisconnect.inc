<?php
// Logoff from Oracle...
if ( $dbUp )
{
    OCILogoff( $dbh );

    $dbUp = false;
}
?>
