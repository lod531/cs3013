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

$thread_id = $_GET['thread'];

$sql = "SELECT
			id,
			title,
      threadText,
      active
		FROM
			threads
		WHERE
			id = $thread_id";

$result = $mysqli->query($sql);

if(!$result)
{
	echo 'The thread could not be displayed, please try again later.';
}
else
{
	if($result->num_rows == 0)
	{
		echo 'This thread doesn&prime;t exist.';
	}
	else
	{
		while($row = $result->fetch_assoc())
		{
			//display post data
			echo '<table class="thread" border="1">
					<tr>
						<th colspan="2">' . $row['title'] . '</th>
					</tr>';

			//fetch the posts from the database
			$posts_sql = "SELECT
						threadParentID,
				    content,
						dateOfCreation,
						lastEdited,
						id,
						creatorID
					FROM
						post
					WHERE
						threadParentID = $thread_id";

			$posts_result = $mysqli->query($posts_sql);
			$thread_status = $row['active'];
			if(!$posts_result)
			{
				echo '<tr><td>The posts in this thread could not be displayed, please try again later.</tr></td></table>';
			}
			else
			{
				$id = -1;
				while ($posts_row = $posts_result->fetch_assoc())
				{
					$original_post = '&op=0';
					$edit_privilege = '';
					if ($id == -1) {
						$original_post = '&op=1';
					}
					$id = $posts_row['id'];
					if (isset($_SESSION['signed_in']) && $posts_row['creatorID'] == $_SESSION['username']) {
						$edit_privilege = '<a class="item" href = "edit_post.php?thread=' . $thread_id . '&id=' . $id . $original_post .'&thr_sta=' . $thread_status . '">Edit post</a><br>';
					}
					if($thread_status == 0){
						$edit_privilege = '';
					}
          $mod_result = $mysqli->query("SELECT * FROM moderators WHERE userID = '". $posts_row['creatorID']. "'");
          			$creator_name = $posts_row['creatorID'];
          			$profile_link = 'profile.php?user=' . $creator_name . ''; 

					echo '<tr class="thread-post">
							<td class="user-post"><b><a href='.$profile_link.'>' . $creator_name . '</a></b><br/><br/><b>Created on: </b><d>' . date('d-m-Y H:i', strtotime($posts_row['dateOfCreation'])) .
							'</d><br/><b>Last edited: </b><d>' . date('d-m-Y H:i', strtotime($posts_row['lastEdited'])) . '</d></td>
							<td class="post-content">' . nl2br(wordwrap(htmlentities(stripslashes($posts_row['content'])), 85, "<br />\n", true)) . '<br><br>' . $edit_privilege . '</td>
						  </tr>';
				}
			}

			if(!isset($_SESSION['signed_in']) || !$_SESSION['signed_in'])
			{
        if($row['active'] == 1)
        {
				echo '<tr><td colspan=2>You must be <a href="login.php">logged in</a> to reply. Not a member? Register <a href="register.php">here</a>.';
        }
        else
        {
          echo '<tr><td colspan="2"><br><h2>Thread Closed</h2></td></tr>';
        }
      }
			else
			{
        if($row['active'] == 1)
        {
				echo '<tr><td colspan="2"><br><h2>Reply:</h2><br><br>
					<form method="post" action="reply.php?thread=' . $row['id'] . '">
						<textarea name="reply_content"></textarea><br /><br />
						<input type="submit" value="Submit reply" />
					</form></td></tr>';
        }
        else
        {
          echo '<tr><td colspan="2"><br><h2>Thread Closed</h2></td></tr>';
        }
			}

			//finish the table
			echo '</table>';
		}
	}
}

include 'footer.php';
?>
