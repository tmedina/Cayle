<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
if (!isset($_POST['run_report']))

//connect to the database & the function.inc file
include("../includes/dbconnect.inc");

//show buttons to choose person or band list
if (!isset($_POST['run_person_report']) && !isset($_POST['run_band_report']))

  {

  $display_block .= "<h2 align=\"left\">Show all person or band records</h2>";
  $display_block .= "<div id=\"links_create\"><a href=\"../admin/index.php\">Back to reports index</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>";
  $display_block .= "<div id=\"text\" align=\"left\"><h3>Show all person records</h3>";
  $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
  $display_block .= "<input type=\"submit\" name=\"run_person_report\" value=\"Show person list\">&nbsp;&nbsp;<input type=submit name=run_csv_person value=\"Export list to Excel\"></form><br />";


  $display_block .= "<h3>Show all band records</h3>";
  $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
  $display_block .=  "<input type=\"submit\" name=\"run_band_report\" value=\"Show band list\">&nbsp;&nbsp;<input type=submit name=run_csv_band value=\"Export list to Excel\"></form></div>";


  }

  //show all person records
  if (isset($_POST['run_person_report']))

  {

    //get all person records
    $get_list = "SELECT fname, lname, address, city, state_name AS state, zip, phone, email,
    user_name AS username, person_comment AS comment, person_status AS status, person.is_active
    FROM person, states, person_status WHERE person.state_id = states.id
    AND person.person_status_id = person_status.id ORDER BY lname, fname ASC";
    $get_list_res = mysql_query($get_list) or die ("ERROR:1 " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($get_list_res) <1)
     {
        $display_block .= "<h2 align=\"left\">Show all person records</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"show_all_person_band.php\">Show all band records</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><p>No person records available</p></div>";
     }

      elseif (mysql_num_rows($get_list_res) > 0)
     {

        $display_block .= "<h2 align=\"left\">Show all person records</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"show_all_person_band.php\">Show all band records</a></div>";
        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
        $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
        $display_block .= "<th>Name</th><th>Address</th><th>City</th><th>State</th><th>ZIP</th><th>Phone</th><th>Email</th><th>Username</th><th>Comment</th><th>Status</th><th>Is active?</th>";

         while($recs = mysql_fetch_array($get_list_res))
         {

         $fname = $recs['fname'];
         $lname = $recs['lname'];
         $address = $recs['address'];
         $city = $recs['city'];
         $state = $recs['state'];
         $zip = $recs['zip'];
         $phone = $recs['phone'];
         $email = $recs['email'];
         $username = $recs['username'];
         $comment = $recs['comment'];
         $status = $recs['status'];
         $is_active = $recs['is_active'];

         $display_block .= "<tr><td>" . $lname . ", " .  $fname . "</td><td>" . $address . "</td><td>" . $city . "</td>
            <td>" . $state . "</td><td>" . $zip . "</td><td>" . $phone . "</td><td>" . $email . "</td>
            <td>" . $username . "</td><td>" . $comment . "</td><td>" . $status . "</td><td>" . $is_active . "</td>
            </tr>";

         }

         $display_block .= "</table><br /><input type=submit name=run_csv_person value=\"Export list to Excel\"></form></div>";
     }
  }

  //show all band records
  if (isset($_POST['run_band_report']))

  {

    //get all person records
    $get_list = "SELECT band_name, band_comment, is_active FROM band WHERE id <> 1 ORDER by band_name ASC;";
    $get_list_res = mysql_query($get_list) or die ("ERROR:2 " . mysql_errno() . "-" . mysql_error() );

    if (mysql_num_rows($get_list_res) <1)
     {
        $display_block .= "<h2 align=\"left\">Show all band records</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"show_all_person_band.php\">Show all person records</a></div>";
        $display_block .= "<div id=\"text\" align=\"left\"><p>No band records available</p></div>";
     }

      elseif (mysql_num_rows($get_list_res) > 0)
     {

        $display_block .= "<h2 align=\"left\">Show all band records</h2>";
        $display_block .= "<div id=\"links_report_new\"><a href=\"../admin/index.php\">Back to reports index</a> | <a href=\"show_all_person_band.php\">Show all person records</a></div>";
        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
        $display_block .= "<div id=\"text\" align=\"left\"><table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">";
        $display_block .= "<th>Band name</th><th>Comment</th><th>Is active?</th>";

         while($recs = mysql_fetch_array($get_list_res))
         {

         $band_name = $recs['band_name'];
         $band_comment = $recs['band_comment'];
         $is_active = $recs['is_active'];

         $display_block .= "<tr><td>" . $band_name . "</td><td>" . $band_comment . "</td><td>" . $is_active . "</td>
            </tr>";

         }

         $display_block .= "</table><br /><input type=submit name=run_csv_band value=\"Export list to Excel\"></form></div>";
     }
  }

  //export person list to Excel
if (isset($_POST['run_csv_person']))
{


$sql = "SELECT fname, lname, address, city, state_name AS state, zip, phone, email,
    user_name AS username, person_comment AS comment, person_status AS status, person.is_active
    FROM person, states, person_status WHERE person.state_id = states.id
    AND person.person_status_id = person_status.id ORDER BY lname, fname ASC";
$sql_res = mysql_query($sql) or die ("ERROR 3: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}

//export band list to Excel
if (isset($_POST['run_csv_band']))
{

$sql = "SELECT band_name, band_comment, is_active FROM band WHERE id <> 1 ORDER by band_name ASC;";
$sql_res = mysql_query($sql) or die ("ERROR 4: " . mysql_errno() . "-" . mysql_error() );

$csvReport = "Y";
include ("../includes/csv_download.inc");
}
?>
<html>
    <head>
    <title>Report: show all person or band records</title>
    <link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
    </head>
<body>
    <? include("../includes/header.inc"); ?>
     <div id="page" align="center">

    <? Print $display_block; ?>
    </div>
</body>
</html>

