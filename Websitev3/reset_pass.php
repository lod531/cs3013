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

echo '<h2>Reset Password</h2>';

//first, check if the user is already signed in. If that is the case, there is no need to display this page
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
    echo 'You are already logged in, you can <a href="logout.php">logout</a> if you want.';
}
else{
     if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
    echo '<form  method="POST" action="">
    E-mail Address: <input type="text" name="email" /><br />
    New Password: <input type="password" name="password" /><br />
    Confirm Password: <input type="password" name="confirmpassword" /><br />
        <input type="hidden" name="q" value="';
        if (isset($_GET["q"])) {
              echo $_GET["q"];
        }
    echo '" /><input type="submit" name="ResetPasswordForm" value=" Reset Password " />
        </form>';
    }
         else
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Save the data
        */
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $hash = $_POST["q"];

    // Use the same salt from the forgot_password.php file
    $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

    // Generate the reset key
    $resetkey = hash('sha512', $salt.$email);

    // Does the new reset key match the old one?
    if ($resetkey == $hash)
    {
        if ($password == $confirmpassword)
        {
            //has and secure the password
            $userpass = sha1($_POST['password']); //HASH PASS
            //$user_pass = $_POST['user_pass'];
            //the form has been posted without, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            $sql = "UPDATE `csforum`.`user` SET `password` = '$userpass' WHERE `user`.`usertitle` = '$email'";

            $queryResult = $mysqli->query($sql);
            if(!$queryResult){
                echo 'An error occured while you were changing password :(</br>Please <a href="resetPass.php">try again</a>.';
            }
            else{
              echo 'Password successfully updated, please <a href="login.php">Login</a> to continue';
              header('Refresh: 2; url=login.php');
            }
        }
      }
}
}
include 'footer.php';
?>
