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

$_GET['module'] = '';
$year = $_GET['year'];

$sql = "SELECT
            name,
            subtitle,
            courseYearID
        FROM
            yearmodule
        WHERE
            courseYearID = $year";

$result = $mysqli->query($sql);

if(!$result)
{
    echo 'The modules for this year could not be displayed, please try again later.';
}
else
{
    if($result->num_rows == 0)
    {
        echo 'No modules created for this year yet.';
    }
    else
    {
        //prepare the table
        echo '<table border="1">
              <tr>
                <th>Modules</th>
                <th>Newest thread</th>
              </tr>';

        while($row = $result->fetch_assoc())
        {
            $module_code = $row['name'];
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo "<h3><a href=\"threads.php?module='$module_code'\">" . $row['name'] . "</a></h3>" . $row['subtitle'];
                echo '</td>';
                echo '<td class="rightpart">';
                $latest_thread_result = $mysqli->query("SELECT id, title, creatorID, dateOfCreation FROM threads WHERE parentModuleID = '" . $row['name'] . "' ORDER BY dateOfCreation DESC LIMIT 1");
                if ($latest_thread_result)
                {
                  if ($latest_thread_result->num_rows != 0)
                  {
                    while($ltr_row = $latest_thread_result->fetch_assoc())
                    {
                    echo '<b><a href="posts.php?thread='.$ltr_row['id'].'">'.$ltr_row['title'].'</a></b> at <b>'.$ltr_row['dateOfCreation'].'</b> by <b>'.$ltr_row['creatorID'].'</b>';
                    }
                    echo '</td>';
                  }
                  else
                  {
                    echo 'No threads.';
                  }
                }
                else
                {
                  echo mysqli_error($mysqli);
                }
            echo '</tr>';
        }
    }
}

include 'footer.php';
?>
