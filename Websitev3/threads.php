<?php
include 'header.php';

$server = "localhost";
$username   = "root";
$password   = "";
$database   = "populateddb";

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
            lastEdited
        FROM
            threads
        WHERE
            parentModuleID = $module";


$result = $mysqli->query($sql);
// $inspector = array();
// $count = 0;
// while($rowwhile = mysqli_fetch_array($result))
// {
//     $inspector = $rowwhile('id');
//     printf("%s\n",$inspector[$count]);
//     $count++;
// }
$posts_sql = "SELECT
                        threadParentID,
                        content,
                        dateOfCreation,
                        lastEdited,
                        id,
                        creatorID
                    FROM
                        post
                    ORDER BY 
                    id
                    DESC";

$posts_result = $mysqli->query($posts_sql);



if(!$result)
{
    echo 'The threads for this module could not be displayed, please try again later.';
}
else if(!$posts_result)
{
    echo 'TTTThe threads for this module could not be displayed, please try again later.';
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
                <th>Modules</th>
                <th>Last reply</th>
              </tr>';
        $questionCount = 0;
        //$countRow = $posts_result->fetch_assoc();
        $threadNumberOn;
        $threadPresent = [];
        while($row = $posts_result->fetch_assoc())
        {
            // $row1 = $result->fetch_array(MYSQLI_BOTH);
            // printf ("%s (%s) (%s)\n", $row["id"], $row["dateOfCreation"],$row["title"]);
            //echo "threadParentId" .$row['threadParentID']. "\n";
            // $parentThread = $row['threadParentID'];
            // $thread = $row['id'];
            // echo $thread;
            // if(in_array($parentThread,$threadPresent))
            // {
            // //     echo $parentThread;
            // //     echo $threadNumberOn;
            //     //echo $parentThread;
            //      continue;
            // }
            // $threadPresent[] = $parentThread;
            // $threadNumberOn = $parentThread;
            // //$threadNumberOn = $row['threadParentID'];
            //echo "\nThread number On " . $threadNumberOn ."\n";
            while($row1 = $result->fetch_assoc()){

                $thread_id = $row1['id'];
                $parentThread = $row['threadParentID'];
                if(in_array($parentThread,$threadPresent))
                {
                    continue;
                }

                //echo " thread_id" . $thread_id;
                // if($thread_id != $threadNumberOn)
                // {
                //     //echo $thread_id;
                //     //echo "hi";
                //     echo $threadNumberOn;
                //     continue;
                // }
                // else{
                 // echo $thread_id;
                 // echo "parent" .$parentThread;
                if($thread_id == $parentThread)
                {
                    $threadPresent[] = $parentThread;
                    echo '<tr>';
                echo '<td class="leftpart">';
                    echo "<h3><a href=\"posts.php?thread='$thread_id'\">" . $row1['title'] . "</a></h3>" . $row1['creatorID'] ;
                echo '</td>';
                echo '<td class="rightpart">';
                echo "<h3><a href=\"posts.php?thread='$thread_id'\">" . $row['creatorID'] . "</a></h3>" .$row['dateOfCreation'] ;
                            //echo "<a href=\"posts.php?id=''>""".$row['creatorID'] .  "User</a> at 10-10" . $row['dateOfCreation'];
                echo '</td>';
            echo '</tr>';
        }

            //break;
        // }

                
            //$postsParentsID = $row['threadParentID']
            

            }
            mysqli_data_seek($result,0);
            
        }
    }
}

include 'footer.php';
?>
