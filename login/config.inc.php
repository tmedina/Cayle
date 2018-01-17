<?php
/*
 * Database
 */

$dbsys = "mysql";
$db_host = "localhost";
$db_database = "nucis_space_tables";
$db_login = "root";
$db_password = "";

// The company name is mandatory.   It is used in the header and also for email notifications.
// The company logo, additional information and URL are all optional.

$mrbs_company = "Nuci's Space";
// Maximum repeating entrys (max needed +1):
$max_rep_entrys = 365 + 1;

// Results per page for searching:
$search["count"] = 20;

// Page refresh time (in seconds). Set to 0 to disable
$refresh_rate = 0;

/***********************************************
 * Authentication settings - read AUTHENTICATION
 ***********************************************/

// $auth["session"] = "php";

// $auth["type"] = "db";

// Configuration parameters for 'cookie' session scheme

// The encryption secret key for the session tokens. You are strongly
// advised to change this if you use this session scheme
// $auth["session_cookie"]["secret"] = "This isn't a very good secret!";
// The expiry time of a session, in seconds
// $auth["session_cookie"]["session_expire_time"] = (60*60*24*30); // 30 days
// Whether to include the user's IP address in their session cookie.
// Increases security, but could cause problems with proxies/dynamic IP
// machines
// $auth["session_cookie"]["include_ip"] = TRUE;


// Cookie path override. If this value is set it will be used by the
// 'php' and 'cookie' session schemes to override the default behaviour
// of automatically determining the cookie path to use
// $cookie_path_override = '';

?>