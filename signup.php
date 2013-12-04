<!DOCTYPE html>
<html>
	<head>
		<title>FET Signup</title>
		<link rel="stylesheet" type="text/css" href="Style/formStyle.css">
		<style>
			label{width: 10em;}
		</style>
	</head>
<body>
	<div id="wrapper">
		<header>
				<h1 class="mainheader">FET Signup</h1><!--end mainheader-->
		</header>
		<form name="signup" action="" method="post">
		<fieldset style="width: 350px; height: 250px; margin: 0 auto;">
			<legend>Create User</legend>
			<label>Enter User ID:</label><input type="text" name="id" /><br />
			<label>Enter Password:</label><input type="password" name="pwd" /><br />
			<label>Enter Password again:</label><input style="margin-bottom: 1em;;" type="password" name="pwd2" /><br />
			<input class="buttons" style="width: 6.5em;" type="submit" value="Login" name="login" />
			<input class="buttons" style="width: 6.5em; margin-top: 0.5em;" type="submit" value="SignUp" name="signup"/>
		</fieldset>
		</form>
	</div>
</body>
</html>

<?php
	include 'PHP/db_connect.php';
	if(isset($_REQUEST['signup'])){
		$id = $_POST['id'];
		$pwd = $_POST['pwd'];
		$pwd2 = $_POST['pwd2'];
		if($pwd !== $pwd2) {
			echo "<script type=\"text/javascript\">
					window.alert(\"Your passwords do not match! Please try again!\")
				  </script>";
			exit;
		}
		else{
			if (isset ($_POST['id'])&& isset ($_POST['pwd'])){
				$id = $_POST['id'];
				$pwd = $_POST['pwd'];
								
				$query = $mysqli->prepare("INSERT INTO `$dbname`.`users` (`user_name`, `password`) VALUES (?, ?)");
				$query->bind_param("ss", $id, $pwd);
				$query->execute();
				
				if ($query->error == "Duplicate entry '$id' for key 'PRIMARY'"){
					echo "<script type=\"text/javascript\">
							window.alert(\"User ID has been used. Please choose another one.\")
						</script>";
				}
				else{
					mkdir("/home/mctom03/public_html/Project/uploads/$id");
					header("Location: index.php");
				}
				
				$mysqli->close();
			}
		}
	}
	if(isset($_REQUEST['login'])){
		header("Location: index.php");
	}
?>
