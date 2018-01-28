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

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$task = $_POST["newTask"]; // get username from post
		$pwd = $_POST["password"]; // get password from post

	}

	

?>

<!DOCTYPE html>
<html>
	
	<?php head(); ?>
	

<body>

	
	<div class="container">
		<div class="row" style="margin-bottom:0px;">
			<h1>Tasks</h1>			
		</div>
		<div class="row">
			<div id="task-body" class="col-sm-offset-3 col-sm-6">
				<?php
					$conn = new Dbconn();
					echo $conn->getAllTasks($userId);					
				
				?>
			</div>
			
		</div>
		
		
		
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
	
	function uploadImage(){
		addImage();
		location.reload();
	}
	
</script>

</html>
