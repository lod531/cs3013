<?php
//create_cat.php
include 'connect.php';
include 'header.php';

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    //someone is calling the file directly, which we don't want
    echo 'This file cannot be called directly.';
}
else
{
    //check for sign in status
    if(!$_SESSION['signed_in'])
    {
        echo 'You must be logged in to post a reply.';
    }
    else
    {
        //a real user posted a real reply
        $user = $_SESSION['username'];
        $thread_id = $_GET['thread'];
        $content = $_POST['reply_content'];
        if(empty($content)){
            echo 'Cannot submit an empty comment. Please enter some text.';
        } else {
            //create connection for real_escape_string; easier way?
            $connection = mysql_connect("localhost", "root", "");
            $content = $connection->real_escape_string($content);
            $sql = "INSERT INTO
                        post( dateOfCreation,
                              creatorID,
                              threadParentID,
                              content
                              )
                    VALUES (NOW(), '$user', '$thread_id', $content)"; 
    
            $result = mysql_query($sql);
    
            if(!$result)
            {
                echo 'Your reply has not been submitted, please try again.';
                echo mysql_error();
            }
            else
            {
                 header("Location: posts.php?thread=" . htmlentities($_GET['thread']) . "");
            }
        }
    }
}

include 'footer.php';
?>
