<?php

	error_reporting(E_ALL);
	ini_set('display_errors','1');
	session_start();

	if (!isset($_SESSION['userId'])){
		header('Location: login.php');
	}

	require('php/functions.php');
	require('php/dbconn.php');
	$userId = $_SESSION["userId"];
	$images = array();
	$path = "images/".$userId."/";

	if ($handle = opendir($path)) {
	    while (false !== ($file = readdir($handle))) {
	        if ('.' === $file) continue;
	        if ('..' === $file) continue;

	        array_push($images,$file);
	    }
	    closedir($handle);
	}
	

?>

<!DOCTYPE html>
<html>
	
	<?php head(); ?>
	

<body style="text-align: center;">

	
	<div class="container">
		<div class="row" style="margin-bottom:0px;">
			<h1>Gallery</h1>			
		</div>
		<div class="row">
			<label class="fileContainer btn btn-default" style="float:right" 
			data-toggle="tooltip" title="Add Image(s)"><span class="glyphicon glyphicon-plus"></span>
				<input type="file" name="files[]" id="fileInput" name="fileInput" 
				onchange="uploadImage()"	data-multiple-caption="{count} files selected" multiple />
			</label>
		</div>
		<?php

			echo '<div class="row">';
			for ($x = 0;$x < sizeof($images);$x++){

				echo '<div class="col-md-3 col-sm3">
						<div class="imgBox" data-toggle="modal" data-target="#myModal" onclick="viewImage('.$x.')">
							<img class="w3-image w3-card-4" src="'.$path.$images[$x].'">
						</div>				
					</div>';

				$divNum = $x + 1;
				if ( $divNum%4 == 0){
					echo '</div><div class="row">';
				}
			}
			echo '</div>';

		?>
		
		
	</div>

	

	<?php foot(); ?>

	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog" >
    <div class="modal-dialog modal-lg" style="margin-top: 10px;">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div id="modal-body" class="imgContainer" style="position:relative;">
        	<div style="text-align: center;">
       			<h2>Loading...</h2>
       		</div>

        </div>
        
      </div>
      
    </div>
  </div>
</body>

<script type="text/javascript">
	var userId = <?php echo json_encode($userId); ?>;
	var images = <?php echo json_encode($images); ?>;

	$( document ).ready(function() {
		updateSize();
	});
	function uploadImage(){
		addImage();
		//	location.reload();
	}
	function updateSize(){
		var width = $('.imgBox').width();		
		$('.imgBox').css({'max-height':width-70+'px'});
	}
	
</script>

</html>
