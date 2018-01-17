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

#  If the host IP is still blank,
#  get it from the hostname -i command
#
if ( !strlen( $hostIP ) ) $hostIP = trim( `hostname -i` );


#  Retrieve the product ID
#
$mainMenuID = $_REQUEST['main_menu_id'] ? $_REQUEST['main_menu_id'] : 0;

?>
<script language="javascript">
<!--

function mainMenuChange()
{
    var scheme = 'http';  //  default the scheme to non-secure

    //  Retrieve the selected option
    //
    var menuVal = document.formNavBar.main_menu_dropdown.value;

    //  Break the value into an array
    //
    var valArray = menuVal.split( '|' );

    //  Assign some variables from the value array
    //
    var main_menu_id = valArray[ 0 ];
    var prod_id_fk   = valArray[ 1 ];
    var app_name     = valArray[ 2 ];
    var new_win_flag = valArray[ 3 ];
    var new_win_name = valArray[ 4 ];
    var new_win_attr = valArray[ 5 ];
    var secure_flag  = valArray[ 6 ];

    //  If the new window flag is affirmative
    //
    if ( new_win_flag.match( /y|yes|t|true|1|on/i  ) ) 
    { 
        //  If the secure connect flag is affirmative,
        //  set the scheme to 'https'
        //
        if ( secure_flag.match( /y|yes|t|true|1|on/i ) )
        {
            scheme = 'https';
        }

        //  Build the next URL to view
        //
        var nextURL = scheme + '://<?=$hostIP?>' + app_name;

        //  Open a new window, saving
        //  the window handle
        //
        var newWin = window.open( nextURL, new_win_name + '<?=$globalEnvs['SITE']?>', new_win_attr );

        //  Set the main menu back to the previous setting
        //
        if ( main_menu_id )
            document.formNavBar.main_menu_dropdown.value = document.formNavBar.prev_menu_val.value;
        else
            document.formNavBar.main_menu_dropdown.selectedIndex = -1;

        //  If the new window successfully opened,
        //  force it to the top
        //
        if ( newWin ) newWin.focus();
                return true; 
    }

    //  Set the next page we go to depending
    //  on the value of the navigation dropdown
    //
    document.formNavBar.action = app_name;

    //  Set the main menu id for this form
    //
    document.formNavBar.main_menu_id.value = main_menu_id;
    document.formNavBar.prod_id.value      = prod_id_fk;

    //  Submit the form to display
    //  the new page
    //
    document.formNavBar.submit();
}

//-->
</script>

<div class="topDiv">
<form name="formNavBar" method="post" action="">
<input type="hidden" name="switch_sess" value="<?=session_id()?>">
<input type="hidden" name="prev_menu_val" value="<?=$_REQUEST['main_menu_dropdown']?>">
<input type="hidden" name="main_menu_id" value="<?=$_REQUEST['main_menu_id']?>">
<input type="hidden" name="prod_id" value="0">
<input type="hidden" name="rpt_type" value="0">
<table border="0" align="center" valign="top" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top" width="20%" nowrap>
      <img src="images/logo.gif" border="0" alt="Verisign, Inc.">
      </font>
    </td>
    <td align="center" valign="middle" width="60%" nowrap>
      <font class="pageTitle">
      <?=$pageTitle?>
<?
#  If we are on the hello screen
#
if ( 0 == $productID  )
{
    #  If we have a user logged in
    #
    if ( isset( $_SESSION['first_name'] ) ) 
    {
        echo "      <br>Hello ", $_SESSION['first_name'];
    }
}
?>
    </td>
    <td align="center" valign="middle" width="20%" nowrap>
      &nbsp;
    </td>
  </tr>
<?
#  If we to show navigation bars
#
if ( !preg_match( '/y|t|o|1/i', $hideNav ) )
{
?>
  <tr>
    <td align="right" valign="middle" width="100%" colspan="3" nowrap>
      <table border="0" align="right" valign="middle" cellpadding="0" cellspacing="0">
        <tr>
          <td align="right" valign="middle" nowrap>
            <font class="labelClean">
            &nbsp; Navigate: &nbsp;
          </td>
          <td align="left" valign="middle" nowrap>
            <select name="main_menu_dropdown" onchange="mainMenuChange();" class="navDropdownProd">
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
        #  Define our list menu items
        #
        array( $row, $menuFields );
    
        #  Create the default menu item
        #
        $row['main_menu_id']               =  0;
        $row['product_id_fk']              =  0;
        $row['menu_title']                 =  '-------select-------';
        $row['application_name']           =  'usermain.php';
        $row['open_in_new_window_flag']    =  'N';
        $row['new_window_name']            =  '';
        $row['new_window_attributes']      =  '';
        $row['secure_connect_flag']        =  'N';
    
        #  Add the default menu item
        #  to our array of menu items
        #
        $menuFields[] = $row;
    
        #  While there are rows to retieve,
        #  output the data into the select
        #  statement
        #
        while ( $row = mysql_fetch_assoc( $result ) )
        {
            #  Add the result row to our list
            #  of menu items
            #
            $menuFields[] = $row;
        }
    }
    
    #  Loop through the array of menu items, and
    #  build the option dropdown
    #
    foreach( $menuFields as $optionRow )
    {
        #  Set (or unset) the 'selected' flag for the option
        #  based on the passed-in menu ID value
        #
        $selectFlag = $mainMenuID == $optionRow['main_menu_id'] ? 'selected' : '';
    
        #  Build the option value
        #
        $optionValue = $optionRow['main_menu_id']            . '|' .
                       $optionRow['product_id_fk']           . '|' .
                       $optionRow['application_name']        . '|' . 
                       $optionRow['open_in_new_window_flag'] . '|' .
                       $optionRow['new_window_name']         . '|' .
                       $optionRow['new_window_attributes']   . '|' .
                       $optionRow['secure_connect_flag']     . '|';
     
?>
              <option value="<?=$optionValue?>" <?=$selectFlag?>><?=$optionRow['menu_title']?></option> 
<?
    }
?>
            </select>&nbsp;&nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?
}
else
{
?>
  <tr>
    <td class="label">
      <font style="font-size: 6pt;"><br>&nbsp;
    </td>
  </td>
<?
}
?>
  <tr>
    <td align="right" valign="bottom" width="100%" colspan="3" nowrap>
      <font style="font-size: 6pt;"><br>&nbsp;
    </td>
  </tr>
</table>
</form>
</div>
