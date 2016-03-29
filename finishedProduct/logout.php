<?php
include 'header.php';

echo '<h2>Logout</h2>';

//check if user if signed in
if($_SESSION['signed_in'] == true)
{
	//unset all variables
	$_SESSION['signed_in'] = NULL;
	$_SESSION['username'] = NULL;
	$_SESSION['usertitle']   = NULL;
	if($_SESSION['moderator'])
	{
		$_SESSION['moderator'] = NULL;
		$_SESSION['modulemod'] = NULL;
	}

	echo 'Successfully logged out. Redirecting...';
    header('Refresh: 2; url=home.php');
}
else
{
	echo 'You are not logged in. Would you like to <a href="login.php">login</a>?';
}

include 'footer.php';
?>
