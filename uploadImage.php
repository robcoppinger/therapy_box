<?php
	
	// error_reporting(E_ALL);
	// ini_set('display_errors','1');	
	require('php/functions.php');
	$userId = $_GET["uid"];

	//Loop through all the files passed by AJAX
	foreach($_FILES as $index => $file){
		// echo $file['name'];
		$fileName = $file['name'];
		$sourcePath = $file['tmp_name']; 	
		$targetPath = "images/".$userId."/".$file['name'];
		$fileType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
		// if file is an image
		if ($fileType == 'jpg' || $fileType == 'png'){
			move_uploaded_file($file["tmp_name"], $targetPath);
			echo "true";

		} else {
			echo false;
		}

	}

	
?>