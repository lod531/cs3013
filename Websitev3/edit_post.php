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


	$thread_ID = $_GET['thread'];
	$post_id = $_GET['id'];
	$op = $_GET['op'];
	//echo 'id = ' . $post_id . '';
	
	/*$get_post_query = "SELECT id,
			title,
      		threadText
		FROM
			threads
		WHERE
			id = $thread_id";*/

	$get_post_sql = "SELECT     content	
						FROM post
						WHERE threadParentID = $thread_ID AND id = $post_id";

	$result = $mysqli->query($get_post_sql);
	$string = '';
	$content = $result->fetch_assoc();

	foreach($content as $str){
		$string = $string . '' . $str;
	}

	echo '<tr><td colspan="2"><h2>Edit Post:</h2><br />
					<form method="post" action="update_post.php?thread=' . $thread_ID . '&id=' . $post_id . '&op=' . $op . '">
						<textarea name="edit_content">'. $string . '</textarea><br /><br />
						<input type="submit" value="Submit edit" />
					</form></td></tr>';


	include 'footer.php';
?>