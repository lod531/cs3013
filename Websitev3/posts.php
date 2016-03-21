<?php
//create_cat.php
include 'connect.php';
include 'header.php';

$thread_id = $_GET['thread'];

$sql = "SELECT
			id,
			title,
      threadText
		FROM
			threads
		WHERE
			id = $thread_id";

$result = mysql_query($sql);

if(!$result)
{
	echo 'The thread could not be displayed, please try again later.';
}
else
{
	if(mysql_num_rows($result) == 0)
	{
		echo 'This thread doesn&prime;t exist.';
	}
	else
	{
		while($row = mysql_fetch_assoc($result))
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
						creatorID
					FROM
						post
					WHERE
						threadParentID = $thread_id";

			$posts_result = mysql_query($posts_sql);

			if(!$posts_result)
			{
				echo '<tr><td>The posts in this thread could not be displayed, please try again later.</tr></td></table>';
			}
			else
			{

				while($posts_row = mysql_fetch_assoc($posts_result))
				{
					echo '<tr class="thread-post">
							<td class="user-post">' . $posts_row['creatorID'] . '<br/>' . date('d-m-Y H:i', strtotime($posts_row['dateOfCreation'])) . '</td>
							<td class="post-content">' . htmlentities(stripslashes($posts_row['content'])) . '</td>
						  </tr>';
				}
			}

			if(!$_SESSION['signed_in'])
			{
				echo '<tr><td colspan=2>You must be <a href="login.php">logged in</a> to reply. Not a member? Register <a href="register.php">here</a>.';
			}
			else
			{
				//show reply box
				echo '<tr><td colspan="2"><h2>Reply:</h2><br />
					<form method="post" action="reply.php?thread=' . $row['id'] . '">
						<textarea name="reply_content"></textarea><br /><br />
						<input type="submit" value="Submit reply" />
					</form></td></tr>';
			}

			//finish the table
			echo '</table>';
		}
	}
}

include 'footer.php';
?>
