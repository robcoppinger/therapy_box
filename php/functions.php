<?php
	
	/**
	*	contains header info for all pages
	*/
	function head(){
		echo '
		<head>
		<title>Therapy Box</title>

		<meta charset = "UTF-8"/>
		<meta name = "viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>

		
		<script src="lib/jquery.min.js"></script>
		<script src="js/functions.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="css/stylesheet.css" rel="stylesheet">

		</head>';
	}
	/**
	*	creates footer element
	*/
	function foot(){
		echo "<div class='footer'></div>";
	}
	/**
	*	generates image preview content
	*/
	function getPhotoPreview(){
		// ------------------------------------------------ retrieve variables
		$userId = $_SESSION["userId"];
		$images = array();
		$path = "images/".$userId."/"; // path to images
		// ----------------------------- open path and retrieve list of images
		if ($handle = opendir($path)) {
		    while (false !== ($file = readdir($handle))) {
		        if ('.' === $file) continue;
		        if ('..' === $file) continue;

		        array_push($images,$path.$file);
		    }
		    closedir($handle);
		}

		// ---------------------------------------------------- generate HTML
		$output = '<div class="row" style="margin-bottom:2px;">';
		$count = 0;
		// loop through images from array of images; max 9 images
		for ($x=0;$x<sizeof($images);$x++){
			if ($x > 8){
				break;
			} else {
				// add html for each image
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
		// ---------------------------------------- generate HTML for empty gallery
		if ($count == 0){
			$output = "<div style='text-align:center; color: grey;'>
			<p>There are currently no photos in your gallery</p>
			<p>click the link above to add some photos</p></div>";
		}

		return $output; // return output
	}
	/**
	*	generates content for the weather preview box
	*	@param lat - location latitude
	*	@param long - location longitude
	*/
	function getWeatherPreview($lat,$long){
		// ---------------------------- set default values if position not available
		if ($lat == null || $long == null){
			$lat = -29.85;
			$long = 31.02;
		}

		// -------------------------- connect to open weather map API & retrieve data

		$api_url = "http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$long."&APPID=6d95183472c0bcafcd617b48d094e654";
		$weather_data = file_get_contents($api_url);
		
		$json = json_decode($weather_data,true);
		// ------------------------------- set variables from retrieved weather data
		$icon = $json['weather'][0]['icon'];
		$temp = round($json["main"]["temp"] - 273.15);
		$minTemp = round($json["main"]["temp_min"] - 273.15);
		$maxTemp = round($json["main"]["temp_max"] - 273.15);
		$description = $json['weather'][0]['description'];		
		$place = $json["name"].", ".$json["sys"]["country"];
	
		// ------------------------------------------------ generate & return HTML
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
	/**
	*	process data from API and create graph from this data showing 
	*	percentage of each clothe type worn
	*/
	function favWarmer(){
		include("lib/fusioncharts.php");
		// fetch data from URL
		$api_url = 'https://therapy-box.co.uk/hackathon/clothing-api.php?username=swapnil';
	    $result = file_get_contents($api_url); 	// get contents
	    $data = json_decode($result,true);		// parse the JSON object to array
	    

	    $clothes = array(); // declare array

	    // -------------------------------------- set key to clothe type, value to 0
	    // -------- result: $clothe keys -> unique clothes; values -> count set to 0
	    foreach($data["payload"] as $element) {
	        $hash = $element["clothe"];
	        $clothes[$hash] = 0;
	    }
	   	    
	    // ----------------- increment the value (count) for each instance of clothe
	    foreach($data["payload"] as $element) {
	       $clothes[$element["clothe"]] ++;
	    }
	   
	    // --------------------------- build json object from array -> input to chart
	    $output = "";
	    $count = 0;
	    foreach ($clothes as $key => $value) {
	      if ($count > 0){
	        $output.= ','; // separate entries with ","
	      } 
	      $output.= '{
	                  "label": "'.$key.'",
	                  "value": "'.$value.'"
	              }';
	      $count++;
	      
	    }

	    // Create the chart - Column 2D Chart with data given in constructor parameter 
	    // Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
	    // pieChart1 - preview; pieChart2 - modal
	    $pieChart1 = new FusionCharts("pie2d", "ex1", "100%", "100%", "chart-1", "json", '{
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
	    $pieChart2 = new FusionCharts("pie2d", "ex2", "100%", "100%", "chart-2", "json", '{
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
	    // return chart objects
	    return array($pieChart1, $pieChart2);
	}
	/**
	*	generate HTML content for news preview, news modal, and sport preview
	*/
	function getNews($ind){
		// ind = 0 -> output for news preview
		// ind = 1 -> output for news modal
		// ind = 2 -> output for sport preview
		// ---------------------------------------------------- get info from rss feed
		$url = '';
		if ($ind == 2){
			$url = 'http://feeds.bbci.co.uk/sport/rss.xml';		// connect to sport rss
		} else {
			$url = 'http://feeds.bbci.co.uk/news/rss.xml';		// connect to news rss
		}
		
		$rss = simplexml_load_file($url);						// get xml encoded rss
	    $item = $rss->channel->item[0];							// set item variable
	    $title = $item->title;                                  // set title
	    $desc = $item->description;    						    // set description
		$namespaces = $item->getNameSpaces(true);               // get namespaces
		$media      = $item->children($namespaces['media']);    // handle namespaces
	    $attr    = $media->thumbnail->attributes();    		    // get attributes
	    $imgUrl = $attr['url'];									// get img url
	    
	    $output = "";
	    // ---------------------- generate HTML for news / sport preview (same layout)
	    if ($ind == 0 || $ind == 2){
	    	$output = "<div class='row' style = 'text-align:center'>
				<h4>$title</h4>
			</div>
			<div class='row newsDesc'>
				<p>$desc</p>
			</div>";
	    } else if ($ind == 1) {
	    	// --------------------------------- generate HTML content for news modal
	    	$output = "
	    		<div class = 'row'>
	    			<h3>$title</h3>
	    		</div>
	    		<div class='row'>
	    			<img class='newsImg' src='$imgUrl'></img>
	    		</div>
	    		<div class='row modalNewsText'>
	    			<p>$desc</p>
	    		</div>";
	    }
	    
	    // return the html
	    return $output;
	}




	
?>