<?php
//  headermain.inc
//

#  If the database is not up, or
#  the global environment isn't set
#
if ( !isset($dbUp) || !sizeof( $globalEnvs ) )
{
    #  Include the database access functions
    #  if they haven't been loaded already
    #
    include_once( "include/dbaccess.inc" );

    #  If the database is not up
    #
    if ( !isset($dbUp) )
    {
        #  Connect to the database
        #
        if ( !dbConnect() )
        {
            header( "Location: dberror.php" );
            exit( 0 );
        }
    }

    #  Get the global configuration file
    #
    $globalEnvs = readGlobalConf();
}

#  Set the host IP and port
#
$hostIP = $_SERVER['HTTP_X_FORWARDED_HOST'];

#  Break apart this pages URL
#
$urlInfo = parse_url( $_SERVER['SCRIPT_URI'] );

#  If the host IP is blank, use the
#  script URI host & port
#
if ( !strlen( $hostIP ) )
{
    $hostIP = $urlInfo['host'];

    #  If the port is not blank, add the
    #  the port
    #
    if ( strlen( $urlInfo['port'] ) )
    {
        $hostIP .= ':' . $urlInfo['port'];
    }
}

#  Retrieve the product ID
#
$productID = $_REQUEST['prod_id'] ? $_REQUEST['prod_id'] : 0;

?>
<script language="javascript">
<!--

function prodIDChange()
{
    var scheme = 'http';  //  default the scheme to non-secure

    //  Set the next page we go to depending
    //  on the value of the navigation dropdown
    //
    var exe = 'f3' + document.formNavbar.prod_id.value;
    var nwn = 'f4' + document.formNavbar.prod_id.value;
    document.formNavbar.action = document.formNavbar[exe].value;

    if (document.formNavbar[nwn].value == "Y")    //The 'Y' indicates a new window
    { 
        var wpoint = 'f5' + document.formNavbar.prod_id.value;
        var wname = document.formNavbar[wpoint].value + '<?=$globalEnvs['SITE']?>';
        var vpoint = 'f6' + document.formNavbar.prod_id.value;
        var wvalu = document.formNavbar[vpoint].value;
        var xpoint = 'f7' + document.formNavbar.prod_id.value;
        var secflg = document.formNavbar[xpoint].value;

        //  If the secure connect flag is affirmative,
        //  set the scheme to 'https'
        //
        if ( secflg.match( /y|yes|t|true|1|on/i ) )
        {
            scheme = 'https';
        }

        <?$nextURL = '://' . $hostIP; ?>
        var goto = scheme + '<?=$nextURL;?>' + document.formNavbar[exe].value;

        //  Open a new window, saving
        //  the window handle
        //
        var newWin = window.open( goto,wname,wvalu );

        //  If the new window successfully opened,
        //  force it to the top
        //
        if ( newWin ) newWin.focus();
                return true; 
    }

    //  Submit the form to display
    //  the new page
    //
    document.formNavbar.submit();
}

//-->
</script>

<div class="topDiv">
<form name="formNavbar" method="post" action="">
<input type="hidden" name="switch_sess" value="<?=session_id()?>">
<table border="0" align="center" valign="top" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="middle" width="20%" nowrap>
      <img src="images/logo.gif" border="0" alt="Verisign, Inc.">
      </font>
    </td>
    <td align="center" valign="middle" width="60%" nowrap>
      <font class="pageTitle">
      <?=$pageTitle?>
      <?if ( isset( $_SESSION['first_name'] ) ) echo "<br>Hello ", $_SESSION['first_name'];?>
    </td>
    <td align="center" valign="middle" width="20%" nowrap>
      &nbsp;
    </td>
  </tr>
  <tr>
    <td align="right" valign="middle" width="100%" colspan="3" nowrap>
      <table border="0" align="right" valign="middle" cellpadding="0" cellspacing="0">
        <tr>
          <td align="right" valign="middle" nowrap>
            <font class="labelClean">
            &nbsp; Navigate: &nbsp;
          </td>
          <td align="left" valign="middle" nowrap>
            <select name="prod_id" onchange="prodIDChange();" class="navDropdownProd">
<?
#  Declare and clear our SQL query
#  variable
#
$snsql      = '';

######################################
#
#  Build my SQL statement to retrieve
#  the valid switch names depending
#  on the report type selected
#  by the user
#
######################################

$snsql = 'select * FROM SPNET_MAIN_MENU smm ' .
         ' left outer join SPNET_PRODUCT sp ' .
         ' on smm.product_id_fk=sp.product_id ' .
         " where sp.enable_flag <> 'N' " .
         " and smm.enable_menu_flag = 'Y' ";


#  If sessions are enabled,
#  add the users privileges to the query
#
if ( session_id() )
{
    $snsql .= " and smm.main_menu_id in (" . $_SESSION['privs'] . ") ";
}

#  Add the order by clause
#
$snsql .= ' order by smm.menu_order asc';

#  Declare our arrays of switch information
#
array( $menuFields => $menuPhp);

#  Execute the query
#
$result = mysql_query( $snsql );

#  If we were able to retrieve a result set
#
if ( $result )
{

    #  While there are rows to retieve,
    #  output the data into the select
    #  statement
    #
    $i = 0;
        $menuFields['f1'.$i] = '0';
        $menuFields['f2'.$i] = '-------select-------';
        $menuFields['f3'.$i] = 'usermain.php';
        $menuFields['f4'.$i] = 'N';
        $menuFields['f5'.$i] = '';
        $menuFields['f6'.$i] = '';
        $menuFields['f7'.$i] = 'N';
    $i = 1;
    while ( $row = mysql_fetch_assoc( $result ) )
    {
        $menuFields['f1'.$i] = $row['product_id_fk'];
        $menuFields['f2'.$i] = $row['menu_title'];
        $menuFields['f3'.$i] = $row['application_name'];
        $menuFields['f4'.$i] = $row['open_in_new_window_flag'];
        $menuFields['f5'.$i] = $row['new_window_name'];
        $menuFields['f6'.$i] = $row['new_window_attributes'];
        $menuFields['f7'.$i] = $row['secure_connect_flag'];
        $i++;
    }


    $end = $i;
}

#  This creates the right side nav dropdown list
#
for( $i = 0; $i < $end; $i++)
{
    $j = 'f1'.$i;       #PROD_ID
    $k = 'f2'.$i;       #PROD_DESCRIPTION
    $m = 'f3'.$i;       #executeable

    $selectFlag = $productID == $menuFields[ $j ] ? 'selected' : '';

    $option = "<option value=\"$menuFields[$j]\" $selectFlag> $menuFields[$k]</option>\n";

    echo $option;
}


$snsql = '';

echo "</select>&nbsp;&nbsp;\n";


#  This sets up the index and php list to be executed
#
for($i = 1;$i < $end;$i++)
{
    $j = 'f1'.$i;       #PROD_ID
    $k = 'f2'.$i;       #PROD_DESCRIPTION
    $m = 'f3'.$i;       #executeable
    $n = 'f4'.$i;       #New Window flag
    $o = 'f5'.$i;       #new_window_name
    $p = 'f6'.$i;       #new_window_attributes
    $q = 'f7'.$i;       #secure_connect_flag
    echo "<input type=\"hidden\" name=\"$m\" value=\"$menuFields[$m]\">\n";
    echo "<input type=\"hidden\" name=\"$n\" value=\"$menuFields[$n]\">\n";
    echo "<input type=\"hidden\" name=\"$o\" value=\"$menuFields[$o]\">\n";
    echo "<input type=\"hidden\" name=\"$p\" value=\"$menuFields[$p]\">\n";
    echo "<input type=\"hidden\" name=\"$q\" value=\"$menuFields[$q]\">\n";
}
?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="right" valign="bottom" width="100%" colspan="3" nowrap>
      <font style="font-size: 6pt;"><br>&nbsp;
    </td>
  </tr>
</table>
</form>
</div>
