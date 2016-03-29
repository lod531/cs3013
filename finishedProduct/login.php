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
else
{
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        /*the form hasn't been posted yet, display it
          note that the action="" will cause the form to post to the same page it is on */
        echo '<form method="post" action="">
            Username: <input type="text" name="username" /></br></br>
            Password: <input type="password" name="password"></br></br>
            <input type="submit" value="Sign in" />
            </form>';
        echo  '<a href="forgot_pass.php">Forgot Your Password? </a>';
    }
    else
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Varify if the data is correct and return the correct response
        */

        $errors = array(); /* declare the array for later use */

        if(!isset($_POST['username']))
        {
            $errors[] = 'The username field must not be empty.';
        }

        if(!isset($_POST['password']))
        {
            $errors[] = 'The password field must not be empty.';
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
            //the form has been posted without errors, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            $sql = "SELECT
                        username,
                        password,
                        usertitle
                    FROM
                        user
                    WHERE
                        username = '" . mysql_real_escape_string($_POST['username']) . "'
                    AND
                        password = '" . sha1($_POST['password']) . "'";

            $result = $mysqli->query($sql);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'Something went wrong while signing in. Please try again later.';
                //echo mysql_error(); //debugging purposes, uncomment when needed
            }
            else
            {
                //the query was successfully executed, there are 2 possibilities
                //1. the query returned data, the user can be signed in
                //2. the query returned an empty result set, the credentials were wrong
                if($result->num_rows == 0)
                {
                    echo 'You have supplied a wrong user/password combination. Please <a href="login.php">try again</a>.';
                    header('Refresh: 1; url=login.php');
                }
                else
                {
                    //set the $_SESSION['signed_in'] variable to TRUE
                    $_SESSION['signed_in'] = true;

                    //we also put the user_id and user_name values in the $_SESSION, so we can use it at various pages
                    while($row = $result->fetch_assoc())
                    {
                        $_SESSION['username']  = $row['username'];
                        $_SESSION['usertitle'] = $row['usertitle'];
                    }

                    //check if user is a moderator
                    $sql = "SELECT userID, yearModuleName FROM moderators WHERE userID = '" . $_SESSION['username'] . "'";
                    $mod_result = $mysqli->query($sql);
                    if ($mod_result)
                    {
                        while ($mod_row = $mod_result->fetch_assoc())
                        {
                            if ($mod_row['userID'] == $_SESSION['username'])
                            {
                                  $_SESSION['moderator'] = true;
                            }
                            $_SESSION['modulemod'] = $mod_row['yearModuleName'];
                        }
                        if ($_SESSION['moderator'])
                        {
                        echo 'Welcome, moderator <b>' . $_SESSION['username'] . '</b>. Redirecting...';
                        header('Refresh: 2; url=moderator.php');
                        }
                        else
                        {
                            $_SESSION['moderator'] = false;
                            echo 'Welcome, <b>' . $_SESSION['username'] . '</b>. Redirecting...';
                            header('Refresh: 1; url=home.php');
                        }
                    }
                    else
                    {
                        $_SESSION['moderator'] = false;
                        echo 'Welcome, <b>' . $_SESSION['username'] . '</b>. Redirecting...';
                        header('Refresh: 1; url=home.php');
                    }
                }
            }
        }
    }
}

include 'footer.php';
?>
