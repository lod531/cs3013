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
            lastEdited ASC ";

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
                <th>Last reply</th>
              </tr>';

        while($row = $result->fetch_assoc())
        {
          if ($row['active'] == 1)
          {
            echo '<tr>';
          }
          else
          {
            echo '<tr bgcolor="#BDBDBD">';
          }
            $thread_id = $row['id'];
                echo '<td class="leftpart">';
                    echo '<h3><a href="posts.php?thread=' . $thread_id . '">' . $row['title'] . '</a></h3>By: <b>' . $row['creatorID'] . '</b>
                    &nbsp&nbsp&nbsp&nbspCreated on:<b> ' . $row['dateOfCreation'] . '<b>';
                echo '</td>';
                echo '<td class="rightpart">';
                            echo '<a href="posts.php?id=">User</a> at 10-10';
                echo '</td>';
            echo '</tr>';
        }
    }
}

include 'footer.php';
?>
