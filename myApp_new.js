
$(document).ready(function() {
 
 //When page loads...
 $(".tab_content").hide(); //Hide all content
 $("ul.tabs li:first").addClass("active").show(); //Activate first tab
 $(".tab_content:first").show(); //Show first tab content
 
 //On Click Event
 $("ul.tabs li").click(function() {
 
  $("ul.tabs li").removeClass("active"); //Remove any "active" class
  $(this).addClass("active"); //Add "active" class to selected tab
  $(".tab_content").hide(); //Hide all tab content
 
  var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
  $(activeTab).fadeIn('fast'); //Fade in the active ID content
  return false;
 });
 
});




var app = angular.module("myApp", []);
app.controller("AppController", function( $scope, myHttp ) {
	$scope.teachers = [];
	$scope.subjects = [];
	$scope.students = [];
	$scope.buildings = [];
	$scope.data = [];

	$scope.getTeachers = function() {
		myHttp.query({
            'query': 'teachers',
            'method': 'get-all'
        }).success(function (data) {
            $scope.teachers = data;
            $scope.act_teacher = $scope.teachers[0];
        });
	}

	$scope.getSubjects = function() {
		myHttp.query({
            'query': 'subjects',
            'method': 'get-all'
        }).success(function (data) {
            $scope.subjects = data;
        });
	}

	$scope.getStudents = function () {
        myHttp.query({
            'query': 'students',
            'method': 'get-all'
        }).success(function (data) {
            $scope.students = data;
            $scope.act_student = $scope.students[0];
        });
    }

	$scope.getTeachers();
	$scope.getSubjects();
	$scope.getStudents();
});
//////////////////////////////
//		TeachersCtrl		//
//////////////////////////////
app.controller('TeachersCtrl', function( $scope, myHttp ) {
	//$scope.teachers = [];
	$scope.addItem = function() {
		var data = $scope.teacherName;
		if(data) {
			myHttp.query({
	            'query': 'teachers',
	            'method': 'new',
	            'name': data
	        }).success(function (res) {
	            $scope.teachers.push({
	                name: data,
	                done: false,
	                id: res });
	            $scope.teacherName = '';
	        });
        }
	}

	$scope.editTeacher = function(id) {
		$scope.template = {
            name: 'detail.html',
            url: 'templates/detail.html'
        };

        for (var i = $scope.teachers.length - 1; i >= 0; i--) {
            if ($scope.teachers[i].id === id) {
                //$scope.item = $scope.teachers[i];
                $scope.item = angular.copy($scope.teachers[i]);
                $scope.temp = angular.copy($scope.item);
            }
        };
	}
	$scope.isClean = function () {
        return angular.equals($scope.temp, $scope.item);
    }

	$scope.cancel = function () {
        $scope.template = '';
    }

    $scope.save = function () {
    	if ($scope.item.name) {
    		console.log('not empty');
    	
	    	myHttp.query({
	    		'query': 'teachers',
	            'method': 'update',
	            'id': $scope.item.id,
	            'name': $scope.item.name
	    	}).success(function(res){
	    		$scope.getTeachers();
	    		$scope.template = '';
	    		console.log(res);
	    	})
    	};
    }

    $scope.destroy = function () {
    	console.log($scope.item);
    	myHttp.query({
       		'query': 'teachers',
            'method': 'delete',
            'id': $scope.item.id
        }).success(function (data) {
        	$scope.getTeachers();
        	$scope.template = '';
        });
    }

});

//////////////////////////////
//		ActivitiesCtrl		//
//////////////////////////////
app.controller('ActivitiesCtrl', function( $scope, myHttp ) {
	$scope.button_value = 'Save';
	$scope.getActivities = function () {
        myHttp.query({
            'query': 'activities',
            'method': 'get-all'
        }).success(function (data) {
            $scope.activities = data;
        });
    }

	$scope.removeS = function () {
		for (var i = $scope.chosenStudents.length - 1; i >= 0; i--) {
			if($scope.chosenStudents[i] == $scope.chosenS) {
				$scope.chosenStudents.splice($scope.chosenStudents.indexOf($scope.chosenS),1);
				$scope.chosenS = $scope.chosenStudents[0];
				return;
			}
		}
	}
	
	$scope.removeT = function () {
		for (var i = $scope.chosenTeachers.length - 1; i >= 0; i--) {
			if($scope.chosenTeachers[i] == $scope.chosenT) {
				$scope.chosenTeachers.splice($scope.chosenTeachers.indexOf($scope.chosenT),1);
				$scope.chosenT = $scope.chosenTeachers[0];
				return;
			}
		}
	}

	$scope.chosenTeachers = [];
	$scope.chosenStudents = [];
	
	$scope.updateT = function() {
		for (var i = $scope.chosenTeachers.length - 1; i >= 0; i--) {
			if($scope.chosenTeachers[i] == $scope.act_teacher) {
				console.log("matches");	
				return;
			}
		}
		
		$scope.chosenTeachers.push($scope.act_teacher);
		$scope.chosenT = $scope.chosenTeachers[0];
	}
	
	$scope.updateS = function() {
		for (var i = $scope.chosenStudents.length - 1; i >= 0; i--) {
			if($scope.chosenStudents[i] == $scope.act_student) {			
				console.log("S matches");	
				return;
			}			
		}
		$scope.chosenStudents.push($scope.act_student);
		$scope.chosenS = $scope.chosenStudents[0];
	}
	
	$scope.editThis = function(id) {
		$scope.chosenS = '';
		$scope.chosenT = '';
		$scope.chosenStudents = [];
		$scope.chosenTeachers = [];
		$scope.subject = $scope.subjects[0];

		for (var i = $scope.activities.length - 1; i >= 0; i--) {
			console.log(i);
			if ($scope.activities[i].id == id) {
				//console.log('match');
				$scope.tmp_id = id;
				$scope.chosenStudents.push({year_name:$scope.activities[i].year_name, id:$scope.activities[i].student_id});
				$scope.chosenTeachers.push({name:$scope.activities[i].teach_name, id:$scope.activities[i].teacher_id});
				$scope.chosenS = $scope.chosenStudents[0];
				$scope.chosenT = $scope.chosenTeachers[0];
				console.log($scope.chosenStudents);
				for (var j = $scope.subjects.length - 1; j >= 0; j--) {
					if($scope.subjects[j].name == $scope.activities[i].subj_name) {
						$scope.subject = $scope.subjects[j];
						continue;
					}
				}
				
				$scope.button_value = 'Update';
				console.log('match');
				$scope.checked = true;
				return;
			};
			
		};
	}
	
	$scope.cancel = function() {
		$scope.getActivities();
		$scope.getSubjects();
		$scope.chosenS = '';
		$scope.chosenT = '';
		$scope.chosenStudents = [];
		$scope.chosenTeachers = [];
		$scope.button_value = 'Save';
		$scope.checked = false;

	}
	
	$scope.deleteAct = function() {
		myHttp.query({
            'query'	: 'activities',
            'method': 'delete',
			'id':$scope.tmp_id
			})
			.success(function (result) {
				$scope.getActivities();
				$scope.button_value = 'Save';
				$scope.checked = false;
			});
		
	}
	
	$scope.saveAct = function() {
		var data = [
			$scope.chosenTeachers,
			$scope.chosenStudents,
			$scope.subject
		];	
		
		if($scope.button_value == 'Update'){
		console.log('update');
			myHttp.query({
            'query'	: 'activities',
            'method': 'update',
            'data'	: data,
			'id':$scope.tmp_id
			})
			.success(function (result) {
				$scope.getActivities();
				$scope.button_value = 'Save';
				$scope.checked = false;
				$scope.tmp_id = '';
			});
		
		} else {
			myHttp.query({
				'query'	: 'activities',
				'method': 'new',
				'data'	: data
			})
			.success(function (result) {
				$scope.getActivities();
			});
		}
		
		$scope.chosenS = '';
		$scope.chosenT = '';
		$scope.chosenStudents = [];
		$scope.chosenTeachers = [];
	}

	$scope.isOK = function() {
		if ($scope.chosenStudents.length && $scope.chosenTeachers.length && $scope.subject) {
			return 0;
		};
		return 1;
	}
    $scope.getActivities();
});
//////////////////////////////
//		SubjectsCtrl		//
//////////////////////////////
app.controller('SubjectsCtrl', function( $scope, myHttp ) {
    $scope.addItem = function () {
        var tmp_name = $scope.subjectName;
        if (tmp_name) {
	        myHttp.query({
	            'query': 'subjects',
	            'method': 'new',
	            'name': tmp_name
	        }).success(function (data) {
	            $scope.subjects.push({
	                name: tmp_name,
	                done: false,
	                id: data
	            });
	            $scope.subjectName = '';
	        });
        };
    }

    $scope.editThis = function (id) {
        $scope.template = {
            name: 'form_nko.html',
            url: 'templates/detail.html'
        };

        for (var i = $scope.subjects.length - 1; i >= 0; i--) {
            if ($scope.subjects[i].id === id) {
                $scope.item = angular.copy($scope.subjects[i]);
                $scope.temp = angular.copy($scope.item);
            }
        };
    }

    $scope.isClean = function () {
        return angular.equals($scope.temp, $scope.item);
    };

    $scope.cancel = function () {
        $scope.template = '';
    };

    $scope.save = function () {
    	if ($scope.item.name) {
	        myHttp.query({
	            'query': 'subjects',
	            'method': 'update',
	            'id': $scope.item.id,
	            'name': $scope.item.name
	        }).success(function (data) {
	            $scope.getSubjects();
	            $scope.template = '';
	        });
        };
    }

    $scope.destroy = function () {
        myHttp.query({
            'query': 'subjects',
            'method': 'delete',
            'id': $scope.item.id
        }).success(function (data) {
            $scope.getSubjects();
            $scope.template = '';
        });
    }

});
//////////////////////////////
//		Students  			//
//////////////////////////////
app.controller('StudentsCtrl', function ($scope, myHttp) {
    $scope.addItem = function () {
        var tmp_name = $scope.studentName;
        var tmp_amount = $scope.studentAmount;
        if (tmp_name && tmp_amount) {
	        myHttp.query({
	            'query': 'students',
	            'method': 'new',
	            'year_name': tmp_name,
	            'num_students': tmp_amount
	        }).success(function (data) {
	            console.log(data);
	            $scope.students.push({
	                id: data,
	                year_name: tmp_name,
	                num_students: tmp_amount
	            });
	            $scope.studentName = '';
	            $scope.studentAmount = '';

	        });
        };

    };
    $scope.isClean = function () {
        return angular.equals($scope.temp, $scope.item);
    };

    $scope.cancel = function () {
        $scope.template = '';
    };

    $scope.editThis = function (id) {
        $scope.template = {
            name: 'form_nko.html',
            url: 'templates/detail_s.html'
        };
        for (var i = $scope.students.length - 1; i >= 0; i--) {
            if ($scope.students[i].id === id) {
                $scope.item = angular.copy($scope.students[i]);
                $scope.temp = angular.copy($scope.item);
            }
        };
    }

    $scope.destroy = function () {
        myHttp.query({
            'query': 'students',
            'method': 'delete',
            'id': $scope.item.id
        })
            .success(function (data) {
                $scope.getStudents();
                $scope.template = '';
            });
    }

    $scope.save = function () {
    	if ($scope.item.year_name && $scope.item.num_students) {
	        myHttp.query({
	            'query': 'students',
	            'method': 'update',
	            'id': $scope.item.id,
	            'year_name': $scope.item.year_name,
	            'num_students': $scope.item.num_students
	        }).success(function (data) {
	            $scope.getStudents();
	            $scope.template = '';
	        });
        };
    };


});
//////////////////////////////
//		SPACE   			//
//////////////////////////////
app.controller('SpaceCtrl', function( $scope, myHttp ) {
	
    $scope.buildings = [];
    $scope.building = [];
    $scope.data = [];
    $scope.rooms = [];
    $scope.update = function () {
        //console.log("B id ");
        $scope.rooms = [];
        for (var i = 0; i < $scope.data.length; i++) {
            if ($scope.data[i].building_id == $scope.building.id) {
                $scope.rooms.push($scope.data[i]);
            }
        }
        $scope.room = $scope.rooms[0];
    }
    $scope.getBuildings = function () {
        myHttp.query({
            'query': 'buildings',
            'method': 'get-all'
        }).success(function (data) {
            $scope.buildings = data;
            $scope.building = $scope.buildings[0];
            $scope.getRooms();
        });

    }
    $scope.getRooms = function() {
    	myHttp.query({
            'query': 'rooms',
            'method': 'get-all'
        }).success(function (data) {
            $scope.data = data;
			$scope.update();
        });
    }
    $scope.getBuildings();


    $scope.addRoom = function () {
        var tmp_id = '';
        if ($scope.roomID) {
            tmp_id = $scope.roomID;
            if ($scope.roomName && $scope.roomCapacity) {
	            for (var i = $scope.data.length - 1; i >= 0; i--) {
	                if ($scope.data[i].id == tmp_id) {
	                    $scope.data[i].name = $scope.roomName;
	                    $scope.data[i].capacity = $scope.roomCapacity;

	                    myHttp.query({
	                        'query': 'rooms',
	                        'method': 'update',
	                        'name': $scope.roomName,
	                        'capacity': $scope.roomCapacity,
	                        'id': tmp_id
	                    }).success(function (data) {
	                        console.log(data);
	                    });
	                }
	            };
        	};
        } else {

            var tmp_name = $scope.roomName;
            var tmp_c = $scope.roomCapacity;
            var tmp_bid = $scope.building.id;
            if (tmp_name && tmp_c) {
	            myHttp.query({
	                'query': 'rooms',
	                'method': 'new',
	                'name': tmp_name,
	                'build_id': tmp_bid,
	                'capacity': tmp_c
	            }).success(function (data) {
	                console.log(data);
	            });

	            $scope.data.push({
	                name: $scope.roomName,
	                capacity: $scope.roomCapacity,
	                building_id: $scope.building.id,
	                id: Math.floor((Math.random() * 100) + 1),
	                done: false
	            });
            };
        }
        console.log($scope.buildings);
        $scope.update();
        $scope.roomID = '';
        $scope.roomName = '';
        $scope.roomCapacity = '';
    }
    $scope.deleteRoom = function (id) {
        console.log("room: " + id);
        myHttp.query({
            'query': 'rooms',
            'method': 'delete',
            'id': id
        }).success(function (data) {
            console.log(data);
        });
        $scope.data.splice($scope.data.indexOf($scope.room), 1);
        $scope.update();

    };

    $scope.show = function () {
        return !$scope.rooms.length;
    }
    $scope.showB = function () {
        return !$scope.buildings.length;
    }

    $scope.addItem = function () {
        var tmp_id = '';
        if ($scope.buildingID) {
            tmp_id = $scope.buildingID;
            if ($scope.buildingInput) {
	            console.log(tmp_id);
	            for (var i = 0; i < $scope.buildings.length; i++) {
	                if ($scope.buildings[i].id == tmp_id) {
	                    myHttp.query({
	                        'query': 'buildings',
	                        'method': 'update',
	                        'name': $scope.buildingInput,
	                        'id': tmp_id
	                    }).success(function (data) {

	                        console.log($scope.buildings);
	                    });
	                    $scope.buildings[i].name = $scope.buildingInput;
	                }
	            }
            };
        } else {
            var tmp_name = $scope.buildingInput;
            if (tmp_name) {
	            myHttp.query({
	                'query': 'buildings',
	                'method': 'new',
	                'name': tmp_name
	            }).success(function (data) {
	                $scope.buildings.push({
	                    name: tmp_name,
	                    id: data,
	                    done: true
	                });
	            });
            };
        }
        $scope.buildingInput = '';

        $scope.buildingID = '';
        $scope.building = $scope.buildings[0];

    };
    $scope.editRoom = function () {
        console.log($scope.building);
        $scope.roomName = $scope.room.name;
        $scope.roomCapacity = $scope.room.capacity;
        $scope.roomID = $scope.room.id;

    };

    $scope.editItem = function () {
        console.log($scope.room);
        $scope.buildingInput = $scope.building.name;
        $scope.buildingID = $scope.building.id;
    };
    $scope.deleteItem = function () {
        console.log($scope.building);
        for (var i = 0; i < $scope.data.length; i++) {
            if ($scope.data[i].building_id == $scope.building.id) {
                console.log('alert');
                if (confirm('Are you sure you want to delete building, building has rooms?')) {
                    $scope.buildings.splice($scope.buildings.indexOf($scope.building), 1);

                    var q = $scope.building.id;
                    var q1 = $scope.data.length;
                    for (var j = q1 - 1; j >= 0; j--) {
                        if ($scope.data[j].building_id === q) {
                            console.log('match ' + j);
                            $scope.data.splice(j, 1);
                            //$scope.deleteRoom($scope.data[j].id);
                        }
                        //console.log($scope.data[j].building_id);
                    };
                    console.log($scope.data);
                    $scope.building = $scope.buildings[0];
                    $scope.update();
                    return;
                } else {
                    return;
                }
            }
        }
        myHttp.query({
            'query': 'buildings',
            'method': 'delete',
            'id': $scope.building.id
        }).success(function (data) {
            console.log(data);
        });

        $scope.buildings.splice($scope.buildings.indexOf($scope.building), 1);
        $scope.building = $scope.buildings[0];
        $scope.update();
    };

});

app.factory('myHttp', function($http){
	return {
		query: function(data) {
			return $http({
				method:'GET',
				headers:{'Content-Type': 'application/x-www-form-urlencoded'},
				params:data,
				url:'process_form.php'
			});
		}
	}
});

