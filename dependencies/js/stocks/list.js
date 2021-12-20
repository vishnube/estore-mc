$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#stk_search_form').initForm();

	// Loading all stocks. Before doing this you should initialize the search form
	loadStocks(0);

	$('form#stk_search_form').on('submit', function (e) {
		e.preventDefault();
		loadStocks(0);
	});

	// Detect pagination click
	$("#stk_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadStocks(pageno);
	});


	$('#stk_search_form .cstr_option').change(function () {
		var t = $(this).closest('form').find('.gdn_option');
		t.noOption();

		var d = $(this).val();
		if (!d)
			return;
		loadOption('godowns/get_godowns_by_central_store', {
			cstr_id: d
		}, t, t.closest('div'));
	});


	$('#stk_search_form .prd_option').change(function () {
		var t = $(this).closest('form').find('.pdbch_option');
		t.noOption();

		var d = $(this).val();
		if (!d)
			return;
		loadOption('product_batches/get_batches_by_prd_ids', {
			prd_ids: d
		}, t, t.closest('div'));
	});
});


// Load pagination
function loadStocks(pagno) {

	// If no task assigned
	if (!tsk_stk_list)
		return;

	var input = $('form#stk_search_form').serializeArray();

	// adding an additional input
	input.push({ name: "flag", value: 1 }) // To get detailed output including unit-html, Qty in Topest Unit.
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #stk_per_page').val() })

	$('#stockQuickSearch').val('');
	var url = site_url("stocks/get_stks/") + pagno;
	$('form#stk_search_form').postForm(url, input, afterStockLoad, '', $('#tbl_stk_container'), false)
}

function loadStockOptions() {
	loadOption('stocks/get_options', [], $('.stk_option'));
}


function onStockPerpageChanged() {
	loadStocks(0)
}
/**
* This function should be in global scope	
* @param {*} res     	:   Ajax Response
* @param {*} container 	:   
* @param {*} input 		:   Ajax posted input data
*/
function afterStockLoad(res, container, input) {
	$("#stk_pagination").html(res.page_link);
	$("#stk_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createStockTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#stk-list-tab').hasClass('active'))
		goToTbl($("#stk_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createStockTable(res) {
	var tbl = $("#tbl_stk");
	tbl.find("tbody").empty();
	sno = Number(res.offset);
	var str = "";
	if (!res.stock_data.length) {
		str = get_no_result_row(tbl);
	}
	var prd_id = '';
	var slNoTxt = ''
	$.each(res.stock_data, function (i, row) {

		if (prd_id != row.prd_id) {
			sno++;
			prd_id = row.prd_id;
			slNoTxt = sno;
		}
		else
			slNoTxt = '';

		str += "<tr class='quick-search-row'>";

		// $('.export').text() will be used for export (Print/PDF/Excel)
		str += "<td><span class='export'>" + slNoTxt + "</span></td>";

		str += "<td>";
		str += "<input type='hidden' class='prd_id' value='" + row.prd_id + "'>"
		str += "<input type='hidden' class='prd_name' value='" + row.prd_name + "'><span class='export' data-exportStyle='font-weight: bold; color:#f00; text-align: left;'>" + row.prd_name + '</span>';
		str += "</td>";

		str += '<td><span class="export">' + (row.CSTR_NAME !== undefined && row.CSTR_NAME ? row.CSTR_NAME : '---') + '</span></td>';

		str += '<td><span class="export">' + (row.BATCH_NAME !== undefined && row.BATCH_NAME ? row.BATCH_NAME : '---') + '</span></td>';

		var qty = parseFloat(row.QTY) ? parseFloat(row.QTY) + ' ' + row.UNIT_NAME : '';
		str += '<td><span class="export">' + qty + '</span></td>';

		var top_qty = parseFloat(row.top_qty) ? parseFloat(row.top_qty) + ' ' + row.top_unt_name : '';

		// Balance qty in basic unit after converting to top unit
		if (parseFloat(row.top_base_qty)) {
			if (top_qty)
				top_qty += ' + ';
			top_qty += parseFloat(row.top_base_qty) + ' ' + row.UNIT_NAME;
		}

		str += '<td><span class="export">' + top_qty + '</span></td>';

		str += '<td>' + row.unit_html + '<span class="export d-none">' + row.unit_html_export + '</span></td>';

		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}



