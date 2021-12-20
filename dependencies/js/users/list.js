$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#usr_search_form').initForm();

	// Loading all users. Before doing this you should initialize the search form
	loadUsers(0);

	$('form#usr_search_form').on('submit', function (e) {
		e.preventDefault();
		loadUsers(0);
	});

	// Detect pagination click
	$("#usr_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadUsers(pageno);
	});



	// If $('#usr_search_form .mbrtp_id') is <input type="text">, We need to load Users.
	loadUserOptions();

	$('#usr_search_form .mbrtp_id').change(loadUserOptions);
});




function onUserPerpageChanged() {
	loadUsers(0);
}


// Load pagination
function loadUsers(pagno) {

	// If no task assigned
	if (!tsk_usr_list)
		return;

	var input = $('form#usr_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #usr_per_page').val() })

	$('#userQuickSearch').val('');
	var url = site_url("users/get_usrs/") + pagno;

	$('form#usr_search_form').postForm(url, input, afterUserLoad, '', $('#tbl_usr_container'), false)
}

function loadUserOptions(form) {
	if (typeof form != 'undefined') {
		var mbrtp_id = form.find('.mbrtp_id').val();
		loadOption('users/get_options', { mbrtp_id: mbrtp_id }, form.find('.usr_option'), form.find('.usr_option').closest('div'));
	}

	else {
		$('.usr_option').each(function () {
			var mbrtp_id = $(this).closest('form').find('.mbrtp_id').length ? $(this).closest('form').find('.mbrtp_id').val() : '';
			loadOption('users/get_options', { mbrtp_id: mbrtp_id }, $(this));
		});
	}
}


$(document).on('change', '.mbrtp_id', function () {
	var mbrtp_id = $(this).val();
	if (!mbrtp_id)
		return;
	var form = $(this).closest('form');
	loadUserOptions(form);
});

/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterUserLoad(res, container, input) {
	$("#usr_pagination").html(res.page_link);
	$("#usr_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createUserTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#user-list-tab').hasClass('active'))
		goToTbl($("#usr_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createUserTable(res) {
	var tbl = $("#tbl_usr");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.user_data.length) {
		str = get_no_result_row(tbl);
	}

	// console.log(res.table)
	$.each(res.user_data, function (i, row) {

		sno += 1;

		str += "<tr class='quick-search-row'>";
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='usr_id' value='" + row.usr_id + "'>";

		var spec = '';
		if (row.usr_type == 1)
			spec = '<span class="sr-bknote"> (Developer)</span>';
		else if (row.usr_type == 2)
			spec = '<span class="sr-bknote"> (Master Admin)</span>';

		str += "<input type='hidden' class='usr_name' value='" + row.mbr_name + "'><span class='export'>" + row.mbr_name + spec + "</span>";
		if (row.is_locked)
			str += '&nbsp;<i class="fas fa-user-lock" title="Locked upto ' + row.usr_lock + '" style="color:#ed7f0a;"></i>';
		str += "</td>";

		str += "<td>";

		str += '<span class="badge bg-danger">' + Object.values(res.user_groups[row.usr_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.user_groups[row.usr_id]).join(', ') + '</span>';

		str += "</td>";

		// Date format documentation:   https://github.com/phstc/jquery-dateFormat
		str += "<td><span class='export'>" + $.format.date(row.usr_date + ' 00:00:00.546', "dd/MM/yyyy") + "</span></td>";


		// If Attempt is 0, then no need show it, else, showing attempts/round.
		var attempt_title = row.usr_attempt > 0 ? row.usr_attempt + '<sup>' + numberToOrdinal(row.usr_attempt) + '</sup>' + " attempt in " + row.usr_attempt_round + '<sup>' + numberToOrdinal(row.usr_attempt_round) + '</sup>' + " round" : '';

		var attempt = '<span data-toggle="tooltip" data-placement="top" title="' + attempt_title + '">' + (row.usr_attempt > 0 ? row.usr_attempt + "/" + row.usr_attempt_round : '') + '</span>';
		str += '<td><span class="export">' + attempt + "</span></td>";


		str += '<td class="text-left">';
		if (row.usr_status == ACTIVE) {
			if (tsk_usr_edit)
				str += editBtn('User', ['.edit_usr']);
			if (tsk_usr_deactivate)
				str += deleteBtn('User', ['.deactivate_usr'], 'Deactivate');
			if ((tsk_usr_edit || tsk_usr_activate) && row.is_locked)
				str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:black');
		}
		else if (row.usr_status == INACTIVE && tsk_usr_activate)
			str += activateBtn('User', ['.activate_usr']);



		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}