<?php
	include 'PHP/validUser.php';
	$semester = $_SESSION['user']['semester'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
	<title>Generate Time Table</title>
	<link rel="stylesheet" type="text/css" href="Style/formStyle.css">
</head>

<body>
	<div id="wrapper">
		<header>
			<h1 class="mainheader">FET Time Tables</h1><!--end mainheader-->
		</header>
		<div id="mainForm">
			<fieldset>
				<legend><?php echo ucfirst($semester);?></legend>
				<?php include 'html/nav.html'?>
			<article id="box">
				<?php include 'PHP/writeToXML.php'?>
			</article>
		</div>
		<?php include 'html/footer.html'?>
	</div>
</body>
</html>
