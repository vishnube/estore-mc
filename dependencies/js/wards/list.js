$(document).ready(function () {
	// Detect pagination click
	$("#wrd_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadWards(pageno);
	});
});


// Load pagination
function loadWards(pagno) {

	// If no task assigned
	if (!tsk_wrd_list)
		return;

	var input = $('form#wrd_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #wrd_per_page').val() })

	$('#wardQuickSearch').val('');
	var url = site_url("wards/get_wrds/") + pagno;
	$('form#wrd_search_form').postForm(url, input, afterWardLoad, '', $('#tbl_wrd_container'), false)
}

function loadWardOptions() {
	loadOption('wards/get_options', [], $('.wrd_option'));
}


function onWardPerpageChanged() {
	loadWards(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterWardLoad(res, container, input) {
	$("#wrd_pagination").html(res.page_link);
	$("#wrd_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createWardTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#wrd_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createWardTable(res) {
	var tbl = $("#tbl_wrd");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.ward_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.ward_data, function (i, row) {
		sno += 1;
		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='wrd_id' value='" + row.wrd_id + "'>"
		str += "<input type='hidden' class='wrd_name' value='" + row.wrd_name + "'><span class='export' data-exportStyle='font-weight: bold; color:#f00; text-align: right;'>" + row.stt_name + '</span>';
		str += "</td>";

		str += "<td><span class='export'>" + row.dst_name + "</span></td>";
		str += "<td><span class='export'>" + row.tlk_name + "</span></td>";
		str += "<td><span class='export'>" + row.ars_name + "</span></td>";
		str += "<td><span class='export'>" + row.wrd_name + "</span></td>";

		str += "<td><span class='export'>" + (row.cstr_name ? row.cstr_name : '<i class="fad fa-question" style="color:red;"></i>') + "</span></td>";
		str += "<td><span class='export'>" + (row.estr_name ? row.estr_name : '<i class="fad fa-question" style="color:red;"></i>') + "</span></td>";
		str += "<td><span class='export'>" + row.wrd_color + "</span></td>";


		str += '<td class="text-right">';

		if (row.wrd_status == ACTIVE) {
			//str += addBtn('Ward', ['#add_wrd']);
			if (tsk_wrd_edit)
				str += editBtn('Ward', ['.edit_wrd']);
			if (tsk_wrd_deactivate)
				str += deleteBtn('Ward', ['.deactivate_wrd'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.wrd_status == INACTIVE && tsk_wrd_activate)
			str += activateBtn('Ward', ['.activate_wrd']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}