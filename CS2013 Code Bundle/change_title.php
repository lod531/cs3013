<?php
include 'header.php';

//database credentials for mysqli connection

$server = "sql307.byethost13.com";
$username   = "b13_17760670";
$password   = "cs3013";
$database   = "b13_17760670_csforum";
//if connection can't be formed with db then display error message
if(!mysqli_connect($server, $username, $password, $database))
{
    exit('Error: could not establish database connection');
}

$mysqli = new mysqli($server, $username, $password, $database);
//interface
//show page header and current usertitle
echo '<h2>Change Your Usertitle.</h2>';
echo '<p>Your current Usertitle is: <i>' . $_SESSION['usertitle'] . '</i>.<br>Enter a new one below to change it,<br>or submit with no entry to remove your title.</p>';


//while user hasn't submitted the form
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//display the form (single text box for new title)
	echo '<form method="post" action="">
            New title: <input type="text" name="new_usertitle" maxlength="10"></br></br>
            <input type="submit" value="Submit." />
         </form>';
}
else{
	//error array declared for later use
	//if empty -> good input
	$errors = array();
	//get logged in user's username
	$user = $_SESSION['username'];
	//if text box is empty, remove user title entirely 
	if(!isset($_POST['new_usertitle'])){
		//update db with empty string for title
		$sql = "UPDATE user
				SET usertitle = ''
				WHERE username = '$user'";
				//query
		$result = $mysqli->query($sql);

		//if query failed
		if(!$result){
			echo 'Something went wrong, please try again later.';
			//$_SESSION['usertitle'] = '';
			header('Refresh: 1; url=profile.php');
		}
		$_SESSION['Usertitle'] = '';
	} else {
		//prevent any special characters being entered to db
		$title = mysqli_real_escape_string($mysqli, $_POST['new_usertitle']);

		//update with safe string

		$sql = "UPDATE user
				SET usertitle = '$title'
				WHERE username = '$user'";
		$result = $mysqli->query($sql);

		if(!$result){
			//query fail so error message
			echo 'Something went wrong, please try again later.';
			header('Refresh: 2; url=profile.php');
		} else {
			//update session title to new title
			echo 'Title change succesful!';
			$_SESSION['usertitle'] = $title;
			header('Refresh: 1; url=profile.php');
		}
	}
}


include 'footer.php';
?>