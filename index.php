<!DOCTYPE html>
<html>
	<head>
		<title>FET Login</title>
		<link rel="stylesheet" type="text/css" href="Style/formStyle.css">
	</head>
<body>
	<div id="wrapper">
		<header>
			<h1 class="mainheader">FET Login</h1><!--end mainheader-->
		</header>
		<form name="login" action="" method="post">
		<fieldset style="width: 275px; height: 275px; margin: 0 auto;">
			<legend>Login</legend>
			<label>User ID:</label><input type="text" name="id" /><br />
			<label>Password:</label><input type="password" name="pwd" /><br />
			<input class="buttons" type="submit" value="Login" name="login" /><br />
			<input class="buttons" type="submit" value="SignUp" name="signup"/>
		</fieldset>
		</form>
	</div>
</body>
</html>

<?php
	include 'PHP/db_connect.php';
	if(isset($_REQUEST['login'])){
		$id = $_POST['id'];
		$pwd = $_POST['pwd'];

		
		$sql = "SELECT * FROM `$users` WHERE `user_name`='$id' and `password` = '$pwd';";
		$result = $mysqli->query($sql);
		$count = $result->num_rows;

		if ($count==1) {
			session_start();
			$_SESSION['user']['username'] = $id; 

			header("Location: timetables.php");
			exit;
		}
		else {
			echo "<script type=\"text/javascript\">
					window.alert(\"Your username or password is incorrect! Please try again!\")
				  </script>";
		}
		$result->free();
		$mysqli->close();
	}
	
	if(isset($_REQUEST['signup'])){
		header("Location: signup.php");
		exit;
	}
?>
