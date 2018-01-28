<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors','1');

	require('php/functions.php'); // import functions page
	require_once('php/dbconn.php'); // import dbconn class
	$conn = new Dbconn();

	// check if session variable has been set
	if (!isset($_SESSION['userId'])){
		header('Location: login.php');
	} else {
		// set local variables to session variables
		$first_name = $_SESSION["first_name"];
		$last_name = $_SESSION["last_name"];
		$userId = $_SESSION["userId"];
		echo "<script>var userId = ".$userId.";</script>";
	}

	$content = array ();
	$content[0]["title"]="News";$content[0]["content"]="<p>Loading news...</p>";
	$content[0]["attr"]="";

	$content[1]["title"]="Weather";$content[1]["content"]=getWeatherPreview(null,null); $content[1]["attr"]="";

	$content[2]["title"]="Sport";$content[2]["content"]="Loading Sport...";
	$content[2]["attr"]="";

	$content[3]["title"]="Photos";$content[3]["content"]=getPhotoPreview();
	$content[3]["attr"]='onclick="window.location.href=\'gallery.php\'"';

	$content[4]["title"]="Tasks";$content[4]["content"]=$conn->getTaskPreview($userId); $content[4]["attr"]='onclick="window.location.href=\'tasks.php\'"';

	$content[5]["title"]="Favourite Warmer";$content[5]["content"]="<div id='chart-1' captionPadding='0'>"; $content[5]["attr"]='data-toggle="modal" data-target="#myModal"';

	
?>

<!DOCTYPE html>
<html>
	
	<?php head(); ?>

<body onresize="updateSize()">

	
	<div class="container">
		<div class=" row banner">
			<h1>Welcome <?php echo $first_name ?></h1>
		</div>

		<div id="main">

			<div class="row">
				<?php 
					
					
					
					

					for ($x=0;$x<sizeof($content);$x++){
						echo '<div class="col-md-4 col-sm-6">
								<div class="boxContainer">
									<div class = "previewHeader">
										<h3 '.$content[$x]["attr"].'>'.$content[$x]["title"].'</h3>
									</div>
									<div class="box">
										<div id="'.$content[$x]["title"].'">
											'.$content[$x]["content"].'
										</div>
									</div>
								</div>
							</div>';
					}

					$columnChart = favWarmer();
					$columnChart->render();

				?>
				
			</div>

		
				
		</div>
	</div>

		
	</div>	
	<?php foot(); ?>

	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="modal-title"></h4>
        </div>
        <div id="chart-1">
        	

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 
</body>

<script type="text/javascript">

	$( document ).ready(function() {
		$("#myModal").on("hidden.bs.modal", function () {
		    //$("#modal-body").html("<h2>Loading...</h2>");
		    //$('#myModal').modal('show');
		});

		updateSize();
		getLocation();

		//var chart = $('#chart-1').html();
		//$('#myModal').html(chart);

	});

	function getLocation() {
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(getWeatherPreview);
	    } else {
	        console.log("Geolocation is not supported by this browser.");
	    }
	}	
	function updateSize(){
		var bwidth = $('.box').width();		
		var iwidth = $('.smallImgBox').width();		
		$('.box').css({'height':bwidth-80+'px'});
		$('.smallImgBox').css({'height':iwidth-30+'px'});
	}
	
</script>

</html>
