<?php include('header.php'); ?>

	


	<div id="banner"></div>
	<div id="content">
        <?php 
        $_GET['year'] = '';
        ?>
		<hr/>
        <!--pass the year selected into the modules.php to retrieve all modules for that year-->
		<a href="modules.php?year=1">1st Year</a></br><hr/>
		<a href="modules.php?year=2">2nd Year</a></br><hr/>
		<a href="modules.php?year=3">3rd Year</a></br><hr/>
		<a href="modules.php?year=4B">4th Year BaMod</a></br><hr/>
		<a href="modules.php?year=4M">4th Year MCS</a></br><hr/>
		<a href="modules.php?year=5">5th Year</a></br><hr/>

	</div>

<?php include('footer.php'); ?>
