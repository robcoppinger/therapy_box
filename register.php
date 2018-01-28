<?php

	error_reporting(E_ALL);
	ini_set('display_errors','1');
	session_start();
	require('php/functions.php');
	require('php/dbconn.php');

	// if POST request
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		// get user input values
		$email = $_POST["email"]; 
		$pwd = $_POST["password"]; 
		$confirmPwd = $_POST["confirmPwd"]; 
		$first_name = $_POST["first_name"]; 
		$last_name = $_POST["last_name"];

		// check if the passwords match
		if ($pwd == $confirmPwd){

			// create connection
			$conn = new Dbconn();

			// Check if the user already exists			
			$checkExists = $conn->checkExists($email);
			if ($checkExists){
				// ------------------------------------------------ Add new user
				// new user -> add user
				// call the addUser function from dbconn page
				// inserts the new user into the database
				$addUser = $conn->addUser($email,$pwd,$first_name,$last_name);
				// dbconn.login() -> retrieves the user info
				$login = $conn->login($email,$pwd);
				if ($login == false){
					// set error indicator
					echo '<script>var fail = 1;</script>';
				} else {
					// ----------------------------------- set session variables
					$_SESSION["userId"] = $login["id"];
					$_SESSION["first_name"] = $login["first_name"];
					$_SESSION["last_name"] = $login["last_name"];

					// ------------------------ create directory for user images
					$dir = 'images/'.$login["id"].'/';
					if (!mkdir($dir, 0777, true)) {
					    die('Failed to create folders...');
					}
					// ----------------------------------- redirect to index.php
					header("Location: index.php");
				}
				
			} else {
				echo "<script>
					if(confirm('user already exists. Please go to the login page')){window.location.href='login.php';}
				</script>";
			}
		} else {
			// -------------------------------- alert unmatching passwords
			echo '<script>
					alert("passwords did not match");
					
				</script>';
		}
		
		

	}


?>

<!DOCTYPE html>
<html>
	
	<?php head(); ?>
	

<body style="text-align: center;">

	
	<div class="container">
		<div class="row" style="">
			<h1>Register</h1>
		</div>
		
		<div class="row" style="margin-top: 50px;">
			<form class="form-horizontal col-sm-offset-4 col-sm-4" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<!-- form data -->
				<div class="form-group row">
					<label class="control-label col-sm-4" for="unsername">First Name:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="first_name" name = "first_name" value="<?php echo isset($_POST["first_name"]) ? $_POST["first_name"] : ''; ?>" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-sm-4" for="unsername">Last Name:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="last_name" name = "last_name" value="<?php echo isset($_POST["last_name"]) ? $_POST["last_name"] : ''; ?>" required>
					</div>
				</div>					
			

				<div class="form-group row" >
					<label class="control-label col-sm-4" for="email">Email:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="email" name="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ''; ?>" required>
					</div>
				</div>

				<div class="form-group row">
					<label class="control-label col-sm-4" for="unsername">Password:</label>
					<div class="col-sm-8">
						<input type="password" class="form-control" id="password" name = "password" required>
					</div>
				</div>
				<div class="form-group row" >
					<label class="control-label col-sm-4" for="password">Confirm password:</label>
					<div class="col-sm-8">
						<input type="password" class="form-control" id="confirmPwd" name="confirmPwd" required>
					</div>
				</div>

				<div class="row" style="margin-top: 50px;">
					<button type="submit" class="btn btn-primary" >Register</button>
				</div>
			</form>
		</div>
		
		
		
	</div>
</body>

<script type="text/javascript">
	
	$( document ).ready(function() {
		
		// if (fail == 1){
		// 	$('#fail').show();
		// }

	});
	
</script>

</html>
