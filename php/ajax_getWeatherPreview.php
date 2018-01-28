<?php

error_reporting(E_ALL);
	ini_set('display_errors','1');
	require('functions.php');

	$lat = $_GET["lat"];
	$long = $_GET["long"];

	echo getWeatherPreview($lat,$long);

?>