<?php
	//Start session
	session_start();
	
	require_once('config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Check error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(dbHost, dbUser, dbPass);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(dbName);
	if(!$db) {
		die("Can't connect to database");
	}
	
	//Function to cleanup values received from the form. 
      Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$username = clean($_POST['username']);
	$password = clean($_POST['password']);
	$cpassword = clean($_POST['cpassword']);
	
	//Input Validations
	if($username == '') {
		$errmsg_arr[] = 'username missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'password missing';
		$errflag = true;
	}
	if($cpassword == '') {
		$errmsg_arr[] = 'Confirm password missing';
		$errflag = true;
	}
	if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Passwords do not match';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: register-form.php");
		exit();
	}

	//Create INSERT query
	$qry = "INSERT INTO reservation_users(username, password) VALUES('$username','".md5($_POST['password'])."')";
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: register-success.php");
		exit();
	}else {
		die("Query failed");
	}
?>