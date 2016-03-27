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

echo '<h2>Register</h2>';

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    echo 'Please <a href="logout.php">logout</a> before registering a new account.';
}
else {
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        /*the form hasn't been posted yet, display it
          note that the action="" will cause the form to post to the same page it is on */
        echo '<form method="post" action="">
            TCD Email: <input type="email" name="user_email"></br>
            Password: <input type="password" name="user_pass"></br>
            Confirm password: <input type="password" name="user_pass_check"></br>
            <input type="submit" value="Register" />
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

            $useremail = $_POST['user_email'];
            $userpass = sha1($_POST['user_pass']); //HASH PASS
            //$user_pass = $_POST['user_pass'];
            //the form has been posted without, so save it
            //notice the use of mysql_real_escape_string, keep everything safe!
            //also notice the sha1 function which hashes the password
            $hash = md5(rand(0,1000));
            //$password = md5(rand(1000,5000));

            $sqlCheckForUser = "SELECT
                        username,
                        password,
                        usertitle,
                        email,
                        hash,
                        active
                    FROM
                        user
                    WHERE
                        username = '" . mysqli_real_escape_string($mysqli, $username) . "'";

            $queryResult = $mysqli->query($sqlCheckForUser);
            if(!$queryResult){
                echo 'An error occured while you were registering :(</br>Please <a href="register.php">try again</a>.';
            }
            else if($queryResult->num_rows == 0){

                $userpass = mysqli_real_escape_string($mysqli, $userpass);
                $title = 'scrublord';
                $hash =  mysqli_real_escape_string($mysqli, $hash);
                $useremail = mysqli_real_escape_string($mysqli, $useremail);
                $sql = "INSERT INTO user (username, password, usertitle, hash, email)
                VALUES ('$username', '$userpass', '$title', '$hash', '$useremail')";

                $result = $mysqli->query($sql);
                if(!$result)
                {
                    //something went wrong, display the error
                    echo 'Something went wrong while registering. Please try again later.';
                    echo mysqli_error($mysqli); //debugging purposes, uncomment when needed
                }
                else
                {
                    echo 'You have successfully registered with the following details<br>Username: ' . $username . '<br>Password: ' . $userpass . '';
                    echo 'You can login with these details once you have <a href="verify.php?email=' . $useremail . '&hash=' . $hash . '">verified your email</a>.';
                    //echo 'Successfully registered. You can now <a href="login.php">sign in</a> and start posting!';
                }
            }
            else{
                //needs updating when password change option is created
                while($matches = $queryResult->fetch_assoc()){
                    
                    if($matches['active']==0){
                        echo 'You have already registered but you still need to <a href="verify.php?email=' . $matches['email'] . '&hash=' . $matches['hash'] . '">verify</a> your email.';
                    } else{
                        echo 'It appears you are already registered! Try <a href="login.php">logging in</a>.</br>If you have forgotten your password you can change it.';
                    }
                }
            }

        }
    }
}

include 'footer.php';
?>
