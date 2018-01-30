<?php

	error_reporting(E_ALL);
	ini_set('display_errors','1');
	session_start();
	require('php/functions.php');

	if (!isset($_SESSION['userId'])){
		header('Location: login.php');
	}
	// ----------------------------------------------------- fetch file contents
	$file = fopen('http://www.football-data.co.uk/mmz4281/1718/I1.csv', 'r');
	$teams = array(); // declare empty array
	// loop through the file lines
	while (($line = fgetcsv($file)) !== FALSE) {
		// add all home team names to the array
   		array_push($teams, $line[2]);	   	
	}
	fclose($file);	// close the file
	$uniqueTeams = array_unique($teams); // create array of unique teams
	array_shift($uniqueTeams); // remove heading value

?>

<!DOCTYPE html>
<html>
	
	<?php head(); ?>
	

<body>

	
	<div class="container">
		<div class="row" style="margin-bottom:0px;">
			<h1>Sports</h1>			
		</div>
		<div class="row">
			<div class="col-sm-2" id="teamsListParent">
				<?php
					// loop through unique teams
					foreach ($uniqueTeams as $value) {
						// add team to team panel. On click -> getTeams() in functions.js
						echo "<div class='teamsListChild' onclick=getTeams('".$value."')><p>".$value."</p></div>";
					}
				?>
			</div>
			<div id="sports-body" class="col-sm-offset-1 col-sm-6" style="text-align: center">
				<p>type or select a team name to see who they beat this season</p>
				<div class="row taskRow">
	        		<div class="col-xs-11">
	        			<input class="form-control" type="text" id="teamSearch" name="teamSearch" placeholder="Search a team name...">	
	        		</div>
	        		<div class="col-xs-1" style="padding-left: 0px;">
	        			<button class="btn btn-primary" style="" onclick="getTeams('')">
	        				<span class="glyphicon glyphicon-search"></span>
	        			</button>
	        		</div>
	        	</div>
	        	<div class = "row divider"></div>
	        	<div class="row" id="teamsDiv" style="margin-bottom: 50px; text-align: center;">
	        		<!-- populated by getTeams() in functions.js -->
	        	</div>
			</div>
			
		</div>
		
		
	</div>

<script type="text/javascript">
	// set event for enter button pressed
	$(document).keyup(function (e) {
		if ($("#teamSearch").is(":focus") && (e.keyCode == 13)) {
		    getTeams('');
		}
	});

	
</script>

</html>
