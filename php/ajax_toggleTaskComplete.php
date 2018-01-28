<?php

error_reporting(E_ALL);
	ini_set('display_errors','1');
	require('dbconn.php');

	$taskId = $_POST["tid"];
	//$userId = $_POST["uid"];

	$conn = new Dbconn();
	$conn->toggleTaskComplete($taskId);
	echo true;

?>