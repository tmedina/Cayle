<?php
//
// Created by Marline Santiago-Cook
// May 1, 2009
// Initiate session
//
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
          <a href="../login/forgot_password_form.php" class="link2">Reset password</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        </tr>
        </table>
    <body>

        <div align="center">
            <h2>Welcome! Please log in:</h2>
            <form method="post" action="login.php" onsubmit="return checkform(this);">
            <table border="0" cellpadding="3" cellspacing="0">
            <tr><td>Username: </td><td><input type="text" id="user_name" name="user_name"></td></tr>
            <tr><td>Password: </td><td><input type="password" id="password" name="password"></td></tr>
            </table>
            <input type="submit" name="Login" value="Login"> </form>

           
        </div>

    </body>

</html>