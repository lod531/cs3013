<?php 
    include('header.php');
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tcdcsforum"; //database name
    $module = $_GET['module']; //get the module code selected from the previous page, and store in $module as string
    $_GET['thread'] = '';

    // Create connection to SQL database
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection to SQL database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
        //query the table of threads, and retrieve all threads in the $module that was selected
        $sql = "SELECT thread_id, thread_title, thread_date, thread_creator, thread_replies FROM threads WHERE thread_module = $module";
        $result = $conn->query($sql);

?>

    <div id="banner"></div>
    <div id="content">
    <hr/>
        
<?php
    if ($result->num_rows > 0) {
        // if query was successful, output each thread in the module, with the corresponding thread id, etc.
        while($row = $result->fetch_assoc()) {
            $thread_id = $row["thread_id"];
            //pass the id of thread selected to posts.php, retrieve all the posts within that thread, then print out each thread's detail in every line
            echo "<a href=\"posts.php?thread='$thread_id'\">Thread ID: " . $row["thread_id"]. ",  Title: " . $row["thread_title"]. ",  By: " . $row["thread_creator"] . ",  Date posted: " . $row["thread_date"] . ",  Replies: " . $row["thread_replies"] . "</a></br><hr/>";
        }
    } else {
        echo "0 threads in $module or SQL query failed";
    }
    $conn->close();
?>
	</div>
<?php include('footer.php'); ?>

