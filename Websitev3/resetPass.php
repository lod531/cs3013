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

echo '<h2>Login</h2>';

//first, check if the user is already signed in. If that is the case, there is no need to display this page
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
    echo 'You are already logged in, you can <a href="logout.php">logout</a> if you want.';
}
else{
     if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
    echo '<form method="post" action="">
            Confirm Username: <input type="text" name="username" ></br>
            Confirm Email: <input type="text" name="user_email" ></br>
            New Password: <input type="password" name="user_pass"></br>
            Confirm password: <input type="password" name="user_pass_check"></br>
            <input type="submit" value="Change Password" />
         </form>';
    }
         else
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Save the data
        */
        $errors = array(); /* declare the array for later use */
        if(isset($_POST['user_email']))
        {
             if(strlen($_POST['user_email']) != 15){
                $errors[] = 'The email must be 15 characters long.';
               }
            else
            {
               //detect valid email through pattern, take username as well
               //preg_match_all('/([a-zA-Z]{7})([a-zA-Z0-9]{1})+(@tcd.ie)/', 'user_email', $validEntries, PREG_SET_ORDER);
               preg_match_all('/([a-zA-Z0-9])+(@tcd.ie)/', $_POST['user_email'], $validEntries, PREG_SET_ORDER);
               if(empty($validEntries)){
                $errors[] = 'Invalid email entered.';
               }
               else{
                //query to scss db to verify the email
                $parsedEmail = explode("@", $_POST['user_email']);
                $username = $parsedEmail[0];
                //$user_name = substr($_POST['user_email'], 0, 8);
               }
            }
        }
        else
        {
            $errors[] = 'The TCD email field must not be empty.';
        }
        if(isset($_POST['user_pass']))
        {
            if($_POST['user_pass'] != $_POST['user_pass_check'])
            {
                $errors[] = 'The two passwords did not match.';
            }
        }
        else
        {
            $errors[] = 'The password field cannot be empty.';
        }
        if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
        {
            echo 'Uh-oh.. a couple of fields are not filled in correctly..';
            echo '<ul>';
            foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
            {
                echo '<li>' . $value . '</li>'; /* this generates a nice error list */
            }
            echo '</ul>';
        }
        else
        {
            $userpass = sha1($_POST['user_pass']); //HASH PASS
            $username = ($_POST['username']);
            //$user_pass = $_POST['user_pass'];
            //the form has been posted without, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            $sql = "UPDATE `csforum`.`user` SET `password` = '$userpass' WHERE `user`.`username` = '$username'";
                       
            $queryResult = $mysqli->query($sql);
            if(!$queryResult){
                echo 'An error occured while you were changing password :(</br>Please <a href="resetPass.php">try again</a>.';
            }
            else{
              echo 'Password Successfully Updated, <a href="login.php">Log In</a> to continue';
              header('Refresh: 2; url=login.php');
            }
        }
      }
}
include 'footer.php';
?>