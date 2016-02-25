

<?php include('header.php'); ?>

<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div id="banner"></div>

<div id="background">
<div id="content">

	<div id = "heading">Login to CS forum</div>


<?php
	
	echo "<form action='sign_up.php' method='post'>
	<div id = boxes>Username : <input type='text' name='username'/> &nbsp <br>
	Password : <input type='password' name ='password' /> &nbsp <br>
	<br> <input type='submit' name='submit' value ='Log In' /> <br> </div>
	";
	//echo "<a class='button' href='sign_up.php' target='_blank'> Sign Up</a>&nbsp</div>";
	echo "<div id = signUpButton><input type='submit' value = 'Sign Up' href='sign_up.php' target='_blank'></a>&nbsp</div>";

?>

</div>
</div>
<hr />
<?php include('footer.php'); ?>