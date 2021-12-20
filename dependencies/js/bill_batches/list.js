$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#blb_search_form').initForm();

	// Making an empty table.
	emptyBillBatchTable()

	$('form#blb_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');
		loadBillBatches();
	});

	// Detect pagination click
	$("#blb_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadBillBatches(pageno);
	});
});

function emptyBillBatchTable() {
	$("#tbl_blb tbody").html(get_no_result_row($("#tbl_blb"), "PLEASE SUBMIT THE FORM"));
}

function loadBillBatchOptions() {
	loadOption('bill_batches/get_options', [], $('.blb_option'), $('.blb_option').closest('div'));
}

function onBillBatchPerpageChanged() {
	loadBillBatches(0)
}

// Load pagination
function loadBillBatches(pagno) {
	var input = $('form#blb_search_form').serializeArray();

	// adding an additional input
	var bill_type = $('.bill_type[name=bill_type]:checked').val();
	input.push({ name: "bill_type", value: bill_type });
	input.push({ name: "blb_for", value: bill_type })
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #blb_per_page').val() })

	//$('#bill_batchQuickSearch').val('');
	var url = site_url("bill_batches/get_blbs/") + pagno;
	$('form#blb_search_form').postForm(url, input, afterBill_batchLoad, '', $('#tbl_blb_container'), false)
}

function afterBill_batchLoad(res, container, input) {
	$("#blb_pagination").html(res.page_link);
	$("#blb_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));
	createBill_batchTable(res);

	//Scroll to table if 'List Tab' is opened
	if ($('#emp-list-tab').hasClass('active'))
		goToTbl($("#blb_pagination"), $('.sr-tbl-cont'));
}

// Create table list
function createBill_batchTable(res) {
	var tbl = $("#tbl_blb");
	tbl.find("tbody").empty();

	var sno = Number(res.offset);
	var str = "";
	if (!res.bill_batch_data.length) {
		str = get_no_result_row(tbl);
	}
	$.each(res.bill_batch_data, function (i, row) {

		sno += 1;
		str += "<tr>";
		str += "<td>" + sno + "</td>";

		str += "<td>";
		str += "<input type='hidden' class='blb_id' value='" + row.blb_id + "'>";
		str += "<input type='hidden' class='blb_name' value='" + row.blb_name + "'><span class='text-success'>" + row.blb_name + '</span>'
		str += "</td>";

		var blb_typefor = row.blb_type == 1 ? 'Non-Tax' : 'Tax';
		str += "<td>" + blb_typefor + "</td>";
		str += "<td>" + row.blb_prefix + "</td>";
		str += "<td>" + row.blb_sufix + "</td>";

		str += '<td class="text-right">';

		if (row.blb_status == ACTIVE) {
			str += editBtn('Bill_batch', ['.edit_blb']);
			str += deleteBtn('Bill_batch', ['.deactivate_blb'], 'Deactivate');
		}
		else if (row.blb_status == INACTIVE)
			str += activateBtn('Bill_batch', ['.activate_blb']);


		str += "</td>";
		str += "</tr>";
	});
	tbl.find("tbody").append(str);
}

