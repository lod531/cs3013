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

$_GET['year'] = '';

$sql = "SELECT
            name,
            subtitle,
            numericalYear
        FROM
            courseYear";

$result = $mysqli->query($sql);

if(!$result)
{
    echo 'The course years could not be displayed, please try again later.';
}
else
{
    if($result->num_rows == 0)
    {
        echo 'No course years defined yet.';
    }
    else
    {
        //prepare the table
        echo '<table border="1">
              <tr>
                <th>Years</th>
              </tr>';

        while($row = $result->fetch_assoc())
        {
            $year = $row['numericalYear'];
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo "<h3><a href=\"modules.php?year='$year'\">" . $row['name'] . "</a></h3>" . $row['subtitle'];
                echo '</td>';
            echo '</tr>';
        }
    }
}

include 'footer.php';
?>
