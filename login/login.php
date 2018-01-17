<?php
//
// Created by Marline Santiago-Cook
// Initiate session
//

//connect to the db
include("../includes/dbconnect.inc");


// Assign the username and password from the form to variables.
$user_name=$_POST['user_name'];
$password=$_POST['password'];
$encrypted_password=md5($password);

$sql="SELECT p.id, ps.id, ps.person_status AS person_status
        FROM person p, person_status ps
       WHERE p.person_status_id = ps.id
         AND user_name='$user_name' and password='$encrypted_password'";

$result=mysql_query($sql)or die (mysql_error());

if($result) {
    if(mysql_num_rows($result) == 1) {

        $row = mysql_fetch_array($result);
        session_start();
        $_SESSION["logged"] = 1;
        $_SESSION["username"] = $user_name;
        
        if ( $row["person_status"] == "Administrator")
        {
            $_SESSION["is_admin"] = 1;
        }
        else
        {
            $_SESSION["is_admin"] = 0;
        }

        header("Location: ../reservation/index.php");
        //header("Location: ../admin/index.php");
    }

    else {
        $_SESSION["logged"] = 0;
        //header("Location: login-form.php");

        $display_block .="<form method=\"post\" action=\"login.php\" onsubmit=\"return checkform(this);\">
            <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">
            <tr><td>Username: </td><td><input type=\"text\" id=\"user_name\" name=\"user_name\"></td></tr>
            <tr><td>Password: </td><td><input type=\"password\" id=\"password\" name=\"password\"></td></tr>
            </table>
            <input type=\"submit\" name=\"Login\" value=\"Login\"> </form>";
        $display_block .= "<h4 align=\"center\" style=\"color:#FF0000\">Login failed!<br />Please check your username and password</h4>";

    }
}
// echo "Failed";
?>

<html>

<head>
<link href="../includes/person_band.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">
    <!--
        function checkform ( form )
        {

        if (form.user_name.value == "") {
            alert( "Please enter your username" );
            form.user_name.focus();
            return false ;
        }

        if (form.password.value == "") {
            alert( "Please enter your password" );
            form.password.focus();
            return false ;
        }

        return true;
        }

//-->

</script>
</head>

   <table border="0" cellspacing="0" cellpadding="3" width="100%" bgcolor="#FFFFFF">
            <tr><td align="left" class="td2"><img src="../includes/images/new_logo_left.gif" border="0" /></td>
                <td align="center" class="td2"><img src="../includes/images/new_logo_center.gif" /></td>
            <td align="right" class="td2"><img src="../includes/images/new_logo_right.gif" /></td></tr>
        </table>
        <table style="border-collapse: separate" bgcolor="#5F86B9" width="100%" cellpadding="1" >
        <tr width="100%">
        <td width="15%" align="right" class="td2">
          <a href="../person_module_admin/reset_password_form_user.php" class="link2">Reset password</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        </tr>
        </table>
    <body>

        <div align="center">
            <h2>Welcome! Please log in:</h2>
            <? Print $display_block; ?>
        </div>

    </body>

</html>