<?php
function queryAll($mysqli)
{
    if (isset($_SESSION['user']['username'], $_SESSION['user']['user_table_id'])) {
        $user_id      = $_SESSION['user']['username'];
        $user_table_id = $_SESSION['user']['user_table_id'];
        $return_arr    = array();
        if ($_GET['query'] == 'teachers') {
            $sql = 
            'SELECT teacher_id, teach_name ' . 
            'FROM teachers, user_tables ' . 
            'WHERE user_tables.user_table_id = teachers.user_table_id ' . 
            'AND user_tables.user_table_id = ' . $user_table_id;
            
            if ($result = $mysqli->query($sql)) {
                
                /* fetch associative array */
                //var_dump($result);die();
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']   = $row['teacher_id'];
                    $row_array['name'] = $row['teach_name'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        } else if ($_GET['query'] == 'subjects') {
            $sql = 
            'SELECT subj_id, subj_name ' . 
            'FROM subjects, user_tables ' . 
            'WHERE user_tables.user_table_id = subjects.user_table_id ' . 
            'AND user_tables.user_table_id = ' . $user_table_id;

            if ($result = $mysqli->query($sql)) {
                
                /* fetch associative array */
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']   = $row['subj_id'];
                    $row_array['name'] = $row['subj_name'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        } else if ($_GET['query'] == 'students') {
            $sql = 'SELECT student_id, year_name, num_students ' . 
            'FROM students, user_tables ' . 
            'WHERE user_tables.user_table_id = students.user_table_id ' . 
            'AND user_tables.user_table_id = ' . $user_table_id;

            if ($result = $mysqli->query($sql)) {
                
                /* fetch associative array */
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']           = $row['student_id'];
                    //$row_array['group_id'] = $row['group_id'];
                    $row_array['num_students'] = $row['num_students'];
                    $row_array['year_name']    = $row['year_name'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        } else if ($_GET['query'] == 'rooms') {
            $sql = 'SELECT room_id, room_name,capacity, building_id ' . 
            'FROM rooms, user_tables ' . 
            'WHERE rooms.user_table_id = user_tables.user_table_id ' . 
            'AND user_tables.user_table_id = ' . $user_table_id;

            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']          = $row['room_id'];
                    $row_array['name']        = $row['room_name'];
                    $row_array['building_id'] = $row['building_id'];
                    $row_array['capacity']    = $row['capacity'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        } else if ($_GET['query'] == 'buildings') {
            $sql = 'SELECT building_id, build_name ' . 
            'FROM buildings, user_tables ' . 
            'WHERE buildings.user_table_id = user_tables.user_table_id ' . 
            'AND user_tables.user_table_id = ' . $user_table_id;

            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']   = $row['building_id'];
                    $row_array['name'] = $row['build_name'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        } else if ($_GET['query'] == 'activities') {
			$sql = 'SELECT activities_id, teach_name, activities.teacher_id,activities.student_id, subj_name, year_name, user_tables.user_table_id '.
			'FROM teachers, subjects, students,activities, user_tables '.
			'WHERE activities.teacher_id = teachers.teacher_id '.
			'AND activities.subj_id = subjects.subj_id '.
			'AND activities.student_id = students.student_id '.
			'AND activities.user_table_id = user_tables.user_table_id '.
			'AND user_tables.user_table_id = '. $user_table_id;
			if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
					$row_array['id']   = $row['activities_id'];
                    $row_array['teach_name']   = $row['teach_name'];
					$row_array['teacher_id']   = $row['teacher_id'];
					$row_array['student_id']   = $row['student_id'];
                    $row_array['subj_name'] = $row['subj_name'];
					$row_array['year_name'] = $row['year_name'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
			
			
		}
        return json_encode($return_arr);
    }
}

function updateRow($mysqli, $id) {

    if ($_GET['query'] == 'teachers') {
        $name = $_GET['name'];
        $stmt = $mysqli->prepare("UPDATE teachers SET teach_name = ? WHERE teacher_id = ?");
        $stmt->bind_param("ss", $name, $id);
        $stmt->execute();
    } else if ($_GET['query'] == 'subjects') {
        $name = $_GET['name'];
        $stmt = $mysqli->prepare("UPDATE subjects SET subj_name = ? WHERE subj_id = ?");
        $stmt->bind_param("ss", $name, $id);
        $stmt->execute();
    } else if ($_GET['query'] == 'students') {
        
        $year_name    = $_GET['year_name'];
        $num_students = $_GET['num_students'];
        //$group_id = $_GET['group_id'];
        
        $stmt = $mysqli->prepare("UPDATE students SET year_name = ?, num_students = ? WHERE student_id = ?");
        $stmt->bind_param("sss", $year_name, $num_students, $id);
        $stmt->execute();
    } else if ($_GET['query'] == 'buildings') {
        $name = $_GET['name'];
        
        $stmt = $mysqli->prepare("UPDATE buildings SET build_name = ? WHERE building_id = ?");
        $stmt->bind_param("ss", $name, $id);
        $stmt->execute();
    } else if ($_GET['query'] == 'rooms') {
        $room_name = $_GET['name'];
        $capacity  = $_GET['capacity'];
        
        $stmt = $mysqli->prepare("UPDATE rooms SET room_name = ?, capacity = ? WHERE room_id = ?");
        $stmt->bind_param("sss", $room_name, $capacity, $id);
        $stmt->execute();
    } else if ($_GET['query'] == 'activities') {
		
		$data = json_decode($_GET['data']);
		$teacher_id = $data[0][0]->id;
		$student_id = $data[1][0]->id;
		$subj_id = $data[2]->id;
		$id = $_GET['id'];
		
        $stmt = $mysqli->prepare("UPDATE  activities SET  teacher_id =  ?,subj_id =  ?,student_id =  ? WHERE  activities.activities_id = ?");
        $stmt->bind_param("ssss", $teacher_id, $subj_id, $student_id, $id);
        $stmt->execute();
    }
    
    $nrows = $stmt->affected_rows;
    
    if (!$nrows) {
        return '{"error":[{"message":"Not updated"}]}';
    }
    return $nrows;
    
}

function createNew($mysqli)
{

    $name          = $_GET['name'];
    $user_table_id = $_SESSION['user']['user_table_id'];
    $user_id       = $_SESSION['user']['username'];
    if ($_GET['query'] == 'teachers') {
        $stmt = $mysqli->prepare('INSERT INTO teachers VALUES (NULL, ?, ?)');
        $stmt->bind_param("ss", $name, $user_table_id);
        $stmt->execute();
        
    } else if ($_GET['query'] == 'subjects') {
        $stmt = $mysqli->prepare('INSERT INTO subjects VALUES (NULL, ?, ?)');
        $stmt->bind_param("ss", $name, $user_table_id);
        $stmt->execute();
        
    } else if ($_GET['query'] == 'students') {
        $year_name    = $_GET['year_name'];
        $num_students = $_GET['num_students'];
        //$group_id = $_GET['group_id'];
        
        $stmt = $mysqli->prepare('INSERT INTO students (user_table_id, year_name, num_students) VALUES(?, ?, ?)');
        $stmt->bind_param("sss", $user_table_id, $year_name, $num_students);
        $stmt->execute();
    } else if ($_GET['query'] == 'buildings') {
        $building_name = $_GET['name'];
        $stmt          = $mysqli->prepare('INSERT INTO buildings (build_name, user_table_id) VALUES (?, ?)');
        $stmt->bind_param("ss", $building_name, $user_table_id);
        $stmt->execute();
    } else if ($_GET['query'] == 'rooms') {
        $room_name = $_GET['name'];
        $capacity  = $_GET['capacity'];
        $build_id  = $_GET['build_id'];
        $stmt      = $mysqli->prepare('INSERT INTO rooms (room_name, capacity, building_id,user_table_id) VALUES (?,?,?,?)');
        $stmt->bind_param("ssss", $room_name, $capacity, $build_id, $user_table_id);
        $stmt->execute();
    } else if ($_GET['query'] == 'activities') {
		$data = json_decode($_GET['data']);
		$teacher_id = $data[0][0]->id;
		$student_id = $data[1][0]->id;
		$subj_id = $data[2]->id;
		
		
		$stmt      = $mysqli->prepare('INSERT INTO `activities` (`activities_id`, `duration`, `total_duration`, `active`, `teacher_id`, `subj_id`, `student_id`, `user_table_id`, `activity_group_id`) VALUES (NULL, 21, 12, "true", ?, ?, ?, ?, NULL);');
		$stmt->bind_param("iiii",$teacher_id, $subj_id, $student_id,$user_table_id);
		$stmt->execute();
    }
    
    $nrows = $mysqli->insert_id;
    if (!$nrows) {
        return '{"error":[{"message":"Not inserted"}]}';
    }
    return $nrows;
    
    
    
}

function destroyRow($mysqli, $id)
{
    
    if ($_GET['query'] == 'teachers') {
        $mysqli->query("DELETE FROM teachers WHERE teacher_id = $id");
    } else if ($_GET['query'] == 'subjects') {
        $mysqli->query("DELETE FROM subjects WHERE subj_id = $id");
    } else if ($_GET['query'] == 'students') {
        $mysqli->query("DELETE FROM students WHERE student_id = $id");
    } else if ($_GET['query'] == 'rooms') {
        $mysqli->query("DELETE FROM rooms WHERE room_id = $id");
    } else if ($_GET['query'] == 'buildings') {
        $mysqli->query("DELETE FROM buildings WHERE building_id = $id");
    } else if ($_GET['query'] == 'activities') {
        $mysqli->query("DELETE FROM activities WHERE activities_id = $id");
    }
    
    $i = $mysqli->affected_rows;
    if (!$i) {
        return '{"error":[{"message":"Not deleted"}]}';
    }
    return $i;
}
