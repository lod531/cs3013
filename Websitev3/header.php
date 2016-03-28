<head>
 	<title>TCD Computer Science Forum</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<h1 align="left">tcdcsforum.</h1>
	<div id="wrapper">
	<div id="menu">
		<a class="item" href="home.php">Home</a>
    <a class="item" href="create_thread.php">Create</a>
		<?php
    session_start();
    if (isset($_SESSION['moderator']) && $_SESSION['moderator'])
    {
    echo '<a class="item" href="moderator.php">Manage</a>';
    }
		echo '<div id="userbar">';
        if (isset($_SESSION['signed_in']))
        {
            if($_SESSION['signed_in'])
        		  {
        			echo '<font face="Roboto">Hello <b>' . htmlentities($_SESSION['username']) . '</b>. Not you? <a class="item" href="logout.php">Logout</a></font>';
        		  }
        }
		else
		{
			echo '<font face="Roboto">Not logged in. <a class="item" href="login.php">Login</a> or <a class="item" href="register.php">Register</a></font>';
		}
		?>
		</div>
	</div>
		<div id="content">
  &nbsp;
