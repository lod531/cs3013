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

//$username = $_GET('user');
//if($username == ''){

	echo "<h2>" . $_SESSION['username'] . "'s Profile</h2>";
	echo "<p>Usertitle: <i>" . $_SESSION['usertitle'] . "</i>         <a class='item' href='change_title.php'>Edit Usertitle.</a></p>";


	$username = $_SESSION['username'];
//}
$sql_get_posts = "SELECT threadParentID,
						creatorID,
						dateOfCreation,
						lastEdited
					FROM post
					WHERE creatorID = '$username'";

$result = $mysqli->query($sql_get_posts);

if(!$result){
	echo 'We tried to retrieve your posts, but something went wrong...<br><br>We did try though. Promise. ' . mysqli_error($mysqli);
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
                <th>Post information</th>
                <th>Post number</th>
              </tr>';
		while($contributions = $result->fetch_assoc()){
			
			$thread_id = $contributions['threadParentID'];
			$link = "posts.php?thread=" . $thread_id . "";

			echo '<tr>';
					echo '<td class="leftpart">';
					echo '<h3><a href=' . $link . '>Created on: <d>' . date('d-m-Y H:i', strtotime($contributions['dateOfCreation'])) . 
							'</a></h3></d><br/><p>Last edited: <d>' . date('d-m-Y H:i', strtotime($contributions['lastEdited'])) . '</p>';
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