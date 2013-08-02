
<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title>Time Table Constraints</title>
	<link rel="stylesheet" type="text/css" href="Style/formStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
	 <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="JS/bootstrap.min.js"></script>
    <script src="JS/myApp.js"></script>
</head>

<body>
	<div id="wrapper">
		<header>
			<h1 class="mainheader">FET Time Tables</h1><!--end mainheader-->
		</header>
		<div id="mainForm">
			<fieldset>
				<legend>Fall2013</legend>
				<nav>
	<ul>
		<li><a href="data.php">Data</a></li>
		<li><a href="constraints.php">Constraints</a></li>
		<li><a href="generate.php">Generate Table</a></li>
		<li><a href="PHP/logout.php">Logout</a></li>
	</ul>		
</nav>		
			<article id="box">
						<ul class="tabs">
							<li><a href="#tab1" data-toggle="tab">Time</a></li>
							<li><a href="#tab2" data-toggle="tab">Space</a></li>
						</ul>
				<div class="tab_container">
					<!-- ================= TAB 1 ========================= -->
					<div id="tab1" class="tab_content" ng-controller="TimeConsCTRL">
						<div style="clear: both">
							Activities constraints<br>
							<div style="width: 160px; float: left">
								<select size="10" style="min-width: 150px;" 
									ng-model="activity" 
									ng-options="(a.id + ' - ' + a.teach_name + ' - ' + a.subj_name) for a in activities" 
									ng-dblclick="addToChosen(activity)">
								</select>
							</div>
							<div style="width: 55px; float: left">
							<button ng-click="addToChosen(activity)" >&nbsp;>&nbsp;>&nbsp;</button>
							<button ng-click="removeFromChosen(chosenAct)">&nbsp;<&nbsp;<&nbsp;</button>
							<button ng-click="clearChosen()" >clear</button>
							</div>
							<div style="width: 175px; float: left">
					            <select size="10" 
					            	ng-model="chosenAct" 
					            	ng-options="(a.id + ' - ' + a.teach_name + ' - ' + a.subj_name) for a in  chosenActivities" style="min-width: 150px;" 
					            	ng-dblclick="removeFromChosen(chosenAct)">
					            </select>
					        </div>
				            <button ng-click="saveChosen()" >{{btn1}}</button><br>
				            <span ng-show="checked">
				                <button ng-click="destroy()" >{{btn2}}</button><br>
				                <button ng-click="cancel()" >{{btn3}}</button>
				            </span>
				    	</div>
				        <div style="clear: both; padding-top:20px;">
				            <ul class="unstyled">
				                <li ng-repeat="con in constraints">
				                    Activities same starting time, WP:{{con.weight}}%, NA:{{con.num}} ID:{{con.id}}
				                    <a href ng-click="editCons(con.id)">edit</a>
				                </li>
				            </ul>
				        </div>
				    </div>
				    <!-- =================== TAB 2 ======================= -->
				    <div id="tab2" class="tab_content" ng-controller="SubjectsCtrl">
				        Time constraints
					</div>
				    <!-- ================== END ======================== -->
				</div>
				</article>	
			</fieldset>
		</div>
		<footer>

</footer>	</div>
</body>
</html>
