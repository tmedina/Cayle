<html>

<head>


</head>

   <table border="0" cellspacing="0" cellpadding="3" width="100%" bgcolor="#FFFFFF">
            <tr><td align="left" class="td2"><a href="../../reservation/index.php"><img src="../../includes/images/new_logo_left.gif" border="0" /></a></td>
                <td align="center" class="td2"><img src="../../includes/images/new_logo_center.gif" /></td>
            <td align="right" class="td2"><img src="../../includes/images/new_logo_right.gif" /></td></tr>
        </table>
        <table bgcolor="#5F86B9" width="100%">
        <tr width="100%">
        <td width="15%" align="right" class="td2">
          <a href="../admin/index.php" class="link2">Reset password</a>
        </td>
        </tr>
        </table>
    <body>

        <div id="page" align="center">

            <form method="post" action="login.php">
                <br />Username: <input type="text" id="user_name" name="name">
                <br />Password: <input type="password" id="password" name="password">
            <br /><input type="submit" name="Login" value="Login"> </form>


            <? Print $display_block; ?>
        </div>

    </body>

</html>