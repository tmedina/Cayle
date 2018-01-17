<?php
//
// Created by Marline Santiago-Cook
// 
//
?>

<html>

    <head>

        <link href="../../includes/person_band_res.css" rel="stylesheet" type="text/css" />


    <div id=\"text\">


        <form method="post" action="change-password.php">


            <br> <td>Username: <input type="text"  size=40 id="user_name" name="user_name"> </tr></br>
            <br><td>Password: <input type="password"  size=40 id="password" name="password"></tr></br>
            <br><td>New password: <input type="password"  size=40 id="newpassword" name="newpassword"></tr></br>
            <br><td>Confirm new password: <input type="password"  size=40 id="confirmnedpassword" name="confirmnedpassword"></tr></br>
        <br><td><input type="submit" name="Update password" value="Update password"></tr></br> </form>

<? Print $display_block; ?>
    </div>

    </body>

</html>