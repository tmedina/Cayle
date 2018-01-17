<?php

//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}

//connect to that database
include("../includes/dbconnect.inc");


if ($_POST[op] != "submit")
    {

    $person_id = $_POST[sel_id];

    $get_info = "SELECT id, fname, lname, phone, user_name FROM person WHERE id = $_POST[sel_id]";
    $get_info_res = mysql_query($get_info) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );


    if (mysql_num_rows($get_info_res)> 0)
        {
        while ($person_info = mysql_fetch_array($get_info_res))
        {

        $id = $person_info['id'];
        $fname = $person_info['fname'];
        $lname = $person_info['lname'];
        $phone = $person_info['phone'];
        $user_name = $person_info['user_name'];

        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\" >";
        $display_block .= "<table width=\"500\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";
        $display_block .= "<tr><td><b>Record for:</b></td><td>$fname $lname - $phone</td></tr>";
        $display_block .= "<tr><td><b>User name:</b></td><td> $user_name</td></tr>";
        $display_block .= "<tr><td><b>Enter new password:</b></td><td> <input type=\"text\" size=50 name=\"new_password\" value=\"$new_password\"></td></tr>";
        $display_block .= "<tr><td><b>Confirm password:</b></td><td> <input type=\"text\" size=50 name=\"confirm_password\" value=\"$confirm_password\"></td></tr>";
        $display_block .= "<tr><td colspan=2 align=\"center\"><input type=\"reset\" value=\"Clear form\">
        <input type=\"hidden\" name=\"id\" value=\"$id\">
        <input type=\"hidden\" name=\"op\" value=\"submit\">
        <input type=\"submit\" name=\"submit\" value=\"Submit\"></td></tr></table></form>";
        }

        }


    }

  if ($_POST[op] == "submit")

    {
        $id = $_POST['id'];
        $user_name = $_POST['user_name'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $encrypted_password = md5($new_password);

        if ($new_password == $confirm_password)
        {

        $reset_password = "UPDATE person SET password='$encrypted_password' WHERE id=$id";
        $reset_password_res = mysql_query($reset_password) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

        $display_block .= "<h4>Password updated</h4>";

        }

        else
        {

          $id = $_POST['id'];

    $get_info = "SELECT id, fname, lname, phone, user_name FROM person WHERE id = $id";
    $get_info_res = mysql_query($get_info) or die ("ERROR 1: " . mysql_errno() . "-" . mysql_error() );


    if (mysql_num_rows($get_info_res)> 0)
        {
        while ($person_info = mysql_fetch_array($get_info_res))
        {

        $id = $person_info['id'];
        $fname = $person_info['fname'];
        $lname = $person_info['lname'];
        $phone = $person_info['phone'];
        $user_name = $person_info['user_name'];

        $display_block .= "<form method=\"post\" action=\"$_SERVER[PHP_SELF]\" onsubmit=\"return checkform(this);\" >";
        $display_block .= "<table width=\"500\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\">";
        $display_block .= "<tr><td><b>Record for:</b></td><td>$fname $lname - $phone</td></tr>";
        $display_block .= "<tr><td><b>User name:</b></td><td> $user_name</td></tr>";
        $display_block .= "<tr><td><b>Enter new password:</b></td><td> <input type=\"text\" size=50 name=\"new_password\" value=\"$new_password\"></td></tr>";
        $display_block .= "<tr><td><b>Confirm password:</b></td><td> <input type=\"text\" size=50 name=\"confirm_password\" value=\"$confirm_password\"></td></tr>";
        $display_block .= "<tr><td colspan=2 align=\"center\"><input type=\"reset\" value=\"Clear form\">
        <input type=\"hidden\" name=\"id\" value=\"$id\">
        <input type=\"hidden\" name=\"op\" value=\"submit\">
        <input type=\"submit\" name=\"submit\" value=\"Submit\"></td></tr></table></form>";
        }

        }

          $display_block .= "<h4 style=\"color:#FF0000\">Passwords don't match - please try again</h4>";
          
        }
    }

?>

<html><head>
<title>Reset password</title>
    <link href="includes/person_band_admin.css" rel="stylesheet" type="text/css" />
    <!--Test-->
     <script language="JavaScript" type="text/javascript">
    <!--
        function checkform ( form )
        {

        if (form.new_password.value == "") {
            alert( "Please enter new password" );
            form.new_password.focus();
            return false ;
        }

        if (form.confirm_password.value == "") {
            alert( "Please confirm your new password" );
            form.confirm_password.focus();
            return false ;
        }

        return true;
        }

//-->

</script>
    
</head>
    <body>
     <? include("../includes/header.inc"); ?>

    <div id="page" align="center">
    <h2 align="left">Reset password - administrator view</h2>
    <? Print $display_block; ?>
    </div>
    </body>
</html>