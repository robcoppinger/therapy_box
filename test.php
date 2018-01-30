<?php
    
	$file = fopen('http://www.football-data.co.uk/mmz4281/1718/I1.csv', 'r');
	$count = 0;
	$team = "JUVENTUS";
	$teams = array();

	while (($line = fgetcsv($file)) !== FALSE) {
   		array_push($teams, $line[2]);	   	
	}
	fclose($file);
	$unique = array_unique($teams);
	foreach ($unique as $value) {
		echo $value."<br>";
	}
	//print_r($teams);
	//echo $count;

?>