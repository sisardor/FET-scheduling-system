<?php
	include 'PHP/validUser.php';
	include 'PHP/db_connect.php';
	$query = "SELECT semester FROM user_tables WHERE user_name = '".$_SESSION['user']['username']."'";
	$result = $mysqli->query($query);
	$semester = "";
	while($tuple = $result->fetch_assoc()){
		$semester .= "<option value=\"".$tuple['semester']."\">".$tuple['semester']."</option>";
	}
	$result->free();
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

    <title>Navbar Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="http://localhost2/tmp/FET-scheduling-system/dist/css/bootstrap.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style>
    body {
  		padding-top: 20px;
  		padding-bottom: 20px;
	}

	.navbar {
	  margin-bottom: 20px;
	}
    </style>
  </head>

  <body>

    <div class="container">

      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="./">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>
            <li><a href="../navbar-fixed-top/">Fixed top</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Navbar example</h1>
        <p>This example is a quick exercise to illustrate how the default, static navbar and fixed to top navbar work. It includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>
        <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
        </p>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>


<?php

	$userID = $_SESSION['user']['username'];
	$pathToMyDir = "/home/mctom03/public_html/Project/uploads/".$userID."/";

	#user file upload
	if(isset($_REQUEST['upload']) && $_REQUEST['uploadSem'] !== NULL){ 
		$file = $_FILES['user-file']['tmp_name'];
		$path = $_FILES['user-file']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		if($ext === 'fet'){
			$semester =	$_REQUEST['uploadSem'];
			mkdir($pathToMyDir.$semester);
			$semFile = $semester.".fet";
			$pathToMyDir .= $semester."/";
			
			move_uploaded_file($file, $pathToMyDir.$path);
			rename($pathToMyDir.$path, $pathToMyDir.$semFile);
			
			$fetcmd = "export DISPLAY=:0.0; cd ".$pathToMyDir."; fet --inputfile=".$semFile." --outputdir=".$semester.";";
			exec($fetcmd);
			
			$insert = $mysqli->prepare("INSERT INTO ".$dbname.".user_tables (semester, user_name) VALUES (?, ?)");
			$insert->bind_param("ss", $semester, $userID);
			$insert->execute();
					
			$_SESSION['user']['semester'] = $semester;
			
			$select = "SELECT user_table_id FROM user_tables WHERE semester = '".$semester."' AND user_name = '".$userID."'";
			$selectResult = $mysqli->query($select);
			$tableID = $selectResult->fetch_assoc();
			$_SESSION['user']['user_table_id'] = $tableID['user_table_id'];
			$selectResult->free();
			
			$mysqli->close();
		}
		else{
			echo "<script type=\"text/javascript\">
								window.alert(\"Invalid File! Please input a valid .fet file.\")
					</script>";
		}
	}

	#creates a new user table
	if(isset($_REQUEST['create']) && $_REQUEST['createSem'] !== NULL){	
		$semester = $_REQUEST['createSem'];
		$institutionName = $_REQUEST['institution'];
		$comments = $_REQUEST['comments'];
		
		/*Code to check if semester already exists and if they want to continue...*/
		
		mkdir($pathToMyDir.$semester);
		$insert = $mysqli->prepare("INSERT INTO ".$dbname.".user_tables (semester, institution_name, comments, user_name) VALUES (?, ?, ?, ?)");
		$insert->bind_param("ssss", $semester, $institutionName, $comments, $userID);
		$insert->execute();
		
		$_SESSION['user']['semester'] = $semester;
		
		$select = "SELECT user_table_id FROM user_tables WHERE semester = '".$semester."' AND user_name = '".$userID."'";
		$selectResult = $mysqli->query($select);
		$tableID = $selectResult->fetch_assoc();
		$_SESSION['user']['user_table_id'] = $tableID['user_table_id'];
		
		$selectResult->free();
		
		$mysqli->close();
		
		header("Location: data.php");
		exit;
	}
	
	#works on exisiting table
	if(isset($_REQUEST['view']) && $_REQUEST['existSem']){
		$semester = $_REQUEST['existSem'];
		$_SESSION['user']['semester'] = $semester;
		
		$select = "SELECT user_table_id FROM user_tables WHERE semester = '".$semester."' AND user_name = '".$userID."'";
		$selectResult = $mysqli->query($select);
		$tableID = $selectResult->fetch_assoc();
		
		$_SESSION['user']['user_table_id'] = $tableID['user_table_id'];
		
		$selectResult->free();
		
		$mysqli->close();
		
		header("Location: data.php");
		exit;
	}

	if(isset($_REQUEST['logout'])){
		session_destroy();
		header("Location: index.php");
	}
	
?>	
