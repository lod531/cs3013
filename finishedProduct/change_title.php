<?php
include 'header.php';

$server = "localhost";
$username   = "root";
$password   = "";
$database   = "csforum";

if(!mysqli_connect($server, $username, $password, $database))
{
    exit('Error: could not establish database connection');
}

$mysqli = new mysqli($server, $username, $password, $database);

echo '<h2>Change Your Usertitle.</h2>';
echo '<p>Your current Usertitle is: <i>' . $_SESSION['usertitle'] . '</i>.<br>Enter a new one below to change it,<br>or submit with no entry to remove your title.</p>';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
	echo '<form method="post" action="">
            New title: <input type="text" name="new_usertitle" maxlength="10"></br></br>
            <input type="submit" value="Submit." />
         </form>';
}
else{

	$errors = array();

	$user = $_SESSION['username'];

	if(!isset($_POST['new_usertitle'])){
		$sql = "UPDATE user
				SET usertitle = ''
				WHERE username = '$user'";
		$result = $mysqli->query($sql);

		if(!$result){
			echo 'Something went wrong, please try again later.';
			$_SESSION['usertitle'] = '';
			header('Refresh: 1; url=profile.php');
		}
	} else {
		$title = mysqli_real_escape_string($mysqli, $_POST['new_usertitle']);

		$sql = "UPDATE user
				SET usertitle = '$title'
				WHERE username = '$user'";
		$result = $mysqli->query($sql);

		if(!$result){
			echo 'Something went wrong, please try again later.';
			header('Refresh: 2; url=profile.php');
		} else {
			echo 'Title change succesful!';
			$_SESSION['usertitle'] = $title;
			header('Refresh: 1; url=profile.php');
		}
	}
}


include 'footer.php';
?>