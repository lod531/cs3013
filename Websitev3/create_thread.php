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

echo '<h2>Create A Thread</h2>';
if(!isset($_SESSION['signed_in']) || $_SESSION['signed_in'] == false)
{
    //the user is not signed in
    echo 'Sorry, you have to be <a href="login.php">logged in</a> to create a thread.';
}
else
{
    //the user is signed in
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        //the form hasn't been posted yet, display it
        //retrieve the categories from the database for use in the dropdown
        $sql = "SELECT
                    name,
                    subtitle,
                    courseYearID
                FROM
                    yearmodule";

        $result = $mysqli->query($sql);

        //REQUIRES ERROR HANDLING, SUBMITS WHEN FIELDS ARE BLANK.

        if(!$result)
        {
            //the query failed, uh-oh :-(
            echo 'Error while selecting from database. Please try again later.';
        }
        else
        {
            if($result->num_rows == 0)
            {
                echo 'There are no modules to post threads in.';
            }
            else
            {

                echo '<form method="post" action="">
                    Title: <input type="text" name="thread_title" /></br></br>
                    Module: ';
                echo '<select name="thread_module">';
                    while($row = $result->fetch_assoc())
                    {
                        echo '<option value="' . $row['name'] . '">' . $row['name'] . ' - ' . $row['subtitle'] . '</option>';
                    }
                echo '</select></br></br>';

                echo 'Message: <textarea name="thread_content" /></textarea></br></br>
                    <input type="submit" value="Create" />
                 </form>';
            }
        }
    }
    else
    {
        //start the transaction
        $query  = "BEGIN WORK;";
        $result = $mysqli->query($query);

        if(!$result)
        {
            //Damn! the query failed, quit
            echo 'An error occured while creating your thread. Please try again later.';
        }
        else
        {
            $thread_title = mysqli_real_escape_string($mysqli, $_POST['thread_title']);
            $thread_module = mysqli_real_escape_string($mysqli, $_POST['thread_module']);
            $thread_creator = $_SESSION['username'];

            if(empty($thread_title)){
                echo 'You must give your thread a title.';
            } else if(empty($thread_module)){
                echo 'Please select a module.';
            } else{

                //the form has been posted, so save it
                //insert the topic into the topics table first, then we'll save the post into the posts table
                $sql = "INSERT INTO
                           threads(title,
                                  lastEdited,
                                   dateOfCreation,
                                   parentModuleID,
                                   creatorID)
                       VALUES('$thread_title', NOW(), NOW(), '$thread_module', '$thread_creator')";

                $result = $mysqli->query($sql);
                if(!$result)
                {
                    //something went wrong, display the error
                    echo 'An error occured while inserting your thread. Please try again later.' . mysqli_error($mysqli);
                    $sql = "ROLLBACK;";
                    $result = $mysqli->query($sql);
                }
                else
                {
                    $post_content = mysqli_real_escape_string($mysqli, $_POST['thread_content']);
                    //check for empty posts
                    if(empty($post_content)){
                        echo 'Cannot submit an empty thread. Please add some content.';
                    } else {
                        //post has content so continue
                        //the first query worked, now start the second, posts query
                        //retrieve the id of the freshly created topic for usage in the posts query
                        $thread_id = mysqli_insert_id($mysqli);

                        $sql = "INSERT INTO
                                    post(content,
                                          lastEdited,
                                          dateOfCreation,
                                          threadParentID,
                                          creatorID)
                                VALUES
                                    ('$post_content', NOW(), NOW(), '$thread_id', '$thread_creator')";
                        $result = $mysqli->query($sql);
                        if(!$result)
                        {
                            //something went wrong, display the error
                            echo 'An error occured while inserting your post. Please try again later.' . mysqli_error($mysqli);
                            $sql = "ROLLBACK;";
                            $result = $mysqli->query($sql);
                        }
                        else
                        {
                            $sql = "COMMIT;";
                            $result = $mysqli->query($sql);

                            //after a lot of work, the query succeeded!
                            echo 'You have successfully created <a href="posts.php?thread='. $thread_id . '">your new thread</a>.';
                        }
                    }
                }
            }
        }
    }
}

include 'footer.php';
?>
