<?php

error_reporting(E_ALL);
	ini_set('display_errors','1');
	require('dbconn.php');

	$userId = $_POST["uid"];
	$task = $_POST["tsk"];

	echo $task;

	$conn = new Dbconn();
	$conn->addTask($userId,$task);

	echo $conn->getAllTasks($userId);
	

?>