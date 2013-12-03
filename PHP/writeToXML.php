<?php
	include 'db_connect.php';
#-------------------------------------------------------------------------------------------------
#builds a node with text
	function buildNode(&$fet, $parent, $element, $value){
		$node = $fet->createElement($element);
		$node = $parent->appendChild($node);
		
		$nodeVal = $fet->createTextNode($value);
		$nodeVal = $node->appendChild($nodeVal);
	}


#-------------------------------------------------------------------------------------------------
#querys the database to retrieve the row count from $table	
	function getCount(&$fet, $table, $mysqli, $join){ 
		$qCount = "SELECT count(*) AS \"count\" ".$join;
		$qCountRes = $mysqli->query($qCount);
		$tupleC = $qCountRes->fetch_assoc();
		
		buildNode($fet, $table, 'Number', $tupleC['count']);
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve several Activity_ID's from activities table according to the value passed 
	function activityIDNode(&$fet, $parent, $tableName, $columnName, $value, $mysqli){
		$query = "SELECT activities_id FROM ".$tableName." WHERE ".$columnName." = ".$value;
		$queryResult = $mysqli->query($query);
		
		while($tuple = $queryResult->fetch_assoc()){
			buildNode($fet, $parent, 'Activity_Id', $tuple['activities_id']);
		}
	}
	
#-------------------------------------------------------------------------------------------------	
#querys the database to retrieve Institution_Name from userTables table
	function getInstituition(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT institution_name FROM user_tables WHERE user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		$tuple = $queryResult->fetch_assoc();
		
		buildNode($fet, $root, 'Institution_Name', $tuple['institution_name']);	
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Comments from user_tables table
	function getComments(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT comments FROM user_tables WHERE user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		$tuple = $queryResult->fetch_assoc();
		
		if($tuple['comments'] !== NULL){
			buildNode($fet, $root, 'Comments',  $tuple['comments']);
		}
		else{
			buildNode($fet, $root, 'Comments',  'Default comments');
		}
	}
	
#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Hours_List from hours table
	function getHours(&$fet, $root, $mysqli, $userTableID){
		#main query to retrieve each hour_name from hours table
		$hrsJoin = "FROM user_tables, hours WHERE user_tables.user_table_id = hours.user_table_id AND hours.user_table_id = ".$userTableID;
		$query = "SELECT hour_name ".$hrsJoin;
		$queryResult = $mysqli->query($query);
		
		$hours = $fet->createElement('Hours_List');
		$hours = $root->appendChild($hours);
		
		getCount($fet, $hours, $mysqli, $hrsJoin);
		
		while($tuple = $queryResult->fetch_assoc()){
			buildNode($fet, $hours, 'Name', $tuple['hour_name']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Days_List from days table
	function getDays(&$fet, $root, $mysqli, $userTableID){
		#main query to retrieve each days_name from days table
		$daysJoin = "FROM user_tables, days WHERE user_tables.user_table_id = days.user_table_id AND days.user_table_id = ".$userTableID;
		$query = "SELECT day_name ".$daysJoin;
		$queryResult = $mysqli->query($query);
		
		$days = $fet->createElement('Days_List');
		$days = $root->appendChild($days);
		
		getCount($fet, $days, $mysqli, $daysJoin);
		
		while($tuple = $queryResult->fetch_assoc()){
			buildNode($fet, $days, 'Name', $tuple['day_name']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Students_List from students table
	function getStudents(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT year_name, num_students FROM user_tables, students WHERE user_tables.user_table_id = students.user_table_id ";
		$query .= "AND students.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		
		$students = $fet->createElement('Students_List');
		$students = $root->appendChild($students);
		
		while($tuple = $queryResult->fetch_assoc()){
			$year = $fet->createElement('Year');
			$year = $students->appendChild($year);
			
			buildNode($fet, $year, 'Name', $tuple['year_name']);
			buildNode($fet, $year, 'Number_of_Students', $tuple['num_students']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Teachers_List from teachers table
	function getTeachers(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT teach_name FROM user_tables, teachers WHERE user_tables.user_table_id = teachers.user_table_id ";
		$query .= "AND teachers.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
				
		$teachers = $fet->createElement('Teachers_List');
		$teachers = $root->appendChild($teachers);
		
		while($tuple = $queryResult->fetch_assoc()){
			$teacher = $fet->createElement('Teacher');
			$teacher = $teachers->appendChild($teacher);
			
			buildNode($fet, $teacher, 'Name', $tuple['teach_name']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Subjects_List from subjects table
	function getSubjects(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT subj_name FROM user_tables, subjects WHERE user_tables.user_table_id = subjects.user_table_id ";
		$query .= "AND subjects.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		
		$subjects = $fet->createElement('Subjects_List');
		$subjects = $root->appendChild($subjects);
		
		while($tuple = $queryResult->fetch_assoc()){
			$subject = $fet->createElement('Subject');
			$subject = $subjects->appendChild($subject);
			
			buildNode($fet, $subject, 'Name', $tuple['subj_name']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Activites_List from activities table
	function getActivities(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT teach_name, subj_name, year_name, duration, total_duration, activity_group_id, active, activities.comments ";
		$query .= "FROM user_tables, activities, teachers, subjects, students WHERE  user_tables.user_table_id = activities.user_table_id ";
		$query .= "AND activities.teacher_id = teachers.teacher_id AND activities.subj_id = subjects.subj_id ";
		$query .= "AND activities.student_id = students.student_id AND activities.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		
		$activities = $fet->createElement('Activities_List');
		$activities = $root->appendChild($activities);
		
		$count = 1;
		while($tuple = $queryResult->fetch_assoc()){
			$activity = $fet->createElement('Activity');
			$activity = $activities->appendChild($activity);
			
			buildNode($fet, $activity, 'Teacher', $tuple['teach_name']);
			buildNode($fet, $activity, 'Subject', $tuple['subj_name']);
			buildNode($fet, $activity, 'Students', $tuple['year_name']);
			buildNode($fet, $activity, 'Duration', $tuple['duration']);
			buildNode($fet, $activity, 'Total_Duration', $tuple['total_duration']);
			buildNode($fet, $activity, 'Id', $count);
			buildNode($fet, $activity, 'Activity_Group_Id', $tuple['activity_group_id']);
			buildNode($fet, $activity, 'Active',  $tuple['active']);
			buildNode($fet, $activity, 'Comments',  $tuple['comments']);	
			$count++;	
		}
	}	

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Buildings_List from buildings table
	function getBuildings(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT build_name FROM user_tables, buildings WHERE user_tables.user_table_id = buildings.user_table_id ";
		$query .= "AND buildings.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		
		$buildings = $fet->createElement('Buildings_List');
		$buildings = $root->appendChild($buildings);
		
		while($tuple = $queryResult->fetch_assoc()){
			$building = $fet->createElement('Building');
			$building = $buildings->appendChild($building);
			
			buildNode($fet, $building, 'Name', $tuple['build_name']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Rooms_List from rooms table
	function getRooms(&$fet, $root, $mysqli, $userTableID){
		$query = "SELECT room_name, build_name, capacity FROM user_tables, rooms, buildings WHERE user_tables.user_table_id = buildings.user_table_id ";
		$query .= "AND buildings.building_id = rooms.building_id  AND buildings.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);
		
		$rooms = $fet->createElement('Rooms_List');
		$rooms = $root->appendChild($rooms);
		
		while($tuple = $queryResult->fetch_assoc()){
			$room = $fet->createElement('Room');
			$room = $rooms->appendChild($room);
			
			buildNode($fet, $room, 'Name', $tuple['room_name']);
			buildNode($fet, $room, 'Building', $tuple['build_name']);
			buildNode($fet, $room, 'Capacity', $tuple['capacity']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Time_Constraints_List from constraints, time_constraints, and min_days_constraints tables
	function getTimeConstraints(&$fet, $root, $mysqli, $userTableID){
		$timeCons = $fet->createElement('Time_Constraints_List');
		$timeCons = $root->appendChild($timeCons);
		
		$basic = $fet->createElement('ConstraintBasicCompulsoryTime');
		$basic = $timeCons->appendChild($basic);
		
		buildNode($fet, $basic, 'Weight_Percentage', '100');
		buildNode($fet, $basic, 'Active', 'True');				
		buildNode($fet, $basic, 'Comments',  '');
					
		getMinDays($fet, $timeCons, $mysqli, $userTableID);
		
		getSameStartHour($fet, $timeCons, $mysqli, $userTableID);	
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve ConstraintMinDaysBetweenActivities for Time_Constraints_List
#from constraints, time_constraints, and min_days_constraints tables		
	function getMinDays(&$fet, $timeCons, $mysqli, $userTableID){
		$query = "SELECT weight_percentage, consecutive_if_same_day, num_of_activities, min_days, active, min_days_constraints.comments, ";
		$query .= "min_days_cons_id FROM user_tables, min_days_constraints ";
		$query .= "WHERE user_tables.user_table_id = min_days_constraints.user_table_id ";
		$query .= "AND min_days_constraints.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);

		while($tuple = $queryResult->fetch_assoc()){
			$minDaysCons = $fet->createElement('ConstraintMinDaysBetweenActivities');
			$minDaysCons = $timeCons->appendChild($minDaysCons);

			buildNode($fet, $minDaysCons, 'Weight_Percentage', $tuple['weight_percentage']);
			buildNode($fet, $minDaysCons, 'Consecutive_If_Same_Day', $tuple['consecutive_if_same_day']);
			buildNode($fet, $minDaysCons, 'Number_of_Activities', $tuple['num_of_activities']);
			
			
			#passes the min_days_const_id to retrieve all activity ids in min_days_for_activities table
			activityIDNode($fet, $minDaysCons, "min_days_for_activities", "min_days_cons_id", $tuple['min_days_cons_id'], $mysqli);

			buildNode($fet, $minDaysCons, 'MinDays', $tuple['min_days']);
			buildNode($fet, $minDaysCons, 'Active',  $tuple['active']);
			buildNode($fet, $minDaysCons, 'Comments', $tuple['comments']);	
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve ConstraintActivitiesSameStartingHour for Time_Constraints_List 
# from constraints, time_constraints and same_start_hour_constraint tables	
	function getSameStartHour(&$fet, $timeCons, $mysqli, $userTableID){
		$query = "SELECT weight_percentage, num_of_activities, active, same_start_hr_constraints.comments, same_start_cons_id ";
		$query .= "FROM user_tables, same_start_hr_constraints WHERE user_tables.user_table_id = same_start_hr_constraints.user_table_id ";
		$query .= "AND same_start_hr_constraints.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);

		while($tuple = $queryResult->fetch_assoc()){
			$minDaysCons = $fet->createElement('ConstraintActivitiesSameStartingHour');
			$minDaysCons = $timeCons->appendChild($minDaysCons);

			buildNode($fet, $minDaysCons, 'Weight_Percentage', $tuple['weight_percentage']);
			buildNode($fet, $minDaysCons, 'Number_of_Activities', $tuple['num_of_activities']);
		
			#passes the same_start_cons_id to retrieve all activity ids in activites_same_start table
			activityIDNode($fet, $minDaysCons, "activities_same_start", "same_start_cons_id", $tuple['same_start_cons_id'], $mysqli);

			buildNode($fet, $minDaysCons, 'Active', $tuple['active']);
			buildNode($fet, $minDaysCons, 'Comments', $tuple['comments']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Space_Constraints_List from constraints and space_constraints tables	
	function getSpaceConstraints(&$fet, $root, $mysqli, $userTableID){
		$spaceCons = $fet->createElement('Space_Constraints_List');
		$spaceCons = $root->appendChild($spaceCons);		

		$basic = $fet->createElement('ConstraintBasicCompulsorySpace');
		$basic = $spaceCons->appendChild($basic);
		
		buildNode($fet, $basic, 'Weight_Percentage', '100');
		buildNode($fet, $basic, 'Active', 'True');				
		buildNode($fet, $basic, 'Comments',  '');

		$query = "SELECT weight_percentage, activity_id, active, space_constraints.comments, space_cons_id FROM user_tables, space_constraints ";
		$query .= "WHERE user_tables.user_table_id = space_constraints.user_table_id AND space_constraints.user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);

		while($tuple = $queryResult->fetch_assoc()){
			$actPrefRooms = $fet->createElement('ConstraintActivityPreferredRooms');
			$actPrefRooms = $spaceCons->appendChild($actPrefRooms);

			buildNode($fet, $actPrefRooms, 'Weight_Percentage', $tuple['weight_percentage']);
			buildNode($fet, $actPrefRooms, 'Activity_Id', $tuple['activity_id']);
			
			getPrefRooms($fet, $actPrefRooms, $mysqli, $tuple['space_cons_id']);

			buildNode($fet, $actPrefRooms, 'Active', $tuple['active']);
			buildNode($fet, $actPrefRooms, 'Comments', $tuple['comments']);
		}
	}

#-------------------------------------------------------------------------------------------------
#querys the database to retrieve Preferred_Rooms according to space_cons_id from space_constraints and preferred_rooms tables
	function getPrefRooms(&$fet, $actPrefRooms, $mysqli, $space_cons_id){
		$qTables = "FROM space_constraints, preferred_rooms, rooms ";
		$qJoins = "WHERE space_constraints.space_cons_id = preferred_rooms.space_cons_id AND preferred_rooms.room_id = rooms.room_id ";
		$qSearch = "AND preferred_rooms.space_cons_id = ".$space_cons_id;

		$query = "SELECT room_name ".$qTables.$qJoins.$qSearch;
		$queryResult = $mysqli->query($query);

		$qCount = "SELECT count(*) AS \"count\" ".$qTables.$qJoins.$qSearch;
		$qCountRes = $mysqli->query($qCount);
		
		$count =$qCountRes ->fetch_assoc();
		
		buildNode($fet, $actPrefRooms, 'Number_of_Preferred_Rooms', $count['count']);

		while($tuple = $queryResult->fetch_assoc()){
			buildNode($fet, $actPrefRooms, 'Preferred_Room', $tuple['room_name']);
		}
	}

#-------------------------------------------------------------------------------------------------
#Main Program
#all queries are according to user_table_id, which is passed through session cookies
	if(isset($_SESSION['user']['user_table_id'])){
		$userTableID = $_SESSION['user']['user_table_id'];
		$userName = $_SESSION['user']['username'];
		$semester = $_SESSION['user']['semester'];
		echo $userTableID.' '.$semester;
	
		$query = "SELECT semester FROM user_tables WHERE user_table_id = ".$userTableID;
		$queryResult = $mysqli->query($query);

		$table = $queryResult->fetch_assoc();
		$tableName = $table['semester'];
		
		$fet = new DOMDocument('1.0', "UTF-8");
		$fet->formatOutput = true;
		
		$root = $fet->createElement('fet');
		$rootAttr = $fet->createAttribute('version');
		$rootAttr->value = '5.18.0';
		$root->appendChild($rootAttr);
		$fet->appendChild($root);
		
		getInstituition($fet, $root, $mysqli, $userTableID);
		getComments($fet, $root, $mysqli, $userTableID);
		getHours($fet, $root, $mysqli, $userTableID);
		getDays($fet, $root, $mysqli, $userTableID);
		getStudents($fet, $root, $mysqli, $userTableID);
		getTeachers($fet, $root, $mysqli, $userTableID);
		getSubjects($fet, $root, $mysqli, $userTableID);
		
		buildNode($fet, $root, 'Activity_Tags_List', '');
		
		getActivities($fet, $root, $mysqli, $userTableID);
		getBuildings($fet, $root, $mysqli, $userTableID);
		getRooms($fet, $root, $mysqli, $userTableID);
		getTimeConstraints($fet, $root, $mysqli, $userTableID);
		getSpaceConstraints($fet, $root, $mysqli, $userTableID);

		#closes the link to the database
		$mysqli->close();
		
		/*echo $fet->saveXML();*/
		$dir = "/home/mctom03/public_html/Project/uploads/".$userName."/".$semester."/";
		$dir .= $semester.$userTableID.".fet";
		$fet->save($dir);
	}
?>
