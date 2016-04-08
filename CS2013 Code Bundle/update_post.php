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
        echo 'You must be logged in to edit a post.';
    }
    else
    {
        //a real user posted a real reply
        $user = $_SESSION['username'];
        $thread_id = $_GET['thread'];
        $id = $_GET['id'];
        $op = $_GET['op'];
        $content = mysqli_real_escape_string($mysqli, $_POST['edit_content']);
        if(empty($content)){
            echo 'Cannot submit an empty post. Please enter some text.';
        } else {

            $get_thread_row = "SELECT id
                    FROM post
                    WHERE threadParentID = $thread_id";
            $results = $mysqli->query($get_thread_row);
            
            $sql = "UPDATE post SET lastEdited = NOW(), content = '$content' WHERE id = $id";
            if($op == 1){
                $sql2 = "UPDATE threads SET lastEdited = NOW(), threadText = '$content', WHERE id = $thread_id";

                $result2 = $mysqli->query($sql2);

                if(!$result2){
                    echo 'Your edit has not been submitted, please try again.';
                    echo mysqli_error($mysqli);
                    //die();
                }
            }
            
            $result = $mysqli->query($sql);
            
            if(!$result)
            {
                echo 'Your edit has not been submitted, please try again.';
                echo mysqli_error($mysqli);
            }
            else
            {
                 //echo 'Content now reads as: ' . $content . '';
                $header_redirect = 'posts.php?thread='.$thread_id.'';
                header("Location:" . $header_redirect);
            }
        }
    }
}

include 'footer.php';
?>
