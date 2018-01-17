
<?php

include("../includes/dbconnect.inc");

$user_name=$_POST['user_name'];
$password=$_POST['password'];
$encrypted_password=md5($password);
$newpassword = $_POST['newpassword'];
$confirmnedpassword = $_POST['confirmnedpassword'];
$newencrypted_password=md5($newpassword);

$result = mysql_query("SELECT password FROM person WHERE use_name='$user_name' and password='$encrypted_password'")or die (mysql_error());
if(!$result)
{
echo "The username you entered does not exist";
}
else
if($password!= mysql_result($result, 0))
{
echo "You entered an incorrect password";
}
if($newpassword=$confirmnedpassword)
    $sql=mysql_query("UPDATE person SET password='$newencrypted_password' where user_name='$user_name'")or die (mysql_error());
    if($sql)
    {
    echo "Password was updated";  
    }
else
{
echo "The passwords do not match";
}
?>