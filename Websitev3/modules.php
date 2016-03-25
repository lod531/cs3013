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
                <th>Last thread</th>
              </tr>';

        while($row = $result->fetch_assoc())
        {
            $module_code = $row['name'];
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo "<h3><a href=\"threads.php?module='$module_code'\">" . $row['name'] . "</a></h3>" . $row['subtitle'];
                echo '</td>';
                echo '<td class="rightpart">';
                            echo '<a href="posts.php?thread=">Thread title goes here</a> at 10-10 by blablabla';
                echo '</td>';
            echo '</tr>';
        }
    }
}

include 'footer.php';
?>
