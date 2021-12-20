$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#cstr_search_form').initForm();

	// Loading all central_stores. Before doing this you should initialize the search form
	loadCentralStores(0);

	$('form#cstr_search_form').on('submit', function (e) {
		e.preventDefault();
		loadCentralStores(0);
	});

	// Detect pagination click
	$("#cstr_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadCentralStores(pageno);
	});

	// Detect pagination click
	$(document).on("click", ".download-licence", function (e) {
		var licFile = $(this).attr('data-lic');
		window.open(base_url(licFile), "_blank");
	});
});


// Load pagination
function loadCentralStores(pagno) {

	// If no task assigned
	if (!tsk_cstr_list)
		return;

	var input = $('form#cstr_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #cstr_per_page').val() })

	$('#central_storeQuickSearch').val('');
	var url = site_url("central_stores/get_cstrs/") + pagno;
	$('form#cstr_search_form').postForm(url, input, aftercentral_storeLoad, '', $('#tbl_cstr_container'), false)
}

function loadCentralStoreOptions() {
	loadOption('central_stores/get_options', [], $('.cstr_option'));
}


function oncentral_storePerpageChanged() {
	loadCentralStores(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function aftercentral_storeLoad(res, container, input) {
	$("#cstr_pagination").html(res.page_link);
	$("#cstr_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createcentral_storeTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#cstr_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createcentral_storeTable(res) {
	var tbl = $("#tbl_cstr");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.central_store_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.central_store_data, function (i, row) {
		sno += 1;

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='mbr_id' value='" + row.mbr_id + "'>";
		str += "<input type='hidden' class='mbr_name' value='" + row.mbr_name + "'>";
		str += "<input type='hidden' class='cstr_id' value='" + row.cstr_id + "'>";
		str += "<span class='export' data-exportStyle=''>";
		str += row.cstr_code + ' - ' + row.cstr_name;
		str += " <span class='text-success'> (<b>" + res.estore_count[row.cstr_id] + "</b> Estores)</span>";
		str += "</span>";
		if (row.cstr_lic)
			str += "&nbsp; <span class='download-licence' data-lic='" + row.cstr_lic + "' title='Show Licence'><i class='fal fa-file-download'></i></span>&nbsp;";

		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-cstr-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";

		str += "</td>";

		str += "<td>";
		if (row.cstr_mob1 && row.cstr_mob2) {
			str += row.cstr_mob1 + '<br>' + row.cstr_mob2;
			str += "<span class='export d-none'>" + row.cstr_mob1 + ", " + row.cstr_mob2 + "</span>";
		}
		else if (row.cstr_mob1)
			str += "<span class='export'>" + row.cstr_mob1 + "</span>";
		else if (row.cstr_mob2)
			str += "<span class='export'>" + row.cstr_mob1 + "</span>";
		else
			str += "<span class='export'></span>";

		str += "</td>";

		str += "<td>";
		str += '<span class="badge bg-danger">' + Object.values(res.cats[row.cstr_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.cats[row.cstr_id]).join(', ') + '</span>';

		str += "</td>";


		str += "<td>";

		str += "<span class='export'><span class='text-info'>" + row.tlk_name + '</span> / <span class="text-success">' + row.dst_name + '</span> / <span class="text-danger">' + row.stt_name + '</span></span>';
		str += "</td>";

		// Date format documentation:   https://github.com/phstc/jquery-dateFormat
		str += "<td><span class='export'>" + $.format.date(row.mbr_date + ' 00:00:00.546', "dd/MM/yyyy") + "</span></td>";

		str += '<td class="text-right">';

		if (row.mbr_status == ACTIVE) {
			//str += addBtn('central_store', ['#add_mbr']);
			if (tsk_cstr_edit)
				str += editBtn('central_store', ['.edit_mbr']);
			if (tsk_cstr_deactivate)
				str += deleteBtn('central_store', ['.deactivate_mbr'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.mbr_status == INACTIVE && tsk_cstr_activate)
			str += activateBtn('central_store', ['.activate_mbr']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}




$(document).on('click', '.show-cstr-details', function () {
	var mbr_id = $(this).closest('tr').find('.mbr_id').val();
	var mbr_name = $(this).closest('tr').find('.mbr_name').val();
	var input = { mbr_id: mbr_id };

	$('#central_store_details_modal').postForm(site_url("central_stores/get_details"), input, function (res) {
		$('#central_store_details_modal').modal('show');
		$('#central_store_details_modal .modal-title').html(mbr_name);
		$('#central_store_details_modal .modal-body').html(res.html);
	});
});