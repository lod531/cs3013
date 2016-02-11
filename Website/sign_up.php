
<?php include('header.php'); ?>

	<div id="banner"></div>
	<div id="content">
		<h3>Sign up to forum</h3>
			<?php
				echo "<form action='check.php' method='post'>
				Username : <input type='text' name='username'/> &nbsp;
				Password : <input type='password' name ='password' /> &nbsp;
				Password Again : <input type='password' name ='passwordRetry' /> &nbsp;
				Email : <input type='Email' name='email' /> &nbsp;
				<input type='submit' name='submit' value ='Log In' />
				";
			?>
	</div>
<?php include('footer.php'); ?>