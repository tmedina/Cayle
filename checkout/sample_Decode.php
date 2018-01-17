<?php
//Checks to see if user is logged in; if not redirect to log-in page
session_start();
//print_r ($_SESSION);

if((!isset($_SESSION["logged"])) && !$_SESSION["logged"])
{
    header("Location: ../login/login-form.php") ;
}
?>
<html>
<body>
<p>
<?php

$enc = mysql_query("SELECT ENCODE('testing','')");
$str_enc = mysql_fetch_array($enc);
$val_enc = $str_enc[0];
echo $val_enc."<br>\n";

$dec = mysql_query("SELECT DECODE('".$val_enc."','')");
$str_dec = mysql_fetch_array($dec);
$val_dec = $str_dec[0];
echo $val_dec;

?>
</p>

</body>
</html>