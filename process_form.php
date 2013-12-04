<?php

include 'PHP/validUser.php';
include 'PHP/db_connect.php';
include 'PHP/functions.php';


if( isset($_REQUEST['query'] )) {
	$i = $_GET['method'];

	switch($i) {
		case 'new':
			echo createNew($mysqli);
			break;
		case 'update':
			echo updateRow($mysqli, $_GET['id']);
			break;
		case 'delete':
			echo destroyRow($mysqli,$_GET['id']);
			break;
		case 'get-all':
			echo queryAll($mysqli);
			break;
		case 'get-single':
			echo querySingle($mysqli, $_GET['id']);
			break;
	}
	die();
}


						
