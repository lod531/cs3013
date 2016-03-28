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
        $content = mysqli_real_escape_string($mysqli, $_POST['reply_content']);
        if(empty($content)){
            echo 'Cannot submit an empty comment. Please enter some text.';
        } else {
            $sql = "INSERT INTO
                        post( dateOfCreation,
                              lastEdited,
                              creatorID,
                              threadParentID,
                              content
                              )
                    VALUES (NOW(), NOW(), '$user', '$thread_id', '$content')";

            $result = $mysqli->query($sql);
            $update = $mysqli->query("UPDATE threads SET lastEdited = NOW() WHERE id= '$thread_id'");
            if(!$result || !$update)
            {
                echo 'Your reply has not been submitted, please try again.';
                echo mysqli_error($mysqli);
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
