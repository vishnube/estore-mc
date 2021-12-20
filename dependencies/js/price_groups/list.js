

// Load pagination
function load_price_groups(pagno) {

	// If no task assigned
	if (!tsk_pgp_list)
		return;

	var input = $('form#pgp_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #pgp_per_page').val() })

	$('#price_groupQuickSearch').val('');
	var url = site_url("price_groups/get_pgps/") + pagno;
	$('form#pgp_search_form').postForm(url, input, after_price_group_load, '', $('#tbl_pgp_container'), false)
}

function load_price_group_options() {
	var input = new Object();
	$('.pgp_option').each(function () {
		loadOption('price_groups/get_options', [], $(this));
	});
}


function on_price_group_perpage_changed() {
	load_price_groups(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function after_price_group_load(res, container, input) {
	$("#pgp_pagination").html(res.page_link);
	$("#pgp_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createPrice_groupTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#pgp-list-tab').hasClass('active'))
		goToTbl($("#pgp_pagination"), $('#tbl_pgp_container'));
}

// Create table list
function createPrice_groupTable(res) {
	var tbl = $("#tbl_pgp");
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
		str += "<input type='hidden' class='pgp_id' value='" + row.pgp_id + "'>"
		str += "<input type='hidden' class='pgp_name' value='" + row.pgp_name + "'><span class='export'>" + row.pgp_name + '</span>';
		str += "</td>";
		// Date format documentation:   https://github.com/phstc/jquery-dateFormat
		str += "<td><span class='export'>" + $.format.date(row.pgp_date + ' 00:00:00.546', "dd/MM/yyyy") + "</span></td>";

		str += "<td><span class='export'>" + row.pgp_disc + "</span></td>";


		str += '<td class="text-right">';

		if (row.pgp_status == ACTIVE) {
			//str += addBtn('Price_group', ['#add_pgp']);
			if (tsk_pgp_edit)
				str += editBtn('Price group', ['.edit_pgp']);
			if (tsk_pgp_deactivate)
				str += deleteBtn('Price group', ['.deactivate_pgp'], 'Deactivate');
			//str += otherBtn('User', ['.unlock_usr'], 'Unlock', 'fas fa-unlock', 'color:#e9c818');
		}
		else if (row.pgp_status == INACTIVE && tsk_pgp_activate)
			str += activateBtn('Price group', ['.activate_pgp']);

		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}