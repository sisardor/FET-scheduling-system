<?php
	include 'PHP/validUser.php';
	$semester = $_SESSION['user']['semester'];
?>
<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title>Time Table Data</title>
	<link rel="stylesheet" type="text/css" href="Style/formStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
    <script src="http://code.angularjs.org/1.1.5/angular-resource.js"></script>
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
					<ul class="tabs">
					   <li><a href="#tab1" data-toggle="tab">Teachers</a></li>
					   <li><a href="#tab2" data-toggle="tab">Subjects</a></li>
					   <li><a href="#tab3" data-toggle="tab">Students</a></li>
					   <li><a href="#tab4" data-toggle="tab">Space</a></li>
					   <li><a href="#tab5" data-toggle="tab">Activities</a></li>
					</ul>
					<div class="tab_container" ng-controller="AppController">
							<!-- ================= TAB 1 ========================= -->
							<div id="tab1" class="tab_content" ng-controller="TeachersCtrl">
								<form name="myForm">
									Teacher name:<br> <input name="input" ng-model="teacherName" required>
									<button ng-click="addItem()" >Add</button><br>
								</form>
								<ul class="unstyled">
									<li ng-repeat="teacher in teachers">
										{{teacher.name}}
										<a href ng-click="editTeacher(teacher.id)">edit</a>
									</li>
								</ul>
								<div ng-include src="template.url"></div>
							</div>
							<!-- =================== TAB 2 ======================= -->
							<div id="tab2" class="tab_content" ng-controller="SubjectsCtrl">
								<form name="myForm">
									Subject name:<br> <input name="input" ng-model="subjectName" required>
									<button ng-click="addItem()" >Add</button><br>
								</form>
								<ul class="unstyled">
									<li ng-repeat="subject in subjects">
										{{subject.name}}
										<a href ng-click="editThis(subject.id)">edit</a>
									</li>
								</ul>
								<div ng-include src="template.url"></div>
							</div>
							<!-- =================== TAB 3 ======================= -->
							<div id="tab3" class="tab_content" ng-controller="StudentsCtrl">
								<form name="myForm">
									Student name:<br> <input name="input" ng-model="studentName" required><br>
									Student amount:<br> <input name="input" ng-model="studentAmount" required><br>
									<button ng-click="addItem()">Add</button><br>
								</form>

								<ul class="unstyled">
									<li ng-repeat="student in students">
										{{student.year_name}} - {{student.num_students}}
										<a href ng-click="editThis(student.id)">edit</a>
									</li>
								</ul>
								<div ng-include src="template.url"></div>
							</div>
							<!-- =================== TAB 4 ======================= -->
							<div id="tab4" class="tab_content" ng-controller="SpaceCtrl">
								<div style="width: 300px; float: left">
									<p>Add buildings</p>
									Building:<br> 
									<input name="input" ng-model="buildingInput" required><br>
									<input name="input" type="hidden" ng-model="buildingID"><br>
									<button ng-click="addItem()">Add</button> 
									<button ng-click="editItem()" ng-disabled="showB()">Edit</button>
									<button ng-click="deleteItem()" ng-disabled="showB()">Delete</button><br>

									<select ng-model="building" 
										ng-options="c.name for c in buildings" size="10" style="min-width: 150px;" 
										ng-change="update()">
									</select>
								</div>

								<div style="width: 300px; float: left">
									<p>Add rooms</p> 
									Select building: <br>
									<select ng-model="building" 
										ng-options="c.name for c in buildings" 
										ng-change="update()">
									</select><br/>

									Room name: <br><input name="input" ng-model="roomName" required><br>
									Capacity: <br><input name="input" ng-model="roomCapacity" required><br>
									<input name="input" type="hidden" ng-model="roomID"><br>
									<button ng-click="addRoom()">Add</button> 
									<button ng-click="editRoom()" ng-disabled="show()">Edit</button>
									<button ng-click="deleteRoom(room.id)" ng-disabled="show()">Delete</button><br>

									<select 
										ng-model="room" 
										ng-options="(r.name + ' - ' + r.capacity) for r in rooms" size="10" 
										style="min-width: 150px;">
									</select>
								</div>
							</div>
							<!-- =================== TAB 5 ======================= -->
							<div id="tab5" class="tab_content" ng-controller="ActivitiesCtrl">
								<p>Activities</p>
								<ul class="unstyled">
									<li ng-repeat="act in activities">
										{{act.teach_name}} - {{act.subj_name}} - {{act.year_name}}
										<a href ng-click="editThis(act.id)">edit</a>
									</li>
								</ul>
								 Teachers<br>
								<div style="width: 175px; float: left">
									<select size="10" 
										ng-model="act_teacher" 
										ng-options="t.name for t in teachers" style="min-width: 150px;" 
										ng-dblclick="updateT()" autofocus>
									</select>
								</div>

								<div style="width: 175px; float: left">	
									<select size="10" 
										ng-model="chosenT"
										ng-options="ct.name for ct in chosenTeachers" style="min-width: 150px;" 
										ng-dblclick="removeT()">
									</select>
								</div>

								<div style="clear: both">
									Students<br>
									<div style="width: 175px; float: left">
										<select size="10" style="min-width: 150px;" 
											ng-model="act_student" 
											ng-options="st.year_name for st in students" 
											ng-dblclick="updateS()">
										</select>
									</div>
									<div style="width: 175px; float: left">
										<select size="10" 
											ng-model="chosenS" 
											ng-options="st.year_name for st in chosenStudents" style="min-width: 150px;" 
											ng-dblclick="removeS()">
										</select>
									</div>
								</div>

								<select ng-model="subject" ng-options="sb.name for sb in subjects" >
									<option value="">- select -</option>
								</select>
						<br>
								<button ng-click="saveAct()" ng-disabled="isOK()">{{button_value}}</button>
								<span ng-show="checked">
								<button ng-click="cancel()" ng-disabled="isOK()">Cancel</button>
								<button ng-click="deleteAct()" ng-disabled="isOK()">Delete</button>
								</span>

								<div ng-include src="template.url"></div>
							</div>
							<!-- ================== END ======================== -->

						</div><!--END tab-->
					<br>				
				</article>	
			</fieldset>
		</div>
	</div>
</body>
</html>
