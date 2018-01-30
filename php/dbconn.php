<?php
	/**
	* 
	*/
	//$db = new Dbconn();
	// error_reporting(E_ALL);
	// ini_set('display_errors','1');

	/**
	* instanciates the database connection
	* fetches connection info from config file
	* conatins methods for accessing data
	*/
	class Dbconn 
	{
		private $conn; // connection object

		function __construct()
		{
			// --------------------- retrieve connection info from config file
			$config = 'config.json';
 			// if config file not found, set one dir higher
			if(!file_exists($config)){
				$config = "../".$config;
			}

			$config = file_get_contents($config);
			$json = json_decode($config, true);
			
			// ------------------------------------------- database login info
			$host = $json['db_host'];
			$user = $json['db_user'];
			$password = $json['db_pwd'];	
			$dbname = $json['db_name'];

			// ------------------------------------------ establish connection 		
			$this->conn = new mysqli($host, $user, $password, $dbname);
			if ($this->conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}
		}

		/**
		*	checks the database to test if the user
		*	credentials are correct
		*	@param email - Email address of the user
		* 	@param pwd - Password for the user
		* 	@return - array containing the user info
		*/
		public function login($email,$pwd){
			$sql = "SELECT id, first_name, last_name
				FROM users 
				WHERE email = ? AND password = ?";
			// ------------------------------------------ prepare the query
			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}
			// -------------------- prepare and execute parameterised query
			$stmt->bind_param("ss", $email, $pwd); // bind parameters			
			$stmt->execute(); // execute the query
			
			// ------------------------------------------ fetch the results
			$result = $stmt->get_result();			
			$return = array(); // declare return object
			// loop through result
			while ($myrow = $result->fetch_assoc()) {
				// add results to $results array
				$return['id'] = $myrow['id'];
				$return['first_name'] = $myrow['first_name'];
				$return['last_name'] = $myrow['last_name'];	
				$return['username'] = $myrow['username'];	
			}

			// --------------------------- check if returned array is empty
			if (empty($return)){
				return false;
			} else {
				return $return; // return the array
			}
		}
		/**
		*	Used on the register page
		*	Checks whether a user exists already
		*	@param email - Email of the user
		*	@return boolean - false if exists, true if not
		*/
		public function checkExists($email){

			// ------------- declare SQL statement for duplicate validation
			$sql = "SELECT * FROM users where email = ?";
			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}
			// -------------------- prepare and execute parameterised query
			$stmt->bind_param("s", $email);			
			$stmt->execute();
			// fetch the result
			$result = $stmt->get_result();
			$duplicate = 0;
			while ($myrow = $result->fetch_assoc()) {
				// increment duplicate indicator
				$duplicate++;
			}

			// --------------------------- check if user duplicate exists
			if ($duplicate > 0){
				return false; // duplicate exists, return false
			} else {
				return true; // No duplicate -> free to add user
			}


		}
		/**
		*	adds a new user into the database
		*	@param email - Email of the user
		*	@param password - password of the user
		*	@param first_name - first_name of the user
		*	@param last_name - last_name of the user
		*	@return boolean - success
		*/
		public function addUser($email,$password,$first_name,$last_name){
			// no duplicate - add user
			// ---------------------------------------------- declare insert statement
			$usr = "username";
			$sql = "INSERT INTO users (username,email,password,first_name,last_name) VALUES (?,?,?,?,?)";
			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}
			// -------------------------------- prepare and execute parameterised query
			$stmt->bind_param("sssss", $usr, $email,$password,$first_name,$last_name);		
			$stmt->execute();
			// fetch the result
			$result = $stmt->get_result();
			return $result;
		}

		/**
		*	builds HTML for the task preview box
		*	@param userId - user_id of the user
		*	@return HTML content
		*/
		public function getTaskPreview($userId){

			// ----------------------------------------------- declare SQL statement
			$sql = "SELECT * FROM tasks WHERE user_id = ? AND is_complete = 0";

			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}	
			// ------------------------------ prepare and execute parameterised query
			$stmt->bind_param("i", $userId);		
			$stmt->execute();
			// fetch the result
			$result = $stmt->get_result();
			// ------------------------------------------------------- built the HTML
			$output = "";
			while ($myrow = $result->fetch_assoc()) {
			$output.= '<div class="row taskDisp">
								<div class = "col-xs-10" style="padding:0px;margin:0px;">
									<p>'.$myrow["task"].'</p>
								</div>
								<div class="col-xs-1" style="padding:0px;margin-right:0px;">
									<input style="float: right;" type="checkbox" class="checkbox" onchange="taskTogglePreview('.$myrow["id"].')">
								</div>
						</div>';
			}
			// ----------------------------------------------- set html if no content
			if ($output == ""){
				$output = "<div style='text-align:center; color: grey;'>
			<p>There are currently no uncompleted tasks.</p>
			<p>Click the link above to add some tasks</p></div>";
			}

			return $output; // return the html

		}

		/**
		*	toggles the 'complete' status for a task
		*	@param taskId - id of the task to toggle
		*	@return void
		*/
		public function toggleTaskComplete($taskId){

			// ----------------------------------------- declare SQL to toggle task

			$sql = "UPDATE tasks SET is_complete = !is_complete WHERE id = ?";

			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}	

			// --------------------------- prepare and execute parameterised query
			$stmt->bind_param("i", $taskId);		
			$stmt->execute();
		}

		/**
		*	build HTML for the task modal
		*	@param userId - user_id of the current user
		*	@return HTML content
		*/
		public function getAllTasks($userId){

			// ------------------------------------ Declare SQL to retrieve tasks
			$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY is_complete ASC";

			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}	
			// --------------------------- prepare and execute parameterised query
			$stmt->bind_param("i", $userId);		
			$stmt->execute();
			// ----------------------------------------------------- Build the HTML
			// fetch the result
			$result = $stmt->get_result();
			// ------------------------------------ set static HTML
			$output = '<div class="row taskRow">
			        		<div class="col-xs-11">
			        			<input class="form-control" type="text" id="newTask" name="newTask" placeholder="Add a new task">	
			        		</div>
			        		<div class="col-xs-1" style="padding-left: 0px;">
			        			<button class="btn btn-primary" onclick="addTask()">
			        				<span class="glyphicon glyphicon-plus" ></span>
			        			</button>
			        		</div>
			        	</div><div class = "row divider"></div>';

			// -------------------------- create each task element
			while ($myrow = $result->fetch_assoc()) {

				// check task status and assign relevant classes
				if ($myrow["is_complete"] == 0){
					$class = "incomplete"; 
					$glyph = "glyphicon glyphicon-ok";
				} else {
					$class = "complete";
					$glyph = "glyphicon glyphicon-share-alt flipped";
				}

				$output.= '<div class="row taskRow">
			        		<div class="col-xs-11">
			        			<input class="form-control" id="'.$class.'" type="text" name="" value = "'.$myrow["task"].'" disabled>	
			        		</div>
			        		<div class="col-xs-1" style="padding-left: 0px;">
			        			<button class="btn btn-default" onclick="taskToggleMain('.$myrow["id"].')" style="padding: 10px;">
			        				<span class="'.$glyph.'"></span>
			        			</button>
			        		</div>
			        	</div> ';
			}

			return $output; // return the HTML
		}

		/**
		*	add a task to the database
		*	@param userId - user_id for the current user
		*	@param task - task description to insert
		*/
		public function addTask($userId, $task){
			// -------------------------------------------- declare INSERT SQL
			$sql = "INSERT INTO tasks(user_id, task) VALUES(?,?)";

			$stmt = $this->conn->prepare($sql);
			if ($stmt == false){
				return false;
			}	
			// ---------------------- prepare and execute parameterised query
			$stmt->bind_param("is", $userId,$task);		
			$stmt->execute();
		}

		
	}
?>