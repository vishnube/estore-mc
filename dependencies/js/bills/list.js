$(document).ready(function () {

	// Initializing the search form with default values.
	$('form#bls_search_form').initForm();

	// Loading all bills. Before doing this you should initalize the search form
	loadBills(0);

	$('form#bls_search_form').on('submit', function (e) {
		e.preventDefault();
		$(this).closest('.sr-collapse').collapse('hide');

		var billType = $('.bill_type[name=bill_type]:checked').val();

		// If no task assigned
		if (!tasks['tsk_' + billType + '_list']) {
			Swal.fire('Oops!', 'No task found', 'error');
			return;
		}

		//Scroll to table if 'List Tab' is opened
		if ($('#bls-list-tab').hasClass('active'))
			goToTbl($("#bls_pagination"), $('.dv-tbl-port'));

		loadBills(0);
	});

	// Detect pagination click
	$("#bls_pagination").on("click", "a", function (e) {
		e.preventDefault();
		var pageno = $(this).attr("data-ci-pagination-page");
		if (typeof pageno == "undefined")
			return;
		loadBills(pageno);
	});

	$('#bls_search_form #cstr_id').change(function () {
		var t = $(this).closest('form').find('#fmly_id');
		t.noOption();
		var d = $(this).val();
		if (!d) return;
		loadOption('bills/get_cstr_family_options', {
			mbr_id: d
		}, t, t.closest('div'));
	});

	$('#bls_search_form .prd_option').change(function () {
		var t = $(this).closest('form').find('.pdbch_option');
		t.noOption();
		var d = $(this).val();
		if (!d) return;
		loadOption('product_batches/get_batches_by_prd_ids', {
			prd_ids: d
		}, t, t.closest('div'));
	});

});


function onBillsPerpageChanged() {
	loadBills(0)
}

function emptyBillsTable() {
	// Grid Table
	var Grid_tbl1 = $("#tbl_bls_grid"); // For display table 
	var Grid_tbl2 = $("#tbl_bls_grid_print"); // For export (print/excel) table @  views\bills\print_report.php

	// A4 Table
	var A4_tbl1 = $("#tbl_bls_a4_container"); // For display table (Body Section)
	var A4_tbl2 = $("#tbl_bls_a4_footer .tot-val"); // For display table (Footer Section)
	var A4_tbl3 = $("#tbl_bls_a4_print"); // For export (print) table @  views\bills\list_display_a4_export.php
	var A4_tbl4 = $("#tbl_bls_a4_excel");// For export (excel) table @  views\bills\list_display_a4_export.php

	Grid_tbl1.find('tbody').html(get_no_result_row(Grid_tbl1, "PLEASE SUBMIT THE FORM"));
	Grid_tbl1.find('tfoot td, tfoot th').html('');
	Grid_tbl2.find('tbody,tfoot').html('');

	A4_tbl1.html(get_no_result_div("PLEASE SUBMIT THE FORM"));
	A4_tbl2.html('0.00');
	A4_tbl3.find('tbody,tfoot').html('');
	A4_tbl4.find('tbody,tfoot').html('');

	//Clearing Pagination
	$("#bls_pagination").html('');
	$("#bls_pagination_msg").html('');
}

function loadBills(pagno) {

	// Making empty all tables
	emptyBillsTable();

	var input = $('form#bls_search_form').serializeArray();

	var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

	// Value should match Tbl: bill_types.btp_type
	// "bill-type-purchase-tab" => pchs, "bill-type-sale-tab" => sls
	var billCat = (curTabID == "bill-type-purchase-tab") ? 'pchs' : 'sls';
	var billType = $('.bill_type[name=bill_type]:checked').val();

	// If no task assigned
	if (!tasks['tsk_' + billType + '_list'])
		return;

	// Adding an additional input
	input.push({ name: "bls_bill_cat", value: billCat });
	input.push({ name: "bls_bill_type", value: billType }); // pchs_bls, pchs_qtn, sls_bls, sls_qtn, ect
	input.push({ name: "offset", value: pagno })
	input.push({ name: "per_page", value: $('.dv-perpage #bls_per_page').val() })

	var url = site_url("bills/get_blss");
	$('form#bls_search_form').postForm(url, input, function (res) {

		$("#bls_pagination").html(res.page_link);
		$("#bls_pagination_msg").html(formatPaginDetails(res.num_rows, res.total_rows, res.offset));

		var tblDisplay = $('#bls_search_form .rep_form:checked').val();
		if (tblDisplay == 'Grid') {
			$('#dv-Grid').show();
			$('#dv-Grid').removeClass('active').addClass('active');
			$('#dv-A4').hide();
			$('#dv-A4').removeClass('active');
			$('#dv-Tile').hide();
			$('#dv-Tile').removeClass('active');
			createGridTable(res);
		}
		else if (tblDisplay == 'A4') {
			$('#dv-A4').show();
			$('#dv-A4').removeClass('active').addClass('active');
			$('#dv-Grid').hide();
			$('#dv-Grid').removeClass('active');
			$('#dv-Tile').hide();
			$('#dv-Tile').removeClass('active');
			createA4Table(res);
		}
		else if (tblDisplay == 'Tile') {
			$('#dv-Tile').show();
			$('#dv-Tile').removeClass('active').addClass('active');
			$('#dv-A4').hide();
			$('#dv-A4').removeClass('active');
			$('#dv-Grid').hide();
			$('#dv-Grid').removeClass('active');
		}
	}, '', $(".dv-tbl-port"), false);
}

function loadBillOptions() {
	loadOption('bills/get_options', [], $('.bls_option'));
}

// Create Grid table list
function createGridTable(res) {
	var billType = $('.bill_type[name=bill_type]:checked').val();

	var tbl1 = $("#tbl_bls_grid"); // For display table 
	var tbl2 = $("#tbl_bls_grid_print"); // For export (print/excel) table @  views\bills\print_report.php

	tbl1.find("tbody").empty();
	tbl2.find("tbody").empty();

	var sno = Number(res.offset);
	var col_index1, col_index2, rowspan, row_count, prd_col_start_index;
	var rowspan = '';
	var str1 = ""; // For display table 
	var str2 = ""; // For export (print/excel) table @  views\bills\print_report.php

	var totQty = 0, totAmt = 0, totCGST = 0, totSGST = 0, totIGSt = 0, totGrsAmt = 0, totKFC = 0, totGrsDisc = 0, totRound = 0, totNetAmt = 0, totPaid = 0, totBal = 0;

	if (!res.bill_data.length) {
		str1 += get_no_result_row(tbl1);
	}
	else {
		$.each(res.bill_data, function (i, row) {
			rowspan = row.blp_data.length;
			rowspan = rowspan > 1 ? "rowspan ='" + rowspan + "'" : '';
			row_count = 1;
			col_index1 = 0;
			col_index2 = 0;
			sno += 1;
			str1 += "<tr class='bls-tr'>";
			str2 += "<tr>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + sno + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + sno + "</td>";

			var billNo = row.bill_no.blb_prefix + row.bill_no.bln_name + row.bill_no.blb_sufix;

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">";
			str1 += "<input type='hidden' class='bls_id' value='" + row.bls_id + "'>";
			str1 += "<input type='hidden' class='bls_name' value='" + billNo + "'>" + billNo;
			str1 += "&nbsp;<i title='Show Details' class='cursor-pointer show-bls-details fad fa-map-marker-exclamation' style='--fa-primary-color: #ff00a7;--fa-secondary-color: #f690d3;'></i>";
			str1 += "</td>";

			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + billNo + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + row.bls_date + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + row.bls_date + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + row.from + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + row.from + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + row.to + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + row.to + "</td>";

			// Product colums start
			prd_col_start_index = col_index1;

			str1 += "<td data-col='" + (++col_index1) + "'>" + row.blp_data[0].gdn_name + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + row.blp_data[0].gdn_name + "</td>";
			str1 += "<td data-col='" + (++col_index1) + "'>" + row.blp_data[0].prd_name + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + row.blp_data[0].prd_name + "</td>";

			var batch = row.blp_data[0].pdbch_name ? row.blp_data[0].pdbch_name : '';
			str1 += "<td data-col='" + (++col_index1) + "'>" + batch + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + batch + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(row.blp_data[0].blp_qty) + ' ' + row.blp_data[0].unt_name + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(row.blp_data[0].blp_qty) + ' ' + row.blp_data[0].unt_name + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(row.blp_data[0].blp_trate) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(row.blp_data[0].blp_trate) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(row.blp_data[0].blp_amount) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(row.blp_data[0].blp_amount) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + '(' + parseFloat(row.blp_data[0].blp_cgst_p) + ' %) ' + row.blp_data[0].blp_cgst + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + '(' + parseFloat(row.blp_data[0].blp_cgst_p) + ' %) ' + row.blp_data[0].blp_cgst + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + '(' + parseFloat(row.blp_data[0].blp_sgst_p) + ' %) ' + row.blp_data[0].blp_sgst + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + '(' + parseFloat(row.blp_data[0].blp_sgst_p) + ' %) ' + row.blp_data[0].blp_sgst + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + '(' + parseFloat(row.blp_data[0].blp_igst_p) + ' %) ' + row.blp_data[0].blp_igst + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + '(' + parseFloat(row.blp_data[0].blp_igst_p) + ' %) ' + row.blp_data[0].blp_igst + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(row.blp_data[0].blp_gross_amt) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(row.blp_data[0].blp_gross_amt) + "</td>";
			// Product colums End



			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + parseFloat(row.bls_cess_total) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + parseFloat(row.bls_cess_total) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + parseFloat(row.bls_gross_disc) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + parseFloat(row.bls_gross_disc) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + parseFloat(row.bls_round) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + parseFloat(row.bls_round) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + parseFloat(row.bls_net_amount) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + parseFloat(row.bls_net_amount) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + parseFloat(row.bls_paid) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + parseFloat(row.bls_paid) + "</td>";

			str1 += "<td data-col='" + (++col_index1) + "' " + rowspan + ">" + parseFloat(row.bls_balance) + "</td>";
			str2 += "<td data-col='" + (++col_index2) + "' " + rowspan + ">" + parseFloat(row.bls_balance) + "</td>";


			str1 += '<td class="text-right" ' + rowspan + '>';

			if (row.bls_status == ACTIVE) {
				// If no task assigned
				if (tasks['tsk_' + billType + '_edit'])
					str1 += editBtn('Bill', ['.edit_bls']);
				if (tasks['tsk_' + billType + '_deactivate'])
					str1 += deleteBtn('Bill', ['.delete_bls']);
			}
			// else if (row.bls_status == INACTIVE)
			// 	if (tasks['tsk_' + billType + '_activate'])
			// 		str1 += activateBtn('Bill', ['.activate_bls']);


			str1 += "</td>";
			str1 += "</tr>";
			str2 += "</tr>";

			// Adding multiple products (Iff more than one product in a bill)
			if (row.blp_data.length > 1) {
				$.each(row.blp_data, function (j, blp_row) {
					// First product details already print, so continuing.
					if (j == 0)
						return;
					str1 += "<tr class='bls-tr'>";
					str2 += "<tr>";

					col_index1 = col_index2 = prd_col_start_index;
					str1 += "<td data-col='" + (++col_index1) + "'>" + blp_row.gdn_name + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + blp_row.gdn_name + "</td>";
					str1 += "<td data-col='" + (++col_index1) + "'>" + blp_row.prd_name + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + blp_row.prd_name + "</td>";

					var batch = blp_row.pdbch_name ? blp_row.pdbch_name : '';
					str1 += "<td data-col='" + (++col_index1) + "'>" + batch + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + batch + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(blp_row.blp_qty) + ' ' + blp_row.unt_name + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(blp_row.blp_qty) + ' ' + blp_row.unt_name + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(blp_row.blp_trate) + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(blp_row.blp_trate) + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(blp_row.blp_amount) + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(blp_row.blp_amount) + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + '(' + blp_row.blp_cgst_p + ' %) ' + parseFloat(blp_row.blp_cgst) + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + '(' + blp_row.blp_cgst_p + ' %) ' + parseFloat(blp_row.blp_cgst) + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + '(' + blp_row.blp_sgst_p + ' %) ' + parseFloat(blp_row.blp_sgst) + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + '(' + blp_row.blp_sgst_p + ' %) ' + parseFloat(blp_row.blp_sgst) + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + '(' + blp_row.blp_igst_p + ' %) ' + parseFloat(blp_row.blp_igst) + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + '(' + blp_row.blp_igst_p + ' %) ' + parseFloat(blp_row.blp_igst) + "</td>";

					str1 += "<td data-col='" + (++col_index1) + "'>" + parseFloat(blp_row.blp_gross_amt) + "</td>";
					str2 += "<td data-col='" + (++col_index2) + "'>" + parseFloat(blp_row.blp_gross_amt) + "</td>";

					str1 += "</tr>";
					str2 += "</tr>";
					totQty = bcadd(totQty, blp_row.blp_qty, 5);
				});
			}

			totQty = bcadd(totQty, row.blp_data[0].blp_qty, 5);
			totAmt = bcadd(totAmt, row.bls_amt_total, 5);
			totCGST = bcadd(totCGST, row.bls_cgst_total, 5);
			totSGST = bcadd(totSGST, row.bls_sgst_total, 5);
			totIGSt = bcadd(totIGSt, row.bls_igst_total, 5);
			totGrsAmt = bcadd(totGrsAmt, row.bls_gross_total, 5);
			totKFC = bcadd(totKFC, row.bls_cess_total, 5);
			totGrsDisc = bcadd(totGrsDisc, row.bls_gross_disc, 5);
			totRound = bcadd(totRound, row.bls_round, 5);
			totNetAmt = bcadd(totNetAmt, row.bls_net_amount, 5);
			totPaid = bcadd(totPaid, row.bls_paid, 5);
			totBal = bcadd(totBal, row.bls_balance, 5);
		});
	}
	tbl1.find("tbody").html(str1);
	tbl2.find("tbody").html(str2);

	// Tfoot
	col_index1 = 0;
	str1 = "<tr>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totQty, 2) + " Units</td>";
	str1 += "<th data-col='" + (++col_index1) + "'></td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totAmt, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totCGST, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totSGST, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totIGSt, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totGrsAmt, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totKFC, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totGrsDisc, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totRound, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totNetAmt, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totPaid, 2) + "</td>";
	str1 += "<th data-col='" + (++col_index1) + "'>" + roundNum(totBal, 2) + "</td>";
	str2 = str1;
	str1 += "<th></th></tr>";
	str2 += "</tr>";

	tbl1.find("tfoot").html(str1);
	tbl2.find("tfoot").html(str2);
}



// Create A4 table list
function createA4Table(res) {
	createA4MainTable(res)

	var tbl2 = $("#tbl_bls_a4_print"); // For export (print) table @  views\bills\list_display_a4_export.php
	var tbl3 = $("#tbl_bls_a4_excel"); // For export (excel) table @  views\bills\list_display_a4_export.php
	createA4ExportTable(res, tbl2, tbl3);
	return;
}


$(document).on('click', '.show-bls-details', function () {
	var bls_id = $(this).closest('.bls-tr').find('.bls_id').val();
	var input = { bls_id: bls_id };

	$('#bill_details_modal').postForm(site_url("bills/get_details"), input, function (res) {
		$('#bill_details_modal').modal('show');
		$('#bill_details_modal .modal-content').html(res.html);
	});
});