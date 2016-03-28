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
  $_GET['checklist'] = '';
  $sql = "SELECT
              id,
              dateOfCreation,
              title,
              parentModuleID,
              creatorID,
              threadText,
              lastEdited,
              active
          FROM
              threads
          WHERE
              parentModuleID = '" . $_SESSION['modulemod'] . "'";

  $result = $mysqli->query($sql);

  if(!$result)
  {
      echo 'The threads for your moderated module could not be displayed, please try again later.';
  }
  else
  {
      if($result->num_rows == 0)
      {
          echo 'There are no threads in your moderated module.';
      }
      else
      {
          //prepare the table
          echo '<table border="1">
                <tr>
                  <th>Thread Name</th>
                  <th>Creator</th>
                  <th>Posts</th>
                  <th>Active</th>
                </tr>';

          while($row = $result->fetch_assoc())
          {
              echo '<form action="moderator.php" method="post">';
              echo '<tr>';
                  echo '<td class="leftpart">';
                      echo '<input type="checkbox" name="checklist[]" value="' . $row['id'] . '"><label>&nbsp&nbsp<a href="posts.php?thread=' . $row['id'] . '">' . $row['title'] .'</a></label><br/>';
                  echo '</td>';
                  echo '<td class="leftpart">' . $row['creatorID'] . '  </td>';
                  $postcount_result = $mysqli->query("SELECT * FROM post WHERE threadParentID = '" . $row['id'] . "'");
                  echo '<td class="leftpart">';
                  if($postcount_result)
                  {
                    echo $postcount_result->num_rows;
                  }
                  else
                  {
                    echo mysqli_error($mysqli);
                  }
                  echo '</td>';
                  $active = '';
                  if ($row['active'] == 1) {$active = "Yes";} else {$active = "No";}
                  echo '<td class="leftpart">' . $active . '  </td>';

              echo '</tr>';
          }

          echo '<tr><td colspan=4>';

          if((isset($_POST['delete']) && isset($_POST['checklist']) && is_array($_POST['checklist'])))
          {
            foreach($_POST['checklist'] as $selected)
                {
                  $post_del_result = $mysqli->query("DELETE FROM post WHERE threadParentID = $selected ");
                  $thread_del_result = $mysqli->query("DELETE FROM threads WHERE id = $selected");
                }
                if (!$post_del_result || !$thread_del_result)
                {
                  echo 'Deletion failed.';
                  header('Refresh: 1; url=moderator.php');
                }
                else
                {
                  echo 'Deletion successful. Refreshing table...';
                  header('Refresh: 1; url=moderator.php');
                }
          }
          else if ((isset($_POST['close']) && isset($_POST['checklist']) && is_array($_POST['checklist'])))
          {
            foreach($_POST['checklist'] as $selected)
                {
                  $thread_close_result = $mysqli->query("UPDATE threads SET active = 0 WHERE id = $selected");
                }
                if (!$thread_close_result)
                {
                  echo 'Closure failed.';
                  header('Refresh: 1; url=moderator.php');
                }
                else
                {
                  echo 'Closure successful. Refreshing table...';
                  header('Refresh: 1; url=moderator.php');
                }
          }

          else if ((isset($_POST['reopen']) && isset($_POST['checklist']) && is_array($_POST['checklist'])))
          {
            foreach($_POST['checklist'] as $selected)
                {
                  $thread_reopen_result = $mysqli->query("UPDATE threads SET active = 1 WHERE id = $selected");
                }
                if (!$thread_reopen_result)
                {
                  echo 'Reopening failed.';
                  header('Refresh: 1; url=moderator.php');
                }
                else
                {
                  echo 'Reopening successful. Refreshing table...';
                  header('Refresh: 1; url=moderator.php');
                }
          }
          else
          {
            echo '<input type="submit" name="delete" Value="Delete selected"/>&nbsp';
            echo '<input type="submit" name="close" Value="Close selected"/>&nbsp';
              echo '<input type="submit" name="reopen" Value="Reopen selected"/></br>';
          }
          echo '</td></tr>';
          echo '</form>';
      }
  }
include 'footer.php';
?>
