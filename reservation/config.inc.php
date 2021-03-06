<?php
// get database info from includes directory
include ("../includes/config.inc");

// $Id: config.inc.php 999 2009-01-22 00:36:30Z cimorrison $
// 20090302 wilson: create an array to map period difference to time difference
// 20090318 wilson: change period. noon to 2am.
// 20090318 wilson: get db info from ../includes/config.inc
// 20090319 wilson: create an array, periods_val, to map period values

/**************************************************************************
 *   MRBS Configuration File
 *   Configure this file for your site.
 *   You shouldn't have to modify anything outside this file.
 **************************************************************************/

// The timezone your meeting rooms run in. It is especially important
// to set this if you're using PHP 5 on Linux. In this configuration
// if you don't, meetings in a different DST than you are currently
// in are offset by the DST offset incorrectly.
//
// When upgrading an existing installation, this should be set to the
// timezone the web server runs in.
//
//$timezone = "Europe/London";


/*******************
 * Database settings
 ******************/

// Which database system: "pgsql"=PostgreSQL, "mysql"=MySQL,
// "mysqli"=MySQL via the mysqli PHP extension
$dbsys = "mysql";
// Hostname of database server. For pgsql, can use "" instead of localhost
// to use Unix Domain Sockets instead of TCP/IP.
$db_host = $dbHost;
// Database name:
$db_database = $dbName;
// Database login user name:
$db_login = $dbUser;
// Database login password:
$db_password = $dbPass;
// Prefix for table names.  This will allow multiple installations where only
// one database is available
$db_tbl_prefix = "reservation_";
// Uncomment this to NOT use PHP persistent (pooled) database connections:
// $db_nopersist = 1;

/*********************************
 * Site identification information
 *********************************/
$mrbs_admin = "Your Administrator";
$mrbs_admin_email = "admin_email@your.org";

// The company name is mandatory.   It is used in the header and also for email notifications.
// The company logo, additional information and URL are all optional.

$mrbs_company = "Your Company";   // This line must always be uncommented ($mrbs_company is used in various places)

// Uncomment this next line to use a logo instead of text for your organisation in the header
//$mrbs_company_logo = "your_logo.gif";    // name of your logo file.   This example assumes it is in the MRBS directory

// Uncomment this next line for supplementary information after your company name or logo
//$mrbs_company_more_info = "You can put additional information here";  // e.g. "XYZ Department"

// Uncomment this next line to have a link to your organisation in the header
//$mrbs_company_url = "http://www.your_organisation.com/";

// This is to fix URL problems when using a proxy in the environment.
// If links inside MRBS appear broken, then specify here the URL of
// your MRBS root directory, as seen by the users. For example:
// $url_base =  "http://webtools.uab.ericsson.se/oam";
// It is also recommended that you set this if you intend to use email
// notifications, to ensure that the correct URL is displayed in the
// notification.
$url_base = "";


/*******************
 * Calendar settings
 *******************/

// Note: Be careful to avoid specify options that displays blocks overlaping
// the next day, since it is not properly handled.

// This setting controls whether to use "clock" based intervals (FALSE and
// the default) or user defined periods (TRUE).  If user-defined periods
// are used then $resolution, $morningstarts, $eveningends,
// $eveningends_minutes and $twentyfourhour_format are ignored.
$enable_periods = TRUE;

// Resolution - what blocks can be booked, in seconds.
// Default is half an hour: 1800 seconds.
$resolution = 1800;

// Default duration - default length (in seconds) of a booking.
// Ignored if $enable_periods is TRUE
// Defaults to (60 * 60) seconds, i.e. an hour
$default_duration = (60 * 60);

// Start and end of day.
// NOTE:  The time between the beginning of the last and first
// slots of the day must be an integral multiple of the resolution,
// and obviously >=0.


// The default settings below (along with the 30 minute resolution above)
// give you 24 half-hourly slots starting at 07:00, with the last slot
// being 18:30 -> 19:00

// The beginning of the first slot of the day
$morningstarts         = 10;   // must be integer in range 0-23
$morningstarts_minutes = 0;   // must be integer in range 0-59

// The beginning of the last slot of the day
$eveningends           = 23;  // must be integer in range 0-23
$eveningends_minutes   = 30;   // must be integer in range 0-59

// Example 1.
// If resolution=3600 (1 hour), morningstarts = 8 and morningstarts_minutes = 30 
// then for the last period to start at say 4:30pm you would need to set eveningends = 16
// and eveningends_minutes = 30

// Example 2.
// To get a full 24 hour display with 15-minute steps, set morningstarts=0; eveningends=23;
// eveningends_minutes=45; and resolution=900.



// Define the name or description for your periods in chronological order
// For example:
// $periods[] = "Period&nbsp;1"
// $periods[] = "Period&nbsp;2"
// ...
// or
// $periods[] = "09:15&nbsp;-&nbsp;09:50"
// $periods[] = "09:55&nbsp;-&nbsp;10:35"
// ...
// &nbsp; is used to ensure that the name or description is not wrapped
// when the browser determines the column widths to use in day and week
// views
//
// NOTE:  MRBS assumes that the descriptions are valid HTML and can be output
// directly without any encoding.    Please ensure that any special characters
// are encoded, eg '&' to '&amp;', '>' to '&gt;', lower case e acute to 
// '&eacute;' or '&#233;', etc.

// NOTE:  The maximum number of periods is 60.   Do not define more than this.
$periods[] = "12:00";
$periods[] = "12:30";
$periods[] = "13:00";
$periods[] = "13:30";
$periods[] = "14:00";
$periods[] = "14:30";
$periods[] = "15:00";
$periods[] = "15:30";
$periods[] = "16:00";
$periods[] = "16:30";
$periods[] = "17:00";
$periods[] = "17:30";
$periods[] = "18:00";
$periods[] = "18:30";
$periods[] = "19:00";
$periods[] = "19:30";
$periods[] = "20:00";
$periods[] = "20:30";
$periods[] = "21:00";
$periods[] = "21:30";
$periods[] = "22:00";
$periods[] = "22:30";
$periods[] = "23:00";
$periods[] = "23:30";
$periods[] = "00:00";
$periods[] = "00:30";
$periods[] = "01:00";
$periods[] = "01:30";
$periods[] = "02:00";

// NOTE:  The maximum number of periods is 60.   Do not define more than this.

// Do some checking
if ($enable_periods)
{
  if (count($periods) > 60)
  {
    die('Configuration error: too many periods defined');
  }
}
else
{
  $start_first_slot = ($morningstarts*60) + $morningstarts_minutes;   // minutes
  $start_last_slot  = ($eveningends*60) + $eveningends_minutes;       // minutes
  $start_difference = ($start_last_slot - $start_first_slot) * 60;    // seconds
  if (($start_difference < 0) or ($start_difference%$resolution != 0))
  {
    die('Configuration error: start and end of day incorrectly defined');
  }
}


// 20090302 wilson: create an array to map period difference to time difference
//
$arr_period_time_diff = array(
                             1 => "30   minutes",
                             2 => "1    hour",
                             3 => "1.5  hours",
                             4 => "2    hours",
                             5 => "2.5  hours",
                             6 => "3    hours",
                             7 => "3.5  hours",
                             8 => "4    hours",
                             9 => "4.5  hours",
                             10 => "5 hours",
                             11 => "5.5 hours",
                             12 => "6 hours",
                             13 => "6.5 hours",
                             14 => "7 hours",
                             15 => "7.5 hours",
                             16 => "8 hours",
                             17 => "8.5 hours",
                             18 => "9 hours",
                             19 => "9.5 hours",
                             20 => "10 hours",
                             21 => "10.5 hours",
                             22 => "11 hours",
                             23 => "11.5 hours",
                             24 => "12 hours",
                             25 => "12.5 hours",
                             26 => "13 hours",
                             27 => "13.5 hours",
                             28 => "14 hours",
                             29 => "14.5 hours",
                             30 => "15 hours",
                             31 => "15.5 hours",
                             32 => "16 hours"
                           );

// 20090319 wilson: create an array, periods_val, to map period values
//
$periods_val[120000] = "120000";
$periods_val[123000] = "123000";
$periods_val[130000] = "130000";
$periods_val[133000] = "133000";
$periods_val[140000] = "140000";
$periods_val[143000] = "143000";
$periods_val[150000] = "150000";
$periods_val[153000] = "153000";
$periods_val[160000] = "160000";
$periods_val[163000] = "163000";
$periods_val[170000] = "170000";
$periods_val[173000] = "173000";
$periods_val[180000] = "180000";
$periods_val[183000] = "183000";
$periods_val[190000] = "190000";
$periods_val[193000] = "193000";
$periods_val[200000] = "200000";
$periods_val[203000] = "203000";
$periods_val[210000] = "210000";
$periods_val[213000] = "213000";
$periods_val[220000] = "220000";
$periods_val[223000] = "223000";
$periods_val[230000] = "230000";
$periods_val[233000] = "233000";
$periods_val[0] = "240000";
$periods_val[3000] = "243000";
$periods_val[10000] = "250000";
$periods_val[13000] = "253000";
$periods_val[20000] = "260000";

// Start of week: 0 for Sunday, 1 for Monday, etc.
$weekstarts = 0;

// Days of the week that should be hidden from display
// 0 for Sunday, 1 for Monday, etc.
// For example, if you want Saturdays and Sundays to be hidden set $hidden_days = array(0,6);
//
// By default the hidden days will be removed completely from the main table in the week and month
// views.   You can alternatively arrange for them to be shown as narrow, greyed-out columns
// by editing the CSS file.   Look for $column_hidden_width in mrbs.css.php.
//
// [Note that although they are hidden from display in the week and month views, they 
// can still be booked from the edit_entry form and you can display the bookings by
// jumping straight into the day view from the date selector.]
$hidden_days = array();

// Trailer date format: 0 to show dates as "Jul 10", 1 for "10 Jul"
$dateformat = 0;

// Time format in pages. 0 to show dates in 12 hour format, 1 to show them
// in 24 hour format
$twentyfourhour_format = 1;

/************************
 * Miscellaneous settings
 ************************/

// Maximum repeating entrys (max needed +1):
$max_rep_entrys = 365 + 1;

// Default report span in days:
$default_report_days = 60;

// Results per page for searching:
$search["count"] = 20;

// Page refresh time (in seconds). Set to 0 to disable
$refresh_rate = 60;

// Trailer type.   FALSE gives a trailer complete with links to days, weeks and months before
// and after the current date.    TRUE gives a simpler trailer that just has links to the
// current day, week and month.
$simple_trailer = TRUE;

// should areas be shown as a list or a drop-down select box?
$area_list_format = "list";
//$area_list_format = "select";

// Entries in monthly view can be shown as start/end slot, brief description or
// both. Set to "description" for brief description, "slot" for time slot and
// "both" for both. Default is "both", but 6 entries per day are shown instead
// of 12.
$monthly_view_entries_details = "both";

// To view weeks in the bottom (trailer.inc) as week numbers (42) instead of
// 'first day of the week' (13 Oct), set this to TRUE
$view_week_number = FALSE;

// To display times on right side in day and week view, set to TRUE;
$times_right_side = FALSE;

// Control the active cursor in day/week/month views.   By default, highlighting
// is implemented using the CSS :hover pseudo-class.    For old browers such as
// IE6, this is not supported and MRBS will automatically switch over to use 
// JavaScript highlighting - for which there are three different modes: 'bgcolor',
// 'class' and 'hybrid'.  If clients have VERY old browsers, then you may even want
// to disable the JavaScript highlighting by setting $javascript_cursor to false.
$javascript_cursor = TRUE; // Change to FALSE if clients have very old browsers
                           // incompatible with JavaScript.
$show_plus_link = FALSE;   // Change to TRUE to always show the (+) link as in
                           // MRBS 1.1.
$highlight_method = "hybrid"; // One of "bgcolor", "class", "hybrid".   "hybrid" is recommended as it is
                              // faster in old browsers such as IE6 - which is the only time that
                              // JavaScript highlighting is used anyway.    The rest of the time CSS
                              // highlighting is used, whether or not $javascript_cursor is set.

// Define default starting view (month, week or day)
// Default is day
$default_view = "day";

// Define default room to start with (used by index.php)
// Room numbers can be determined by looking at the Edit or Delete URL for a
// room on the admin page.
// Default is 0
$default_room = 0;

/***********************************************
 * Authentication settings - read AUTHENTICATION
 ***********************************************/

$auth["session"] = "php"; // How to get and keep the user ID. One of
           // "http" "php" "cookie" "ip" "host" "nt" "omni"
           // "remote_user"

$auth["type"] = "db"; // How to validate the user/password. One of "none"
                          // "config" "db" "db_ext" "pop3" "imap" "ldap" "nis"
                          // "nw" "ext".
// NOTE:  if you are using the "db" authentication scheme, then make sure you keep the admin user
// "administrator" below.    The system initially relies on there being an admin called "administrator".
// Once you have set up the user list and created one or more other admins (by adding them both to the
// user list and the config file), then you can delete or rename the "administrator" user.

// Configuration parameters for 'cookie' session scheme

// The encryption secret key for the session tokens. You are strongly
// advised to change this if you use this session scheme
$auth["session_cookie"]["secret"] = "This isn't a very good secret!";
// The expiry time of a session, in seconds
$auth["session_cookie"]["session_expire_time"] = (60*60*24*30); // 30 days
// Whether to include the user's IP address in their session cookie.
// Increases security, but could cause problems with proxies/dynamic IP
// machines
$auth["session_cookie"]["include_ip"] = TRUE;


// Cookie path override. If this value is set it will be used by the
// 'php' and 'cookie' session schemes to override the default behaviour
// of automatically determining the cookie path to use
$cookie_path_override = '';

// The list of administrators (can modify other peoples settings)
$auth["admin"][] = "127.0.0.1";   // localhost IP address. Useful with IP sessions.
$auth["admin"][] = "administrator"; // A user name from the user list. Useful 
                                    // with most other session schemes.
//$auth["admin"][] = "10.0.0.1";
//$auth["admin"][] = "10.0.0.2";
//$auth["admin"][] = "10.0.0.3";

// 'auth_config' user database
// Format: $auth["user"]["name"] = "password";
$auth["user"]["administrator"] = "secret";
$auth["user"]["alice"] = "a";
$auth["user"]["bob"] = "b";

// 'session_http' configuration settings
$auth["realm"]  = "mrbs";

// 'session_remote_user' configuration settings
//$auth['remote_user']['logout_link'] = '/logout/link.html';

// 'auth_ext' configuration settings
$auth["prog"]   = "";
$auth["params"] = "";

// 'auth_db_ext' configuration settings
// The 'db_system' variable is equivalent to the core MRBS $dbsys variable,
// and allows you to use any of MRBS's database abstraction layers for
// db_ext authentication.
$auth['db_ext']['db_system'] = 'mysql';
$auth['db_ext']['db_host'] = 'localhost';
$auth['db_ext']['db_username'] = 'authuser';
$auth['db_ext']['db_password'] = 'authpass';
$auth['db_ext']['db_name'] = 'authdb';
$auth['db_ext']['db_table'] = 'users';
$auth['db_ext']['column_name_username'] = 'name';
$auth['db_ext']['column_name_password'] = 'password';
// Either 'md5', 'sha1', 'crypt' or 'plaintext'
$auth['db_ext']['password_format'] = 'md5';

// 'auth_ldap' configuration settings
// Where is the LDAP server
//$ldap_host = "localhost";
// If you have a non-standard LDAP port, you can define it here
//$ldap_port = 389;
// If you do not want to use LDAP v3, change the following to false
$ldap_v3 = true;
// If you want to use TLS, change the following to true
$ldap_tls = false;
// LDAP base distinguish name
// See AUTHENTICATION for details of how check against multiple base dn's
//$ldap_base_dn = "ou=organizationalunit,dc=my-domain,dc=com";
// Attribute within the base dn that contains the username
//$ldap_user_attrib = "uid";
// If you need to search the directory to find the user's DN to bind
// with, set the following to the attribute that holds the user's
// "username". In Microsoft AD directories this is "sAMAccountName"
//$ldap_dn_search_attrib = "sAMAccountName";
// If you need to bind as a particular user to do the search described
// above, specify the DN and password in the variables below
// $ldap_dn_search_dn = "cn=Search User,ou=Users,dc=some,dc=company";
// $ldap_dn_search_password = "some-password";

// 'auth_ldap' extra configuration for ldap configuration of who can use
// the system
// If it's set, the $ldap_filter will be combined with the value of
// $ldap_user_attrib like this:
//   (&($ldap_user_attrib=username)($ldap_filter))
// After binding to check the password, this check is used to see that
// they are a valid user of mrbs.
//$ldap_filter = "mrbsuser=y";

// 'auth_imap' configuration settings
// See AUTHENTICATION for details of how check against multiple servers
// Where is the IMAP server
$imap_host = "imap-server-name";
// The IMAP server port
$imap_port = "143";

// 'auth_imap_php' configuration settings
$auth["imap_php"]["hostname"] = "localhost";
// You can also specify any of the following options:
// Specifies the port number to connect to
//$auth["imap_php"]["port"] = 993;
// Use SSL
//$auth["imap_php"]["ssl"] = TRUE;
// Use TLS
//$auth["imap_php"]["tls"] = TRUE;
// Turn off SSL/TLS certificate validation
//$auth["imap_php"]["novalidate-cert"] = TRUE;

// 'auth_pop3' configuration settings
// See AUTHENTICATION for details of how check against multiple servers
// Where is the POP3 server
$pop3_host = "pop3-server-name";
// The POP3 server port
$pop3_port = "110";


/**********************************************
 * Email settings
 **********************************************/

// You can override the charset used in emails if $unicode_encoding is 1
// (utf-8) if you like, but be sure the charset you choose can handle all
// the characters in the translation and that anyone may use in a
// booking description
//$mail_charset = "iso-8859-1";

// Set to TRUE if you want to be notified when entries are booked. Default is
// FALSE
define ("MAIL_ADMIN_ON_BOOKINGS", FALSE);

// Set to TRUE if you want AREA ADMIN to be notified when entries are booked.
// Default is FALSE. Area admin emails are set in room_area admin page.
define ("MAIL_AREA_ADMIN_ON_BOOKINGS", FALSE);

// Set to TRUE if you want ROOM ADMIN to be notified when entries are booked.
// Default is FALSE. Room admin emails are set in room_area admin page.
define ("MAIL_ROOM_ADMIN_ON_BOOKINGS", FALSE);

// Set to TRUE if you want ADMIN to be notified when entries are deleted. Email
// will be sent to mrbs admin, area admin and room admin as per above settings,
// as well as to booker if MAIL_BOOKER is TRUE (see below).
define ("MAIL_ADMIN_ON_DELETE", FALSE);

// Set to TRUE if you want to be notified on every change (i.e, on new entries)
// but also each time they are edited. Default is FALSE (only new entries)
define ("MAIL_ADMIN_ALL", FALSE);

// Set to TRUE is you want to show entry details in email, otherwise only a
// link to view_entry is provided. Irrelevant for deleted entries. Default is
// FALSE.
define ("MAIL_DETAILS", FALSE);

// Set to TRUE if you want BOOKER to receive a copy of his entries as well any
// changes (depends of MAIL_ADMIN_ALL, see below). Default is FALSE. To know
// how to set mrbs to send emails to users/bookers, see INSTALL.
define ("MAIL_BOOKER", FALSE);

// If MAIL_BOOKER is set to TRUE (see above) and you use an authentication
// scheme other than 'auth_db', you need to provide the mail domain that will
// be appended to the username to produce a valid email address (ie.
// "@domain.com").
define ("MAIL_DOMAIN", '');

// If you use MAIL_DOMAIN above and username returned by mrbs contains extra
// strings appended like domain name ('username.domain'), you need to provide
// this extra string here so that it will be removed from the username.
define ("MAIL_USERNAME_SUFFIX", '');

// Set the name of the Backend used to transport your mails. Either "mail",
// "smtp" or "sendmail". Default is 'mail'. See INSTALL for more details.
define ("MAIL_ADMIN_BACKEND", "mail");

/*******************
 * Sendmail settings
 */

// Set the path of the Sendmail program (only used with "sendmail" backend).
// Default is "/usr/bin/sendmail"
define ("SENDMAIL_PATH", "/usr/bin/sendmail");

// Set additional Sendmail parameters (only used with "sendmail" backend).
// (example "-t -i"). Default is ""
define ("SENDMAIL_ARGS", '');

/*******************
 * SMTP settings
 */

// Set smtp server to connect. Default is 'localhost' (only used with "smtp"
// backend).
define ("SMTP_HOST", "localhost");

// Set smtp port to connect. Default is '25' (only used with "smtp" backend).
define ("SMTP_PORT", 25);

// Set whether or not to use SMTP authentication. Default is 'FALSE'
define ("SMTP_AUTH", FALSE);

// Set the username to use for SMTP authentication. Default is ""
define ("SMTP_USERNAME", '');

// Set the password to use for SMTP authentication. Default is ""
define ("SMTP_PASSWORD", '');

/**********************
 * Miscellaneous settings
 */

// Set the language used for emails (choose an available lang.* file).
// Default is 'en'.
define ("MAIL_ADMIN_LANG", 'en');

// Set the email address of the From field. Default is $mrbs_admin_email
define ("MAIL_FROM", $mrbs_admin_email);

// Set the recipient email. Default is $mrbs_admin_email. You can define
// more than one recipient like this "john@doe.com,scott@tiger.com"
define ("MAIL_RECIPIENTS", $mrbs_admin_email);

// Set email address of the Carbon Copy field. Default is ''. You can define
// more than one recipient (see MAIL_RECIPIENTS)
define ("MAIL_CC", '');

/**********
 * Language
 *&********/

// Set this to 1 to use UTF-8 in all pages and in the database, otherwise
// text gets entered in the database in different encodings, dependent
// on the users' language
$unicode_encoding = 1;

// Set this to a different language specifier to default to different
// language tokens. This must equate to a lang.* file in MRBS.
// e.g. use "fr" to use the translations in "lang.fr" as the default
// translations
$default_language_tokens = "en";

// Set this to 1 to disable the automatic language changing MRBS performs
// based on the user's browser language settings. It will ensure that
// the language displayed is always the value of $default_language_tokens,
// as specified above
$disable_automatic_language_changing = 0;

// Set this to a valid locale (for the OS you run the MRBS server on)
// if you want to override the automatic locale determination MRBS
// performs
$override_locale = "";

// faq file language selection. IF not set, use the default english file.
// IF your language faq file is available, set $faqfilelang to match the
// end of the file name, including the underscore (ie. for site_faq_fr.html
// use "_fr"
$faqfilelang = ""; 

// This next require must be done after the definitions above, as the definitions
// are used in the included file
require_once "language.inc";

/*************
 * Entry Types
 *************/

// This array maps entry type codes (letters A through J) into descriptions.
// Each type has a color (see TD.x classes in the style sheet mrbs.css.php).
// The value for each type is a short (one word is best) description of the
// type. The values must be escaped for HTML output ("R&amp;D").
// Please leave I and E alone for compatibility.
// If a type's entry is unset or empty, that type is not defined; it will not
// be shown in the day view color-key, and not offered in the type selector
// for new or edited entries.

// $typel["A"] = "A";
// $typel["B"] = "B";
// $typel["C"] = "C";
// $typel["D"] = "D";
$typel["E"] = get_vocab("external");
// $typel["F"] = "F";
// $typel["G"] = "G";
// $typel["H"] = "H";
$typel["I"] = get_vocab("internal");
// $typel["J"] = "J";

/********************************************************
 * PHP System Configuration - internal use, do not change
 ********************************************************/

// Disable magic quoting on database returns:
set_magic_quotes_runtime(0);

// Make sure notice errors are not reported, they can break mrbs code:
error_reporting (E_ALL ^ E_NOTICE);

// These variables specify the names of the tables in the database
// These should not need to be changed.  Please change $db_tbl_prefix
// in the database section above.
$tbl_area   = $db_tbl_prefix . "area";
$tbl_entry  = $db_tbl_prefix . "entry";
$tbl_repeat = $db_tbl_prefix . "repeat";
$tbl_room   = $db_tbl_prefix . "room";
$tbl_users  = $db_tbl_prefix . "users";

?>
