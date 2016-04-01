<?php
	include 'header.php';
	/*
	*	NOTE: this requires updates to the database to add email, active and hash
	*/


	$server = "sql307.byethost13.com";
$username   = "b13_17760670";
$password   = "cs3013";
$database   = "b13_17760670_csforum";


	if(!mysqli_connect($server, $username, $password, $database))
	{
	    exit('Error: could not establish database connection');
	}

	$mysqli = new mysqli($server, $username, $password, $database);


	$email = $_GET['email'];
	$hash = $_GET['hash'];

	$email = mysqli_real_escape_string($mysqli, $email);
	$hash = mysqli_real_escape_string($mysqli, $hash);

	$sql_search = "SELECT email, hash, active FROM user WHERE email='$email' AND hash='$hash' AND active='0'";

	$result = $mysqli->query($sql_search);
	$matches = $result->num_rows;

	if($matches > 0){
		$sql_update = "UPDATE user SET active = 1 WHERE email = '$email' AND hash = '$hash'";

		$update_result = $mysqli->query($sql_update);

		if(!$update_result){
			echo '<h2>Uh oh...</h2><br><br>Account activation failed.<br>Try again later.';
		} else{
			echo '<h2>Success.</h2><br><br>Your account has been activated!<br>You can now <a href="login.php">login</a>.';
		}
	} else{
		echo '<h2>Registration error.</h2>:/';
	}

	include 'footer.php';
?>