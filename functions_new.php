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
			$sql = 'SELECT activities_id, duration,total_duration,activities.comments, teach_name, activities.teacher_id,activities.student_id, subj_name, activities.subj_id, year_name, user_tables.user_table_id '.
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
                    $row_array['subj_id'] = $row['subj_id'];
					$row_array['year_name'] = $row['year_name'];
                    $row_array['duration'] = $row['duration'];
                    $row_array['comment'] = $row['comments'];
                    $row_array['total_duration'] = $row['total_duration'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
		} else if ($_GET['query'] == 'space_constraints') {
            $sql = 'SELECT same_start_cons_id, weight_percentage, num_of_activities,active ' . 
            'FROM same_start_hr_constraints ' . 
            'WHERE user_table_id = ' . $user_table_id;

            if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']   = $row['same_start_cons_id'];
                    $row_array['weight'] = $row['weight_percentage'];
                    $row_array['num'] = $row['num_of_activities'];
                    $row_array['active'] = $row['active'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        } 
        else if ($_GET['query'] == 'activities_same_start') {
            $id = $_GET['id'];
            $sql = 'SELECT activities_id, teach_name, activities.teacher_id,activities.student_id, subj_name, activities.subj_id, year_name '.
            'FROM teachers, subjects, students,activities '.
            'WHERE activities.teacher_id = teachers.teacher_id '.
            'AND activities.subj_id = subjects.subj_id ' . 
            'AND activities.student_id = students.student_id '. 
            'AND activities.activities_id IN ('.
                'SELECT activities_id '.
                'FROM activities_same_start, same_start_hr_constraints '.
                'WHERE same_start_hr_constraints.same_start_cons_id = activities_same_start.same_start_cons_id '.
                'AND same_start_hr_constraints.same_start_cons_id = '. $id .')';

           if ($result = $mysqli->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $row_array['id']   = $row['activities_id'];
                    $row_array['teach_name']   = $row['teach_name'];
                    $row_array['teacher_id']   = $row['teacher_id'];
                    $row_array['student_id']   = $row['student_id'];
                    $row_array['subj_name'] = $row['subj_name'];
                    $row_array['subj_id'] = $row['subj_id'];
                    $row_array['year_name'] = $row['year_name'];
                    array_push($return_arr, $row_array);
                }
                /* free result set */
                $result->free();
            }
        }
        else if ($_GET['query'] == 'basic') {
            $table = $_GET['table'];

            $result = $mysqli->query("SELECT COUNT(*) FROM $table WHERE user_table_id = $user_table_id");
            $row = $result->fetch_row();
            return $row[0];

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
        $duration = $data[3]; $total_duration = $data[3];
        $comment = $data[4];

		$id = $_GET['id'];

		
        $stmt = $mysqli->prepare("UPDATE  activities SET  teacher_id =  ?,subj_id =  ?,student_id =  ?, duration = ?, total_duration = ?, comments = ? WHERE  activities.activities_id = ?");
        $stmt->bind_param("iiiiisi", $teacher_id, $subj_id, $student_id, $duration,$total_duration,$comment, $id);
        $stmt->execute();
        $nrows = $stmt->affected_rows;
    }
    else if ($_GET['query'] == 'space_constraints') {

        $mysqli->query("DELETE FROM activities_same_start WHERE same_start_cons_id = $id");
        $i = $mysqli->affected_rows;
        if ($i) {
            $data = json_decode($_GET['data']);
            $num = count($data);
            $weight_percentage = $_GET['weight'];

            $stmt = $mysqli->prepare("UPDATE  same_start_hr_constraints SET  num_of_activities =  ?, weight_percentage = ? WHERE  same_start_cons_id = ?");
            $stmt->bind_param("sss", $num, $weight_percentage, $id);
            $stmt->execute();
            $nrows = $stmt->affected_rows;
    
            if ($nrows) {

                foreach ($data as $d) {

                    $stmt = $mysqli->prepare('INSERT INTO activities_same_start (same_start_cons_id, activities_id) VALUES (?, ?)');
                    $stmt->bind_param("ss", $id, $d->id);
                    $stmt->execute();
                    $xx = $mysqli->insert_id;

                    if (!$xx) {
                        return "error";
                    }
                   
                }
                return '{"success":"updated"}';
            }

            return '{"error":[{"message":"Not deleted"}]}';
        }
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
        $duration = $data[3];
        $comment = $data[4];
		
		$stmt      = $mysqli->prepare('INSERT INTO `activities` (`activities_id`, `duration`, `total_duration`, `active`, `teacher_id`, `subj_id`, `student_id`, `user_table_id`, `activity_group_id`, comments) VALUES (NULL, ?, ?, "true", ?, ?, ?, ?, 0,?);');
		$stmt->bind_param("iiiiiis",$duration, $duration,$teacher_id, $subj_id, $student_id,$user_table_id, $comment);
		$stmt->execute();
    } else if ($_GET['query'] == 'space_constraints') {
        $data = json_decode($_GET['data']);
        $weight_percentage = $_GET['weight'];
        $trueFalse = 'true';
        $str = '';
        $num = count($data);
        //return "$weight_percentage, $num, $trueFalse, $user_table_id";

        $stmt = $mysqli->prepare('INSERT INTO same_start_hr_constraints (weight_percentage, num_of_activities, active, comments, user_table_id) VALUES (?, ?, ?, NULL, ?)');
        $stmt->bind_param("ssss", $weight_percentage, $num, $trueFalse, $user_table_id);

        $stmt->execute();


        $nrows = $mysqli->insert_id;
        // if (!$nrows) {
        //     return '{"error":[{"message":"Not inserted"}]}';
        // }
        // return $nrows;

        $str='';
        foreach ($data as $d) {

            $stmt = $mysqli->prepare('INSERT INTO activities_same_start (same_start_cons_id, activities_id) VALUES (?, ?)');
            $stmt->bind_param("ss", $nrows, $d->id);
            $stmt->execute();
            $xx = $mysqli->insert_id;
            if (!$xx) {
                return "error";
            }
           
        }

        return '{"success":"true"}';
    } else if ($_GET['query'] == 'basic') {
        $data = $_GET['data'];
        $table = $_GET['table'];
        $num=0;
        
        if ($table == 'days') {
            //return $data . " days!";
            $mysqli->query("DELETE FROM days WHERE user_table_id = $user_table_id");

            //if ($data == 2) {
                $mysqli->query("INSERT INTO days (day_name, user_table_id) VALUES ('Monday',  $user_table_id)");
                $mysqli->query("INSERT INTO days (day_name, user_table_id) VALUES ('Tuesday',  $user_table_id)");
                $num++;
                $num++;
                
            //} 
            if ($data >= 3) {
                $mysqli->query("INSERT INTO days (day_name, user_table_id) VALUES ('Wednesday',  $user_table_id)");
                $num++;
            } 
            if ($data >= 4) {
                $mysqli->query("INSERT INTO days (day_name, user_table_id) VALUES ('Thursday',  $user_table_id)");
                $num++;
            } 
            if ($data >= 5) {
                $mysqli->query("INSERT INTO days (day_name, user_table_id) VALUES ('Friday',  $user_table_id)");
                $num++;
            } 
            if ($data >= 6) {
                $mysqli->query("INSERT INTO days (day_name, user_table_id) VALUES ('Saturday',  $user_table_id)");
                $num++;
            } 

        } else if($table == 'hours') {
            $mysqli->query("DELETE FROM hours WHERE user_table_id = $user_table_id");

            $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('8:30', $user_table_id)");
            $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('9:30', $user_table_id)");
            $num++;
            $num++;

            if ($data >= 3) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('10:30', $user_table_id)");
                $num++;
            }
            if ($data >= 4) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('11:30', $user_table_id)");
                $num++;
            }
            if ($data >= 5) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('12:30', $user_table_id)");
                $num++;
            }
            if ($data >= 6) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('13:30', $user_table_id)");
                $num++;
            }
            if ($data >= 7) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('14:30', $user_table_id)");
                $num++;
            }
            if ($data >= 8) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('15:30', $user_table_id)");
                $num++;
            }
            if ($data >= 9) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('16:30', $user_table_id)");
                $num++;
            }
            if ($data >= 10) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('17:30', $user_table_id)");
                $num++;
            }
            if ($data >= 11) {
                $mysqli->query("INSERT INTO hours (hour_name, user_table_id) VALUES ('18:30', $user_table_id)");
                $num++;
            }
        }
        $r = array(
            'table' => $table, 
            'count' => $num
        );

        return json_encode($r);
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
    } else if ($_GET['query'] == 'space_constraints') {
        $mysqli->query("DELETE FROM activities_same_start WHERE same_start_cons_id = $id");
        $mysqli->query("DELETE FROM same_start_hr_constraints WHERE same_start_cons_id = $id");
    }
    
    $i = $mysqli->affected_rows;
    if (!$i) {
        return '{"error":[{"message":"Not deleted"}]}';
    }
    return $i;
}
