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
            Email: <input type="text" name="email" /></br>
           	
            <input type="submit" value="Forgot Password" />
         </form>';
    }
    else
    {
        /* so, the form has been posted, we'll process the data in three steps:
            1.  Check the data
            2.  Let the user refill the wrong fields (if necessary)
            3.  Varify if the data is correct and return the correct response
        */

        $errors = array(); /* declare the array for later use */

        if(!isset($_POST['email']))
        {
            $errors[] = 'The username field must not be empty.';
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
                        usertitle
                    FROM
                        user
                    WHERE
                        usertitle 
                    LIKE
                    	'" .($_POST['email']) . "'";

            $result = $mysqli->query($sql);
            if(!$result)
            {
                //something went wrong, display the error
                echo 'Something went wrong while signing in. Please try again later. ';
                echo mysql_error(); //debugging purposes, uncomment when needed
            }
            else
            {
                //the query was successfully executed, there are 2 possibilities
                //1. the query returned data, the user can be signed in
                //2. the query returned an empty result set, the credentials were wrong
                if($result->num_rows == 0)
                {
                    echo 'The Email is not registered.';
                }
                else
                {
                    $email1 = $_POST['email'];
  					$code = md5(uniqid(rand()));
                    $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";
                    $passwordWeb = hash('sha512', $salt.$email1);
                    $pwrurl = 
  						$message= "
					       Hello , 
					       <br /><br />
					       We got requested to reset your password, if you do this then just click the following link to reset your password, if not just ignore                   this email,
					       <br /><br />
					       Click Following Link To Reset Your Password 
					       <br /><br />
					       <a href='www.csforum.ie/resetPass.php?q= .$email1. '>click here to reset your password</a>
					       <br /><br />
					       thank you :)
					       ";
					  $subject = "Password Reset";
					  
						mail($_POST['email'],$subject,$message);
					  
					  echo 'Email Sent to Inbox ';
                    //  echo $passwordWeb;
                }
            }
        }
    }
}

include 'footer.php';
?>
