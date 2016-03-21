<?php
//create_cat.php
include 'connect.php';
include 'header.php';

$module = $_GET['module'];
$_GET['thread'] = '';
 
$sql = "SELECT
            id,
            dateOfCreation,
            title,
            parentModuleID,
            creatorID,
            threadText
        FROM
            threads
        WHERE
            parentModuleID = $module";
 
$result = mysql_query($sql);
 
if(!$result)
{
    echo 'The threads for this module could not be displayed, please try again later.';
}
else
{
    if(mysql_num_rows($result) == 0)
    {
        echo 'No threads in this module yet.';
    }
    else
    {
        //prepare the table
        echo '<table border="1">
              <tr>
                <th>Modules</th>
                <th>Last reply</th>
              </tr>'; 
             
        while($row = mysql_fetch_assoc($result))
        {               
            $thread_id = $row['id'];
            echo '<tr>';
                echo '<td class="leftpart">';
                    echo "<h3><a href=\"posts.php?thread='$thread_id'\">" . $row['title'] . "</a></h3>" . $row['creatorID'];
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