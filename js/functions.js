/**
*	retrieves all tasks for a user
*/
function getAllTasks(){
	// send ajax to php/ajax_getAllTasks.php
	$.ajax({url: "php/ajax_getAllTasks.php",
		type: "POST",
		data: {uid: userId },
		success: function(result){
			// set html to result
			$('#task-body').html(result);
		}
	});
}
/**
*	adds a task to the user's task list
*/
function addTask(){
	var task = $('#newTask').val();				// get task text
	if (task == ""){							// check not empty
		alert("Please enter a value");
	} else {
		// send ajax
		$.ajax({url: "php/ajax_addTask.php",
			type: "POST",
			data: {uid: userId, tsk: task },
			success: function(result){
				// refresh the task list
				getAllTasks();	
			}
		});
	}
}
/**
*	toggles complete of tasks from preview
*/	
function taskTogglePreview(taskId){
	// send ajax
	$.ajax({url: "php/ajax_toggleTaskComplete.php",
		type: "POST",
		data: {tid: taskId },
		success: function(result){
			// update the content
			updateTaskPreview();
		}
	});
}
/**
*	updates the content of the task preview window
*/
function updateTaskPreview(){
	$.ajax({url: "php/ajax_getTaskPreview.php",
		type: "POST",
		data: {uid: userId },
		success: function(result){
			$('#Tasks').html(result);			
		}
	});
}
/**
*	toggle task complete from task page
*/
function taskToggleMain(taskId){
	$.ajax({url: "php/ajax_toggleTaskComplete.php",
		type: "POST",
		data: {uid: userId, tid: taskId },
		success: function(result){
			getAllTasks();
		}
	});
}
/**
*	display selected image in the modal
*	@param ind - specifies the id of the image
*/
function viewImage(ind){
	nextInd = ind+1;	// get next img id
	prevInd = ind-1;	// get previous img id

	if (ind == 0){prevInd = 0;}						// first image - set prev = current
	if (nextInd == images.length){nextInd = ind;}	// last image - set next = current

	// --------------------------------------------------------- set HTML for the image
	src = "images/"+userId+"/"+images[ind];
	img = "<img class='w3-image w3-card-4' src='"+src+"''>"
	btnr = "<button class='btn img-btn-right' onclick='viewImage("+nextInd+")'>\
	<span class='glyphicon glyphicon-chevron-right'></span></button>";
	btnl = "<button class='btn img-btn-left'onclick='viewImage("+prevInd+")'>\
	<span class='glyphicon glyphicon-chevron-left'></span></button>";
	$('#modal-body').html(img+btnl+btnr); // set html in the modal
}
/**
*	adds an image to the server
*/
function addImage(){
	// --------------------------------------------------------- get selected input images
	var form_data = new FormData();
	for (var i = 0, len = document.getElementById('fileInput').files.length; i < len; i++) {
        form_data.append("file" + i, document.getElementById('fileInput').files[i]);
    }
    // send to ajax
   	$.ajax({
		url: "uploadImage.php?uid="+userId, // Url to which the request is send
		type: "POST",     // Type of request to be send, called as method
		data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		contentType: false,       // The content type used when sending data to the server.
		cache: false,             // To unable request pages to be cached
		processData:false,        // To send DOMDocument or non processed data file it is set to false
		success: function(result){
			// void
		}
	});
}
/**
*	get weather html from AJAX
*	@param position - location from HTML Geolocation
*/
function getWeatherPreview(position) {
	var lat = position.coords.latitude;
	var long = position.coords.longitude;
    
    $.ajax({url: "php/ajax_getWeatherPreview.php?lat="+lat+"&long="+long,
		type: "GET",
		success: function(result){
			$('#Weather').html(result); // update HTML
		}
	});
}
/**
*	return teams that user's team has beaten
*	@param input - name of selected team
*/
function getTeams(input){
	// input only required from click, not type
	team = "";								// declare empty team var
	if (input == ''){						// no input given
		team = $('#teamSearch').val();		// fetch value from input
	} else {
		team = input;						// team = input passed
		$('#teamSearch').val(team);			// set input element to team
	}
	
	$.ajax({url: "php/ajax_getTeams.php?team="+team,
		type: "GET",
		success: function(result){
			$('#teamsDiv').html(result);
			// set the html content
		}
	});
}