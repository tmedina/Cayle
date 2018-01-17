<?php

###############################3
#  This function will output
#  a file/report in text
#  format to the browser
################################

#  If the txt report flag is 
#  populated
#
if ( strlen( $txtReport ) )
{
    #  If the TXT flag is enabled
    #
    if ( $txtReport == "Y" )
    {

        #  Clear the output buffe
        #
        ob_clean();

        #  Turn OFF any binary compression
        #
        ini_set( 'zlib.output_compression','Off' );
    
        #  Build a timestamp for the file
        #
        $lastModified = date( "D, d M Y H:i:s T" );
    
        #  Send the headers to the browser
        #  telling it that there is a CSV file
        #  ready to download
        #
        #header( "Content-Type: application/vnd.ms-excel" );
        header( "Content-Type: text/plain" );
        #header( 'Content-Disposition: inline; filename="'.$txtFile.'";' );
        header( 'Content-Disposition: attachment; filename="' . $txtFile . '";' );
        header( 'Accept-Ranges: bytes' );
        header( 'Last-Modified: ' . $lastModified . '.ETag: "30365-3600-915c7b00"' );
        header( "Content-Length: " . filesize( $txtFile ) );
    
        #  Send the CSV file to the browser
        #
        @readfile( $txtFile );
    
        #  Flush the output buffer
        #
        flush();

        #  Exit Happily
        #
        exit( 0 );
    }
}

?>
