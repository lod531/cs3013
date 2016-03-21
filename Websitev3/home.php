<?php
//create_cat.php
include 'connect.php';
include 'header.php';

$_GET['year'] = '';
 
$sql = "SELECT
            name,
            subtitle,
            numericalYear
        FROM
            courseyear";
 
$result = mysql_query($sql);
 
if(!$result)
{
    echo 'The course years could not be displayed, please try again later.';
}
else
{
    if(mysql_num_rows($result) == 0)
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
             
        while($row = mysql_fetch_assoc($result))
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