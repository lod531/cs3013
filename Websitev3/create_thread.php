<?php
include 'connect.php';
include 'header.php';

echo '<h2>Create a thread</h2>';
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

        $result = mysql_query($sql);

        //REQUIRES ERROR HANDLING, SUBMITS WHEN FIELDS ARE BLANK.

        if(!$result)
        {
            //the query failed, uh-oh :-(
            echo 'Error while selecting from database. Please try again later.';
        }
        else
        {
            if(mysql_num_rows($result) == 0)
            {
                echo 'There are no modules to post threads in.';
            }
            else
            {

                echo '<form method="post" action="">
                    Title: <input type="text" name="thread_title" /></br>
                    Module:';
                echo '<select name="thread_module">';
                    while($row = mysql_fetch_assoc($result))
                    {
                        echo '<option value="' . $row['name'] . '">' . $row['name'] . ' - ' . $row['subtitle'] . '</option>';
                    }
                echo '</select></br>';

                echo 'Message: <textarea name="thread_content" /></textarea>
                    <input type="submit" value="Create topic" />
                 </form>';
            }
        }
    }
    else
    {
        //start the transaction
        $query  = "BEGIN WORK;";
        $result = mysql_query($query);

        if(!$result)
        {
            //Damn! the query failed, quit
            echo 'An error occured while creating your thread. Please try again later.';
        }
        else
        {
            $thread_title = mysql_real_escape_string($_POST['thread_title']);
            $thread_module = mysql_real_escape_string($_POST['thread_module']);
            $thread_creator = $_SESSION['username'];
            //the form has been posted, so save it
            //insert the topic into the topics table first, then we'll save the post into the posts table
            $sql = "INSERT INTO
                       threads(title,
                               dateOfCreation,
                               parentModuleID,
                               creatorID)
                   VALUES('$thread_title', NOW(), '$thread_module', '$thread_creator')";

            $result = mysql_query($sql);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'An error occured while inserting your thread. Please try again later.' . mysql_error();
                $sql = "ROLLBACK;";
                $result = mysql_query($sql);
            }
            else
            {
                $post_content = mysql_real_escape_string($_POST['thread_content']);
                //the first query worked, now start the second, posts query
                //retrieve the id of the freshly created topic for usage in the posts query
                $thread_id = mysql_insert_id();

                $sql = "INSERT INTO
                            post(content,
                                  dateOfCreation,
                                  threadParentID,
                                  creatorID)
                        VALUES
                            ('$post_content', NOW(), '$thread_id', '$thread_creator')";
                $result = mysql_query($sql);

                if(!$result)
                {
                    //something went wrong, display the error
                    echo 'An error occured while inserting your post. Please try again later.' . mysql_error();
                    $sql = "ROLLBACK;";
                    $result = mysql_query($sql);
                }
                else
                {
                    $sql = "COMMIT;";
                    $result = mysql_query($sql);

                    //after a lot of work, the query succeeded!
                    echo 'You have successfully created <a href="posts.php?thread='. $thread_id . '">your new thread</a>.';
                }
            }
        }
    }
}

include 'footer.php';
?>
