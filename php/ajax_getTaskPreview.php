<?php

error_reporting(E_ALL);
	ini_set('display_errors','1');
	require('dbconn.php');

	$userId = $_POST["uid"];
	$conn = new Dbconn();
	$result = $conn->getTaskPreview($userId);
	echo $result;

?>