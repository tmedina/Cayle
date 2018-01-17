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
        #  Execute the query
        #
        $result = mysql_query( $sql );
    
        #  If we didn't get a result, then exit
        #
        if ( !result )
        {
            header( "Location: dberror.php" );
            exit( 0 );
        }
    
        #  Turn OFF any binary compression
        #
        ini_set( 'zlib.output_compression','Off' );
    
        #  Build the name of the CSV file
        #
        $baseFileName   = preg_replace( '/[\s()\'\"\[\]]+/', '_', $pageTitle );
        $csvFile        = $baseFileName . '.csv';
        $csvFile_mangle = $baseFileName . '_' . time() . '.csv';

        #  Open the CSV file for output
        #
        $fp = fopen( './tmp/' . $csvFile_mangle, "w" );

        #  Get the number of rows
        #
        $numRows = mysql_num_rows( $result );
    
        #  Get the number of fields
        #
        $numFields = mysql_num_fields( $result );
    
        #  Output a row that contains the
        #  column headers
        #
        for ( $colNo = 0; $colNo < $numFields; $colNo++ )
        {
            #  Output a comma if this is not the
            #  first field
            #
            if ( $colNo > 0 ) fwrite( $fp, ',' );
    
            #  Output the column name
            #
            fwrite( $fp, '"' . mysql_field_name( $result, $colNo ) . '"' );
        }
    
        #  End this first fow
        #
        fwrite( $fp, "\n" );
    
        #  While there are rows in the result set
        #
        while ( $row = mysql_fetch_row( $result ) )
        {
            #  Loop through each columns and output
            #  the values
            #
            for ( $colNo = 0; $colNo < sizeof( $row ); $colNo++ )
            {
                #  Output a comma if this is not the
                #  first field
                #
                if ( $colNo > 0 ) fwrite( $fp,  ',' );

                #  Put (="") quotes around numbers, and
                #  regular ("") quotes around text
                #
                if ( preg_match( "/^\s*\d+\s*$/", $row[ $colNo ] ) )
                {
                    #  In order to keep the number from going scientific,
                    #  if the string is greater than 9 characters,
                    #  put a '="' around the column, otherwise don't put
                    #  quotes at all
                    #
                    if ( strlen( $row[ $colNo ] ) > 9 )
                    {
                        $quote1 = '="';
                        $quote2 = '"';
                    }
                    else
                    {
                        #$row[ $colNo ] = intval( $row[ $colNo ] );
                        $quote1 = $quote2 = "";
                    }
                }
                else
                {
                    $quote1 = '"';
                    $quote2 = '"';
                }

                #  Output the column value
                #
                fwrite( $fp, $quote1 . $row[ $colNo ] . $quote2 );
            }

            #  Terminate the row
            #
            fwrite( $fp, "\n" );
        }
    
        #  Close the file
        #
        fclose( $fp );
    
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

        #  Flush the output buffer
        #
        flush();
    
        #  Remove the file
        #
        unlink( './tmp/' . $csvFile_mangle );

        #  Disconnect from the database
        #
        dbDisconnect();

        #  Exit Happily
        #
        exit( 0 );
    }
}

?>
