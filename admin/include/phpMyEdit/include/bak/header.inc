<?
//  header.inc

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

#  Read the menu and report ID from the posted page
#  
#
$mainMenuID = $_REQUEST['main_menu_id']  ? $_REQUEST['main_menu_id']  : "0";
$subMenuID  = $_REQUEST['sub_menu_id']   ? $_REQUEST['sub_menu_id']   : "0";

#  If we are to hide the navigation
#  dropdowns, clear the product and
#  report types
#
if ( preg_match( '/y|t|o|1/i', $hideNav ) )
{
    $mainMenuID = '';
    $subMenuID  = '';
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

?>

<div class="topDiv">
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
    document.formNavBar.main_menu_id.value      = main_menu_id;
    document.formNavBar.sub_menu_id.value       = 0;
    document.formNavBar.prod_id.value           = prod_id_fk;
    document.formNavBar.rpt_type.value          = 0; 
    document.formNavBar.prev_sub_menu_val.value = '';

    //  Submit the form to display
    //  the new page
    //
    document.formNavBar.submit();
}


function subMenuChange()
{
    var scheme = 'http';  //  default the scheme to non-secure

    //  Retrieve the selected option
    //
    var menuVal = document.formNavBar.sub_menu_dropdown.value;

    //  Break the value into an array
    //
    var valArray = menuVal.split( '|' );

    //  Assign some variables from the value array
    //
    var menu_id          = valArray[ 0 ];
    var menu_order       = valArray[ 1 ];
    var parent_menu_id   = valArray[ 2 ];
    var app_name         = valArray[ 3 ];
    var new_win_flag     = valArray[ 4 ];
    var new_win_name     = valArray[ 5 ];
    var new_win_attr     = valArray[ 6 ];
    var secure_flag      = valArray[ 7 ];

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
        if ( document.formNavBar.prev_sub_menu_val.value ) 
            document.formNavBar.sub_menu_dropdown.value = document.formNavBar.prev_sub_menu_val.value;
        else
            document.formNavBar.sub_menu_dropdown.selectedIndex = 0;

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
    document.formNavBar.main_menu_id.value = parent_menu_id;
    document.formNavBar.sub_menu_id.value  = menu_id;
    document.formNavBar.rpt_type.value     = menu_order;

    //  Submit the form to display
    //  the new page
    //
    document.formNavBar.submit();
}

-->
</script>
<form name="formNavBar" method="post" action="">
<input type="hidden" name="switch_sess" value="<?=session_id()?>">
<input type="hidden" name="prev_sub_menu_val" value="<?=$_REQUEST['sub_menu_dropdown']?>">
<input type="hidden" name="prev_menu_val" value="<?=$_REQUEST['main_menu_dropdown']?>">
<input type="hidden" name="main_menu_id" value="<?=$_REQUEST['main_menu_id']?>">
<input type="hidden" name="sub_menu_id" value="<?=$_REQUEST['sub_menu_id']?>">
<input type="hidden" name="prod_id" value="<?=$_REQUEST['prod_id']?>">
<input type="hidden" name="rpt_type" value="<?=$_REQUEST['rpt_type']?>">
<table border="0" align="center" valign="top" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" width="100%" nowrap>
      <table border="0" align="center" valign="top" width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="top" width="20%" nowrap>
            <img src="images/logo.gif" border="0" alt="Verisign, Inc.">
            </font>
          </td>
          <td align="center" valign="middle" width="60%" nowrap>
            <font class="pageTitle">
            <?=$pageTitle?>
          </td>
          <td align="right" valign="middle" width="20%" nowrap>
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?
#  If we are not to hide the navigation dropdowns
#
if ( !preg_match( '/y|t|o|1/i', $hideNav ) )
{
?>
  <tr>
    <td align="right" valign="middle" nowrap>
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
    if ( session_id() || strlen( $_SESSION['privs'] ) )
    {
        $snsql .= " and smm.main_menu_id in (" . $_SESSION['privs'] . ") ";
    }
    
    #  Add the order by clause
    #
    $snsql .= ' order by smm.menu_order asc';
    
    #  Define our list menu items
    #
    array( $row, $menuFields );
    
    #  Execute the query
    #
    $result = mysql_query( $snsql );
    
    #  If we were able to retrieve a result set
    #
    if ( $result )
    {
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
    <td>
      <font style="font-size: 6pt;"><br>&nbsp;
    </td>
  </tr>
<?
}

#  If we are not to show navigation bars
#
if ( !preg_match( '/y|t|o|1/i', $hideNav ) )
{
?>
  <tr>
    <td align="left" valign="middle" width="100%" colspan="3" nowrap>
      <table border="0" align="left" valign="middle" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="middle" nowrap>
            &nbsp;&nbsp;
<?

    #  Define our list menu items
    #
    array( $subMenuFields );
            
    #  If the product ID has a value
    #
    if ( strlen( $mainMenuID ) )
    {
        ######################################
        #
        #  Build my SQL statement to retrieve
        #  the sub-menu items for this main
        #  menu
        #
        ######################################
        
        $snsql = "select smm.* FROM SPNET_SUB_MENU smm " .
                 " left outer join SPNET_MAIN_MENU sp " .
                 " on smm.parent_menu_id=sp.product_id_fk " .
                 " where sp.enable_menu_flag <> 'N' " .
                 " and smm.enable_menu_flag = 'Y' " .
                 " and sp.product_id_fk = '$mainMenuID' " .
                 " order by smm.menu_order asc ";
        
        #  Execute the query
        #
        $result = mysql_query( $snsql );
        
        #  If we were able to retrieve a result set
        #
        if ( $result )
        {
?>
            <select name="sub_menu_dropdown" onchange="subMenuChange();" class="navDropdownRpt">
<?
            #  While there are rows to retieve,
            #  output the data into the select
            #  statement
            #
            while ( $row = mysql_fetch_assoc( $result ) )
            {
                #  Add the result row to our list
                #  of menu items
                #
                $subMenuFields[] = $row;
            }
            
            #  Loop through the array of menu items, and
            #  build the option dropdown
            #
            foreach( $subMenuFields as $optionRow )
            {
                #  Set (or unset) the 'selected' flag for the option
                #  based on the passed-in menu ID value
                #
                $selectFlag = $subMenuID == $optionRow['menu_id'] ? 'selected' : '';
            
                #  Build the option value
                #
                $optionValue = $optionRow['menu_id']                 . '|' .
                               $optionRow['menu_order']              . '|' .
                               $optionRow['parent_menu_id']          . '|' .
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
<?
        }  #No submenu found
    }
?>

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
    <td>
      <font style="font-size: 6pt;"><br>&nbsp;
    </td>
  </tr>
<?
}
?>
</table>
</form>
</div>
