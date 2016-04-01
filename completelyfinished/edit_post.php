<?php
	include 'header.php';

$server = "sql307.byethost13.com";
$username   = "b13_17760670";
$password   = "cs3013";
$database   = "b13_17760670_csforum";


	if(!mysqli_connect($server, $username, $password, $database))
	{
    	exit('Error: could not establish database connection');
	}

	$mysqli = new mysqli($server, $username, $password, $database);


	$thread_ID = $_GET['thread'];
	$post_id = $_GET['id'];
	$op = $_GET['op'];
	//echo 'id = ' . $post_id . '';

	$check_thread_closure = "SELECT active
		FROM
			threads
		WHERE
			id = $thread_ID";

	$closed_thread = 0;

	$result = $mysqli->query($check_thread_closure);
	if(!$result){
		echo mysqli_error($mysqli);
	} else {
		while($i = $result->fetch_assoc()){
			if($i['active']==0){
				echo '<h2>This thread has been closed, no edits can be made.</h2>';
				header("Refresh: 2; url=posts.php?thread='$thread_ID'");
				$closed_thread = 1;
			}
		}
	}

	$get_post_sql = "SELECT     content	
						FROM post
						WHERE threadParentID = $thread_ID AND id = $post_id";

	$result = $mysqli->query($get_post_sql);
	$string = '';
	$content = $result->fetch_assoc();

	foreach($content as $str){
		$string = $string . '' . $str;
	}

	$edit_button = '<input type="submit" value="Submit edit" />';
	if($closed_thread){
		$edit_button = '';
	}

	echo '<tr><td colspan="2"><h2>Edit Post:</h2><br />
					<form method="post" action="update_post.php?thread=' . $thread_ID . '&id=' . $post_id . '&op=' . $op . '">
						<textarea name="edit_content">'. $string . '</textarea><br /><br />
						' . $edit_button . '
					</form></td></tr>';


	include 'footer.php';
?>