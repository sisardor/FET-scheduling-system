<?php
	session_start();
	/* here place the code to prevent invaildate user access the page*/
	if(!isset($_SESSION['user']['username'])){
		echo "<p><strong>You cannot directly access to this page</strong></p>";
		echo "<a href=\"http://mctom2261.j43.ca/Project/index.php\">Login page</a><br/>";
		echo "<a href=\"http://mctom2261.j43.ca/Project/signup.php\">Signup page</a><br/>";
		die();
	}

