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

$username = '';
$title = '';
$title_str = '';
if(isset($_GET['user'])){ //&& isset($_GET['title'])){
	$username = $_GET['user'];
	//$title = $_GET('title');
	$fetch_title = "SELECT usertitle FROM user WHERE username = '$username'";
	$result = $mysqli->query($fetch_title);
	if(!$result){
		echo mysqli_error($mysqli);
	} else{
		while($row = $result->fetch_assoc()){
			$title = $row['usertitle'];
		}
	}
	$title_str = '<p>Usertitle: <i>' . $title . '</i>';
} else {
	$username = $_SESSION['username'];
	$title = $_SESSION['usertitle'];
	$title_str = "<p>Usertitle: <i>" . $title . "</i>         <a class='item' href='change_title.php'>Edit Usertitle.</a></p>";
}
echo "<h2>" . $username . "'s Profile</h2>";
echo $title_str;

$sql_get_posts = "SELECT threadParentID,
						creatorID,
						dateOfCreation,
						lastEdited
					FROM post
					WHERE creatorID = '$username'";

$result = $mysqli->query($sql_get_posts);

if(!$result){
	echo 'We tried to retrieve' . $username . '\'s posts, but something went wrong...<br><br>We did try though. Promise. ' . mysqli_error($mysqli);
} else{
	
	/*$rand_val = 1;

	$get_limit = "SELECT MAX(id) FROM threads";
	$return_val = $mysqli->query($get_limit);
	while($limit = $return_val->fetch_assoc()){
		$rand_val = rand(1, $limit);
	}*/
	if($result->num_rows==0){

		//$link = "posts.php?thread=" . $rand_val . "";
	
		$msg = "It looks like you haven't posted anything yet! <a href='home.php'>Change that</a>.";
		echo wordwrap($msg, 85);

	} else{
		$post_num = 1;
		//init table
		echo '<table border="1">
              <tr>
                <th>' . $username . '\'s Post History</th>
                <th>Post number</th>
              </tr>';
		while($contributions = $result->fetch_assoc()){
			
			$thread_id = $contributions['threadParentID'];
			$title = '';

			$sql_get_parent = "SELECT title 
					FROM threads 
					WHERE id = $thread_id";

			$thread_title = $mysqli->query($sql_get_parent);
			if(!$thread_title){
				//echo mysqli_error($mysqli);
			} else{
				while($titles = $thread_title->fetch_assoc()){
					$title = $titles['title'];
				}
				//echo $title;
			}

			$link = "posts.php?thread=" . $thread_id . "";

			echo '<tr>';
					echo '<td class="leftpart">';
					echo '<h3><a href=' . $link . '>' . $title . '</a><br/>Created on: <d>' . date('d-m-Y H:i', strtotime($contributions['dateOfCreation'])) . 
							'</h3></d><br/><p>Last edited: <d>' . date('d-m-Y H:i', strtotime($contributions['lastEdited'])) . '</p>';
					echo '</td>';
					echo '<td class="rightpart">'; 
						echo '<h3><a href=' . $link . '>Post #' . $post_num . '</a></h3>';
					echo '</td>';
				  /*echo '<td class="leftpart">';
                    echo "<h3><a href=\"threads.php?module='$module_code'\">" . $row['name'] . "</a></h3>" . $row['subtitle'];
                echo '</td>';*/
            $post_num = $post_num + 1;
		}
	}
}

include 'footer.php';
?>