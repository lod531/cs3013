<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div id="wrapper">
	<h1>Sign up to forum</h1>

<?php

		$username = $_POST['username'];
		$password = $_POST['password'];
		$passwordRetry = $_POST['passwordRetry'];

		if($password != $passwordRetry || $password == null)
		{
			echo "Thou shall not pass";
			echo "<form action='sign_up.php'>

			<input type='submit' name='submit' value ='Passwords did not match, Please retry' />"
			;
		}
		else {
			echo 
			"<a class='button' href='forum_login.php' target='_blank'> An account was created</a>"
			;
		}

	

?>
</div>
</body>
</html>