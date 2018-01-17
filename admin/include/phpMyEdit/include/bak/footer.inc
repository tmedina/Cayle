<?php
//  footer.inc
//

#  Common Footer for all pages
#

#  Get the version for this build
#  of the system
#
if ( file_exists( "./version.html" ) )
{
    $versionStr = trim( file_get_contents( "./version.html" ) );
}
?>
<div class="footerDiv">
  <table border="0" align="center" valign="bottom">
    <tr>
      <td align="center" valign="middle">
        <font class="footerText">
        &copy; 2006 VeriSign, Inc. All rights reserved.</font>
        <a class="footerLink" 
            href="javascript:window.open( 'http://www.verisign.com/legalNotice.html', 'Verisign', 'menubar=no,width=800,height=700,resizable,toolbar=no,location=no,directories=no,scrollbars' );"  
            onclick="window.open( 'http://www.verisign.com/legalNotice.html', 'Verisign', 'menubar=no,width=800,height=700,resizable,toolbar=no,location=no,directories=no,scrollbars' ); return false;" >Legal Notices</a>
        <font class="footerText">
<?

#  If the version string has length,
#  display it
#
if ( strlen( $versionStr ) )
{
    #  Remove any tags (if they exist)
    #  and keep the version string
    #
    if ( preg_match( '/^(\s*\<.*?\>)*(.*?)(\s*\<\/.*?\>)*$/s', $versionStr, $match ) )
    {
        $versionStr = $match[ 2 ];
    }

    #  If the result of searching for the build
    #  label text resulted in a string with
    #  characters in it, display the string
    #  
    if ( strlen( $versionStr ) )
        echo "        ( $versionStr )\n";
}
?>
      </td>
    </tr>
  </table>
</div>

