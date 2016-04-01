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

$module = $_GET['module'];
$_GET['thread'] = '';

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
            parentModuleID = $module
        ORDER BY
            lastEdited DESC";

$result = $mysqli->query($sql);

if(!$result)
{
    echo 'The threads for this module could not be displayed, please try again later.';
}
else
{
    if($result->num_rows == 0)
    {
        echo 'No threads in this module yet.';
    }
    else
    {
        //prepare the table
        echo '<table border="1">
              <tr>
                <th>Threads</th>
                <th>Last post</th>
              </tr>';

        while($row = $result->fetch_assoc())
        {
          $active = '';
          if ($row['active'] == 0)
          {
            $active = "Closed";
          }
          else
          {
            $active = "Active";
          }
            $thread_id = $row['id'];
            $user = $row['creatorID'];
            $link = 'profile.php?user=' . $user;
                echo '<td class="leftpart">';
                    echo '<h3><a href="posts.php?thread=' . $thread_id . '">' . $row['title'] . '</a></h3>By: <b><a href='.$link.'>' . $user . '</a></b>
                    &nbsp&nbsp&nbsp&nbspCreated on:<b> ' . $row['dateOfCreation'] . '</b>&nbsp&nbsp&nbsp&nbsp'.$active.'';
                echo '</td>';
                echo '<td class="rightpart">';
                $latest_post_result = $mysqli->query("SELECT creatorID, dateOfCreation FROM post WHERE threadParentID = '" . $row['id'] . "' ORDER BY dateOfCreation DESC LIMIT 1");
                if ($latest_post_result)
                {
                  if ($latest_post_result->num_rows != 0)
                  {
                    while($ltp_row = $latest_post_result->fetch_assoc())
                    {
                    $commenter = $ltp_row['creatorID'];
                    $profile_link = 'profile.php?user=' . $commenter;
                    echo '<b><a href='.$profile_link.'>' . $commenter . '</a></b> at <b>' . $ltp_row['dateOfCreation'] . '</b>';
                    }
                    echo '</td>';
                  }
                  else
                  {
                    echo 'No replies.';
                  }
                }
                else
                {
                  echo mysqli_error($mysqli);
                }
                echo '</td>';
            echo '</tr>';
        }
    }
}

include 'footer.php';
?>
