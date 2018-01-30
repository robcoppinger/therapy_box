<?php
/**
*	returns an HTML list of all teams that a specified team beat
*/
error_reporting(E_ALL);
	// ---------------------------------------------------- retrieve the csv document
	$file = fopen('http://www.football-data.co.uk/mmz4281/1718/I1.csv', 'r');
	$count = 0; 						// initiate counter
	$team = $_GET["team"]; 				// get the team name variable
	$teamUpper = strtoupper($team); 	// set to upper
	$teams = array(); 					// declare empty array of beaten teams

	// ------------------------------------------- loop through each line of the file
	while (($line = fgetcsv($file)) !== FALSE) {
		// ------------------------------------- line[2]->home team; 
		// ------------------------------------- line[3]->away team; 
		// ------------------------------------- line[9]->which team won (H/A)
	   	if (strtoupper($line[2]) == $teamUpper && $line[9] == "H"){
	   		// user's team is home team & home team won
	   		array_push($teams, $line[3]); // add away team to array
	   	} else if (strtoupper($line[3]) == $teamUpper && $line[9] == "A"){
	   		// user's team is away team & away team won
	   		array_push($teams, $line[2]); // add home team to list
	   	} 
	   // echo $line[2]."<br>";
	}
	fclose($file);
	// ---------------------------------------------------------------- Generate HTML
	$output = "<p><b>".$team."</b> beat the following teams: </p>";
	$count = 0;
	foreach ($teams as $value) {
		$output .= '
			<div class="row taskRow">
        		<input class="form-control" style="cursor:text" id="'.$value.'" type="text" name="" value = "'.$value.'" disabled>	
        	</div>
		';
		$count++;
	}
	// ----------------------------------------------------------- return HTML content
	if ($count == 0){
		// array is empty - notify the user
		echo "<p>'<b>".$team."</b>' was not found, or did not win any matches.</p>";
	} else {
		// return the HTML list of teams
		echo $output;	
	}
	
?>