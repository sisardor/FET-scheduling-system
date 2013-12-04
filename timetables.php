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
<html>
<head>
<meta charset="UTF-8">
	<title>Time Tables</title>
	<link rel="stylesheet" type="text/css" href="Style/formStyle.css">
	<style>
		 label{margin-top: 0.5em; margin-right: 0.25em;}
	</style>
</head>

<body>
	<div id="wrapper">
		<header>
			<h1 class="mainheader">FET Time Tables</h1><!--end mainheader-->
		</header>
		<article>
			<form name="fet" action="" method="POST" enctype="multipart/form-data">
			<fieldset>
				<legend>Time Tables</legend>
				
				<section id="create">
					<h1>Create a New Table</h1>
					<label>Semester:</label><input class="text" type="text" name="createSem" /><br/>
					<label>Institution Name:</label><input style="margin-top: 1.75em; margin-bottom: 1em;" type="text" name="institution" /><br/>
					<label style="margin-top:0;">Comments:</label><textarea rows="3" cols="25" name="comments"></textarea><br/>
					<!--This button should add a new entry to user_tables-->
					<input class="buttons" type="submit" name="create" value="Create Table"/>
				</section><!--end create-->
				
				<section id="exist">
					<h1>Work on Existing Tables</h1>
					<label>Semester:</label><select style="margin-bottom: 3em; margin-top: 0.5em;" name="existSem">
						<option value="" selected></option>
						<?php echo $semester?>
					</select><br/>
					<input class="buttons" type="submit" name="view" value="View/Edit Table"/>
				</section><!--end exist-->
				
				<section id="upload">
					<h1>Upload a .fet XML File</h1>
					<label>FET file:</label><input type="file" name="user-file" /><br/>
					<label>Semester:</label><input class="text" type="text" name="uploadSem" /><br/>
					<input class="buttons" type="submit" name="upload" value="Upload File" />
				</section><!--end upload-->
				
				<section id="logout">
					<input class="buttons" type="submit" name="logout" value="Logout" />
				</section><!--end logout-->
			</fieldset>
			</form>
		</article>
	</div><!--end wrapper-->
</body>
</html>

<?php

	$userID = $_SESSION['user']['username'];
	$pathToMyDir = "/Applications/MAMP/htdocs/tmp/FET-scheduling-system/uploads/".$userID."/";

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
