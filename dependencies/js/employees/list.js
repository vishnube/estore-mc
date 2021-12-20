$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#emply_search_form').initForm();

	// Loading all employees. Before doing this you should initialize the search form
	loadEmployees(0);

	$('form#emply_search_form').on('submit', function (e) {
		e.preventDefault();
		loadEmployees(0);
	});

	// Detect pagination click
	$("#emply_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadEmployees(pageno);
	});
});


// Load pagination
function loadEmployees(pagno) {

	// If no task assigned
	if (!tsk_emply_list)
		return;

	var input = $('form#emply_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #emply_per_page').val() })

	$('#employeeQuickSearch').val('');
	var url = site_url("employees/get_emplys/") + pagno;
	$('form#emply_search_form').postForm(url, input, afterEmployeeLoad, '', $('#tbl_emply_container'), false)
}

function loadEmployeeOptions() {
	loadOption('employees/get_options', [], $('.emply_option'));
}


function onEmployeePerpageChanged() {
	loadEmployees(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterEmployeeLoad(res, container, input) {
	$("#emply_pagination").html(res.page_link);
	$("#emply_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createEmployeeTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emply-list-tab').hasClass('active'))
		goToTbl($("#emply_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createEmployeeTable(res) {
	var tbl = $("#tbl_emply");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.employee_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.employee_data, function (i, row) {
		sno += 1;

		var spec = '';
		if (row.emply_is_admin == 1)
			spec = '<span class="sr-bknote"> (Master Admin)</span>';

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='mbr_id' value='" + row.mbr_id + "'>"
		str += "<input type='hidden' class='mbr_name' value='" + row.mbr_name + "'><span class='export' data-exportStyle='font-weight: bold; color:#f00; text-align: right;'>" + row.mbr_name + spec + '</span>';
		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-emply-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";

		str += "</td>";
		str += "<td>";
		str += '<span class="badge bg-danger">' + Object.values(res.cats[row.mbr_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.cats[row.mbr_id]).join(', ') + '</span>';
		str += "</td>";

		// Date format documentation:   https://github.com/phstc/jquery-dateFormat
		str += "<td><span class='export'>" + $.format.date(row.mbr_date + ' 00:00:00.546', "dd/MM/yyyy") + "</span></td>";
		str += '<td>' +
			(row.mbr_status == ACTIVE ? '<span class="badge bg-success export">Active</span>' : '<span class="badge bg-warning export">Inactive</span>') +
			"</td>";
		str += '<td class="text-right">';

		if (row.mbr_status == ACTIVE) {
			//str += addBtn('Employee', ['#add_mbr']);
			if (tsk_emply_edit)
				str += editBtn('Employee', ['.edit_mbr']);
			if (tsk_emply_deactivate)
				str += deleteBtn('Employee', ['.deactivate_mbr'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.mbr_status == INACTIVE && tsk_emply_activate)
			str += activateBtn('Employee', ['.activate_mbr']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}




$(document).on('click', '.show-emply-details', function () {
	var mbr_id = $(this).closest('tr').find('.mbr_id').val();
	var input = { mbr_id: mbr_id };

	$('#employee_details_modal').postForm(site_url("employees/get_details"), input, function (res) {
		$('#employee_details_modal').modal('show');
		$('#employee_details_modal .emply_wage_option').html(res.emply_wage_option);
		$('#employee_details_modal .emply_daily').html(res.emply_daily);
		$('#employee_details_modal .emply_ot').html(res.emply_ot);
		$('#employee_details_modal .emply_salary').html(res.emply_salary);
		$('#employee_details_modal .mbr_name').html(res.mbr_name);
		$('#employee_details_modal .mbr_address').html(nl2br(res.mbr_address));
		$('#employee_details_modal .mbr_date').html($.format.date(res.mbr_date + ' 00:00:00.546', "dd/MM/yyyy"));
		$('#employee_details_modal .mbr_ob').html(getCrDrAmount(res.mbr_ob));
		// var arr = [];
		// $.each(res.category, function (i, val) {
		// 	arr.push(val);
		// });
		//$('#employee_details_modal .mbrcat').html(arr.join(', '));
		$('#employee_details_modal .mbrcat').html('<span class="badge bg-warning">' + Object.values(res.category).join('</span>&nbsp;<span class="badge bg-warning">') + '</span>');
	});
});