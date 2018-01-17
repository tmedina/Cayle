<?php

###############################3
#  This function will output
#  a roaming report in CSV
#  format to the browser
################################


#  If the CSV report flag is 
#  populated
#
if ( strlen( $csvReport ) )
{
    #  If the CSV flag is enabled
    #
    if ( $csvReport == "Y" )
    {
        #  Connect to the database
        #
        $csvDBConn =  OCILogon( $dbUser, $dbPass, $dbName );

        #  Parse the query
        #
        $stmt = oci_parse( $csvDBConn, $sql );
    
        #  If we didn't get a result, then exit
        #
        if ( !stmt )
        {
            header( "Location: dberror.php" );
            exit( 0 );
        }

        #  Execute the query
        #
        if ( !oci_execute( $stmt ) )
        {
            header( "Location: dberror.php" );
            exit( 0 );
        }
    
        #  Turn OFF any binary compression
        #
        ini_set( 'zlib.output_compression','Off' );
    
        #  Build the name of the CSV file
        #
        $baseFileName   = preg_replace( '/[\s\.\'"-]+/', '_', $pageTitle );
        $csvFile        = $baseFileName . '.csv';
        $csvFile_mangle = $baseFileName . getmypid() . '.csv';
    
        #  Open the CSV file for output
        #
        $fp = fopen( './tmp/' . $csvFile_mangle, "w" );
    
        #  Get the number of fields
        #
        $numFields = oci_num_fields( $stmt );
    
        #  Output a row that contains the
        #  column headers
        #
        for ( $colNo = 1; $colNo <= $numFields; $colNo++ )
        {
            #  Output a comma if this is not the
            #  first field
            #
            if ( $colNo > 1 ) fwrite( $fp, ',' );
    
            #  Output the column name
            #
            fwrite( $fp, '"' . oci_field_name( $stmt, $colNo ) . '"' );
        }
    
        #  End this first fow
        #
        fwrite( $fp, "\n" );
    
        #  While there are rows in the result set
        #
        while ( $row = oci_fetch_array( $stmt, OCI_BOTH ) )
        {
            #  Loop through each columns and output
            #  the values
            #
            for ( $colNo = 0; $colNo < $numFields; $colNo++ )
            {
                #  Output a comma if this is not the
                #  first field
                #
                if ( $colNo > 0 ) fwrite( $fp,  ',' );
    
                $quote = '';
    
                #  Put quotes around fields that
                #  don't start with numbers or are null
                #
                if ( preg_match( "/^\s*[^\d\.]/", $row[ $colNo ] ) )
                    $quote = '"';
    
                #  Output the column value
                #
                fwrite( $fp, $quote . $row[ $colNo ] . $quote );
            }
    
            #  Terminate the row
            #
            fwrite( $fp, "\n" );
        }
    
        #  Close the file
        #
        fclose( $fp );
    
        #  Disconnect from the database
        #
        OCILogoff( $csvDBConn );
    
        #  Clear the output buffe
        #
        ob_clean();

        #  Build a timestamp for the file
        #
        $lastModified = date( "D, d M Y H:i:s T" );
    
        #  Send the headers to the browser
        #  telling it that there is a CSV file
        #  ready to download
        #
        header( "Content-Type: application/vnd.ms-excel" );
        #header( 'Content-Disposition: inline; filename="'.$csvFile.'";' );
        header( 'Content-Disposition: attachment; filename="' . $csvFile . '";' );
        header( 'Accept-Ranges: bytes' );
        header( 'Last-Modified: ' . $lastModified . '.ETag: "30365-3600-915c7b00"' );
        header( "Content-Length: " . filesize( './tmp/' . $csvFile_mangle ) );
    
        #  Send the CSV file to the browser
        #
        @readfile( './tmp/' . $csvFile_mangle );
    
        #  Remove the file
        #
        unlink( './tmp/' . $csvFile_mangle );
    
        #  Exit Happily
        #
        exit( 0 );
    }
}

?>
