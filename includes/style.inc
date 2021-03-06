<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}


// $Id: style.inc 999 2009-01-22 00:36:30Z cimorrison $

global $unicode_encoding;
global $vocab; // outside of scope; needs to be denote as global

// NOTE:  The filename for the stylesheet for <= IE6 must contain the string 'ielte6'.
// The href strings in the <link> tags below are used by InitActiveCell in xbLib.js to 
// test whether the browser is IE6 or below - and therefore whether to use JavaScript
// highlighting instead of CSS highlighting.   In particular the href string for the
// stylesheet for browsers IE6 or below must contain the string 'ielte6'.
?>
    <link rel="stylesheet" href="mrbs.css.php" type="text/css">
    <link rel="stylesheet" media="print" href="mrbs-print.css" type="text/css">
    <!--[if IE]>
    <link rel="stylesheet" href="mrbs-ie.css" type="text/css">
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" href="mrbs-ie8.css" type="text/css">
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" href="mrbs-ielte7.css" type="text/css">
    <![endif]-->
    <!--[if lte IE 6]>
    <link rel="stylesheet" href="mrbs-ielte6.css" type="text/css">
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=<?php

if ($unicode_encoding)
{
  echo "utf-8";
}
else
{
  // We use $vocab directly instead of get_vocab() because we have
  // no requirement to convert the vocab text, we just output
  // the charset
  echo $vocab["charset"];
}
?>">
    <meta name="robots" content="noindex">
<?php

global $refresh_rate;
global $PHP_SELF;

if (($refresh_rate != 0) &&
    preg_match("/(day|week|month)\.php/",$PHP_SELF))
{
  echo "    <meta http-equiv=\"Refresh\" content=\"$refresh_rate\">\n";
}
?>
