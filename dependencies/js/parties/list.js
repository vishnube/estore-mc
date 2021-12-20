$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#prty_search_form').initForm();

	// Loading all parties. Before doing this you should initialize the search form
	loadParties(0);

	$('form#prty_search_form').on('submit', function (e) {
		e.preventDefault();
		loadParties(0);
	});

	// Detect pagination click
	$("#prty_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadParties(pageno);
	});
});


// Load pagination
function loadParties(pagno) {

	// If no task assigned
	if (!tsk_prty_list)
		return;

	var input = $('form#prty_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #prty_per_page').val() })

	$('#partyQuickSearch').val('');
	var url = site_url("parties/get_prtys/") + pagno;
	$('form#prty_search_form').postForm(url, input, afterPartyLoad, '', $('#tbl_prty_container'), false)
}

function loadPartyOptions() {
	loadOption('parties/get_options', [], $('.prty_option'));
}


function onPartyPerpageChanged() {
	loadParties(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterPartyLoad(res, container, input) {
	$("#prty_pagination").html(res.page_link);
	$("#prty_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createPartyTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#prty_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createPartyTable(res) {
	var tbl = $("#tbl_prty");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.party_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.party_data, function (i, row) {
		sno += 1;

		var spec = '';
		if (row.prty_is_admin == 1)
			spec = '<span class="sr-bknote"> (Master Admin)</span>';

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='mbr_id' value='" + row.mbr_id + "'>"
		str += "<input type='hidden' class='prty_id' value='" + row.prty_id + "'>"
		str += "<input type='hidden' class='mbr_name' value='" + row.mbr_name + "'><span class='export' data-exportStyle='font-weight: bold; color:#f00; text-align: right;'>" + row.mbr_name + spec + '</span>';
		str += "&nbsp;<i title='Show Details' class='cursor-pointer show-prty-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";

		// GST Data
		str += '<div class="gst-dv" style="font-size:10px;">';
		if (row.gst) {
			gst_count = row.gst.length;
			var addGST = '<i class="fas fa-plus cursor-pointer add_gst" title="Add New GST Details"></i>';
			if (!gst_count)
				str += "<span class='text-danger'>No GST Details</span>&nbsp;" + addGST;
			else if (gst_count == 1)
				str += "<span class='text-primary cursor-pointer show_gst' title='View' style='font-weight:bold'>GST: " + row.gst[0].gst_name + "&nbsp(" + row.gst[0].stt_name + ")</span>&nbsp;" + addGST;
			else
				str += "<span class='text-success cursor-pointer show_gst' style='font-size:10px;text-decoration:underline;'>Show Gst Details</span>&nbsp;" + addGST;
		}
		str += '</div>';

		str += "</td>";
		str += "<td>";
		str += '<span class="badge bg-danger">' + Object.values(res.cats[row.mbr_id]).join('</span>&nbsp;<span class="badge bg-danger">') + '</span>';
		str += '<span class="export d-none">' + Object.values(res.cats[row.mbr_id]).join(', ') + '</span>';
		str += "</td>";

		str += "<td>" + row.addr + "<span class='export d-none'>" + row.addr_comma + "</span></td>";
		str += '<td>' + row.contacts + "<span class='export d-none'>" + row.contacts_comma + "</span></td>";
		str += '<td class="text-right">';

		if (row.mbr_status == ACTIVE) {
			//str += addBtn('Party', ['#add_mbr']);
			if (tsk_prty_edit)
				str += editBtn('Party', ['.edit_mbr']);
			if (tsk_prty_deactivate)
				str += deleteBtn('Party', ['.deactivate_mbr'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.mbr_status == INACTIVE && tsk_prty_activate)
			str += activateBtn('Party', ['.activate_mbr']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}




$(document).on('click', '.show-prty-details', function () {
	var mbr_id = $(this).closest('tr').find('.mbr_id').val();
	var input = { mbr_id: mbr_id };
	$('#party_details_modal').postForm(site_url("parties/get_details"), input, function (res) {
		$('#party_details_modal').modal('show');
		$('#party_details_modal .modal-content').html(res.html);
	});
});