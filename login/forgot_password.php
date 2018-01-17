<?php
// 
// Created by Marline Santiago-Cook
// User password reset - forgot password
// Phone number and username used as unique identifiers for the employee
//

//connect to database
include("../includes/dbconnect.inc");

// Assign the username and password from the form to variables.
$user_name=$_POST['user_name'];
$phone=$_POST['phone'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
$encrypted_password=md5($new_password);

//verify passwords match
if ( $new_password != $confirm_password )
{
    echo "Passwords do not match. Please try again";
    $display_block .= "<tr><td><b>Passwords do not match. Please try again</b></td><td></td></tr>";
    exit (0);
}
if ( $new_password == "" || $confirm_password == "")
{
    echo "Password is required. Please try again";
    exit (0);
}

$sql="SELECT phone, user_name FROM person WHERE user_name = '$user_name' AND phone='$phone'";
$result=mysql_query($sql)or die (mysql_error());
$row = mysql_fetch_array($result);

if ( isset($row[user_name]) )
{    
    $reset_password = "UPDATE person SET password='$encrypted_password' WHERE user_name = '$user_name' AND phone='$phone'";
    $reset_password_res = mysql_query($reset_password) or die ("ERROR: " . mysql_errno() . "-" . mysql_error() );

    //mysql_query("UPDATE person SET password='$encrypted_password' where user_name='$user_name'") or die (mysql_error());
    echo "Password was updated";
}
else
{
      echo "Incorrect username or phone number. Please try again";

}
?>

