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

echo '<h2>Reset Password</h2>';

//first, check if the user is already signed in. If that is the case, there is no need to display this page
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
    echo 'You are already logged in, you can <a href="logout.php">logout</a> if you want.';
}
else{
     if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        echo '<form method="post" action="">
                    New password: <input type="password" name="new_pass1"></br></br>
                    Confirm new password: <input type="password" name="new_pass2"></br></br>
                    <input type="submit" value="Submit." />
            </form>';
    }
    else
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Save the data
        */
        $email = $_GET['email'];
        $password = $_POST["new_pass1"];
        $confirmpassword = $_POST["new_pass2"];
        //$hash = $_POST["q"];

        // Use the same salt from the forgot_password.php file
        $errors = array();
        $pass1 = ''; 
        $pass2 = '';
        if(!isset($_POST['new_pass1']) || !isset($_POST['new_pass2'])){
            $errors[] = 'All fields must be set.';
        } else{
            $pass1 = $_POST['new_pass1'];
            $pass2 = $_POST['new_pass2'];
        }

        if($pass1!=$pass2){
            $errors[] = 'Passwords do not match.';
        }

        if(!empty($errors)){
            foreach($errors as $err){
                echo '<li>' . $err . '</li><br>';
            }
        } else{
            $setNewPass = mysqli_real_escape_string($mysqli, sha1($pass1));
            $sql_change_pass = "UPDATE user 
                                SET password = '$setNewPass'
                                WHERE email = '$email'";
            $query_result = $mysqli->query($sql_change_pass);
            if(!$query_result){
                echo 'Error: could not update password, try again later.';
            } else{
                echo 'Password change successful!';
                header('Refresh : 2, url=login.php');
            }
        }
    }
}
include 'footer.php';
?>
