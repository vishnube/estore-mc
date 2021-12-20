$(document).ready(function () {
	// Loading all tasks.
	loadTasks();
});


// Load pagination
function loadTasks() {
	var url = site_url("tasks/get_tasks");
	$('tsk-main-container').postForm(url, [], afterTaskLoad, '', $('#tsk-main-container'), false);
}


/**
* This function should be in global scope	
* @param {*} res     		:   Ajax Response
* @param {*} container     	:   Ajax Response
* @param {*} input 			:   Ajax posted input data
*/
function afterTaskLoad(res, container, input) {
	$("#tsk-main-container").html(res.html);
}