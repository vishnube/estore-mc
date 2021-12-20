$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#estr_search_form').initForm();

	// Loading all estores. Before doing this you should initialize the search form
	loadestores(0);

	$('form#estr_search_form').on('submit', function (e) {
		e.preventDefault();
		loadestores(0);
	});

	// Detect pagination click
	$("#estr_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadestores(pageno);
	});


	$('#estr_search_form .stt_option').change(function () {
		$(this).closest('form').find('.dst_option').noOption();
		$(this).closest('form').find('.tlk_option').noOption();

		var t = $(this).closest('form').find('.dst_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('districts/get_options', {
			dst_fk_states: d
		}, t, t.closest('div'));
	});

	$('#estr_search_form .dst_option').change(function () {
		$(this).closest('form').find('.tlk_option').noOption();

		var t = $(this).closest('form').find('.tlk_option');
		var d = $(this).val();
		if (!d)
			return;
		loadOption('taluks/get_options', {
			tlk_fk_districts: d
		}, t, t.closest('div'));
	});
});


// Load pagination
function loadestores(pagno) {

	// If no task assigned
	if (!tsk_estr_list)
		return;

	var input = $('form#estr_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #estr_per_page').val() })

	$('#estoreQuickSearch').val('');
	var url = site_url("estores/get_estrs/") + pagno;
	$('form#estr_search_form').postForm(url, input, afterestoreLoad, '', $('#tbl_estr_container'), false)
}

function loadestoreOptions() {
	//loadOption('estores/get_options', [], $('.estr_option'));
}


function onestorePerpageChanged() {
	loadestores(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterestoreLoad(res, container, input) {
	$("#estr_pagination").html(res.page_link);
	$("#estr_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createestoreTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#estr_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createestoreTable(res) {
	var tbl = $("#tbl_estr");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.estore_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.estore_data, function (i, row) {
		sno += 1;

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='mbr_id' value='" + row.mbr_id + "'>";
		str += "<input type='hidden' class='estr_id' value='" + row.estr_id + "'>";
		str += "<input type='hidden' class='mbr_name' value='" + row.mbr_name + "'>";
		str += "<span class='export' data-exportStyle=''>";
		str += row.estr_name;
		str += "</span>";
		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-estr-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";

		str += "</td>";

		str += "<td><span class='export'>" + res.family_count[row.estr_id] + " / " + res.ward_count[row.estr_id] + "</span></td>";
		str += "<td><span class='export'>" + (row.estr_place ? row.estr_place : '') + "</span></td>";

		str += "<td>";
		if (row.estr_mob1 && row.estr_mob2) {
			str += row.estr_mob1 + '<br>' + row.estr_mob2;
			str += "<span class='export d-none'>" + row.estr_mob1 + ", " + row.estr_mob2 + "</span>";
		}
		else if (row.estr_mob1)
			str += "<span class='export'>" + row.estr_mob1 + "</span>";
		else if (row.estr_mob2)
			str += "<span class='export'>" + row.estr_mob1 + "</span>";
		else
			str += "<span class='export'></span>";
		str += "</td>";

		str += "<td>";
		str += '<span class="badge bg-danger">' + Object.values(res.cats[row.estr_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.cats[row.estr_id]).join(', ') + '</span>';

		str += "</td>";


		str += "<td>";

		str += "<span class='export'><span class='text-pink' title='Central Store'>" + row.cstr_code + " - " + row.cstr_name + "</span> / <span class='text-info' title='Taluk'>" + row.tlk_name + '</span> / <span class="text-success" title="District">' + row.dst_name + '</span> / <span class="text-danger" title="State">' + row.stt_name + '</span></span>';
		str += "</td>";

		// Date format documentation:   https://github.com/phstc/jquery-dateFormat
		str += "<td><span class='export'>" + $.format.date(row.mbr_date + ' 00:00:00.546', "dd/MM/yyyy") + "</span></td>";

		str += '<td class="text-right">';

		if (row.mbr_status == ACTIVE) {
			if (tsk_estr_edit)
				str += editBtn('estore', ['.edit_mbr']);
			if (tsk_estr_deactivate)
				str += deleteBtn('estore', ['.deactivate_mbr'], 'Deactivate');
		}
		else if (row.mbr_status == INACTIVE && tsk_estr_activate)
			str += activateBtn('estore', ['.activate_mbr']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}




$(document).on('click', '.show-estr-details', function () {
	var mbr_id = $(this).closest('tr').find('.mbr_id').val();
	var mbr_name = $(this).closest('tr').find('.mbr_name').val();
	var input = { mbr_id: mbr_id };

	$('#estore_details_modal').postForm(site_url("estores/get_details"), input, function (res) {
		$('#estore_details_modal').modal('show');
		$('#estore_details_modal .modal-title').html(mbr_name);
		$('#estore_details_modal .modal-body').html(res.html);
	});
});