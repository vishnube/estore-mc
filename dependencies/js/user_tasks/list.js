$(document).ready(function () {

	$('.utsk_container #grp_id').change(function () {
		var grp_id = $(this).val();
		if (!grp_id)
			return;

		// Loading all user_tasks.
		loadusertasks();
	});

});


// Load pagination
function loadusertasks() {
	var grp_id = $('.utsk_container #grp_id').val();
	if (!grp_id)
		return;

	var url = site_url("user_tasks/get_user_tasks");
	$('uutsk-main-container').postForm(url, { grp_id: grp_id }, afterTaskLoad, '', $('#uutsk-main-container'), false);
}

$(document).on('switchChange.bootstrapSwitch', '#uutsk-main-container .switch_utsk', function (event, checked) {
	var grp_id = $('.utsk_container #grp_id').val();
	if (!grp_id) {
		Swal.fire('Oops!', "Please select a user", 'error');
		return;
	}

	var curTask = $(this).closest('li').find('.tsk_id').val();

	var parentIds = [];

	var input = {};

	input[Object.keys(input).length] = {
		utsk_fk_tasks: curTask,
		utsk_fk_groups: grp_id,
		status: checked ? 1 : 0
	};

	if (checked) {
		var parentIds = $(this).parents('ul.child-container').map(function () {
			return $(this).attr('data-parent-id');
		}).get();

		$.each(parentIds, function (index, value) {
			var parentSwitch = $('#uutsk-main-container input.tsk_id[value=' + value + ']').closest('li').find('.switch_utsk');
			if (parentSwitch.bootstrapSwitch('state') === false) {
				parentSwitch.bootstrapSwitch('state', true, true);
				input[Object.keys(input).length] = {
					utsk_fk_tasks: value,
					utsk_fk_groups: grp_id,
					status: 1
				};
			}
		});


		$('#uutsk-main-container ul[data-parent-id=' + curTask + '] .switch_utsk').bootstrapSwitch('state', true, true);
		$('#uutsk-main-container ul[data-parent-id=' + curTask + '] input.tsk_id').each(function () {
			input[Object.keys(input).length] = {
				utsk_fk_tasks: $(this).val(),
				utsk_fk_groups: grp_id,
				status: 1
			};
		});

	}
	else {
		$('#uutsk-main-container ul[data-parent-id=' + curTask + '] .switch_utsk').bootstrapSwitch('state', false, true);

		$('#uutsk-main-container ul[data-parent-id=' + curTask + '] input.tsk_id').each(function () {
			input[Object.keys(input).length] = {
				utsk_fk_tasks: $(this).val(),
				utsk_fk_groups: grp_id,
				status: 0
			};
		})
	}

	$(this).closest('li').postForm(site_url("user_tasks/save"), input, '', '', true, false);
});



/**
* This function should be in global scope	
* @param {*} res     		:   Ajax Response
* @param {*} container     	:   Ajax Response
* @param {*} input 			:   Ajax posted input data
*/
function afterTaskLoad(res, container, input) {
	$("#uutsk-main-container").html(res.html);

	// For dynamically loaded bootstrap switches.
	$("input[data-bootstrap-switch]").each(function () {
		$(this).bootstrapSwitch('state', $(this).prop('checked'));
	});
}