<?php
	include 'PHP/validUser.php';
	$semester = $_SESSION['user']['semester'];
?>
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
				<legend><?php echo ucfirst($semester);?></legend>
				<?php include 'html/nav.html'?>
		
			<article id="box">
				<!--<ul class="tabs">
					<li><a href="#tab1" data-toggle="tab">Teachers</a></li>
					<li><a href="#tab2" data-toggle="tab">Subjects</a></li>
					<li><a href="#tab3" data-toggle="tab">Students</a></li>
					<li><a href="#tab4" data-toggle="tab">Space</a></li>
					<li><a href="#tab5" data-toggle="tab">Activities</a></li>
				</ul>

				<div class="tab_container">
					<div id="tab1" class="tab_content">
						<div ng-controller="TeachersCtrl">
							<form name="myForm">
								Teacher name:<br> <input name="input" ng-model="teacherName" required>
								<span class="error" ng-show="myForm.input.$error.required">Required!</span>
								<button ng-click="addItem()" >Add</button><br>
							</form>
							<ul class="unstyled">
								<li ng-repeat="teacher in teachers">
									<span class="done-{{todo.done}}">{{teacher.name}}</span>
									<a href ng-click="editThis(teacher.id)">edit</a>
								</li>
							</ul>
							<div ng-include src="template.url"></div>
							<div ng-view></div>
						</div>
					</div>
					
					<div id="tab2" class="tab_content" ng-controller="SubjectsCtrl">
						<form class="subForm" name="myForm">
							Subject name:<br> <input name="input" ng-model="subjectName" required>
							<span class="error" ng-show="myForm.input.$error.required">Required!</span>
							<button ng-click="addItem()" >Add</button><br>
						</form>
						<ul class="unstyled">
							<li ng-repeat="subject in subjects">
								<span class="done-{{todo.done}}">{{subject.name}}</span>
								<a href ng-click="editThis(subject.id)">edit</a>
							</li>
						</ul>
						<div ng-include src="template.url"></div>
						<div ng-view="testXX"></div>

					</div>
    
					<div id="tab3" class="tab_content" ng-controller="StudentsCtrl">
						<form class="subForm" name="myForm">
							Student name:<br> <input name="input" ng-model="studentName" required><br>
							Student amount:<br> <input name="input" ng-model="studentAmount" required><br>
							<span class="error" ng-show="myForm.input.$error.required">Required!</span>
							<button ng-click="addItem()">Add</button><br>
						</form>
						<ul class="unstyled">
							<li ng-repeat="student in students">
								<span class="done-{{todo.done}}">{{student.year_name}} - {{student.num_students}}</span>
								<a href ng-click="editThis(student.id)">edit</a>
							</li>
						</ul>
						<div ng-include src="template.url"></div>
					</div>
					
					<div id="tab4" class="tab_content" ng-controller="SpaceCtrl">
						<div style="width: 300px; float: left">
							<p>Add buildings</p>
							<form class="subForm" name="myForm">
								Building:<br> <input name="input" ng-model="buildingInput"><br>
								<input name="input" type="hidden" ng-model="buildingID"><br>
								<button ng-click="addItem()">Add</button> <button ng-click="editItem()" ng-disabled="showB()">Edit</button>
								<button ng-click="deleteItem()" ng-disabled="showB()">Delete</button><br>
							</form>
							<select ng-model="building" ng-options="c.name for c in buildings" size="10" ng-change="update()" style="min-width: 200px;"></select>
						</div>
						<div style="width: 300px; float: left">
							<p>Add rooms</p> 
							<form class="subForm" name="myForm">
								Select building: <br><select ng-model="building" ng-options="c.name for c in buildings" ng-change="update()"></select><br/>
								Room name: <br><input name="input" ng-model="roomName" required><br>
								Capacity: <br><input name="input" ng-model="roomCapacity" required><br>
								<input name="input" type="hidden" ng-model="roomID"><br>
								<button ng-click="addRoom()">Add</button> <button ng-click="editRoom()" ng-disabled="show()">Edit</button>
								<button ng-click="deleteRoom(room.id)" ng-disabled="show()">Delete</button><br>
							</form><br>
							<select ng-model="room" ng-options="(r.name + ' - ' + r.capacity) for r in rooms" size="10" style="min-width: 200px;" select>
						</div>
					</div>
      
					<div id="tab5" class="tab_content" ng-controller="ActivitiesCtrl">
						<h1>Activities will be implemented soon</h1>
						<form class="subForm" name="myForm">
							<button ng-click="addItem()">Add</button><br>
						</form>
						<div ng-include src="template.url"></div>
					</div>
				</div>
				<br>-->				
				</article>	
			</fieldset>
		</div>
		<?php include 'html/footer.html'?>
	</div>
</body>
</html>
