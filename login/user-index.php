<?php
	require_once('auth.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


</head>
<body>
<h1>Welcome <?php echo $_SESSION['SESS_USERNAME'];?></h1>
<a href="user-profile.php">My Profile</a> | <a href="logout.php">Logout</a>
<p>This is a password protected area only accessible to registered employees. </p>
</body>
</html>
