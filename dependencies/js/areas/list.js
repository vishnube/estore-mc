

// Load pagination
function loadAreas(pagno) {

	// If no task assigned
	if (!tsk_wrd_list)
		return;

	var input = $('form#ars_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #ars_per_page').val() })

	$('#areaQuickSearch').val('');
	var url = site_url("areas/get_arss/") + pagno;
	$('form#ars_search_form').postForm(url, input, afterAreaLoad, '', $('#tbl_ars_container'), false)
}

function loadAreaOptions() {
	var input = new Object();
	var tlk_id, cstr_id;
	$('.ars_option').each(function () {
		tlk_id = $(this).closest('form').find('.tlk_option').val();
		cstr_id = $(this).closest('form').find('.cstr_option').val();

		// If no both Taluk and Central Stores selected, skiping this step
		if (!tlk_id && !cstr_id)
			return true;

		input = {
			ars_fk_taluks: tlk_id,
			cstr_id: cstr_id
		}
		loadOption('areas/get_options', input, $(this));
	});
}


function onAreaPerpageChanged() {
	loadAreas(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterAreaLoad(res, container, input) {
	$("#ars_pagination").html(res.page_link);
	$("#ars_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createAreaTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#ars_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createAreaTable(res) {
	var tbl = $("#tbl_ars");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.area_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.area_data, function (i, row) {
		sno += 1;
		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='ars_id' value='" + row.ars_id + "'>"
		str += "<input type='hidden' class='ars_name' value='" + row.ars_name + "'><span class='export'>" + row.stt_name + '</span>';
		str += "</td>";

		str += "<td><span class='export'>" + row.dst_name + "</span></td>";
		str += "<td><span class='export'>" + row.tlk_name + "</span></td>";
		str += "<td><span class='export'>" + row.ars_name + "</span></td>";
		str += "<td><span class='export'>" + res.area_type_option[row.ars_type] + "</span></td>";
		str += "<td><span class='export'>" + row.cstr_name + "</span></td>";


		str += '<td class="text-right">';

		if (row.ars_status == ACTIVE) {
			//str += addBtn('Area', ['#add_ars']);
			if (tsk_wrd_edit)
				str += editBtn('Area', ['.edit_ars']);
			if (tsk_wrd_deactivate)
				str += deleteBtn('Area', ['.deactivate_ars'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.ars_status == INACTIVE && tsk_wrd_activate)
			str += activateBtn('Area', ['.activate_ars']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}