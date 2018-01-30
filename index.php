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
	$content[0]["title"]="News";$content[0]["content"]= getNews(0);
	$content[0]["attr"]='onclick="showNews()"';

	$content[1]["title"]="Weather";$content[1]["content"]=getWeatherPreview(null,null); $content[1]["attr"]="style='cursor: default;'";

	$content[2]["title"]="Sport";$content[2]["content"]=getNews(2);
	$content[2]["attr"]='onclick = "window.location.href=\'sports.php\'"';

	$content[3]["title"]="Photos";$content[3]["content"]=getPhotoPreview();
	$content[3]["attr"]='onclick="window.location.href=\'gallery.php\'"';

	$content[4]["title"]="Tasks";$content[4]["content"]=$conn->getTaskPreview($userId); $content[4]["attr"]='onclick="window.location.href=\'tasks.php\'"';

	$content[5]["title"]="Favourite Warmer";$content[5]["content"]="<div id='chart-1' captionPadding='0'>"; 
	$content[5]["attr"]='onclick="showWarmer()"';

	
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

					$pieChart = favWarmer(); // get chart details
					$pieChart[0]->render(); // render chart in  preview
					$pieChart[1]->render(); // render chart in modal

				?>
				
			</div>

		
				
		</div>
	</div>

		
	</div>	
	<?php foot(); ?>

	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content" style="color: black">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="favWarmer" style="color:black; display: none;">Favourite Warmer</h4>
          <h2 class="newsModal" style="color:black; display: none;">News</h4>
        </div>
        <div class="newsModal" style="padding-left: 50px; text-align: center">
        	<?php echo getNews(1) ?>
        </div>
        <div class="favWarmer" id="chart-2" style="display: none;">        	
        	<!-- placeholder for chart -->
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
		    $('.favWarmer').hide();
		    $('.newsModal').hide();

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
	function showNews(){
		$('#myModal').modal('toggle');
		$('.newsModal').show();
	}
	function showWarmer(){
		$('.favWarmer').show();
		$('#myModal').modal('toggle');		
	}
</script>

</html>
