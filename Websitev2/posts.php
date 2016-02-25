<?php 
    include('header.php');
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tcdcsforum";
    $thread_id = $_GET['thread']; //get the thread id of the thread selected from the previous page

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
        //query table of posts, and retrieve all the posts in the thread that was selected
        $sql = "SELECT post_id, post_content, post_date, post_creator FROM posts WHERE post_threadid = $thread_id";
        $result = $conn->query($sql);

?>
    <div id="banner"></div>
    <div id="content">
    <hr/>
<?php
    if ($result->num_rows > 0) {
        // if query was successful, output each post in the thread, with the corresponding post id, post content etc.
        while($row = $result->fetch_assoc()) {
            echo "Post ID: " . $row["post_id"]. ",  User: " . $row["post_creator"]. ",  Content: " . $row["post_content"] . ",  Posted on: " . $row["post_date"] . "</a></br><hr/>";
        }
    } else {
        echo "0 posts in this thread or SQL query failed";
    }
    $conn->close();
?>
	</div>




<?php include('footer.php'); ?>

