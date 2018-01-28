<?php
	
	
	function head(){
		echo '
		<head>
		<title>Therapy Box</title>

		<meta charset = "UTF-8"/>
		<meta name = "viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>

		<link href="css/stylesheet.css" rel="stylesheet">
		<script src="lib/jquery.min.js"></script>
		<script src="js/functions.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		</head>';
	}

	function foot(){
		echo "<div class='footer'></div>";
	}

	function getPhotoPreview(){
		$userId = $_SESSION["userId"];
		$images = array();
		$path = "images/".$userId."/";		

		if ($handle = opendir($path)) {
		    while (false !== ($file = readdir($handle))) {
		        if ('.' === $file) continue;
		        if ('..' === $file) continue;

		        array_push($images,$path.$file);
		    }
		    closedir($handle);
		}

		// generate HTML
		$output = '<div class="row" style="margin-bottom:2px;">';
		$count = 0;
		for ($x=0;$x<sizeof($images);$x++){
			if ($x > 8){
				break;
			} else {

				$output.='<div class="col-sm-4 smallImgBox" style="padding: 1px; overflow:hidden">
							<img class="w3-image w3-card-4" src="'.$images[$x].'">
						</div>';
				$divNum = $x+1;
				if ($divNum%3 == 0){
					$output.='</div><div class="row" style="margin-bottom:2px;">';
				}

			}
			$count++;
		}
		$output.="</div>";

		if ($count == 0){
			$output = "<div style='text-align:center; color: grey;'>
			<p>There are currently no photos in your gallery</p>
			<p>click the link above to add some photos</p></div>";
		}

		return $output;
	}

	function getWeatherPreview($lat,$long){

		if ($lat == null || $long == null){
			$lat = -29.85;
			$long = 31.02;
		}

		$api_url = "http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$long."&APPID=6d95183472c0bcafcd617b48d094e654";
		$weather_data = file_get_contents($api_url);
		//echo $weather_data;
		$json = json_decode($weather_data,true);

		$icon = $json['weather'][0]['icon'];
		$temp = round($json["main"]["temp"] - 273.15);
		$minTemp = round($json["main"]["temp_min"] - 273.15);
		$maxTemp = round($json["main"]["temp_max"] - 273.15);
		$description = $json['weather'][0]['description'];
		//$wind = $json["wind"]["speed"];
	
		$place = $json["name"].", ".$json["sys"]["country"];
	

		return '
			<h5 style="position:absolute;right:20;top:0;">'.$place.'</h5>
			<div style="text-align: center; margin-top: 5vh;">
				<img src="http://openweathermap.org/img/w/'.$icon.'.png"></img>
				<h5 style="display:inline-block;">'.$temp.'°C</h5>					
			</div>
			<div style="text-align: center;">
				<h4 style="color:blue; display: inline-block; margin-right: 20px;margin-left: 10px;">'.$minTemp.'°C</h4>
				<h4 style="color:red; display: inline-block; margin-left: 20px;">'.$maxTemp.'°C</h4>
				<p>'.$description.'</p>				
			</div>';


	}

	function favWarmer(){
		include("lib/fusioncharts.php");
		$api_url = 'https://therapy-box.co.uk/hackathon/clothing-api.php?username=swapnil';
	    $result = file_get_contents($api_url);
	    $data = json_decode($result,true);
	    

	    $clothes = array();

	    foreach($data["payload"] as $element) {
	        $hash = $element["clothe"];
	        $clothes[$hash] = $element;
	    }
	    foreach ($clothes as $key => $value) {
	      $clothes[$key] = 0;
	    }
	    

	    foreach($data["payload"] as $element) {
	       $clothes[$element["clothe"]] ++;
	    }
	    //print_r($clothes);

	    $output = "";
	    $count = 0;
	    foreach ($clothes as $key => $value) {
	      if ($count > 0){
	        $output.= ',';
	      } 
	      $output.= '{
	                  "label": "'.$key.'",
	                  "value": "'.$value.'"
	              }';
	      $count++;
	      
	    }

	      // Create the chart - Column 2D Chart with data given in constructor parameter 
	      // Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
	      $columnChart = new FusionCharts("pie2d", "ex1", "100%", "100%", "chart-1", "json", '{
	          "chart": {
	              "caption": "My Favourite Warmer",
	              "numberPrefix": "",
	              "showPercentInTooltip": "0",
	              "decimals": "1",
	              "useDataPlotColorForLabels": "1",
	              "theme": "fint"
	          },
	          "data": [
	              '.$output.'
	          ]
	      }');
	      // Render the chart
	      return $columnChart;
		}



	
?>