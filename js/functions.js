

function getAllTasks(){
	$('#modal-title').html("Task List");
	$.ajax({url: "php/ajax_getAllTasks.php",
		type: "POST",
		data: {uid: userId },
		success: function(result){
			$('#task-body').html(result);
		}
	});
}

function addTask(){
	var task = $('#newTask').val();
	if (task == ""){
		alert("Please enter a value");
	} else {
		$.ajax({url: "php/ajax_addTask.php",
			type: "POST",
			data: {uid: userId, tsk: task },
			success: function(result){
				//console.log(result);
				//$('#task-body').html(result);
				getAllTasks();
				//updateTaskPreview();
			}
		});
	}
}

function taskTogglePreview(taskId){
	// console.log(taskId);
	// console.log(userId);
	$.ajax({url: "php/ajax_toggleTaskComplete.php",
		type: "POST",
		data: {tid: taskId },
		success: function(result){
			
			updateTaskPreview();

		}
	});
}

function updateTaskPreview(){
	$.ajax({url: "php/ajax_getTaskPreview.php",
		type: "POST",
		data: {uid: userId },
		success: function(result){
			//console.log(result);
			$('#Tasks').html(result);
			
		}
	});
}

function taskToggleMain(taskId){
	$.ajax({url: "php/ajax_toggleTaskComplete.php",
		type: "POST",
		data: {uid: userId, tid: taskId },
		success: function(result){
			//console.log(result);
			getAllTasks();
			//updateTaskPreview();
		}
	});
}

function viewImage(ind){
	nextInd = ind+1;
	prevInd = ind-1;

	if (ind == 0){prevInd = 0;}
	if (nextInd == images.length){nextInd = ind;}

	src = "images/"+userId+"/"+images[ind];
	img = "<img class='w3-image w3-card-4' src='"+src+"''>"
	btnr = "<button class='btn img-btn-right' onclick='viewImage("+nextInd+")'>\
	<span class='glyphicon glyphicon-chevron-right'></span></button>";
	btnl = "<button class='btn img-btn-left'onclick='viewImage("+prevInd+")'>\
	<span class='glyphicon glyphicon-chevron-left'></span></button>";
	$('#modal-body').html(img+btnl+btnr);
}

function addImage(){
	
	var form_data = new FormData();
	for (var i = 0, len = document.getElementById('fileInput').files.length; i < len; i++) {
        form_data.append("file" + i, document.getElementById('fileInput').files[i]);
    }
    
   	$.ajax({
		url: "uploadImage.php?uid="+userId, // Url to which the request is send
		type: "POST",     // Type of request to be send, called as method
		data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		contentType: false,       // The content type used when sending data to the server.
		cache: false,             // To unable request pages to be cached
		processData:false,        // To send DOMDocument or non processed data file it is set to false
		success: function(result){
			// success
		}
	});
}

function getWeatherPreview(position) {
	var lat = position.coords.latitude;
	var long = position.coords.longitude;
    
    $.ajax({url: "php/ajax_getWeatherPreview.php?lat="+lat+"&long="+long,
		type: "GET",
		success: function(result){
			$('#Weather').html(result);
		}
	});
}