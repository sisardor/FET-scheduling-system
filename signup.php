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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="http://localhost2/tmp/FET-scheduling-system/dist/css/bootstrap.css" rel="stylesheet">


    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <style>
 body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  font-size: 16px;
  height: auto;
  padding: 10px;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="text"] {
  margin-bottom: -1px;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
.form-signin .p_last {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
  </style>

  <body>

    <div class="container">

      <form name="signup" action="" method="post" class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" 	   class="form-control" name="id"   placeholder="Enter user ID"  autofocus>
        <input type="password" class="form-control" name="pwd"  placeholder="Enter password" >
        <input type="password" class="form-control p_last" name="pwd2" placeholder="Enter password again" >

        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login" name="login">Sign in</button>
        <button class="btn btn-lg btn-primary btn-block" type="submit" value="SignUp" name="signup">Sign up</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
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
