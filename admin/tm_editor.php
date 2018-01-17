<?php
//  tm_editor.php
//  20090409 wilson: extend functionality of phpMyEdit to make it table-driven
//                  by using table tm_table and tm_column so we don't have
//                  to have code for each table editor we need.
########################################################################
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
include ("../includes/config.inc");
include ("../includes/header.inc");
//header("Cache-control: private"); # IE 6 Fix.

#  Set memory
#
#ini_set("memory_limit","128M");

#  Read the posted varibles
#
$tbl_name = isset($_GET[tbl_name]) ? $_GET[tbl_name] : $_GET[tblName];
$tbl_label = isset($_GET[tbl_label]) ? $_GET[tbl_label] : $_GET[tblLabel];
$back_page = $_GET[back_page];

//echo $tbl_name . " " . $tbl_label . " " . $back_page;

#  Set the page title
#
$pageTitle="Table Maintenance: " . $tbl_label . $tbl_name;

#  Set help page
#
$helpPage = 'tblEditor.htm';

?>
<html>
<head>
	<title><?=$pageTitle?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
		hr.pme-hr	     { border: 0px solid; padding: 0px; margin: 0px; border-top-width: 1px; height: 1px; }
		table.pme-main 	     { border: #999999 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
		table.pme-navigation { background-color: #BFD5EA; border: #999999 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%;}
		th.pme-header	     { border: #999999 1px solid; padding: 4px; background: #BFD5EA; }
		td.pme-key-0, td.pme-value-0, td.pme-help-0, td.pme-navigation-0, td.pme-cell-0,
		td.pme-key-1, td.pme-value-1, td.pme-help-0, td.pme-navigation-1, td.pme-cell-1,
		td.pme-sortinfo, td.pme-filter { border: #999999 1px solid; padding: 3px; }
		td.pme-buttons { text-align: left;   }
		td.pme-message { text-align: center; }
		td.pme-stats   { text-align: right;  }
	</style>

	<script type="text/javascript" src="include/phpMyEdit/extensions/js/jscalendar/calendar.js"></script>
	<script type="text/javascript" src="include/phpMyEdit/extensions/js/jscalendar/lang/calendar-en.js"></script>
	<script type="text/javascript" src="include/phpMyEdit/extensions/js/jscalendar/calendar-setup.js"></script>
	<link rel="stylesheet" type="text/css" media="screen"
		  href="include/phpMyEdit/extensions/js/jscalendar/calendar-system.css">
</head>

<link rel="stylesheet" href="include/stylesheet.css" type="text/css">
<link rel="stylesheet" href="../includes/person_band.css" type="text/css">

<script language="javascript">
<!--
function backBtnClick()
{
    var v_action = '<?=$back_page?>';
    document.formReport.action=v_action;
    document.formReport.submit();
}
-->
</script>

<body>
<?
//include ( "include/header.inc" );
include ("../includes/dbconnect.inc");

?>
<div class="bodyDiv">
<form name="formReport" method="POST" action="">
<input type="hidden" name="switch_sess" value="<?=session_id()?>">
<input type="hidden" name="main_menu_id" value="<?=$_REQUEST['main_menu_id']?>">
<input type="hidden" name="sub_menu_id" value="<?=$_REQUEST['sub_menu_id']?>">
<input type="hidden" name="tblName" value="<?=$tbl_name?>">
<input type="hidden" name="tblLabel" value="<?=$tbl_label?>">
<input type="hidden" name="back_page" value="<?=$back_page?>">

<table border="0" align="center" valign="middle" width="100%">

  <tr>
    <td align="top" valign="top" width="100%">
      <table  border="0" align="center" valign="top" cellpadding="0" width="95%" cellspacing="0">
        <tr>
          <td class="actionRow">
            <table align="center" valign="middle" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left" valign="middle" nowrap>
                  <DIV ID="oSphere" ></DIV>
                  <br>
<!--<button onClick="backBtnClick();" class="formButton"><font class="buttonText">&nbsp; Menu &nbsp;</button>-->
                  <a href="index.php">Admin Menu</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center" valign="middle" nowrap>
          &nbsp;
          </td>
        </tr>
        <tr>
          <td align="center" valign="middle" width="100%" nowrap>
            <table border="0" align="center" valign="middle" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="listHeader" align="left">
                  &nbsp;<b>Table Editor: <? echo $tbl_label; ?></b>&nbsp;
                </td>
             </tr>
           </table>
         </td>
       </tr>
       <tr>
         <td class="bodyColor">

<?php
// Read global variable to get database connection info
//$globalEnvs = readGlobalConf();

/*
 * Read TM TABLES to get TM parameters needed
 *
 */

//  Build the query
$sql = "select tbl_name, tbl_key, tbl_key_type, sort_fields, options, filters
        from tm_table
        where tbl_name = '$tbl_name'";
//echo $sql;

// Execute the query
$result = mysql_query( $sql ) or die (mysql_error());

// If we were able to retrieve a result set
if ( $result )
{
    // While there are rows to retieve,
    // output the data into the select
    // statement
    while ( $row = mysql_fetch_row( $result ) )
    {
        $tm_tb             = $row[ 0 ];
        $tm_key            = $row[ 1 ];
        $tm_key_type       = $row[ 2 ];
        $tm_sort_fields    = $row[ 3 ];
        $tm_options        = $row[ 4 ];
        $tm_filters        = $row[ 5 ];
    }
}

// Database info parms
$opts['hn'] = $dbHost;         #$globalEnvs[ 'DBHOST' ];
$opts['un'] = $dbUser;         #$globalEnvs[ 'DBUSER' ];
$opts['pw'] = $dbPass;         #$globalEnvs[ 'DBPASS' ];
$opts['db'] = $dbName;         #$globalEnvs[ 'DBNAME' ];
$opts['tb'] = $tm_tb;


// Name of field which is the unique key
$opts['key'] = $tm_key;

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = $tm_key_type;

/*
 * Sorting field(s).
 * tm_sort_fields may contain several fields to sort on separated by semicolon
 *
 */
// splitting sort fields into an array
array ( $sortFieldList );
$tm_sort_fields = preg_replace("/ /", "", $tm_sort_fields); // get rid of spaces
$sortFieldList = split( ';', $tm_sort_fields );

// assign the array to the option parameter
$opts['sort_field'] = $sortFieldList;


// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 15;

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = $tm_options;

// Number of lines to display on multiple selection filters
$opts['multiple'] = 4;

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DUB';

// wdstar 2007-06-14
// This is to get rid of the Go To Drop Down
//
/*
$opts['buttons']['L']['up'] = array('<<', '<', 'add', 'view', 'change', 'copy', 'delete', '>', '>>');
$opts['buttons']['L']['down'] = $opts['buttons']['L']['up'];
$opts['buttons']['F']['up'] = array('<<', '<', 'add', 'view', 'change', 'copy', 'delete', '>', '>>');
$opts['buttons']['F']['down'] = $opts['buttons']['F']['up'];
$opts['buttons']['V']['up'] = array('change','cancel');
$opts['buttons']['V']['down'] = $opts['buttons']['V']['up'];
*/

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => false,
	'tabs'  => true
        //'num_pages' => false,       //wdstar 2007-06-14
        //'num_records' => false,     //wdstar 2007-06-14
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

/* Table-level filter capability. If set, it is included in the WHERE clause
   of any generated SELECT statement in SQL query. This gives you ability to
   work only with subset of data from table.

$opts['filters'] = "column1 like '%11%' AND column2<17";
$opts['filters'] = "section_id = 9";
*/
$opts['filters'] = $tm_filters;

/* Auditing table */
$opts['logtable'] = 'TM_AUDIT';

/* Persistent CGI Variables */
$opts['cgi']['persist'] = array ( 'back_page' => $back_page );



/* Field definitions

Fields will be displayed left to right on the screen in the order in which they
appear in generated list. Here are some most used field options documented.

['name'] is the title used for column headings, etc.;
['maxlen'] maximum length to display add/edit/search input boxes
['trimlen'] maximum length of string content to display in row listing
['width'] is an optional display width specification for the column
          e.g.  ['width'] = '100px';
['mask'] a string that is used by sprintf() to format field output
['sort'] true or false; means the users may sort the display on this column
['strip_tags'] true or false; whether to strip tags from content
['nowrap'] true or false; whether this field should get a NOWRAP
['select'] T - text, N - numeric, D - drop-down, M - multiple selection
['options'] optional parameter to control whether a field is displayed
  L - list, F - filter, A - add, C - change, P - copy, D - delete, V - view
            Another flags are:
            R - indicates that a field is read only
            W - indicates that a field is a password field
            H - indicates that a field is to be hidden and marked as hidden
['URL'] is used to make a field 'clickable' in the display
        e.g.: 'mailto:$value', 'http://$value' or '$page?stuff';
['URLtarget']  HTML target link specification (for example: _blank)
['textarea']['rows'] and/or ['textarea']['cols']
  specifies a textarea is to be used to give multi-line input
  e.g. ['textarea']['rows'] = 5; ['textarea']['cols'] = 10
['values'] restricts user input to the specified constants,
           e.g. ['values'] = array('A','B','C') or ['values'] = range(1,99)
['values']['table'] and ['values']['column'] restricts user input
  to the values found in the specified column of another table
['values']['description'] = 'desc_column'
  The optional ['values']['description'] field allows the value(s) displayed
  to the user to be different to those in the ['values']['column'] field.
  This is useful for giving more meaning to column values. Multiple
  descriptions fields are also possible. Check documentation for this.
*/


//  Build the query
$sql = "select TC.name, TC.label, TC.maxlen, TC.trimlen, TC.width, TC.sort, TC.strip_tags, ";
$sql .= "TC.nowrap, TC.options, TC.calendar, TC.values_hardcode, TC.values_table, ";
$sql .= "TC.values_column, TC.values_desc_columns, TC.required, TC.regex, TC.hint, TC.description ";
$sql .= "from tm_column TC, tm_table TT ";
$sql .= "where TC.tm_table_id_fk = TT.id ";
$sql .= "and TT.tbl_name = '$tbl_name' ";
$sql .= "order by TC.order_id, TC.id";
//echo $sql;

// Execute the query
$result = mysql_query( $sql ) or die(mysql_error());

// If we were able to retrieve a result set
if ( $result )
{
    // While there are rows to retieve,
    // output the data into the select
    // statement
    while ( $row = mysql_fetch_row( $result ) )
    {
        $tc_name           = $row[ 0 ];
        $tc_label          = $row[ 1 ];
        $tc_maxlen         = $row[ 2 ];
        $tc_trimlen        = $row[ 3 ];
        $tc_width          = $row[ 4 ];
        $tc_sort           = $row[ 5 ];
        $tc_strip_tags     = $row[ 6 ];
        $tc_nowrap         = $row[ 7 ];
        $tc_options        = $row[ 8 ];
        $tc_calendar       = $row[ 9 ];
        $tc_values_hardcode = $row[ 10];
        $tc_values_table   = $row[ 11];
        $tc_values_column  = $row[ 12];
        $tc_values_desc    = $row[ 13];
        $tc_required       = $row[ 14];
        $tc_regexp         = $row[ 15];
        $tc_hint           = $row[ 16];
        $tc_description    = $row[ 17];


        if (strlen ($tc_name) ) {
          $opts['fdd'][$tc_name]['name'] = $tc_label;
        }

        $opts['fdd'][$tc_name]['select'] = 'T';

        if (strlen ($tc_options) ) {
          $opts['fdd'][$tc_name]['options'] = $tc_options;
        }

        if (strlen ($tc_name) ) {
          $opts['fdd'][$tc_name]['maxlen'] = $tc_maxlen;
        }

        if (strlen ($tc_trimlen) ) {
          $opts['fdd'][$tc_name]['trimlen'] = $tc_trimlen;
        }

        if (strlen ($tc_width) ) {
          $opts['fdd'][$tc_name]['width'] = $tc_width;
        }

        if (strlen ($tc_sort) ) {
          $opts['fdd'][$tc_name]['sort'] = $tc_sort;
        }

        if (strlen ($tc_strip_tags) ) {
          $opts['fdd'][$tc_name]['strip_tags'] = $tc_strip_tags;
        }

        if (strlen ($tc_nowrap) ) {
          $opts['fdd'][$tc_name]['nowrap'] = $tc_nowrap;
        }

        if (strlen ($tc_calendar) ) {
          $opts['fdd'][$tc_name]['calendar'] = $tc_calendar;
        }

        if (strlen ($tc_values_hardcode) )
        {
           array ( $arr_hardcode );
           $arr_hardcode = split( ';', $tc_values_hardcode );
           $opts['fdd'][$tc_name]['values'] = $arr_hardcode;
        }

        if (strlen ($tc_values_table) )
        {
           if ( $tc_values_table == 'BOOLEAN' )
           {
              $opts['fdd'][$tc_name]['values2'] = array( '0' => 'False', '1' => 'True' );
           }
           else
           {
              $opts['fdd'][$tc_name]['values']['table'] = $tc_values_table;
           }
        }

        if (strlen ($tc_values_column) )
        {
           $opts['fdd'][$tc_name]['values']['column'] = $tc_values_column;
        }


        if (strlen ($tc_values_desc) )
        {
           // splitting drop down description into an array
           array ( $valuesDescList );
           $tc_values_desc = preg_replace("/ /", "", $tc_values_desc );  //get rid spaces
           $valueDescList  = split( ';', $tc_values_desc );
           $i=0;
           foreach ( $valueDescList as $valueDesc )
           {
              $opts['fdd'][$tc_name]['values']['description']['columns'][$i] = $valueDesc;
              $opts['fdd'][$tc_name]['values']['description']['divs'][$i] = ' ';
              $i++;
           }
           if ( strlen ($tc_required) < 1 )
           {
              $opts['fdd'][$tc_name]['values2'] = array( '' => 'Select' );
           }
        }

        if ( strlen ($tc_required) )
        {
           $opts['fdd'][$tc_name]['js']['required'] = $tc_required;
        }
        if ( strlen ($tc_regexp) )
        {
           $opts['fdd'][$tc_name]['js']['regexp'] = $tc_regexp;
        }
        if ( strlen ($tc_hint) )
        {
           $opts['fdd'][$tc_name]['js']['hint'] = $tc_hint;
        }

        $opts['fdd'][$tc_name]['help'] = $tc_description;

    } //while
}

// Call the phpMyEdit
require_once 'include/phpMyEdit/extensions/phpMyEdit-mce-cal.class.php';
new phpMyEdit_mce_cal($opts);

?>
<b>* = Required</b>
         </td>
       </tr>

        <tr>
         <td>&nbsp;

         </td>
        </tr>
     </table>
   </td>
 </tr>
</table>
</form>
</div> <!-- Body Div      -->
<?
#  Include the footer
#
//include( "../includes/footer.inc" );
?>
</body>
</html>

