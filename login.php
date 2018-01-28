<?php

	error_reporting(E_ALL);
	ini_set('display_errors','1');
	
	session_start();
	require('php/functions.php');
	require('php/dbconn.php');

	// if POST request
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$usr = $_POST["username"]; // get username from post
		$pwd = $_POST["password"]; // get password from post

		// establish DB connection
		$conn = new Dbconn();
		// fetch user information. dbconn.login()
		$login = $conn->login($usr,$pwd);

		// error logging in
		if ($login == false){
			// set fail indicator -> JS show fail message
			echo '<script>var fail = 1;</script>';
		} else {
			// set session variables
			$_SESSION["userId"] = $login["id"];
			$_SESSION["username"] = $login["username"];
			$_SESSION["first_name"] = $login["first_name"];
			$_SESSION["last_name"] = $login["last_name"];
			header("Location: index.php"); // redirect to main page
		}
	}


?>

<!DOCTYPE html>
<html>
	
	<?php head(); // print out the header ?>
	

<body style="text-align: center;">

	
	<div class="container">
		<div class="row" style="">
			<h1>Login</h1>
			<p>don't have an account? click <a style="font-weight: bold" href="register.php">here</a> to register</p>
		</div>
		
		<div class="row" style="margin-top: 50px;">
			<form class="form-inline" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
				<div class="form-group">
					<label for="unsername">Email:</label>
					<input type="text" class="form-control" id="username" name = "username">
				</div>
				<div class="form-group" style="margin-left: 20px;">
					<label for="password">password:</label>
					<input type="password" class="form-control" id="password" name="password">
				</div>


				<div class="row" style="margin-top: 50px;">
					<button type="submit" class="btn btn-primary" >Login</button>
				</div>
				<div class="row fail">
					<p>Login failed. please try again</p>
				</div>
			</form>
		</div>

		
		
	</div>
</body>

<script type="text/javascript">
	
	$( document ).ready(function() {
		
		if (fail == 1){
			// show the fail message
			$('.fail').show();
		}

	});
	
</script>

</html>
