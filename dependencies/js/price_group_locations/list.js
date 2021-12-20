
// Load pagination
function load_price_group_locations(pagno) {

	// If no task assigned
	if (!tsk_pgp_list)
		return;

	var input = $('form#pgpl_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #pgpl_per_page').val() })

	$('#priceGroupLocationsQuickSearch').val('');
	var url = site_url("price_group_locations/get_pgpls/") + pagno;
	$('form#pgpl_search_form').postForm(url, input, after_price_group_locations_load, '', $('#tbl_pgpl_container'), false)
}


function onPriceGroupLocationsPerpageChanged() {
	load_price_group_locations(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function after_price_group_locations_load(res, container, input) {
	$("#pgpl_pagination").html(res.page_link);
	$("#pgpl_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createPrice_group_locationsTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#pgpl-tab').hasClass('active'))
		goToTbl($("#pgpl_pagination"), $('#tbl_pgpl_container'));
}

// Create table list
function createPrice_group_locationsTable(res) {
	var tbl = $("#tbl_pgpl");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.price_group_data.length) {
		str = get_no_result_row(tbl);
	}

	$.each(res.price_group_data, function (i, row) {
		sno += 1;
		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export' data-exportStyle='text-align: left;'>" + sno + "</span></td>";
		str += "<td>";
		str += "<input type='hidden' class='pgpl_id' value='" + row.pgpl_id + "'>"
		str += "<input type='hidden' class='pgp_name' value='" + row.pgp_name + "'>";
		str += row.pgp_name

		var validity = $.format.date(row.pgpl_vf + ' 00:00:00.546', "dd/MM/yyyy") + ' - ' + $.format.date(row.pgpl_vt + ' 00:00:00.546', "dd/MM/yyyy");

		var vf = new Date(row.pgpl_vf);
		var vt = new Date(row.pgpl_vt);
		var today = new Date();
		vf = new Date(vf.getUTCFullYear(), vf.getUTCMonth(), vf.getUTCDate())
		vt = new Date(vt.getUTCFullYear(), vt.getUTCMonth(), vt.getUTCDate())
		today = new Date(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate());
		var vcls = (vf <= today && vt >= today) ? 'text-success' : 'text-danger';
		str += '<div style="font-size: 12px;"><span class="' + vcls + '" title="Validity">' + validity + '</span></div>';

		str += "<span class='export d-none'>" + row.pgp_name + ' (' + validity + ')</span>';
		str += "</td>";
		str += "<td><span class='export'>" + (row.stt_name ? row.stt_name : '') + "</span></td>";
		str += "<td><span class='export'>" + (row.dst_name ? row.dst_name : '') + "</span></td>";
		str += "<td><span class='export'>" + (row.ars_name ? row.ars_name : '') + "</span></td>";
		str += "<td><span class='export'>" + (row.cstr_name ? row.cstr_name : '') + "</span></td>";
		str += "<td><span class='export'>" + (row.wrd_name ? row.wrd_name : '') + "</span></td>";
		str += '<td class="text-right">';

		if (tsk_pgp_edit)
			str += editBtn('Location', ['.edit_pgpl']);
		if (tsk_pgp_deactivate)
			str += deleteBtn('Location', ['.delete_pgpl']);

		str += "</td>";
		str += "</tr>";
	});

	tbl.find("tbody").append(str);
}