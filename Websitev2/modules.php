<?php
    include('header.php'); 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tcdcsforum";
    $year = $_GET['year']; //get the year selected from the previous page, and store in $year as string
    $_GET['module'] = '';
    

    // Create connection to SQL database
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection to SQL database
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
        //query the table of modules, and retrieve all modules in the year that was selected
        $sql = "SELECT module_code, module_name, module_threads FROM modules WHERE module_year = '$year'";
        $result = $conn->query($sql);
?>

	<div id="banner"></div>
	<div id="content">
    <hr/>
<?php
    if ($result->num_rows > 0) {
        // if query was successful, output each module in the year selected
        while($row = $result->fetch_assoc()) {
            $module_code = $row["module_code"];
             //pass the module code of module selected to threads.php to retrieve all the threads within that module, then print out each module's code and name in every line
            echo "<a href=\"threads.php?module='$module_code'\">" . $row["module_code"] . ": " . $row["module_name"] . "</a></br><hr/>";
        }
    } else {
        echo "0 modules in year selected or SQL query failed";
    }
    $conn->close();
?>
	</div>

<?php include('footer.php'); ?>